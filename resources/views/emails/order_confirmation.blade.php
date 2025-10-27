<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Hi {{ $name }},</h2>
    <p>Thank you for your order. Your order ID is <strong>#{{ $order_id }}</strong>.</p>
    <p>Total Amount: <strong>{{ number_format($total, 2) }} Tk</strong></p>

    @if($password)
        <p>You have been registered automatically. Your login credentials:</p>
        <p>Email: {{ $email ?? 'Your Email' }} <br>
           Password: {{ $password }}</p>
        <p>Please login and change your password for security.</p>
    @endif

    <p>We will process your order soon. Thank you for shopping with BookSaw!</p>
</body>
</html>
