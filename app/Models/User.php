<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    use HasFactory;

    const __COMMOM__ = 1;
    const __SHOPMAN__ = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type'
    ];

    protected $attributes = [
        'type' => self::__COMMOM__,
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactionAsPayer(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function transactionAsPayee(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    public static function returnTypeDescriptionUser(int $type): string
    {
        return match($type) {
            self::__COMMOM__ => 'Commom',
            self::__SHOPMAN__ => 'Shopman'
        };
    }
}
