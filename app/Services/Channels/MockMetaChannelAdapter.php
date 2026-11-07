<?php

namespace App\Services\Channels;

use App\Core\Contracts\ChannelAdapterInterface;
use Illuminate\Support\Str;

class MockMetaChannelAdapter implements ChannelAdapterInterface
{
    public function __construct(protected string $provider = 'facebook') {}

    public function provider(): string
    {
        return $this->provider;
    }

    public function sendMessage(string $threadId, string $body, array $payload = []): array
    {
        return [
            'external_id' => 'mock_out_'.Str::lower(Str::random(12)),
            'status' => 'sent',
            'mock' => true,
            'thread_id' => $threadId,
            'body' => $body,
        ];
    }

    public function parseIncoming(array $webhook): ?array
    {
        return [
            'thread_id' => (string) ($webhook['thread_id'] ?? 'mock_thread_'.Str::random(6)),
            'external_id' => (string) ($webhook['external_id'] ?? 'mock_in_'.Str::random(10)),
            'body' => (string) ($webhook['body'] ?? ''),
            'sender' => (string) ($webhook['sender'] ?? 'customer'),
        ];
    }
}
