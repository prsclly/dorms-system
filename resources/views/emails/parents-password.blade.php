<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your DORMS Account</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4;">
    <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; margin-top: 40px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#004080" style="padding: 30px;">
                            <h1 style="color: #ffffff; margin: 0;">DORMS Parents Account</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333;">
                            <h2 style="margin-top: 0;">Hi {{ $parent->name }},</h2>
                            <p>Your account has been created for the <strong>DORM Management System</strong>.</p>

                            <div style="background-color: #f0f0f0; padding: 20px; border-radius: 6px; margin: 20px 0;">
                                <p style="margin: 0;"><strong>Login Email:</strong> {{ $parent->email }}</p>
                                <p style="margin: 0;"><strong>Password:</strong> {{ $password }}</p>
                            </div>

                            <p>You can log in at: <br>
                                <a href="{{ url('/parent/login') }}" style="color: #004080; font-weight: bold;">
                                    {{ url('/parent/login') }}
                                </a>
                            </p>

                            <p style="margin-top: 30px;">We recommend that you change your password after your first login for security purposes.</p>

                            <p style="margin-top: 40px;">Best regards,<br>
                            <strong>President Senior High School</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f0f0f0" style="padding: 20px; font-size: 12px; color: #666;">
                            This is an automated message. Please do not reply.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
