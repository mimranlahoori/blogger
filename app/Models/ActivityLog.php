<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'description', 'ip_address',
        'user_agent', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Methods
    public static function log($action, $description = null, $userId = null, $metadata = [])
    {
        return self::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => $metadata
        ]);
    }
}
