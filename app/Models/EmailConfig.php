<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    use HasFactory;

    protected $table = 'email_configs';

    protected $fillable = [
        'module',
        'action',
        'subject',
        'html_template',
        'smtp_host',
        'smtp_secure',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'from_email',
        'from_name',
        'status',
        'variables',
        'to',
        'cc',
        'bcc',
    ];

    protected $casts = [
        'status' => 'boolean',
        'smtp_port' => 'integer',
    ];

    /**
     * Get active configuration for a specific module and action
     */
    public static function getActive(string $module, string $action): ?self
    {
        return self::where('module', $module)
            ->where('action', $action)
            ->where('status', 1)
            ->first();
    }
}
