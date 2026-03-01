<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    
                    <!-- Header with Logo -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #2f4a1e 0%, #263d18 100%); padding: 40px 30px; text-align: center;">
                            <img src="https://nulac.in/images/new.png" alt="{{ config('app.name') }}" style="height: 50px; width: auto; margin-bottom: 20px;">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">
                                Reset Your Password
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Greeting -->
                            <p style="color: #1f2a1a; font-size: 16px; font-weight: 600; margin: 0 0 20px 0;">
                                Hello {{ $user->name }},
                            </p>

                            <!-- Message -->
                            <p style="color: #6a7a63; font-size: 15px; line-height: 1.6; margin: 0 0 25px 0;">
                                You are receiving this email because we received a password reset request for your account.
                            </p>

                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 16px 40px; background-color: #2f4a1e; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 800; font-size: 16px; letter-spacing: 0.3px; box-shadow: 0 4px 12px rgba(47, 74, 30, 0.3);">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiry Notice -->
                            <div style="background-color: #fff3cd; border-left: 4px solid #f1cc24; padding: 15px 20px; border-radius: 8px; margin: 25px 0;">
                                <p style="color: #856404; font-size: 14px; font-weight: 600; margin: 0 0 5px 0;">
                                    ⏰ Important Notice
                                </p>
                                <p style="color: #856404; font-size: 13px; line-height: 1.5; margin: 0;">
                                    This password reset link will expire in 60 minutes.
                                </p>
                            </div>

                            <!-- Additional Info -->
                            <p style="color: #6a7a63; font-size: 14px; line-height: 1.6; margin: 25px 0 0 0;">
                                If you did not request a password reset, no further action is required. Your password will remain unchanged.
                            </p>

                            <!-- Alternative Link -->
                            <div style="background-color: #f9fdf7; border: 1px solid #e7e7e7; border-radius: 8px; padding: 20px; margin: 30px 0;">
                                <p style="color: #1f2a1a; font-size: 13px; font-weight: 600; margin: 0 0 10px 0;">
                                    Having trouble clicking the "Reset Password" button?
                                </p>
                                <p style="color: #6a7a63; font-size: 12px; line-height: 1.5; margin: 0 0 10px 0;">
                                    Copy and paste the URL below into your web browser:
                                </p>
                                <p style="color: #2f4a1e; font-size: 12px; word-break: break-all; margin: 0; font-family: monospace; background-color: #ffffff; padding: 10px; border-radius: 6px; border: 1px solid #e7e7e7;">
                                    {{ $resetUrl }}
                                </p>
                            </div>

                            <!-- Security Tips -->
                            <div style="border-top: 2px solid #e7e7e7; padding-top: 25px; margin-top: 30px;">
                                <p style="color: #1f2a1a; font-size: 14px; font-weight: 700; margin: 0 0 12px 0;">
                                    🔒 Security Tips
                                </p>
                                <ul style="color: #6a7a63; font-size: 13px; line-height: 1.7; margin: 0; padding-left: 20px;">
                                    <li>Never share your password with anyone</li>
                                    <li>Use a strong, unique password</li>
                                    <li>If you didn't request this reset, please contact us immediately</li>
                                </ul>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fdf7; padding: 30px; text-align: center; border-top: 1px solid #e7e7e7;">
                            <p style="color: #6a7a63; font-size: 13px; margin: 0 0 15px 0;">
                                Best regards,<br>
                                <strong style="color: #2f4a1e;">The {{ config('app.name') }} Team</strong>
                            </p>
                            
                            <!-- Social Links (Optional) -->
                            <div style="margin: 20px 0;">
                                <a href="{{ route('home') }}" style="color: #2f4a1e; text-decoration: none; font-size: 13px; font-weight: 600; margin: 0 10px;">
                                    Visit Website
                                </a>
                                <span style="color: #e7e7e7;">|</span>
                                <a href="{{ route('contact') }}" style="color: #2f4a1e; text-decoration: none; font-size: 13px; font-weight: 600; margin: 0 10px;">
                                    Contact Support
                                </a>
                            </div>

                            <p style="color: #6a7a63; font-size: 11px; line-height: 1.5; margin: 15px 0 0 0;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                                This is an automated message, please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
