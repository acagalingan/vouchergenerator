<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // might need to be implemented better for scaleability and race conditions
    public static function generateVoucherCode()
    {
        $code = Str::random(5);

        while(self::query()->where('code', $code)->exists()){
            $code = Str::random(5);
        }
        return $code;
    }
}
