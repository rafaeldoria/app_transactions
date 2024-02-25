<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    const __TYPE_CPF__ = 1;
    const __TYPE_CNPJ__ = 2;

    protected $fillable = [
        'type',
        'value',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function returnTypeDescriptionDocument(int $type): string
    {
        return match($type) {
            self::__TYPE_CPF__ => 'CPF',
            self::__TYPE_CNPJ__ => 'CNPJ'
        };
    }
}
