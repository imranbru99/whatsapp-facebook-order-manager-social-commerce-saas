<?php

namespace App\Filament\Business\Pages;

use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Services\Channels\MockWhatsAppChannelAdapter;
use App\Services\ChatToOrderService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class ChannelSimulator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static string $view = 'filament.business.pages.channel-simulator';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?string $title = 'Mock Channel Simulator';

    public ?array $data = [];

    public ?string $resultSummary = null;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) $user?->business_id;
    }

    public function mount(): void
    {
        $this->form->fill([
            'channel_type' => 'whatsapp',
            'customer_name' => 'রহিম উদ্দিন',
            'body' => "আমি রহিম উদ্দিন\n01712345678\norder চাই: Blue Shirt 2 pcs size L\nঠিকানা: ধানমন্ডি, ঢাকা\nhome delivery",
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('channel_type')
                    ->options([
                        'whatsapp' => 'WhatsApp (mock)',
                        'facebook' => 'Facebook Messenger (mock)',
                        'instagram' => 'Instagram (mock)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('customer_name')->required(),
                Forms\Components\Textarea::make('body')->rows(8)->required()->label('Incoming message'),
                Forms\Components\Toggle::make('create_order')->default(true)->label('Also create draft order via AI extraction'),
            ])
            ->statePath('data');
    }

    public function simulate(ChatToOrderService $chatToOrder): void
    {
        $state = $this->form->getState();
        $businessId = (int) auth()->user()->business_id;
        $branchId = auth()->user()->branch_id;

        $channel = Channel::query()->firstOrCreate(
            [
                'business_id' => $businessId,
                'type' => $state['channel_type'],
                'name' => 'Demo '.ucfirst($state['channel_type']),
            ],
            [
                'branch_id' => $branchId,
                'external_id' => 'mock_'.$state['channel_type'],
                'status' => 'connected',
                'is_mock' => true,
            ]
        );

        $adapter = new MockWhatsAppChannelAdapter;
        $parsed = $adapter->parseIncoming([
            'body' => $state['body'],
            'sender' => $state['customer_name'],
        ]);

        $customer = Customer::query()->firstOrCreate(
            [
                'business_id' => $businessId,
                'phone' => '01712345678',
            ],
            [
                'ulid' => (string) Str::ulid(),
                'branch_id' => $branchId,
                'name' => $state['customer_name'],
                'preferred_channel' => $state['channel_type'],
                'language' => 'bn',
            ]
        );

        $conversation = Conversation::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $businessId,
            'branch_id' => $branchId,
            'channel_id' => $channel->id,
            'customer_id' => $customer->id,
            'assigned_to' => auth()->id(),
            'external_thread_id' => $parsed['thread_id'],
            'status' => 'open',
            'subject' => 'Simulated '.$state['channel_type'].' chat',
            'unread_count' => 1,
            'last_message_at' => now(),
        ]);

        Message::query()->create([
            'business_id' => $businessId,
            'conversation_id' => $conversation->id,
            'direction' => 'inbound',
            'sender_type' => 'customer',
            'external_id' => $parsed['external_id'],
            'body' => $parsed['body'],
            'sent_at' => now(),
        ]);

        $summary = "Conversation #{$conversation->id} created.";

        if (! empty($state['create_order'])) {
            $order = $chatToOrder->createDraftFromConversation($conversation);
            $summary .= " Draft order {$order->number} total ".number_format($order->grand_total / 100, 2).' BDT.';
        }

        $this->resultSummary = $summary;
        Notification::make()->title('Mock message ingested')->body($summary)->success()->send();
    }
}
