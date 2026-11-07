<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Services\ChatToOrderService;
use App\Support\TenantContext;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SimulateChannelMessageCommand extends Command
{
    protected $signature = 'channel:simulate
        {--business=demo-fashion : Business slug}
        {--type=whatsapp : Channel type}
        {--body= : Message body}
        {--order : Create draft order}';

    protected $description = 'Simulate an inbound mock channel message (no real Meta APIs)';

    public function handle(ChatToOrderService $chatToOrder): int
    {
        $business = Business::query()->where('slug', $this->option('business'))->firstOrFail();
        TenantContext::set($business->id);

        $body = $this->option('body') ?: "আমি রহিম\n01712345678\norder চাই: Blue Shirt 1 pcs size L\nঠিকানা: ঢাকা";

        $channel = Channel::query()->firstOrCreate(
            ['business_id' => $business->id, 'type' => $this->option('type')],
            ['name' => 'CLI '.$this->option('type'), 'is_mock' => true, 'status' => 'connected']
        );

        $customer = Customer::query()->firstOrCreate(
            ['business_id' => $business->id, 'phone' => '01712345678'],
            ['ulid' => (string) Str::ulid(), 'name' => 'রহিম', 'language' => 'bn']
        );

        $conversation = Conversation::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $business->id,
            'channel_id' => $channel->id,
            'customer_id' => $customer->id,
            'external_thread_id' => 'cli_'.Str::random(6),
            'status' => 'open',
            'subject' => 'CLI simulation',
            'last_message_at' => now(),
        ]);

        Message::query()->create([
            'business_id' => $business->id,
            'conversation_id' => $conversation->id,
            'direction' => 'inbound',
            'sender_type' => 'customer',
            'external_id' => 'cli_msg_'.Str::random(8),
            'body' => $body,
            'sent_at' => now(),
        ]);

        $this->info("Conversation {$conversation->id} created.");

        if ($this->option('order')) {
            $order = $chatToOrder->createDraftFromConversation($conversation);
            $this->info("Draft order {$order->number} grand_total={$order->grand_total}");
        }

        TenantContext::clear();

        return self::SUCCESS;
    }
}
