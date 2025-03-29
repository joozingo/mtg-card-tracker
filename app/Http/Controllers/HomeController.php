<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Collection;
use App\Models\Deck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the landing page with statistics
     */
    public function index()
    {
        // Get card statistics
        $totalCards = Card::count();
        $totalInCollection = Collection::sum('quantity');

        // Get most valuable cards in collection
        $mostValuableCards = Card::join('collections', 'cards.id', '=', 'collections.card_id')
            ->selectRaw('cards.*, collections.quantity')
            ->whereNotNull('cards.price_usd')
            ->orderByRaw('cards.price_usd * collections.quantity DESC')
            ->limit(5)
            ->get();

        // Get collection value
        $collectionValue = Card::join('collections', 'cards.id', '=', 'collections.card_id')
            ->selectRaw('SUM(cards.price_usd * collections.quantity) as total_value')
            ->whereNotNull('cards.price_usd')
            ->value('total_value');

        // Get deck statistics
        $totalDecks = Deck::count();
        $latestDecks = Deck::orderBy('created_at', 'desc')->limit(3)->get();

        // Get card types distribution
        $cardTypeDistribution = Card::join('collections', 'cards.id', '=', 'collections.card_id')
            ->selectRaw('
                CASE
                    WHEN type_line LIKE "%Creature%" THEN "Creatures"
                    WHEN type_line LIKE "%Instant%" THEN "Instants"
                    WHEN type_line LIKE "%Sorcery%" THEN "Sorceries"
                    WHEN type_line LIKE "%Enchantment%" THEN "Enchantments"
                    WHEN type_line LIKE "%Artifact%" THEN "Artifacts"
                    WHEN type_line LIKE "%Planeswalker%" THEN "Planeswalkers"
                    WHEN type_line LIKE "%Land%" THEN "Lands"
                    ELSE "Other"
                END as card_type,
                SUM(collections.quantity) as count
            ')
            ->groupBy('card_type')
            ->orderBy('count', 'desc')
            ->get();

        // Get rarity distribution
        $rarityDistribution = Card::join('collections', 'cards.id', '=', 'collections.card_id')
            ->selectRaw('rarity, SUM(collections.quantity) as count')
            ->groupBy('rarity')
            ->orderBy('count', 'desc')
            ->get();

        // Get color distribution
        $colorDistribution = Card::join('collections', 'cards.id', '=', 'collections.card_id')
            ->selectRaw('
                CASE
                    WHEN color_identity = "[]" THEN "Colorless"
                    WHEN color_identity LIKE "%W%" AND color_identity LIKE "%U%" AND color_identity LIKE "%B%" AND color_identity LIKE "%R%" AND color_identity LIKE "%G%" THEN "Five-Color"
                    WHEN (color_identity LIKE "%W%" AND color_identity LIKE "%U%") OR
                         (color_identity LIKE "%U%" AND color_identity LIKE "%B%") OR
                         (color_identity LIKE "%B%" AND color_identity LIKE "%R%") OR
                         (color_identity LIKE "%R%" AND color_identity LIKE "%G%") OR
                         (color_identity LIKE "%G%" AND color_identity LIKE "%W%") OR
                         (color_identity LIKE "%W%" AND color_identity LIKE "%B%") OR
                         (color_identity LIKE "%U%" AND color_identity LIKE "%R%") OR
                         (color_identity LIKE "%B%" AND color_identity LIKE "%G%") OR
                         (color_identity LIKE "%R%" AND color_identity LIKE "%W%") OR
                         (color_identity LIKE "%G%" AND color_identity LIKE "%U%") THEN "Multi-Color"
                    WHEN color_identity LIKE "%W%" THEN "White"
                    WHEN color_identity LIKE "%U%" THEN "Blue"
                    WHEN color_identity LIKE "%B%" THEN "Black"
                    WHEN color_identity LIKE "%R%" THEN "Red"
                    WHEN color_identity LIKE "%G%" THEN "Green"
                    ELSE "Other"
                END as color,
                SUM(collections.quantity) as count
            ')
            ->groupBy('color')
            ->orderBy('count', 'desc')
            ->get();

        // Get wishlist stats
        $wishlistCount = \App\Models\Wishlist::count();
        $highPriorityCount = \App\Models\Wishlist::where('priority', 1)->count();

        return view('welcome', compact(
            'totalCards',
            'totalInCollection',
            'mostValuableCards',
            'collectionValue',
            'totalDecks',
            'latestDecks',
            'cardTypeDistribution',
            'rarityDistribution',
            'colorDistribution',
            'wishlistCount',
            'highPriorityCount'
        ));
    }
}
