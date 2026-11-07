<?php

namespace App\Services;

use App\Models\AiExtraction;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use ImranDevBd\AiHub\Facades\AIHub;

class AiOrderExtractionService
{
    /**
     * Extract structured order fields from chat text.
     * Uses laravel-ai-hub when configured; otherwise a high-fidelity Bangla/English mock parser.
     */
    public function extract(string $text, int $businessId, ?Conversation $conversation = null): AiExtraction
    {
        $usedMock = false;
        $payload = null;
        $confidence = 0.0;

        try {
            if ($this->hasAiKey()) {
                $response = AIHub::prompt(
                    "Extract order JSON with keys: customer_name, phone, address, district, thana, products (array of {name, quantity, size, color, price}), delivery_preference, notes. Text:\n".$text
                )->asJsonObject()->send();

                $payload = $response->json(true);
                $confidence = 0.85;
            }
        } catch (\Throwable $e) {
            Log::warning('AI extraction fell back to mock', ['error' => $e->getMessage()]);
        }

        if (! is_array($payload)) {
            $usedMock = true;
            $payload = $this->mockExtract($text);
            $confidence = (float) ($payload['_confidence'] ?? 0.72);
            unset($payload['_confidence']);
        }

        return AiExtraction::query()->create([
            'business_id' => $businessId,
            'conversation_id' => $conversation?->id,
            'source_text' => $text,
            'extracted' => $payload,
            'confidence' => $confidence,
            'used_mock' => $usedMock,
        ]);
    }

    protected function hasAiKey(): bool
    {
        return filled(config('ai-hub.providers.openai.api_key'))
            || filled(env('OPENAI_API_KEY'))
            || filled(env('GEMINI_API_KEY'))
            || filled(env('ANTHROPIC_API_KEY'));
    }

    /**
     * Deterministic mock parser for Bangla / Banglish / English order messages.
     *
     * @return array<string, mixed>
     */
    public function mockExtract(string $text): array
    {
        $phone = null;
        if (preg_match('/(01[3-9]\d{8})/', $text, $m)) {
            $phone = $m[1];
        }

        $name = 'Customer';
        if (preg_match('/(?:ami|name|নাম)[:\s]+([^\n,]+)/iu', $text, $m)) {
            $name = trim($m[1]);
        } elseif (preg_match('/^([^\n,]+?)(?:\s+017|\s+018|\s+019|\s+013|\s+014|\s+015|\s+016)/u', $text, $m)) {
            $name = trim($m[1]);
        }

        $qty = 1;
        if (preg_match('/(\d+)\s*(?:pcs|piece|টা|টি|টির)/iu', $text, $m)) {
            $qty = (int) $m[1];
        }

        $product = 'Product';
        if (preg_match('/(?:order|চাই|নিতে চাই|কিনব)\s*[:\-]?\s*([^\n]+)/iu', $text, $m)) {
            $product = trim($m[1]);
        } elseif (preg_match('/(shirt|dress|pant|sharee|saree|lipstick|gadget|phone|কামিজ|শার্ট|প্যান্ট|শাড়ি)/iu', $text, $m)) {
            $product = $m[1];
        }

        $size = null;
        if (preg_match('/\b(XXL|XL|L|M|S)\b/i', $text, $m)) {
            $size = strtoupper($m[1]);
        }

        $address = null;
        if (preg_match('/(?:address|ঠিকানা)[:\s]+([^\n]+)/iu', $text, $m)) {
            $address = trim($m[1]);
        }

        $district = null;
        foreach (['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet', 'Barisal', 'Rangpur', 'ঢাকা', 'চট্টগ্রাম'] as $d) {
            if (stripos($text, $d) !== false) {
                $district = $d;
                break;
            }
        }

        return [
            'customer_name' => $name,
            'phone' => $phone,
            'address' => $address ?? ($district ? $district.', Bangladesh' : null),
            'district' => $district,
            'thana' => null,
            'products' => [[
                'name' => $product,
                'quantity' => $qty,
                'size' => $size,
                'color' => null,
                'price' => null,
            ]],
            'delivery_preference' => stripos($text, 'home') !== false || stripos($text, 'হোম') !== false ? 'home' : 'standard',
            'notes' => null,
            '_confidence' => ($phone && $product !== 'Product') ? 0.88 : 0.65,
        ];
    }
}
