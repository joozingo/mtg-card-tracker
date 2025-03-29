<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of wishlist items.
     */
    public function index()
    {
        $wishlistItems = Wishlist::with('card')
            ->where('user_id', auth()->id())
            ->orderBy('priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Show the form for adding a card to the wishlist.
     */
    public function create(Card $card)
    {
        // Check if card is already in wishlist for this user
        $existingWishlist = Wishlist::where('card_id', $card->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingWishlist) {
            return redirect()->route('wishlist.edit', $existingWishlist);
        }

        return view('wishlist.create', compact('card'));
    }

    /**
     * Store a newly created wishlist item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'priority' => 'required|integer|in:1,2,3',
            'max_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Add user_id to the validated data
        $validated['user_id'] = auth()->id();

        // Check if card is already in wishlist for this user
        $existing = Wishlist::where('card_id', $validated['card_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('wishlist.edit', $existing)
                ->with('error', 'This card is already in your wishlist.');
        }

        $wishlistItem = Wishlist::create($validated);

        return redirect()->route('wishlist.index')
            ->with('success', 'Card added to your wishlist successfully.');
    }

    /**
     * Show the form for editing the specified wishlist item.
     */
    public function edit(Wishlist $wishlist)
    {
        // Check if the wishlist belongs to the authenticated user
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->load('card');
        return view('wishlist.edit', compact('wishlist'));
    }

    /**
     * Update the specified wishlist item.
     */
    public function update(Request $request, Wishlist $wishlist)
    {
        // Check if the wishlist belongs to the authenticated user
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'priority' => 'required|integer|in:1,2,3',
            'max_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $wishlist->update($validated);

        return redirect()->route('wishlist.index')
            ->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Remove the specified wishlist item.
     */
    public function destroy(Wishlist $wishlist)
    {
        // Check if the wishlist belongs to the authenticated user
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $wishlist->delete();

        return redirect()->route('wishlist.index')
            ->with('success', 'Item removed from your wishlist.');
    }

    /**
     * Move a card from wishlist to collection
     */
    public function moveToCollection(Wishlist $wishlist)
    {
        // Check if the wishlist belongs to the authenticated user
        if ($wishlist->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $card = $wishlist->card;

        // Delete from wishlist
        $wishlist->delete();

        // Redirect to add to collection form
        return redirect()->route('collection.create', $card)
            ->with('success', 'Card removed from wishlist. Add it to your collection:');
    }
}
