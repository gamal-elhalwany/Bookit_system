<!DOCTYPE html>
<html>
<head>
    <title>Verify your account</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Your verification code is:</p>
    <h2>{{ $code }}</h2>
    <p>Please enter this code in the app to verify your email.</p>
</body>
</html>
