<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetController extends Controller
{
    /**
     * Display a listing of card sets.
     */
    public function index(Request $request)
    {
        // Handle search for set code or name
        $search = $request->input('search');
        // Handle sorting of sets
        $sort = $request->input('sort', 'name_asc');

        $query = Card::query();
        if ($search) {
            $query->where('set', 'like', "%{$search}%")
                ->orWhere('set_name', 'like', "%{$search}%");
        }
        // Base query grouping and counting
        $setsQuery = $query->groupBy('set', 'set_name')
            ->select('set', 'set_name', DB::raw('count(*) as card_count'));
        // Apply sorting
        switch ($sort) {
            case 'name_desc':
                $setsQuery->orderBy('set_name', 'desc');
                break;
            case 'code_asc':
                $setsQuery->orderBy('set', 'asc');
                break;
            case 'code_desc':
                $setsQuery->orderBy('set', 'desc');
                break;
            case 'count_asc':
                $setsQuery->orderBy('card_count', 'asc');
                break;
            case 'count_desc':
                $setsQuery->orderBy('card_count', 'desc');
                break;
            case 'name_asc':
            default:
                $setsQuery->orderBy('set_name', 'asc');
                break;
        }
        $sets = $setsQuery->get();

        return view('sets.index', compact('sets', 'search', 'sort'));
    }

    /**
     * Display the cards for a specific set.
     */
    public function show(Request $request, string $set)
    {
        $query = Card::with('collections')->where('set', $set);
        // Apply filters similar to advanced card search
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('text')) {
            $query->where('oracle_text', 'like', '%' . $request->text . '%');
        }
        if ($request->filled('type')) {
            $query->where('type_line', 'like', '%' . $request->type . '%');
        }
        if ($request->filled('rarity')) {
            $query->where('rarity', $request->rarity);
        }
        if ($request->filled('mana_cost')) {
            $query->where('mana_cost', 'like', '%' . $request->mana_cost . '%');
        }
        if ($request->has('colors') && is_array($request->colors) && !empty($request->colors)) {
            $colorMatch = $request->get('color_match', 'include');
            if ($colorMatch === 'exact') {
                $query->where(function ($q) use ($request) {
                    foreach ($request->colors as $color) {
                        $q->where('color_identity', 'like', '%' . $color . '%');
                    }
                    $allColors = ['W', 'U', 'B', 'R', 'G'];
                    foreach (array_diff($allColors, $request->colors) as $excludedColor) {
                        $q->where('color_identity', 'not like', '%' . $excludedColor . '%');
                    }
                });
            } elseif ($colorMatch === 'at_most') {
                $query->where(function ($q) use ($request) {
                    $allColors = ['W', 'U', 'B', 'R', 'G'];
                    foreach (array_diff($allColors, $request->colors) as $excludedColor) {
                        $q->where('color_identity', 'not like', '%' . $excludedColor . '%');
                    }
                });
            } else {
                $query->where(function ($q) use ($request) {
                    foreach ($request->colors as $color) {
                        $q->where('color_identity', 'like', '%' . $color . '%');
                    }
                });
            }
            if (in_array('C', $request->colors)) {
                $query->orWhere('color_identity', '[]');
            }
        }
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
                $query->orderByRaw("FIELD(rarity,'mythic','rare','uncommon','common') ASC")->orderBy('name', 'asc');
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
        $setName = Card::where('set', $set)->value('set_name');

        return view('sets.show', compact('cards', 'set', 'setName'));
    }
}
