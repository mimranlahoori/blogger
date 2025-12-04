<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'to_email', 'subject', 'message', 'headers', 'status',
        'attempts', 'last_attempt', 'sent_at', 'error_message'
    ];

    protected $casts = [
        'last_attempt' => 'datetime',
        'sent_at' => 'datetime'
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
