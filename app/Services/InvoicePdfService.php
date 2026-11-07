<?php

namespace App\Services;

use App\Models\Order;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class InvoicePdfService
{
    public function download(Order $order, string $locale = 'bn')
    {
        $order->loadMissing(['items', 'customer', 'business']);

        $preset = $locale === 'bn' ? 'bengali' : 'default';

        return UnicodePdf::preset($preset)
            ->loadView('pdf.invoice', [
                'order' => $order,
                'locale' => $locale,
            ])
            ->a4()
            ->download('invoice-'.$order->number.'.pdf');
    }
}
