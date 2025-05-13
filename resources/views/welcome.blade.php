<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <!-- resources/views/pay.blade.php -->
    <form method="POST" action="{{ route('phonepe.pay') }}">
        @csrf
        <label for="amount">Enter Amount (in Rupees):</label>
        <input type="number" name="amount" id="amount" min="1" required>
        <button type="submit">Pay with PhonePe</button>
    </form>
</body>

</html>
