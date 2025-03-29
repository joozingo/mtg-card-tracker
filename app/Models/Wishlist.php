<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'priority',
        'max_price',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'max_price' => 'decimal:2',
    ];

    /**
     * Get the card that is in the wishlist
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the user that owns the wishlist entry
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the priority as a text label
     */
    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            1 => 'High',
            2 => 'Medium',
            default => 'Low',
        };
    }
}
