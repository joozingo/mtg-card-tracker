<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'format',
        'description',
        'cover_image',
        'is_public',
        'user_id',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * The cards that belong to the deck
     */
    public function cards()
    {
        return $this->belongsToMany(Card::class)
            ->withPivot('quantity', 'location')
            ->withTimestamps();
    }

    /**
     * Get the user that owns the deck
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get only the main deck cards
     */
    public function mainDeckCards()
    {
        return $this->belongsToMany(Card::class)
            ->withPivot('quantity', 'location')
            ->wherePivot('location', 'main')
            ->withTimestamps();
    }

    /**
     * Get only the sideboard cards
     */
    public function sideboardCards()
    {
        return $this->belongsToMany(Card::class)
            ->withPivot('quantity', 'location')
            ->wherePivot('location', 'sideboard')
            ->withTimestamps();
    }

    /**
     * Get total number of cards in the deck
     */
    public function getTotalCardsAttribute()
    {
        return $this->cards()
            ->wherePivot('location', 'main')
            ->sum('quantity');
    }

    /**
     * Get total number of cards in the sideboard
     */
    public function getTotalSideboardCardsAttribute()
    {
        return $this->cards()
            ->wherePivot('location', 'sideboard')
            ->sum('quantity');
    }
}
