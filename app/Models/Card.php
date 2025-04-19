<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Jobs\CacheCardImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'scryfall_id',
        'name',
        'oracle_id',
        'oracle_text',
        'mana_cost',
        'type_line',
        'set',
        'set_name',
        'rarity',
        'cmc',
        'power',
        'toughness',
        'loyalty',
        'colors',
        'color_identity',
        'layout',
        'reserved',
        'artist',
        'collector_number',
        'image_uri_small',
        'image_uri_normal',
        'image_uri_large',
        'price_usd',
        'price_eur',
        'keywords',
        'legalities',
    ];

    protected $casts = [
        'keywords' => 'array',
        'legalities' => 'array',
        'reserved' => 'boolean',
        'cmc' => 'float',
        'price_usd' => 'float',
        'price_eur' => 'float',
    ];

    public function getColorArrayAttribute()
    {
        return $this->colors ? json_decode($this->colors) : [];
    }

    public function getColorIdentityArrayAttribute()
    {
        return $this->color_identity ? json_decode($this->color_identity) : [];
    }

    /**
     * Get the collections for this card
     */
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * Check if this card is in the user's collection
     */
    public function isInCollection()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->collections()->where('user_id', auth()->id())->exists();
    }

    /**
     * Get the total quantity of this card in collection
     */
    public function collectionQuantity()
    {
        if (!auth()->check()) {
            return 0;
        }
        return $this->collections()->where('user_id', auth()->id())->sum('quantity');
    }

    /**
     * The decks that this card belongs to
     */
    public function decks()
    {
        return $this->belongsToMany(Deck::class)
            ->withPivot('quantity', 'location')
            ->withTimestamps();
    }

    /**
     * Get the wishlist entry for this card
     */
    public function wishlist()
    {
        if (!auth()->check()) {
            return $this->hasOne(Wishlist::class)->whereNull('id');
        }
        return $this->hasOne(Wishlist::class)->where('user_id', auth()->id());
    }

    /**
     * Check if this card is in the wishlist
     */
    public function isInWishlist()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->hasOne(Wishlist::class)->where('user_id', auth()->id())->exists();
    }

    /**
     * Get the local cached image URL or the Scryfall URL,
     * and dispatch a job to cache if not found locally.
     */
    public function getLocalOrScryfallImageUrl(string $size = 'normal'): ?string
    {
        // Generate the expected relative path in the public disk
        $prefix = substr($this->scryfall_id, 0, 2);
        $filename = $this->scryfall_id . '.jpg';
        $relativePath = "card_images/{$size}/{$prefix}/{$filename}";

        // Check if the file exists in the public storage
        if (Storage::disk('public')->exists($relativePath)) {
            // Return the public URL
            return Storage::disk('public')->url($relativePath);
        }

        // File not cached, return the Scryfall URL
        $scryfallUrl = match ($size) {
            'small' => $this->image_uri_small,
            'large' => $this->image_uri_large,
            default => $this->image_uri_normal,
        };

        // If we have a Scryfall URL, dispatch the caching job
        if ($scryfallUrl) {
            CacheCardImage::dispatch($this, $size)->onQueue('image-caching'); // Dispatch to a specific queue if needed
        }

        // Return the original Scryfall URL (or null if none exists)
        return $scryfallUrl;
    }
}
