<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display the user's collection
     */
    public function index()
    {
        $collection = Collection::with('card')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('collection.index', compact('collection'));
    }

    /**
     * Add a card to the collection
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:mint,near_mint,excellent,good,light_played,played,poor',
            'foil' => 'boolean',
            'purchase_price' => 'nullable|numeric',
            'acquired_date' => 'nullable|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $card = Card::find($validated['card_id']);

        // Add the user_id to the validated data
        $validated['user_id'] = auth()->id();

        Collection::create($validated);

        return redirect()->route('collection.index')->with('success', "Added {$validated['quantity']} × {$card->name} to your collection!");
    }

    /**
     * Show the form to add a card to collection
     */
    public function create(Card $card)
    {
        return view('collection.create', compact('card'));
    }

    /**
     * Update a card in the collection
     */
    public function update(Request $request, Collection $collection)
    {
        // Check if the collection belongs to the authenticated user
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:mint,near_mint,excellent,good,light_played,played,poor',
            'foil' => 'boolean',
            'purchase_price' => 'nullable|numeric',
            'acquired_date' => 'nullable|date',
            'notes' => 'nullable|string|max:255',
        ]);

        $collection->update($validated);

        return redirect()->route('collection.index')->with('success', "Updated {$collection->card->name} in your collection!");
    }

    /**
     * Remove a card from the collection
     */
    public function destroy(Collection $collection)
    {
        // Check if the collection belongs to the authenticated user
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $cardName = $collection->card->name;
        $collection->delete();

        return redirect()->route('collection.index')->with('success', "Removed {$cardName} from your collection!");
    }

    /**
     * Show form to edit a collection entry
     */
    public function edit(Collection $collection)
    {
        // Check if the collection belongs to the authenticated user
        if ($collection->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('collection.edit', compact('collection'));
    }
}
