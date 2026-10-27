<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OtpGatewayMessage extends Model
{
    protected $fillable = ['phone', 'message', 'status', 'locked_at', 'sent_at'];
    protected function casts(): array {
        return ['status' => 'integer', 'locked_at' => 'datetime', 'sent_at' => 'datetime'];
    }
}