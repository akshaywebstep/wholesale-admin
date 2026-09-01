<?php

namespace App\Services;

use App\Models\EmailConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class DynamicMailService
{
    /**
     * Send an email dynamically using configuration stored in the email_configs table.
     *
     * @param string $module Module name (e.g. 'customer', 'admin')
     * @param string $action Action name (e.g. 'forgot-password')
     * @param string $toEmail Recipient email address
     * @param array $variables Key-value pairs for placeholder replacement (e.g. ['{name}' => 'John'])
     * @param string|null $recipientName Optional recipient name
     * @return array ['success' => bool, 'message' => string]
     */
    public static function sendDynamicEmail(
        string $module,
        string $action,
        string $toEmail,
        array $variables = [],
        ?string $recipientName = null
    ): array {
        try {
            $config = EmailConfig::getActive($module, $action);

            if (!$config) {
                Log::warning("DynamicMailService: No active email configuration found for module='{$module}' and action='{$action}'.");
                return [
                    'success' => false,
                    'message' => "No active email configuration found for '{$action}'.",
                ];
            }

            // Setup dynamic SMTP if host and credentials are provided
            if (!empty($config->smtp_host) && !empty($config->smtp_password)) {
                self::configureSmtp($config);
            } elseif (!empty($config->smtp_host) && empty($config->smtp_password)) {
                Log::info("DynamicMailService: smtp_password is empty for {$module}/{$action}. Falling back to default mail driver (" . config('mail.default') . ").");
            }

            // Merge default system variables
            $allVariables = array_merge([
                '{app_name}' => config('app.name', 'Carolina Prime Wholesale'),
                '{email}'    => $toEmail,
                '{name}'     => $recipientName ?? 'Customer',
            ], $variables);

            // Replace placeholders in subject and HTML template
            $subject = $config->subject;
            $htmlBody = $config->html_template;

            foreach ($allVariables as $placeholder => $value) {
                $subject = str_replace($placeholder, (string) $value, $subject);
                $htmlBody = str_replace($placeholder, (string) $value, $htmlBody);
            }

            // Send HTML email
            Mail::html($htmlBody, function ($message) use ($toEmail, $recipientName, $subject, $config) {
                $message->to($toEmail, $recipientName)
                        ->subject($subject);

                if (!empty($config->from_email)) {
                    $message->from($config->from_email, $config->from_name ?: config('app.name'));
                }

                // Apply CC if present
                if (!empty($config->cc)) {
                    self::applyRecipients($message, 'cc', $config->cc);
                }

                // Apply BCC if present
                if (!empty($config->bcc)) {
                    self::applyRecipients($message, 'bcc', $config->bcc);
                }
            });

            Log::info("DynamicMailService: Email sent successfully for module='{$module}', action='{$action}' to {$toEmail}.");

            return [
                'success' => true,
                'message' => 'Email sent successfully.',
            ];
        } catch (Throwable $e) {
            Log::error("DynamicMailService Exception: " . $e->getMessage(), [
                'module' => $module,
                'action' => $action,
                'to'     => $toEmail,
                'trace'  => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Configure runtime SMTP transport settings
     */
    protected static function configureSmtp(EmailConfig $config): void
    {
        $port = (int) ($config->smtp_port ?: 587);
        $secure = strtolower(trim((string) $config->smtp_secure));

        $encryption = match ($secure) {
            'ssl', '1' => 'ssl',
            'tls'      => 'tls',
            'null', '' => ($port === 465 ? 'ssl' : ($port === 587 ? 'tls' : null)),
            default    => $secure,
        };

        $scheme = ($encryption === 'ssl' || $port === 465) ? 'smtps' : 'smtp';

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport'  => 'smtp',
            'mail.mailers.smtp.scheme'     => $scheme,
            'mail.mailers.smtp.host'       => $config->smtp_host,
            'mail.mailers.smtp.port'       => $port,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.username'   => $config->smtp_username,
            'mail.mailers.smtp.password'   => $config->smtp_password,
        ]);

        if (!empty($config->from_email)) {
            config([
                'mail.from.address' => $config->from_email,
                'mail.from.name'    => $config->from_name ?: config('mail.from.name'),
            ]);
        }

        Mail::purge('smtp');
    }

    /**
     * Parse and apply CC or BCC recipients (supports JSON array or comma-separated string)
     */
    protected static function applyRecipients($message, string $type, mixed $recipients): void
    {
        if (empty($recipients)) {
            return;
        }

        $list = [];

        if (is_string($recipients)) {
            $decoded = json_decode($recipients, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $list = $decoded;
            } else {
                $list = array_map('trim', explode(',', $recipients));
            }
        } elseif (is_array($recipients)) {
            $list = $recipients;
        }

        foreach ($list as $item) {
            if (is_array($item) && !empty($item['email'])) {
                $name = $item['name'] ?? null;
                $message->{$type}($item['email'], $name);
            } elseif (is_string($item) && filter_var(trim($item), FILTER_VALIDATE_EMAIL)) {
                $message->{$type}(trim($item));
            }
        }
    }
}
