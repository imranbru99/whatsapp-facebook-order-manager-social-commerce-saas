<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Order;
use Illuminate\Support\Str;

class AccountingService
{
    public function ensureChart(int $businessId): void
    {
        $accounts = [
            ['1000', 'Cash', 'asset'],
            ['1100', 'COD Receivable', 'asset'],
            ['1200', 'Inventory', 'asset'],
            ['2000', 'VAT Payable', 'liability'],
            ['4000', 'Sales', 'income'],
            ['5000', 'COGS', 'expense'],
            ['5100', 'Delivery Expense', 'expense'],
        ];

        foreach ($accounts as [$code, $name, $type]) {
            Account::query()->firstOrCreate(
                ['business_id' => $businessId, 'code' => $code],
                ['name' => $name, 'type' => $type]
            );
        }
    }

    public function postOrderSale(Order $order): JournalEntry
    {
        $this->ensureChart((int) $order->business_id);

        $cash = Account::query()->where('business_id', $order->business_id)->where('code', '1100')->first();
        $sales = Account::query()->where('business_id', $order->business_id)->where('code', '4000')->first();

        $entry = JournalEntry::query()->create([
            'business_id' => $order->business_id,
            'number' => 'JE-'.Str::upper(Str::random(6)),
            'entry_date' => now()->toDateString(),
            'memo' => 'Sale '.$order->number,
            'reference_type' => Order::class,
            'reference_id' => $order->id,
        ]);

        JournalLine::query()->create([
            'journal_entry_id' => $entry->id,
            'account_id' => $cash->id,
            'debit' => $order->grand_total,
            'credit' => 0,
        ]);
        JournalLine::query()->create([
            'journal_entry_id' => $entry->id,
            'account_id' => $sales->id,
            'debit' => 0,
            'credit' => $order->grand_total,
        ]);

        return $entry->load('lines');
    }
}
