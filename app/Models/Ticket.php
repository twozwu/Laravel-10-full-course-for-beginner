<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'attachment', 'user_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // 會自動綁上 user_id (xxx_id)
        // return $this->belongsTo(User::class, '自訂ID'); // 如果 id 非預設格式則需自定義
    }
}
