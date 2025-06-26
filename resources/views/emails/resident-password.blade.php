<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Created</title>
</head>
<body>
    <h2>Hi {{ $resident->name }},</h2>
    <p>Your account has been created for the DORM Management System.</p>
    <p><strong>Email:</strong> {{ $resident->email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>Please login to the system using the credentials above.</p>
    <p>It's recommended to change your password after your first login.</p>

    <br>
    <p>Best regards,<br>DORM President High School</p>
</body>
</html>
