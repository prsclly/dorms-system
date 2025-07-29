<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Parent Account</title>
</head>
<body>
    <h2>Hi {{ $parent->name }},</h2>
    <p>Your account has been created for the DORM Management System.</p>

    <p><strong>Login Email:</strong> {{ $parent->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>You can log in at: <a href="{{ url('/parent/login') }}">{{ url('/parent/login') }}</a></p>

    <p>We recommend that you change your password after your first login.</p>

    <br>
    <p>Best regards,<br>DORM President School</p>
</body>
</html>
