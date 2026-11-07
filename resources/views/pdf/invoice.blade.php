<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1>{{ $locale === 'bn' ? 'ইনভয়েস' : 'Invoice' }} {{ $order->number }}</h1>
    <p class="muted">{{ $order->business->name ?? '' }}</p>
    <p>
        {{ $locale === 'bn' ? 'গ্রাহক' : 'Customer' }}:
        {{ $order->shipping_name }} · {{ $order->shipping_phone }}<br>
        {{ $order->shipping_address }}
    </p>
    <table>
        <thead>
            <tr>
                <th>{{ $locale === 'bn' ? 'পণ্য' : 'Item' }}</th>
                <th>{{ $locale === 'bn' ? 'পরিমাণ' : 'Qty' }}</th>
                <th>{{ $locale === 'bn' ? 'মূল্য' : 'Price' }}</th>
                <th>{{ $locale === 'bn' ? 'মোট' : 'Total' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price / 100, 2) }}</td>
                    <td>{{ number_format($item->line_total / 100, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top:16px; font-weight:bold;">
        {{ $locale === 'bn' ? 'সর্বমোট' : 'Grand total' }}:
        {{ number_format($order->grand_total / 100, 2) }} {{ $order->currency }}
    </p>
</body>
</html>
