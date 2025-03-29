<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\CardController;
use App\Models\Card;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // If user is not logged in, redirect to login page
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $totalCards = Card::count();

    // Get user data
    $totalInCollection = \App\Models\Collection::where('user_id', auth()->id())->sum('quantity');

    // Collection value calculation
    $collectionValue = \App\Models\Collection::where('user_id', auth()->id())
        ->join('cards', 'collections.card_id', '=', 'cards.id')
        ->selectRaw('SUM(collections.quantity * cards.price_usd) as total_value')
        ->value('total_value') ?? 0;

    $totalDecks = \App\Models\Deck::where('user_id', auth()->id())->count();

    // Get most valuable cards
    $mostValuableCards = \App\Models\Card::join('collections', 'cards.id', '=', 'collections.card_id')
        ->where('collections.user_id', auth()->id())
        ->select('cards.*', 'collections.quantity')
        ->orderByRaw('cards.price_usd * collections.quantity DESC')
        ->limit(5)
        ->get();

    // Card type distribution
    $cardTypeDistribution = \App\Models\Collection::where('collections.user_id', auth()->id())
        ->join('cards', 'collections.card_id', '=', 'cards.id')
        ->selectRaw("
            CASE
                WHEN cards.type_line LIKE '%Creature%' THEN 'Creatures'
                WHEN cards.type_line LIKE '%Instant%' THEN 'Instants'
                WHEN cards.type_line LIKE '%Sorcery%' THEN 'Sorceries'
                WHEN cards.type_line LIKE '%Artifact%' THEN 'Artifacts'
                WHEN cards.type_line LIKE '%Enchantment%' THEN 'Enchantments'
                WHEN cards.type_line LIKE '%Planeswalker%' THEN 'Planeswalkers'
                WHEN cards.type_line LIKE '%Land%' THEN 'Lands'
                ELSE 'Other'
            END as card_type,
            SUM(collections.quantity) as count")
        ->groupBy('card_type')
        ->orderBy('count', 'desc')
        ->get();

    // Color distribution
    $colorDistribution = \App\Models\Collection::where('collections.user_id', auth()->id())
        ->join('cards', 'collections.card_id', '=', 'cards.id')
        ->selectRaw("
            CASE
                WHEN cards.colors = '[]' THEN 'Colorless'
                WHEN cards.colors LIKE '%W%' AND cards.colors LIKE '%U%' AND cards.colors LIKE '%B%' AND cards.colors LIKE '%R%' AND cards.colors LIKE '%G%' THEN 'Five-Color'
                WHEN JSON_LENGTH(cards.colors) > 1 THEN 'Multi-Color'
                WHEN cards.colors LIKE '%W%' THEN 'White'
                WHEN cards.colors LIKE '%U%' THEN 'Blue'
                WHEN cards.colors LIKE '%B%' THEN 'Black'
                WHEN cards.colors LIKE '%R%' THEN 'Red'
                WHEN cards.colors LIKE '%G%' THEN 'Green'
                ELSE 'Other'
            END as color,
            SUM(collections.quantity) as count")
        ->groupBy('color')
        ->orderBy('count', 'desc')
        ->get();

    // Get latest decks
    $latestDecks = \App\Models\Deck::where('user_id', auth()->id())
        ->withCount([
            'cards as total_cards' => function ($query) {
                $query->where('location', 'main');
            }
        ])
        ->withCount([
            'cards as total_sideboard_cards' => function ($query) {
                $query->where('location', 'sideboard');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    // Wishlist counts
    $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
    $highPriorityCount = \App\Models\Wishlist::where('user_id', auth()->id())
        ->where('priority', 1)
        ->count();

    // Rarity distribution
    $rarityDistribution = \App\Models\Collection::where('collections.user_id', auth()->id())
        ->join('cards', 'collections.card_id', '=', 'cards.id')
        ->selectRaw('cards.rarity, SUM(collections.quantity) as count')
        ->groupBy('cards.rarity')
        ->orderByRaw("FIELD(cards.rarity, 'mythic', 'rare', 'uncommon', 'common')")
        ->get();

    return view('welcome', compact(
        'totalCards',
        'totalInCollection',
        'collectionValue',
        'totalDecks',
        'mostValuableCards',
        'cardTypeDistribution',
        'colorDistribution',
        'latestDecks',
        'wishlistCount',
        'highPriorityCount',
        'rarityDistribution'
    ));
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Collection routes
    Route::resource('collection', CollectionController::class);

    // Wishlist routes
    Route::resource('wishlist', WishlistController::class);
    Route::get('/wishlist/{wishlist}/move-to-collection', [WishlistController::class, 'moveToCollection'])->name('wishlist.move-to-collection');

    // Deck routes
    Route::resource('decks', DeckController::class);
    Route::post('/decks/{deck}/add-card', [DeckController::class, 'addCard'])->name('decks.add-card');
    Route::delete('/decks/{deck}/remove-card', [DeckController::class, 'removeCard'])->name('decks.remove-card');
});

// Card routes (publicly accessible)
Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
Route::get('/cards/search', [CardController::class, 'search'])->name('cards.search');
Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

require __DIR__ . '/auth.php';
