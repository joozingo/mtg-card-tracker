@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1
                style="font-size: 3rem; font-weight: bold; background: linear-gradient(135deg, #4a5568, #2d3748); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 1rem;">
                MTG Card Tracker
            </h1>
            <p style="font-size: 1.25rem; color: #718096; max-width: 800px; margin: 0 auto;">
                Track your Magic: The Gathering collection, build decks, and manage your cards all in one place.
            </p>
        </div>

        <!-- Stats Overview -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div
                style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div
                    style="height: 70px; width: 70px; background-color: #ebf4ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #4299e1;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">{{ number_format($totalCards) }}
                </h2>
                <p style="color: #718096; margin-bottom: 0;">Total Cards in Database</p>
            </div>

            <div
                style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div
                    style="height: 70px; width: 70px; background-color: #e6fffa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #38b2ac;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">
                    {{ number_format($totalInCollection) }}</h2>
                <p style="color: #718096; margin-bottom: 0;">Cards in Your Collection</p>
            </div>

            <div
                style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div
                    style="height: 70px; width: 70px; background-color: #ebf8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #3182ce;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">
                    ${{ number_format($collectionValue ?? 0, 2) }}</h2>
                <p style="color: #718096; margin-bottom: 0;">Collection Value</p>
            </div>

            <div
                style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div
                    style="height: 70px; width: 70px; background-color: #faf5ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #805ad5;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <h2 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $totalDecks }}</h2>
                <p style="color: #718096; margin-bottom: 0;">Decks Built</p>
            </div>
        </div>

        <div class="main-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Left Column -->
            <div>
                <!-- Most Valuable Cards -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem;">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Most Valuable Cards in Collection</h2>

                    @if($mostValuableCards->isEmpty())
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            You don't have any cards in your collection yet.
                            <a href="{{ route('cards.index') }}" style="color: #4299e1; text-decoration: none;">Browse cards</a>
                            to add some.
                        </p>
                    @else
                        <!-- Desktop/Tablet View -->
                        <div class="valuable-cards-table desktop-table" style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <th style="text-align: left; padding: 0.75rem 0.5rem; width: 50%;">Card</th>
                                        <th style="text-align: center; padding: 0.75rem 0.5rem; width: 15%;">Quantity</th>
                                        <th style="text-align: right; padding: 0.75rem 0.5rem; width: 15%;">Value</th>
                                        <th style="text-align: right; padding: 0.75rem 0.5rem; width: 20%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mostValuableCards as $card)
                                        <tr style="border-bottom: 1px solid #f7fafc;">
                                            <td style="padding: 0.75rem 0.5rem; word-wrap: break-word; max-width: 0;">
                                                <div style="display: flex; align-items: center;">
                                                    @if($card->image_uri_small)
                                                        <img src="{{ $card->image_uri_small }}" alt="{{ $card->name }}"
                                                            style="width: 40px; height: auto; border-radius: 0.25rem; margin-right: 0.75rem; flex-shrink: 0;">
                                                    @endif
                                                    <div style="min-width: 0;">
                                                        <div style="font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: normal;">{{ $card->name }}</div>
                                                        <div style="font-size: 0.75rem; color: #718096;">{{ $card->set_name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 0.75rem 0.5rem; text-align: center;">{{ $card->quantity }}</td>
                                            <td style="padding: 0.75rem 0.5rem; text-align: right;">
                                                ${{ number_format($card->price_usd, 2) }}</td>
                                            <td style="padding: 0.75rem 0.5rem; text-align: right; font-weight: 500;">
                                                ${{ number_format($card->price_usd * $card->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View (for very small screens) -->
                        <div class="valuable-cards-mobile mobile-only" style="display: none;">
                            @foreach($mostValuableCards as $card)
                                <div style="border-bottom: 1px solid #e2e8f0; padding: 0.75rem 0; margin-bottom: 0.5rem;">
                                    <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                        @if($card->image_uri_small)
                                            <img src="{{ $card->image_uri_small }}" alt="{{ $card->name }}"
                                                style="width: 40px; height: auto; border-radius: 0.25rem; margin-right: 0.75rem;">
                                        @endif
                                        <div>
                                            <div style="font-weight: 500;">{{ $card->name }}</div>
                                            <div style="font-size: 0.75rem; color: #718096;">{{ $card->set_name }}</div>
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; font-size: 0.875rem;">
                                        <div style="text-align: center;">
                                            <div style="color: #718096;">Quantity</div>
                                            <div style="font-weight: 500;">{{ $card->quantity }}</div>
                                        </div>
                                        <div style="text-align: center;">
                                            <div style="color: #718096;">Value</div>
                                            <div style="font-weight: 500;">${{ number_format($card->price_usd, 2) }}</div>
                                        </div>
                                        <div style="text-align: center;">
                                            <div style="color: #718096;">Total</div>
                                            <div style="font-weight: 700;">${{ number_format($card->price_usd * $card->quantity, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Card Type Distribution -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem;">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Collection Breakdown by Type</h2>

                    @if($cardTypeDistribution->isEmpty())
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            Add cards to your collection to see the type distribution.
                        </p>
                    @else
                        <div class="type-distribution-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            @foreach($cardTypeDistribution as $item)
                                <div style="background-color: #f7fafc; border-radius: 0.5rem; padding: 1rem; text-align: center;">
                                    <div style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.25rem;">{{ $item->count }}</div>
                                    <div style="color: #718096;">{{ $item->card_type }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Color Distribution -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Collection by Color</h2>

                    @if($colorDistribution->isEmpty())
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            Add cards to your collection to see the color distribution.
                        </p>
                    @else
                        <div class="color-distribution-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            @php
                                $colorClasses = [
                                    'White' => '#f7fafc',
                                    'Blue' => '#bee3f8',
                                    'Black' => '#4a5568',
                                    'Red' => '#fed7d7',
                                    'Green' => '#c6f6d5',
                                    'Colorless' => '#e2e8f0',
                                    'Multi-Color' => '#feebc8',
                                    'Five-Color' => '#fefcbf',
                                ];

                                $textColors = [
                                    'Black' => 'white',
                                    'default' => '#2d3748',
                                ];
                            @endphp

                            @foreach($colorDistribution as $item)
                                <div style="background-color: {{ $colorClasses[$item->color] ?? '#f7fafc' }}; border-radius: 0.5rem; padding: 1rem; text-align: center;">
                                    <div style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.25rem; color: {{ $item->color == 'Black' ? $textColors['Black'] : $textColors['default'] }}">{{ $item->count }}</div>
                                    <div style="color: {{ $item->color == 'Black' ? $textColors['Black'] : '#718096' }};">{{ $item->color }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Latest Decks -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem;">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Your Latest Decks</h2>

                    @if($latestDecks->isEmpty())
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            You haven't created any decks yet.
                        </p>
                        <div style="text-align: center;">
                            <a href="{{ route('decks.create') }}"
                                style="display: inline-block; background-color: #4299e1; color: white; border-radius: 0.25rem; padding: 0.5rem 1rem; text-decoration: none; font-weight: 500;">
                                Create Your First Deck
                            </a>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($latestDecks as $deck)
                                <div style="border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem;">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <h3 style="font-size: 1.125rem; font-weight: bold; margin: 0;">{{ $deck->name }}</h3>
                                        @if($deck->format)
                                            <span
                                                style="font-size: 0.75rem; background-color: #ebf8ff; color: #2c5282; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $deck->format }}</span>
                        @endif
                                    </div>
                                    <div style="font-size: 0.875rem; color: #718096; margin-bottom: 0.5rem;">
                                        {{ $deck->total_cards }} cards in main deck
                                        @if($deck->total_sideboard_cards > 0)
                                            • {{ $deck->total_sideboard_cards }} in sideboard
            @endif
                                    </div>
                                    <div style="margin-top: 0.75rem;">
                                        <a href="{{ route('decks.show', $deck) }}"
                                            style="font-size: 0.875rem; color: #4299e1; text-decoration: none;">
                                            View Deck →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 1.5rem; text-align: center;">
                            <a href="{{ route('decks.index') }}"
                                style="font-size: 0.875rem; color: #4299e1; text-decoration: none;">
                                View All Decks →
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Wishlist Status -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 1.5rem;">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Wishlist Status</h2>

                    @if($wishlistCount == 0)
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            Your wishlist is empty. Add cards you want to acquire.
                        </p>
                        <div style="text-align: center;">
                            <a href="{{ route('cards.search') }}"
                                style="display: inline-block; background-color: #f56565; color: white; border-radius: 0.25rem; padding: 0.5rem 1rem; text-decoration: none; font-weight: 500;">
                                Find Cards to Add
                            </a>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="display: flex; align-items: center; justify-content: space-between; background-color: #fed7d7; padding: 1rem; border-radius: 0.5rem;">
                                <div>
                                    <div style="font-size: 0.875rem; color: #e53e3e; font-weight: 500;">Total Wishlist Items</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #c53030;">{{ $wishlistCount }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px; color: #e53e3e;">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; background-color: #feebc8; padding: 1rem; border-radius: 0.5rem;">
                                <div>
                                    <div style="font-size: 0.875rem; color: #dd6b20; font-weight: 500;">High Priority Items</div>
                                    <div style="font-size: 1.5rem; font-weight: bold; color: #c05621;">{{ $highPriorityCount }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px; color: #dd6b20;">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                </svg>
                            </div>

                            <div style="text-align: center; margin-top: 0.5rem;">
                                <a href="{{ route('wishlist.index') }}" style="color: #e53e3e; text-decoration: none; font-weight: 500;">
                                    View Full Wishlist →
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Rarity Distribution -->
                <div
                    style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <h2
                        style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        Collection Rarity</h2>

                    @if($rarityDistribution->isEmpty())
                        <p style="text-align: center; padding: 2rem; color: #718096;">
                            Add cards to your collection to see the rarity distribution.
                        </p>
                    @else
                                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                                        @php
                                            $colors = [
                                                'common' => '#a0aec0',
                                                'uncommon' => '#4a5568',
                                                'rare' => '#3182ce',
                                                'mythic' => '#e53e3e',
                                            ];
                                            $labels = [
                                                'common' => 'Common',
                                                'uncommon' => 'Uncommon',
                                                'rare' => 'Rare',
                                                'mythic' => 'Mythic Rare',
                                            ];
                                            $total = array_sum($rarityDistribution->pluck('count')->toArray());
                                        @endphp

                                        @foreach($rarityDistribution as $item)
                                            <div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                                    <span style="font-weight: 500;">{{ $labels[$item->rarity] ?? ucfirst($item->rarity) }}</span>
                                                    <span>{{ $item->count }} ({{ number_format(($item->count / $total) * 100, 1) }}%)</span>
                                                </div>
                                                <div style="height: 8px; background-color: #e2e8f0; border-radius: 4px; overflow: hidden;">
                                                    <div
                                                        style="height: 100%; width: {{ ($item->count / $total) * 100 }}%; background-color: {{ $colors[$item->rarity] ?? '#718096' }};">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div
            style="background-color: white; border-radius: 0.5rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem;">
            <h2
                style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                Quick Actions</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <a href="{{ route('cards.index') }}"
                    style="background-color: #ebf8ff; border-radius: 0.5rem; padding: 1.5rem; text-align: center; text-decoration: none; transition: transform 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #3182ce; margin: 0 auto 0.75rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; color: #2d3748;">Browse Cards
                    </h3>
                    <p style="color: #718096; font-size: 0.875rem;">Search and explore MTG cards</p>
                </a>

                <a href="{{ route('collection.index') }}"
                    style="background-color: #e6fffa; border-radius: 0.5rem; padding: 1.5rem; text-align: center; text-decoration: none; transition: transform 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #38b2ac; margin: 0 auto 0.75rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; color: #2d3748;">View
                        Collection</h3>
                    <p style="color: #718096; font-size: 0.875rem;">Manage your card collection</p>
                </a>

                <a href="{{ route('decks.index') }}"
                    style="background-color: #faf5ff; border-radius: 0.5rem; padding: 1.5rem; text-align: center; text-decoration: none; transition: transform 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #805ad5; margin: 0 auto 0.75rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; color: #2d3748;">Your Decks
                    </h3>
                    <p style="color: #718096; font-size: 0.875rem;">Build and manage your decks</p>
                </a>

                <a href="{{ route('cards.search') }}"
                    style="background-color: #fff5f5; border-radius: 0.5rem; padding: 1.5rem; text-align: center; text-decoration: none; transition: transform 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #e53e3e; margin: 0 auto 0.75rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; color: #2d3748;">Advanced
                        Search</h3>
                    <p style="color: #718096; font-size: 0.875rem;">Find specific cards by criteria</p>
                </a>

                <a href="{{ route('wishlist.index') }}"
                    style="background-color: #fff5f5; border-radius: 0.5rem; padding: 1.5rem; text-align: center; text-decoration: none; transition: transform 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        style="width: 32px; height: 32px; color: #e53e3e; margin: 0 auto 0.75rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 0.25rem; color: #2d3748;">Wishlist</h3>
                    <p style="color: #718096; font-size: 0.875rem;">Cards you want to acquire</p>
                </a>
                </div>
        </div>
        </div>

    <style>
        a[style*="transition"] {
            display: block;
        }

        a[style*="transition"]:hover {
            transform: translateY(-5px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .color-distribution-grid,
            .type-distribution-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .main-grid {
                grid-template-columns: 1fr !important;
            }

            .valuable-cards-table table {
                table-layout: fixed;
            }

            .valuable-cards-table th,
            .valuable-cards-table td {
                font-size: 0.875rem;
                padding: 0.5rem 0.25rem !important;
            }
        }

        @media (max-width: 480px) {
            .color-distribution-grid,
            .type-distribution-grid {
                grid-template-columns: 1fr !important;
            }

            /* Adjust column widths for very small screens */
            .valuable-cards-table th:first-child,
            .valuable-cards-table td:first-child {
                width: 40% !important;
            }

            .valuable-cards-table th:nth-child(2),
            .valuable-cards-table td:nth-child(2),
            .valuable-cards-table th:nth-child(3),
            .valuable-cards-table td:nth-child(3),
            .valuable-cards-table th:nth-child(4),
            .valuable-cards-table td:nth-child(4) {
                width: 20% !important;
            }
        }

        @media (max-width: 430px) {
            /* Switch to mobile view for very small screens */
            .desktop-table {
                display: none !important;
            }

            .mobile-only {
                display: block !important;
            }
        }
    </style>
@endsection
