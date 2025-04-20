@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ url()->previous() }}"
            style="color: #4a5568; text-decoration: none; display: inline-flex; align-items: center;">
            <span style="margin-right: 0.25rem;">←</span> Back to search
        </a>
    </div>

    @if(session('status'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('status') }}
        </div>
    @endif

    @php
        // Helper functions for card colors
        function getColorIdentityClass($card)
        {
            if (!$card->color_identity || $card->color_identity === '[]') {
                return 'colorless';
            }

            $colorIdentity = json_decode($card->color_identity);

            if (count($colorIdentity) > 2) {
                return 'multicolor';
            }

            if (in_array('W', $colorIdentity)) {
                return 'white';
            } elseif (in_array('U', $colorIdentity)) {
                return 'blue';
            } elseif (in_array('B', $colorIdentity)) {
                return 'black';
            } elseif (in_array('R', $colorIdentity)) {
                return 'red';
            } elseif (in_array('G', $colorIdentity)) {
                return 'green';
            }

            return 'colorless';
        }

        function getColorGradient($card)
        {
            $colorClass = getColorIdentityClass($card);

            switch ($colorClass) {
                case 'white':
                    return 'linear-gradient(135deg, #f7fafc, #e2e8f0)';
                case 'blue':
                    return 'linear-gradient(135deg, #ebf8ff, #bee3f8)';
                case 'black':
                    return 'linear-gradient(135deg, #2d3748, #4a5568)';
                case 'red':
                    return 'linear-gradient(135deg, #fff5f5, #fed7d7)';
                case 'green':
                    return 'linear-gradient(135deg, #f0fff4, #c6f6d5)';
                case 'multicolor':
                    return 'linear-gradient(135deg, #fefcbf, #feebc8, #fed7d7, #c6f6d5, #bee3f8)';
                case 'colorless':
                default:
                    return 'linear-gradient(135deg, #edf2f7, #e2e8f0)';
            }
        }

        function getCardTypeIcon($card)
        {
            $typeLine = strtolower($card->type_line);

            if (strpos($typeLine, 'creature') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M9 18l6-6-6-6"/></svg>';
            } elseif (strpos($typeLine, 'instant') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>';
            } elseif (strpos($typeLine, 'sorcery') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>';
            } elseif (strpos($typeLine, 'enchantment') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>';
            } elseif (strpos($typeLine, 'artifact') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>';
            } elseif (strpos($typeLine, 'planeswalker') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>';
            } elseif (strpos($typeLine, 'land') !== false) {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"/></svg>';
            } else {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><circle cx="12" cy="12" r="10"/></svg>';
            }
        }

        $colorIdentity = getColorIdentityClass($card);
        $gradient = getColorGradient($card);
        $cardTypeIcon = getCardTypeIcon($card);

        // Card text formatting
        $formattedText = $card->oracle_text;
        $formattedText = preg_replace('/\{([WUBRGCTP0-9\/]+)\}/', '<span class="mana-symbol">$1</span>', $formattedText);
    @endphp

    <div
        style="background-color: white; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); overflow: hidden;">
        <div class="card-header"
            style="background: {{ $gradient }}; padding: 1.5rem; position: relative; overflow: hidden;">
            <h1
                style="margin: 0; font-size: 2.25rem; font-weight: bold; {{ $colorIdentity === 'black' ? 'color: white;' : '' }}">
                {{ $card->name }}
            </h1>
            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                <span
                    style="font-size: 0.875rem; background-color: rgba(255, 255, 255, 0.5); padding: 0.25rem 0.5rem; border-radius: 0.25rem; backdrop-filter: blur(5px);">{{ $card->mana_cost }}</span>
                <span
                    style="font-size: 0.875rem; background-color: rgba(255, 255, 255, 0.5); padding: 0.25rem 0.5rem; border-radius: 0.25rem; backdrop-filter: blur(5px); display: flex; align-items: center; gap: 0.25rem;">
                    <span
                        style="width: 1rem; height: 1rem; display: inline-flex; align-items: center; justify-content: center;">{!! $cardTypeIcon !!}</span>
                    {{ $card->type_line }}
                </span>
                <span
                    style="font-size: 0.875rem; background-color: rgba(255, 255, 255, 0.5); padding: 0.25rem 0.5rem; border-radius: 0.25rem; backdrop-filter: blur(5px); text-transform: capitalize;">{{ $card->rarity }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; position: relative;">
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                <div class="card-image-container" style="flex-basis: 40%; margin-right: 2rem; text-align: center;">
                    @if($card->getLocalOrScryfallImageUrl('normal'))
                        <img src="{{ $card->getLocalOrScryfallImageUrl('normal') }}" alt="{{ $card->name }}"
                            style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                    @else
                        <div
                            style="height: 350px; display: flex; align-items: center; justify-content: center; background-color: #e2e8f0; color: #4a5568; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                            No Image
                        </div>
                    @endif
                    <form action="{{ route('cards.refresh-image', $card->scryfall_id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn">Refresh Image</button>
                    </form>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('collection.create', $card->id) }}" class="action-button collection-button"
                        style="flex: 1; padding: 0.75rem; background-color: #4299e1; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; text-align: center; box-shadow: 0 4px 6px rgba(66, 153, 225, 0.5); transition: all 0.2s ease;">
                        Add to Collection
                    </a>

                    <a href="{{ route('wishlist.create', $card->id) }}" class="action-button wishlist-button"
                        style="flex: 1; padding: 0.75rem; background-color: #f56565; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; text-align: center; box-shadow: 0 4px 6px rgba(245, 101, 101, 0.5); transition: all 0.2s ease;">
                        Add to Wishlist
                    </a>
                </div>

                @if($card->isInCollection())
                    <div class="collection-status"
                        style="text-align: center; color: #4a5568; font-size: 0.875rem; background-color: #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;">
                        You have {{ $card->collectionQuantity() }} in your collection
                    </div>
                @endif

                @if($card->isInWishlist())
                    <div class="wishlist-status"
                        style="text-align: center; color: #4a5568; font-size: 0.875rem; background-color: #fed7d7; padding: 0.5rem; border-radius: 0.5rem;">
                        In your wishlist with {{ $card->wishlist->priority_label }} priority
                        <a href="{{ route('wishlist.edit', $card->wishlist) }}"
                            style="color: #e53e3e; font-weight: 500; margin-left: 0.25rem; text-decoration: none;">Edit</a>
                    </div>
                @endif

                <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.75rem; text-align: center;">Add to
                        Deck
                    </h3>

                    <div id="deckSuccessMessage"
                        style="display: none; margin-bottom: 0.75rem; padding: 0.5rem; background-color: #C6F6D5; color: #2F855A; text-align: center; border-radius: 0.375rem; font-weight: 500;">
                        Card added to deck successfully!
                    </div>

                    @php
                        $decks = \App\Models\Deck::orderBy('name')->get();
                    @endphp

                    @if($decks->isEmpty())
                        <p style="text-align: center; font-size: 0.875rem; color: #4a5568;">
                            <a href="{{ route('decks.create') }}" style="color: #4299e1; text-decoration: none;">Create your
                                first deck</a>
                            to add cards.
                        </p>
                    @else
                        <form action="{{ route('decks.add-card', $decks->first()) }}" method="POST" id="addToDeckForm">
                            @csrf
                            <input type="hidden" name="card_id" value="{{ $card->id }}">

                            <div style="margin-bottom: 0.75rem;">
                                <select name="deck_id" id="deck_select"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #f7fafc; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Ctitle%3Edown-arrow%3C%2Ftitle%3E%3Cg%20fill%3D%22%23000000%22%3E%3Cpath%20d%3D%22M10.293%2C3.293%2C6%2C7.586%2C1.707%2C3.293A1%2C1%2C0%2C0%2C0%2C.293%2C4.707l5%2C5a1%2C1%2C0%2C0%2C0%2C1.414%2C0l5-5a1%2C1%2C0%2C1%2C0-1.414-1.414Z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E'); background-size: 0.625em; background-position: calc(100% - 1em) center; background-repeat: no-repeat;">
                                    @foreach($decks as $deck)
                                        <option value="{{ $deck->id }}">{{ $deck->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="margin-bottom: 0.75rem;">
                                <select name="location"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #f7fafc; appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2012%2012%22%3E%3Ctitle%3Edown-arrow%3C%2Ftitle%3E%3Cg%20fill%3D%22%23000000%22%3E%3Cpath%20d%3D%22M10.293%2C3.293%2C6%2C7.586%2C1.707%2C3.293A1%2C1%2C0%2C0%2C0%2C.293%2C4.707l5%2C5a1%2C1%2C0%2C0%2C0%2C1.414%2C0l5-5a1%2C1%2C0%2C1%2C0-1.414-1.414Z%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E'); background-size: 0.625em; background-position: calc(100% - 1em) center; background-repeat: no-repeat;">
                                    <option value="main">Main Deck</option>
                                    <option value="sideboard">Sideboard</option>
                                    <option value="maybe">Maybe Board</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0.75rem; display: flex; align-items: center;">
                                <label for="quantity"
                                    style="margin-right: 0.5rem; white-space: nowrap; font-weight: 500;">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="99"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; background-color: #f7fafc;">
                            </div>

                            <button type="submit" class="action-button deck-button"
                                style="width: 100%; padding: 0.75rem; border: none; background-color: #38a169; color: white; border-radius: 0.5rem; cursor: pointer; font-weight: 500; box-shadow: 0 4px 6px rgba(56, 161, 105, 0.5); transition: all 0.2s ease;">
                                Add to Deck
                            </button>
                        </form>

                        <script>
                            document.getElementById('deck_select').addEventListener('change', function () {
                                const deckId = this.value;
                                document.getElementById('addToDeckForm').action = "/decks/" + deckId + "/add-card";
                            });

                            document.getElementById('addToDeckForm').addEventListener('submit', function (e) {
                                e.preventDefault();

                                const form = this;
                                const formData = new FormData(form);
                                const deckId = document.getElementById('deck_select').value;

                                fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                    }
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        const successMessage = document.getElementById('deckSuccessMessage');
                                        successMessage.style.display = 'block';
                                        successMessage.innerHTML = data.message;

                                        // Set a timeout to redirect to the deck page
                                        setTimeout(() => {
                                            window.location.href = `/decks/${deckId}`;
                                        }, 1000);
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });
                        </script>
                    @endif
                </div>
            </div>

            <div style="padding: 1.5rem;">
                <div class="card-text-box"
                    style="margin-bottom: 1.5rem; padding: 1.25rem; background-color: #f7fafc; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <h2 style="margin: 0 0 0.75rem; font-size: 1.25rem; font-weight: bold;">Card Text</h2>
                    <div class="oracle-text" style="white-space: pre-line; line-height: 1.6;">
                        {!! nl2br(e($card->oracle_text)) !!}
                    </div>
                </div>

                @if($card->flavor_text)
                    <div class="flavor-text-box"
                        style="margin-bottom: 1.5rem; padding: 1.25rem; background-color: #fffaf0; border-radius: 0.5rem; border-left: 4px solid #ed8936; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); font-style: italic; color: #4a5568; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        "{{ $card->flavor_text }}"
                    </div>
                @endif

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    @if($card->power || $card->toughness)
                        <div class="stat-box"
                            style="padding: 1rem; background-color: #e6fffa; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <h2 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #38b2ac;">
                                Power/Toughness</h2>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: bold; text-align: center;">
                                {{ $card->power }}/{{ $card->toughness }}
                            </p>
                        </div>
                    @endif

                    @if($card->loyalty)
                        <div class="stat-box"
                            style="padding: 1rem; background-color: #e9d8fd; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                            <h2 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #805ad5;">Loyalty</h2>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: bold; text-align: center;">{{ $card->loyalty }}
                            </p>
                        </div>
                    @endif

                    <div class="stat-box"
                        style="padding: 1rem; background-color: #feebc8; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <h2 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #d69e2e;">Set
                            Information</h2>
                        <p style="margin: 0; display: flex; justify-content: space-between;"><span>Set:</span>
                            <strong>{{ $card->set_name }} ({{ $card->set }})</strong>
                        </p>
                        <p style="margin: 0.25rem 0 0; display: flex; justify-content: space-between;"><span>Number:</span>
                            <strong>{{ $card->collector_number }}</strong>
                        </p>
                    </div>

                    <div class="stat-box"
                        style="padding: 1rem; background-color: #c6f6d5; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        <h2 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #38a169;">Price</h2>
                        @if($card->price_usd)
                            <p style="margin: 0; display: flex; justify-content: space-between;"><span>USD:</span>
                                <strong>${{ number_format($card->price_usd, 2) }}</strong>
                            </p>
                        @endif
                        @if($card->price_eur)
                            <p style="margin: 0.25rem 0 0; display: flex; justify-content: space-between;"><span>EUR:</span>
                                <strong>€{{ number_format($card->price_eur, 2) }}</strong>
                            </p>
                        @endif
                        @if(!$card->price_usd && !$card->price_eur)
                            <p style="margin: 0; text-align: center;">No price information available</p>
                        @endif
                    </div>
                </div>

                <div class="artist-box"
                    style="margin-bottom: 1.5rem; padding: 1rem; background-color: #ebf8ff; border-radius: 0.5rem; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <h2 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #4299e1;">Artist</h2>
                    <p style="margin: 0; text-align: center; font-style: italic; font-weight: 500;">{{ $card->artist }}</p>
                </div>

                <div class="legality-box" style="margin-top: 1.5rem;">
                    <h2 style="margin: 0 0 0.75rem; font-size: 1.25rem; font-weight: bold;">Format Legality</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.5rem;">
                        @if($card->legalities)
                            @foreach(json_decode($card->legalities) as $format => $legality)
                                <div
                                    style="
                                                                                                                                                                                                            padding: 0.5rem;
                                                                                                                                                                                                            border-radius: 0.5rem;
                                                                                                                                                                                                            font-size: 0.875rem;
                                                                                                                                                                                                            background-color: {{ $legality === 'legal' ? '#c6f6d5' : ($legality === 'not_legal' ? '#fed7d7' : '#e2e8f0') }};
                                                                                                                                                                                                            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                                                                                                                                                                                                            transition: transform 0.2s ease;
                                                                                                                                                                                                        ">
                                    <span
                                        style="text-transform: capitalize; font-weight: 500;">{{ str_replace('_', ' ', $format) }}</span>
                                    <span
                                        style="float: right; text-transform: capitalize; {{ $legality === 'legal' ? 'color: #38a169;' : ($legality === 'not_legal' ? 'color: #e53e3e;' : '') }}">
                                        {{ str_replace('_', ' ', $legality) }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <p>No legality information available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-image-container:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .collection-button:hover {
            background-color: #3182ce;
        }

        .wishlist-button:hover {
            background-color: #e53e3e;
        }

        .deck-button:hover {
            background-color: #2f855a;
        }

        .card-text-box:hover,
        .stat-box:hover,
        .legality-box>div:hover,
        .artist-box:hover,
        .flavor-text-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .mana-symbol {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background-color: #e2e8f0;
            font-weight: bold;
            margin: 0 0.125rem;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .card-header {
                padding: 1rem;
            }

            .card-header h1 {
                font-size: 1.5rem;
            }

            .card-image-container {
                max-width: 300px;
                margin: 0 auto;
            }

            div[style*="grid-template-columns: 1fr 2fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection