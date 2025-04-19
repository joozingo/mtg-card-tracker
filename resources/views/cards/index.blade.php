@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom: 1rem; font-size: 1.875rem; font-weight: bold;">MTG Card Database</h1>

    <div class="flex flex-col md:flex-row md:items-center mb-4">
        <div class="search-form w-full md:w-4/5 mb-4 md:mb-0">
            <form action="{{ route('cards.search') }}" method="GET">
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" name="name" class="search-input" placeholder="Search by card name...">
                    <button type="submit" class="btn">Search</button>
                </div>
                <div style="margin-top: 0.5rem;">
                    <a href="{{ route('cards.search') }}"
                        style="color: #4a5568; text-decoration: none; font-size: 0.875rem;">
                        Advanced Search Options
                    </a>
                </div>
            </form>
        </div>

        <div class="sort-options w-full md:w-1/5 md:ml-4">
            <form action="{{ route('cards.index') }}" method="GET" id="sortForm">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label for="sort" style="font-size: 0.875rem; color: #4a5568;">Sort by:</label>
                    <select name="sort" id="sort" onchange="document.getElementById('sortForm').submit()"
                        style="padding: 0.375rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.25rem; background-color: white; font-size: 0.875rem;">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)
                        </option>
                        <option value="rarity" {{ request('sort') == 'rarity' ? 'selected' : '' }}>Rarity</option>
                        <option value="release_date" {{ request('sort') == 'release_date' ? 'selected' : '' }}>Release Date
                        </option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($cards->count() > 0)
        <div class="card-grid">
            @foreach($cards as $card)
                <div class="card-item" style="position: relative; transition: transform 0.2s, box-shadow 0.2s;">
                    <!-- Collection badge -->
                    @if($card->collectionQuantity() > 0)
                        <div
                            style="position: absolute; top: 10px; right: 10px; background-color: rgba(49, 130, 206, 0.9); color: white; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                            {{ $card->collectionQuantity() }} in collection
                        </div>
                    @endif

                    <!-- Wishlist badge -->
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

                        <!-- Add to collection button overlay -->
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
                                style="text-transform: capitalize;
                                                                                                                                                                                                                                                                                color: {{ $card->rarity == 'common' ? '#4a5568' :
                        ($card->rarity == 'uncommon' ? '#2d3748' :
                            ($card->rarity == 'rare' ? '#2c5282' :
                                ($card->rarity == 'mythic' ? '#c05621' : '#4a5568'))) }};
                                                                                                                                                                                                                                                                                font-weight: {{ $card->rarity == 'common' ? 'normal' : 'bold' }};">
                                {{ $card->rarity }}
                            </span>
                        </div>

                        <!-- Price tag if available -->
                        @if($card->price_usd)
                            <div
                                style="position: absolute; top: 10px; left: 10px; background-color: rgba(34, 84, 61, 0.9); color: white; border-radius: 0.25rem; padding: 0.125rem 0.375rem; font-size: 0.75rem; font-weight: 500; z-index: 10; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                ${{ number_format($card->price_usd, 2) }}
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
        </style>

        <div class="pagination">
            {{ $cards->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 2rem; background-color: white; border-radius: 0.5rem;">
            <p>No cards found in the database.</p>
            <p>Please run the import command to populate the database with cards.</p>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fix for card action overlays not showing on hover
            const cardItems = document.querySelectorAll('.card-item');
            cardItems.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    const actions = this.querySelector('.card-actions');
                    if (actions) {
                        actions.style.transform = 'translateY(0)';
                    }
                });

                item.addEventListener('mouseleave', function () {
                    const actions = this.querySelector('.card-actions');
                    if (actions && window.innerWidth > 768) {
                        actions.style.transform = 'translateY(100%)';
                    }
                });
            });

            // Fix for dropdown menu
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
@endsection