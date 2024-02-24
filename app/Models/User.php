<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;

    const __COMMOM__ = 1;
    const __SHOPMAN__ = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'document'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactionAsPayer(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function transactionAsPayee(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }
}
