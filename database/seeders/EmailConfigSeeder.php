<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailConfig;

class EmailConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $htmlTemplate = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);" cellspacing="0" cellpadding="0" border="0">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #091224 0%, #11203d 100%); padding: 32px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; text-transform: uppercase; font-weight: 800;">
                                Carolina Prime
                            </h1>
                            <p style="color: #94a3b8; margin: 6px 0 0; font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em;">
                                Wholesale Distribution Network
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px;">
                            <h2 style="font-size: 20px; color: #0f172a; margin: 0 0 16px; font-weight: 700;">
                                Reset Your Wholesale Account Password
                            </h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin: 0 0 16px;">
                                Hello <strong>{name}</strong>,
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin: 0 0 24px;">
                                We received a request to reset the password for your wholesale customer account registered with <strong>{email}</strong>.
                            </p>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin: 0 0 28px;">
                                Click the button below to set a new password for your account:
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 35px;">
                                <a href="{reset_link}" style="display: inline-block; background-color: #2563eb; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 8px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35); text-transform: uppercase; letter-spacing: 0.05em;">
                                    Reset Password &rarr;
                                </a>
                            </div>

                            <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 0 0 16px;">
                                If you're having trouble clicking the button above, copy and paste the URL below into your web browser:
                            </p>
                            <p style="font-size: 12px; line-height: 1.6; word-break: break-all; background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 6px; color: #2563eb; margin: 0 0 24px;">
                                <a href="{reset_link}" style="color: #2563eb; text-decoration: none;">{reset_link}</a>
                            </p>

                            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 24px;">
                                <p style="font-size: 12px; line-height: 1.5; color: #94a3b8; margin: 0;">
                                    ⏳ <strong>Security Notice:</strong> This password reset link is valid for <strong>60 minutes</strong>. If you did not make this request, you can safely disregard this email—your account remains completely secure.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center;">
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 6px;">
                                &copy; {app_name}. All rights reserved.
                            </p>
                            <p style="font-size: 11px; color: #94a3b8; margin: 0;">
                                Wholesale Buyer Support: (478) 444-5385 &bull; Carolina Prime Distributors
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;

        EmailConfig::updateOrCreate(
            [
                'module' => 'customer',
                'action' => 'forgot-password',
            ],
            [
                'subject' => 'Forgot Password - Carolina Prime Wholesale',
                'html_template' => $htmlTemplate,
                'smtp_host' => 'smtp.gmail.com',
                'smtp_secure' => 'ssl',
                'smtp_port' => 465,
                'smtp_username' => 'kapilakshu040@gmail.com',
                'smtp_password' => '',
                'from_email' => 'kapilakshu040@gmail.com',
                'from_name' => 'Carolina Prime Distributors',
                'status' => 1,
                'variables' => json_encode([
                    '{name}' => 'Customer Name',
                    '{email}' => 'Customer Email Address',
                    '{reset_link}' => 'Password Reset URL',
                    '{app_name}' => 'Application Name',
                ], JSON_PRETTY_PRINT),
                'to' => null,
                'cc' => json_encode([
                    [
                        'name' => 'Akshay',
                        'email' => 'akshaywebstep@gmail.com',
                    ],
                ]),
                'bcc' => null,
            ]
        );
    }
}
