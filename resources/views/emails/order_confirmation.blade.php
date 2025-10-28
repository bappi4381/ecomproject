<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation - BookSaw</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">

    <div style="max-width: 600px; margin: 0 auto; background: #fff; border:1px solid #ddd; border-radius: 8px; padding: 20px;">
        <h2 style="text-align:center; color:#4e4e4e;">📚 BookSaw Order Confirmation</h2>
        <p>Dear <strong>{{ $name }}</strong>,</p>

        <p>Thank you for your order! Your order has been successfully placed.</p>

        <p><strong>Order ID:</strong> {{ $order_id }}</p>
        <p><strong>Payment Method:</strong> {{ strtoupper($payment_method) }}</p>

        <hr>

        <table width="100%" cellspacing="0" cellpadding="8" style="border-collapse: collapse; border: 1px solid #ddd;">
            <tr style="background-color:#f2f2f2;">
                <th align="left">Description</th>
                <th align="right">Amount (৳)</th>
            </tr>
            <tr>
                <td>Subtotal</td>
                <td align="right">{{ number_format($total - $delivery_charge, 2) }}</td>
            </tr>
            <tr>
                <td>Delivery Charge ({{ $delivery_charge == 80 ? 'Dhaka' : 'Outside Dhaka' }})</td>
                <td align="right">{{ number_format($delivery_charge, 2) }}</td>
            </tr>
            <tr style="background-color:#eaf8ea; font-weight:bold;">
                <td>Grand Total</td>
                <td align="right">{{ number_format($total, 2) }}</td>
            </tr>
        </table>

        <hr>

        @if (!empty($password))
        <p><strong>Account Created:</strong> Since you ordered as a guest, an account has been created for you automatically.</p>
        <p><strong>Temporary Password:</strong> {{ $password }}</p>
        <p>Please log in and change your password after signing in.</p>
        @endif

        <p style="margin-top:20px;">We’ll notify you once your order is shipped.</p>

        <p style="text-align:center; color:#666;">Thank you for shopping with <strong>BookSaw</strong>! 💚</p>
    </div>

</body>
</html>
