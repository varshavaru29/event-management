<!DOCTYPE html>
<html>
<head>
    <title>Ticket Purchase Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>You have successfully purchased {{ $ticketDetails['quantity'] }} ticket(s) for the event:</p>
    <h2>{{ $ticketDetails['ticket_type_name'] }}</h2>
    <p>Date: {{ $ticketDetails['start_date'] }}</p>
    <p>Total Price: ${{ $ticketDetails['total_price'] }}</p>
    <p>We hope you enjoy the event!</p>
</body>
</html>
