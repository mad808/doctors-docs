<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['phone', 'code', 'action', 'expires_at', 'is_used'];
    protected function casts(): array {
        return ['expires_at' => 'datetime', 'is_used' => 'boolean'];
    }
}