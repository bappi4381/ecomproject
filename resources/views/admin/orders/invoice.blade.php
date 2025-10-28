<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color:#333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color:#555; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>BookSaw</h2>
        <p>Order Invoice #{{ $order->id }}</p>
    </div>

    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
    <p><strong>Shipping Address:</strong> {{ $order->user->address }}</p>
    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

    <h4>Order Items</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>tk. {{ number_format($item->price, 2) }}</td>
                <td>tk. {{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Delivery Charge</th>
                <th>tk. {{ number_format($order->delivery_charge, 2) }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Grand Total</th>
                <th>tk. {{ number_format($order->total_price, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
    </div>
</body>
</html>
