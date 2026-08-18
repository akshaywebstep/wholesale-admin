<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Added for API Tokens

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; // HasApiTokens added here

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'business_name',
        'gst_number',
        'kyc_document',
        'address',
        'status',
        'customer_group_id',
        'country_id',
        'state_id',
        'city_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_has_role');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function hasPermission(string $panel, string $module, string $action): bool
    {
        foreach ($this->roles as $role) {
            $has = $role->permissions()
                ->where('panel', $panel)
                ->where('module', $module)
                ->where('action', $action)
                ->where('status', 1)
                ->exists();

            if ($has) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function assignRoles(array $roleIds): void
    {
        $this->roles()->syncWithoutDetaching($roleIds);
    }

    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }
}
