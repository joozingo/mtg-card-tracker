<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'quantity',
        'condition',
        'foil',
        'purchase_price',
        'acquired_date',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'foil' => 'boolean',
        'purchase_price' => 'decimal:2',
        'acquired_date' => 'date',
    ];

    /**
     * Get the card that is in the collection
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the user that owns the collection entry
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
