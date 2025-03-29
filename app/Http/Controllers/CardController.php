<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Card::with('collections');

        // Handle sorting
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price_usd', 'asc')->orderBy('name', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_usd', 'desc')->orderBy('name', 'asc');
                break;
            case 'rarity':
                $query->orderByRaw("FIELD(rarity, 'mythic', 'rare', 'uncommon', 'common') ASC")->orderBy('name', 'asc');
                break;
            case 'release_date':
                $query->orderBy('released_at', 'desc')->orderBy('name', 'asc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $cards = $query->paginate(20)->withQueryString();
        return view('cards.index', compact('cards'));
    }

    /**
     * Search for cards by name, text, type, etc.
     */
    public function search(Request $request)
    {
        $query = Card::with('collections');

        // Search by name
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Search by text
        if ($request->has('text') && !empty($request->text)) {
            $query->where('oracle_text', 'like', '%' . $request->text . '%');
        }

        // Search by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type_line', 'like', '%' . $request->type . '%');
        }

        // Filter by set
        if ($request->has('set') && !empty($request->set)) {
            $query->where('set', $request->set);
        }

        // Filter by rarity
        if ($request->has('rarity') && !empty($request->rarity)) {
            $query->where('rarity', $request->rarity);
        }

        // Filter by mana cost
        if ($request->has('mana_cost') && !empty($request->mana_cost)) {
            $query->where('mana_cost', 'like', '%' . $request->mana_cost . '%');
        }

        // Filter by color identity
        if ($request->has('colors') && is_array($request->colors) && !empty($request->colors)) {
            $colorMatch = $request->get('color_match', 'include');

            if ($colorMatch === 'exact') {
                // Exactly these colors, no more no less
                $query->where(function ($q) use ($request) {
                    foreach ($request->colors as $color) {
                        $q->where('color_identity', 'like', '%' . $color . '%');
                    }

                    // Exclude other colors
                    $allColors = ['W', 'U', 'B', 'R', 'G'];
                    foreach (array_diff($allColors, $request->colors) as $excludedColor) {
                        $q->where('color_identity', 'not like', '%' . $excludedColor . '%');
                    }
                });
            } elseif ($colorMatch === 'at_most') {
                // At most these colors (subset allowed)
                $query->where(function ($q) use ($request) {
                    // Exclude other colors
                    $allColors = ['W', 'U', 'B', 'R', 'G'];
                    foreach (array_diff($allColors, $request->colors) as $excludedColor) {
                        $q->where('color_identity', 'not like', '%' . $excludedColor . '%');
                    }
                });
            } else {
                // Default: Include these colors (additional colors allowed)
                $query->where(function ($q) use ($request) {
                    foreach ($request->colors as $color) {
                        $q->where('color_identity', 'like', '%' . $color . '%');
                    }
                });
            }

            // Handle colorless specifically if requested
            if (in_array('C', $request->colors)) {
                // Modify the query to include colorless cards
                $query->orWhere('color_identity', '[]');
            }
        }

        // Use appends to preserve all filter parameters in pagination links
        $cards = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('cards.search', compact('cards'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $card = Card::with('collections')->where('scryfall_id', $id)->firstOrFail();
        return view('cards.show', compact('card'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
