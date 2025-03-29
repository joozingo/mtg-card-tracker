<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $decks = Deck::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('decks.index', compact('decks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('decks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'format' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        // Add user_id to the validated data
        $validated['user_id'] = auth()->id();

        $deck = Deck::create($validated);

        return redirect()->route('decks.show', $deck)->with('success', "Deck '{$deck->name}' created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Deck $deck)
    {
        // Check if the deck belongs to the authenticated user or is public
        if ($deck->user_id !== auth()->id() && !$deck->is_public) {
            abort(403, 'Unauthorized action.');
        }

        $mainDeckCards = $deck->mainDeckCards;
        $sideboardCards = $deck->sideboardCards;

        // Group cards by type for organized display
        $mainDeckByType = $this->groupCardsByType($mainDeckCards);
        $sideboardByType = $this->groupCardsByType($sideboardCards);

        return view('decks.show', compact('deck', 'mainDeckByType', 'sideboardByType'));
    }

    /**
     * Group cards by their type for organized display
     */
    private function groupCardsByType($cards)
    {
        $groups = [
            'Creatures' => [],
            'Spells' => [],
            'Artifacts' => [],
            'Enchantments' => [],
            'Planeswalkers' => [],
            'Lands' => [],
            'Other' => [],
        ];

        foreach ($cards as $card) {
            $typeLine = strtolower($card->type_line);
            $quantity = $card->pivot->quantity;

            if (str_contains($typeLine, 'creature')) {
                $groups['Creatures'][] = ['card' => $card, 'quantity' => $quantity];
            } elseif (str_contains($typeLine, 'instant') || str_contains($typeLine, 'sorcery')) {
                $groups['Spells'][] = ['card' => $card, 'quantity' => $quantity];
            } elseif (str_contains($typeLine, 'artifact')) {
                $groups['Artifacts'][] = ['card' => $card, 'quantity' => $quantity];
            } elseif (str_contains($typeLine, 'enchantment')) {
                $groups['Enchantments'][] = ['card' => $card, 'quantity' => $quantity];
            } elseif (str_contains($typeLine, 'planeswalker')) {
                $groups['Planeswalkers'][] = ['card' => $card, 'quantity' => $quantity];
            } elseif (str_contains($typeLine, 'land')) {
                $groups['Lands'][] = ['card' => $card, 'quantity' => $quantity];
            } else {
                $groups['Other'][] = ['card' => $card, 'quantity' => $quantity];
            }
        }

        // Remove empty groups
        return array_filter($groups);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deck $deck)
    {
        // Check if the deck belongs to the authenticated user
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('decks.edit', compact('deck'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deck $deck)
    {
        // Check if the deck belongs to the authenticated user
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'format' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $deck->update($validated);

        return redirect()->route('decks.show', $deck)->with('success', "Deck '{$deck->name}' updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deck $deck)
    {
        // Check if the deck belongs to the authenticated user
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $deckName = $deck->name;
        $deck->delete();

        return redirect()->route('decks.index')->with('success', "Deck '{$deckName}' deleted successfully!");
    }

    /**
     * Add a card to the deck
     */
    public function addCard(Request $request, Deck $deck)
    {
        // Check if the deck belongs to the authenticated user
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'quantity' => 'required|integer|min:1|max:99',
            'location' => 'required|in:main,sideboard,maybe',
        ]);

        $card = Card::findOrFail($validated['card_id']);

        // Check if card already exists in this location
        $existingCard = $deck->cards()
            ->wherePivot('card_id', $validated['card_id'])
            ->wherePivot('location', $validated['location'])
            ->first();

        if ($existingCard) {
            // Update quantity
            $deck->cards()->updateExistingPivot($validated['card_id'], [
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
            ]);
        } else {
            // Add new card
            $deck->cards()->attach($validated['card_id'], [
                'quantity' => $validated['quantity'],
                'location' => $validated['location'],
            ]);
        }

        $locationText = ucfirst($validated['location']);
        $successMessage = "Added {$validated['quantity']} × {$card->name} to {$locationText} deck!";

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Remove a card from the deck
     */
    public function removeCard(Request $request, Deck $deck)
    {
        // Check if the deck belongs to the authenticated user
        if ($deck->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'location' => 'required|in:main,sideboard,maybe',
        ]);

        $card = Card::findOrFail($validated['card_id']);

        $deck->cards()
            ->wherePivot('card_id', $validated['card_id'])
            ->wherePivot('location', $validated['location'])
            ->detach();

        return redirect()->back()->with('success', "Removed {$card->name} from the deck!");
    }
}
