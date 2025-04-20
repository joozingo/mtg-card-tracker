@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">{{ $setName }} ({{ strtoupper($set) }})</h1>

    <div class="search-form mb-4">
        <form action="{{ route('sets.show', $set) }}" method="GET">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label for="name" style="display:block; margin-bottom:0.25rem; font-weight:500;">Card Name</label>
                    <input type="text" id="name" name="name" class="search-input" placeholder="Search by card name..."
                        value="{{ request('name') }}">
                </div>
                <div>
                    <label for="text" style="display:block; margin-bottom:0.25rem; font-weight:500;">Card Text</label>
                    <input type="text" id="text" name="text" class="search-input" placeholder="Search card text..."
                        value="{{ request('text') }}">
                </div>
                <div>
                    <label for="type" style="display:block; margin-bottom:0.25rem; font-weight:500;">Card Type</label>
                    <input type="text" id="type" name="type" class="search-input" placeholder="Creature, Instant, etc."
                        value="{{ request('type') }}">
                </div>
                <div>
                    <label for="rarity" style="display:block; margin-bottom:0.25rem; font-weight:500;">Rarity</label>
                    <select id="rarity" name="rarity" class="search-input">
                        <option value="">All Rarities</option>
                        <option value="common" {{ request('rarity') == 'common' ? 'selected' : '' }}>Common</option>
                        <option value="uncommon" {{ request('rarity') == 'uncommon' ? 'selected' : '' }}>Uncommon</option>
                        <option value="rare" {{ request('rarity') == 'rare' ? 'selected' : '' }}>Rare</option>
                        <option value="mythic" {{ request('rarity') == 'mythic' ? 'selected' : '' }}>Mythic Rare</option>
                    </select>
                </div>
                <div>
                    <label for="mana_cost" style="display:block; margin-bottom:0.25rem; font-weight:500;">Mana Cost</label>
                    <input type="text" id="mana_cost" name="mana_cost" class="search-input" placeholder="e.g. {2}{W}{W}"
                        value="{{ request('mana_cost') }}">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.25rem; font-weight:500;">Color Identity</label>
                    <div class="color-filter"
                        style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:0.5rem; align-items:center;">
                        <!-- Copy color-option blocks from cards search -->
                        <div class="color-option">
                            <input type="checkbox" id="color_w" name="colors[]" value="W" {{ is_array(request('colors')) && in_array('W', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_w" class="color-label white-mana" title="White"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #fffbea, #f6e05e); border:2px solid #ecc94b; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#744210;">W</span>
                            </label>
                        </div>
                        <div class="color-option">
                            <input type="checkbox" id="color_u" name="colors[]" value="U" {{ is_array(request('colors')) && in_array('U', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_u" class="color-label blue-mana" title="Blue"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #ebf8ff, #63b3ed); border:2px solid #4299e1; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#2c5282;">U</span>
                            </label>
                        </div>
                        <div class="color-option">
                            <input type="checkbox" id="color_b" name="colors[]" value="B" {{ is_array(request('colors')) && in_array('B', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_b" class="color-label black-mana" title="Black"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #4a5568, #1a202c); border:2px solid #2d3748; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#e2e8f0;">B</span>
                            </label>
                        </div>
                        <div class="color-option">
                            <input type="checkbox" id="color_r" name="colors[]" value="R" {{ is_array(request('colors')) && in_array('R', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_r" class="color-label red-mana" title="Red"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #fff5f5, #fc8181); border:2px solid #f56565; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#c53030;">R</span>
                            </label>
                        </div>
                        <div class="color-option">
                            <input type="checkbox" id="color_g" name="colors[]" value="G" {{ is_array(request('colors')) && in_array('G', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_g" class="color-label green-mana" title="Green"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #f0fff4, #9ae6b4); border:2px solid #68d391; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#276749;">G</span>
                            </label>
                        </div>
                        <div class="color-option">
                            <input type="checkbox" id="color_c" name="colors[]" value="C" {{ is_array(request('colors')) && in_array('C', request('colors')) ? 'checked' : '' }}
                                style="position:absolute; opacity:0; cursor:pointer;">
                            <label for="color_c" class="color-label colorless-mana" title="Colorless"
                                style="display:flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; background:radial-gradient(circle, #f7fafc, #cbd5e0); border:2px solid #a0aec0; cursor:pointer;">
                                <span class="color-icon"
                                    style="font-weight:bold; font-size:1.25rem; color:#4a5568;">C</span>
                            </label>
                        </div>
                        <!-- color-option blocks -->
                        <select name="color_match" class="search-input"
                            style="height:40px; width:auto; max-width:175px; margin-left:0.5rem; display:inline-block;">
                            <option value="exact" {{ request('color_match') == 'exact' ? 'selected' : '' }}>Exactly these
                                colors</option>
                            <option value="include" {{ request('color_match') == 'include' || !request('color_match') ? 'selected' : '' }}>Including these colors</option>
                            <option value="at_most" {{ request('color_match') == 'at_most' ? 'selected' : '' }}>At most these
                                colors</option>
                        </select>
                        <button type="button" id="clear-colors" class="btn"
                            style="background-color:#718096; margin-left:0.5rem; margin-bottom:0.5rem; padding:0.375rem 0.75rem;">Clear</button>
                    </div>
                </div>
                <div>
                    <div style="display:block; align-items:center; gap:0.5rem;">
                        <label for="sort" style="font-size:0.875rem; color:#4a5568;">Sort by:</label>
                        <select name="sort" id="sort" class="search-input"
                            style="width:auto; display:inline-block; padding:0.375rem 0.75rem;">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                            </option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to
                                High)
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to
                                Low)
                            </option>
                            <option value="rarity" {{ request('sort') == 'rarity' ? 'selected' : '' }}>Rarity</option>
                            <option value="release_date" {{ request('sort') == 'release_date' ? 'selected' : '' }}>Release
                                Date
                            </option>
                        </select>
                        <button type="submit" class="btn">Filter</button>
                        <a href="{{ route('sets.show', $set) }}" class="btn bg-gray-600 hover:bg-gray-700">Reset</a>
                    </div>
                </div>
            </div>
            <div style="margin-top:1rem;">

            </div>
        </form>
    </div>

    @if($cards->count() > 0)
        <div class="card-grid">
            @foreach($cards as $card)
                <div class="card-item" style="position: relative; transition: transform 0.2s, box-shadow 0.2s;">
                    @if($card->collectionQuantity() > 0)
                        <div
                            style="position: absolute; top: 10px; right: 10px; background-color: rgba(49, 130, 206, 0.9); color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                            {{ $card->collectionQuantity() }} in collection
                        </div>
                    @endif

                    @if($card->isInWishlist())
                        <div
                            style="position: absolute; top: 10px; right: {{ $card->collectionQuantity() > 0 ? '120px' : '10px' }}; background-color: rgba(237, 100, 166, 0.9); color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                            In wishlist
                        </div>
                    @endif

                    <div style="position: relative; overflow: hidden;">
                        <a href="{{ route('cards.show', $card->scryfall_id) }}">
                            @if($card->getLocalOrScryfallImageUrl('normal'))
                                <img class="card-image" src="{{ $card->getLocalOrScryfallImageUrl('normal') }}" alt="{{ $card->name }}"
                                    style="transition: transform 0.3s;">
                            @else
                                <div
                                    style="height: 300px; display: flex; align-items: center; justify-content: center; background-color: #e2e8f0; color: #4a5568;">
                                    No Image
                                </div>
                            @endif
                        </a>

                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.5); padding: 0.5rem; display: flex; justify-content: space-around; transform: translateY(100%); transition: transform 0.3s;"
                            class="card-actions">
                            <a href="{{ route('collection.create', $card->id) }}"
                                style="display: inline-block; background-color: #4299e1; color: white; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; text-decoration: none; font-weight: 500; margin-right: 0.25rem;">
                                Collection
                            </a>

                            <a href="{{ route('wishlist.create', $card->id) }}"
                                style="display: inline-block; background-color: #ed64a6; color: white; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; text-decoration: none; font-weight: 500; margin-right: 0.25rem;">
                                Wishlist
                            </a>

                            @php
                                $decks = \App\Models\Deck::orderBy('name')->get();
                            @endphp

                            @if($decks->isNotEmpty())
                                <div class="dropdown" style="position: relative; display: inline-block;">
                                    <button class="dropbtn"
                                        style="background-color: #38a169; color: white; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; border: none; font-weight: 500; cursor: pointer;">
                                        Add to Deck
                                    </button>
                                    <div class="dropdown-content"
                                        style="display: none; position: absolute; bottom: 100%; left: 0; background-color: white; min-width: 160px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 20; border-radius: 0.25rem; margin-bottom: 0.25rem;">
                                        @foreach($decks as $deck)
                                            <form action="{{ route('decks.add-card', $deck) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <input type="hidden" name="card_id" value="{{ $card->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="location" value="main">
                                                <button type="submit"
                                                    style="width: 100%; text-align: left; padding: 0.5rem; border: none; background: none; font-size: 0.75rem; cursor: pointer; color: #2d3748;">
                                                    {{ $deck->name }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-content" style="padding: 1rem;">
                        <h3
                            style="margin: 0; font-size: 1.125rem; font-weight: bold; color: #2d3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $card->name }}
                        </h3>
                        <p
                            style="margin: 0.25rem 0 0; color: #718096; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $card->type_line }}
                        </p>

                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; font-size: 0.75rem;">
                            <span
                                style="color: #718096; background-color: #f7fafc; padding: 0.125rem 0.375rem; border-radius: 0.25rem;">{{ $card->set_name }}</span>
                            <span
                                style="text-transform: capitalize; color: {{ $card->rarity == 'common' ? '#4a5568' : ($card->rarity == 'uncommon' ? '#2d3748' : ($card->rarity == 'rare' ? '#2c5282' : ($card->rarity == 'mythic' ? '#c05621' : '#4a5568'))) }}; font-weight: {{ $card->rarity == 'common' ? 'normal' : 'bold' }};">{{ $card->rarity }}</span>
                        </div>

                        @if($card->converted_price)
                            <div
                                style="position: absolute; top: 10px; left: 10px; background-color: rgba(34, 84, 61, 0.9); color: white; border-radius: 0.25rem; padding: 0.125rem 0.375rem; font-size: 0.75rem; font-weight: 500; z-index: 10; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                {{ $card->price_symbol }}{{ number_format($card->converted_price, 2) }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .card-item:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .card-item:hover .card-image {
                transform: scale(1.05);
            }

            .card-item:hover .card-actions {
                transform: translateY(0);
            }

            /* Make action buttons visible on mobile without hover */
            @media (max-width: 768px) {
                .card-actions {
                    transform: translateY(0);
                }
            }

            .dropdown:hover .dropdown-content {
                display: block;
            }

            .dropdown-content button:hover {
                background-color: #f1f5f9;
            }

            /* Color filter styles */
            .color-filter {
                /* Styles moved to inline, keep for potential future use */
                /* display: flex; */
                /* align-items: center; */
            }

            .color-option {
                position: relative;
                /* Adjusted margin-right in the flex container gap */
                /* margin-right: 4px; */
            }

            /* NEW: Base styles for color labels */
            .color-label {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.2s ease;
                /* Use specific border properties */
                border-width: 2px;
                border-style: solid;
                border-color: transparent;
                /* Default border color */
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                position: relative;
                /* Needed for the checkmark */
            }

            .color-icon {
                font-weight: bold;
                font-size: 1.25rem;
            }

            /* NEW: Color-specific styles - only set background and border-color initially (removed !important) */
            .white-mana {
                /* Style applied inline now for testing */
                /* background: radial-gradient(circle, #fffbea, #f6e05e); */
                /* border-color: #ecc94b; */
            }

            .white-mana .color-icon {
                color: #744210;
            }

            .blue-mana {
                background: radial-gradient(circle, #ebf8ff, #63b3ed);
                border-color: #4299e1;
            }

            .blue-mana .color-icon {
                color: #2c5282;
            }

            .black-mana {
                background: radial-gradient(circle, #4a5568, #1a202c);
                border-color: #2d3748;
            }

            .black-mana .color-icon {
                color: #e2e8f0;
            }

            .red-mana {
                background: radial-gradient(circle, #fff5f5, #fc8181);
                border-color: #f56565;
            }

            .red-mana .color-icon {
                color: #c53030;
            }

            .green-mana {
                background: radial-gradient(circle, #f0fff4, #9ae6b4);
                border-color: #68d391;
            }

            .green-mana .color-icon {
                color: #276749;
            }

            .colorless-mana {
                background: radial-gradient(circle, #f7fafc, #cbd5e0);
                border-color: #a0aec0;
            }

            .colorless-mana .color-icon {
                color: #4a5568;
            }


            /* Styles applied when label has .is-checked class (added by JS) */
            .color-label.is-checked {
                transform: scale(1.1);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            /* Checkmark indicator */
            .color-label.is-checked::after {
                content: '✓';
                position: absolute;
                top: -5px;
                right: -5px;
                width: 20px;
                height: 20px;
                background-color: #38a169;
                /* Green checkmark background */
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
                border: 2px solid white;
                /* White border around checkmark */
            }

            /* Specific checked styles triggered by .is-checked class */
            .white-mana.is-checked {
                border-color: #744210 !important;
                /* Darker border when checked - use important to override inline */
                background: radial-gradient(circle, #fefcbf, #ecc94b) !important;
                /* Enhanced background - override inline */
                animation: pulse-white 1.5s infinite;
            }

            .blue-mana.is-checked {
                border-color: #2c5282 !important;
                background: radial-gradient(circle, #bee3f8, #3182ce) !important;
                animation: pulse-blue 1.5s infinite;
            }

            .black-mana.is-checked {
                border-color: #cbd5e0 !important;
                /* Lighter border for contrast */
                background: radial-gradient(circle, #2d3748, #000) !important;
                animation: pulse-black 1.5s infinite;
            }

            .red-mana.is-checked {
                border-color: #c53030 !important;
                background: radial-gradient(circle, #fed7d7, #e53e3e) !important;
                animation: pulse-red 1.5s infinite;
            }

            .green-mana.is-checked {
                border-color: #276749 !important;
                background: radial-gradient(circle, #c6f6d5, #38a169) !important;
                animation: pulse-green 1.5s infinite;
            }

            .colorless-mana.is-checked {
                border-color: #4a5568 !important;
                background: radial-gradient(circle, #edf2f7, #a0aec0) !important;
                animation: pulse-colorless 1.5s infinite;
            }

            /* Keep pulse animations */
            @keyframes pulse-white {
                0% {
                    box-shadow: 0 0 0 0 rgba(236, 201, 75, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(236, 201, 75, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(236, 201, 75, 0);
                }
            }

            @keyframes pulse-blue {
                0% {
                    box-shadow: 0 0 0 0 rgba(66, 153, 225, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(66, 153, 225, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(66, 153, 225, 0);
                }
            }

            @keyframes pulse-black {
                0% {
                    box-shadow: 0 0 0 0 rgba(203, 213, 224, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(203, 213, 224, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(203, 213, 224, 0);
                }
            }

            @keyframes pulse-red {
                0% {
                    box-shadow: 0 0 0 0 rgba(245, 101, 101, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(245, 101, 101, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(245, 101, 101, 0);
                }
            }

            @keyframes pulse-green {
                0% {
                    box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(72, 187, 120, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(72, 187, 120, 0);
                }
            }

            @keyframes pulse-colorless {
                0% {
                    box-shadow: 0 0 0 0 rgba(160, 174, 192, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 6px rgba(160, 174, 192, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(160, 174, 192, 0);
                }
            }

            /* Keep clear button hover and responsive styles */
            #clear-colors:hover {
                background-color: #4a5568;
            }

            @media (max-width: 768px) {
                .color-filter-options {
                    margin-left: 0 !important;
                    margin-top: 0.5rem;
                    flex-direction: column;
                    align-items: flex-start !important;
                }

                .color-filter-options select {
                    width: 100%;
                    margin-bottom: 0.5rem;
                }
            }
        </style>

        <div class="pagination">
            {{ $cards->links() }}
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const colorCheckboxes = document.querySelectorAll('.color-option input[type="checkbox"]');
                const clearButton = document.getElementById('clear-colors');
                const allColorLabels = document.querySelectorAll('.color-label'); // Get all labels

                // Function to update label class based on checkbox state
                function updateLabelClass(checkbox) {
                    const label = document.querySelector(`label[for="${checkbox.id}"]`);
                    if (label) {
                        label.classList.toggle('is-checked', checkbox.checked);
                    }
                }

                // Initial state setup on page load
                colorCheckboxes.forEach(checkbox => {
                    updateLabelClass(checkbox); // Set initial class based on server-rendered checked state
                    checkbox.addEventListener('change', () => {
                        updateLabelClass(checkbox); // Update on change
                    });
                });

                // Fix for clear colors button
                clearButton.addEventListener('click', function () {
                    colorCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                        // Also remove the class from the label
                        const label = document.querySelector(`label[for="${checkbox.id}"]`);
                        if (label) {
                            label.classList.remove('is-checked');
                        }
                    });
                });

                const cardItems = document.querySelectorAll('.card-item');
                cardItems.forEach(item => {
                    item.addEventListener('mouseenter', () => {
                        const actions = item.querySelector('.card-actions');
                        if (actions) {
                            actions.style.transform = 'translateY(0)';
                        }
                    });
                    item.addEventListener('mouseleave', () => {
                        const actions = item.querySelector('.card-actions');
                        if (actions) {
                            actions.style.transform = 'translateY(100%)';
                        }
                    });
                });

                const dropdowns = document.querySelectorAll('.dropdown');
                dropdowns.forEach(dropdown => {
                    dropdown.addEventListener('mouseenter', function () {
                        const content = this.querySelector('.dropdown-content');
                        if (content) {
                            content.style.display = 'block';
                        }
                    });

                    dropdown.addEventListener('mouseleave', function () {
                        const content = this.querySelector('.dropdown-content');
                        if (content) {
                            content.style.display = 'none';
                        }
                    });
                });
            });
        </script>
    @else
        <div style="text-align: center; padding: 2rem; background-color: white; border-radius: 0.5rem;">
            <p>No cards found in this set.</p>
        </div>
    @endif
@endsection