<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'max_participants',
        'contact_person',
        'contact_phone',
        'status',
        'type',
        'submitted_by',
        'submitted_email',
        'submitted_phone',
        'admin_notes',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    public function scopeToday($query)
    {
        return $query->where('event_date', now()->toDateString());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('event_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('event_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeUserSubmitted($query)
    {
        return $query->where('type', 'user');
    }

    public function scopeAdminCreated($query)
    {
        return $query->where('type', 'admin');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date ? $this->event_date->format('d M Y') : '';
    }

    public function getFormattedTimeAttribute()
    {
        try {
            $startTime = '';
            $endTime = '';
            
            // Validasi dan format start_time
            if ($this->start_time) {
                if ($this->isValidTime($this->start_time)) {
                    $startTime = Carbon::parse($this->start_time)->format('H:i');
                } else {
                    \Log::warning('Invalid start_time format', [
                        'event_id' => $this->id,
                        'start_time' => $this->start_time
                    ]);
                    $startTime = 'Invalid Time';
                }
            }
            
            // Validasi dan format end_time
            if ($this->end_time) {
                if ($this->isValidTime($this->end_time)) {
                    $endTime = Carbon::parse($this->end_time)->format('H:i');
                } else {
                    \Log::warning('Invalid end_time format', [
                        'event_id' => $this->id,
                        'end_time' => $this->end_time
                    ]);
                    $endTime = 'Invalid Time';
                }
            }
            
            return $startTime && $endTime ? $startTime . ' - ' . $endTime : ($startTime ?: $endTime);
            
        } catch (\Exception $e) {
            \Log::error('Error formatting time', [
                'event_id' => $this->id,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'error' => $e->getMessage()
            ]);
            
            return 'Invalid Time';
        }
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Cek apakah file exists
            $imagePath = storage_path('app/public/events/' . $this->image);
            if (file_exists($imagePath)) {
                return asset('storage/events/' . $this->image);
            } else {
                \Log::warning('Event image not found', [
                    'event_id' => $this->id,
                    'image' => $this->image,
                    'path' => $imagePath
                ]);
            }
        }
        
        // Fallback ke placeholder image
        return asset('images/placeholder-event.jpg');
    }

    // Helper methods
    public function isUpcoming()
    {
        return $this->event_date >= now()->toDateString();
    }

    public function isPast()
    {
        return $this->event_date < now()->toDateString();
    }

    public function isToday()
    {
        return $this->event_date == now()->toDateString();
    }

    public function isUserSubmitted()
    {
        return $this->type === 'user';
    }

    public function isAdminCreated()
    {
        return $this->type === 'admin';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Helper function untuk validasi format waktu
    private function isValidTime($time)
    {
        // Cek format HH:MM atau H:MM
        if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            return false;
        }
        
        // Cek apakah jam valid (0-23)
        $parts = explode(':', $time);
        $hour = (int)$parts[0];
        $minute = (int)$parts[1];
        
        return $hour >= 0 && $hour <= 23 && $minute >= 0 && $minute <= 59;
    }
}
