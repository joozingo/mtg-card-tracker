@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h1 style="font-size: 2rem; font-weight: bold;">{{ $deck->name }}</h1>
        <div>
            <a href="{{ route('decks.edit', $deck) }}" class="btn"
                style="background-color: #3182ce; margin-right: 0.5rem; padding: 0.5rem 1rem; color: white; text-decoration: none; border-radius: 0.375rem; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; transition: all 0.2s ease;"
                onmouseover="this.style.backgroundColor='#2c5282'; this.style.transform='translateY(-2px)'"
                onmouseout="this.style.backgroundColor='#3182ce'; this.style.transform='translateY(0)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Edit Deck
            </a>
            <a href="{{ route('decks.index') }}" class="btn"
                style="padding: 0.5rem 1rem; background-color: #E2E8F0; color: #4A5568; text-decoration: none; border-radius: 0.375rem; font-weight: 500; transition: all 0.2s ease;"
                onmouseover="this.style.backgroundColor='#CBD5E0'; this.style.transform='translateY(-2px)'"
                onmouseout="this.style.backgroundColor='#E2E8F0'; this.style.transform='translateY(0)'">
                Back to Decks
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: #4CAF50; color: white; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
        <div
            style="background-color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            @if($deck->format)
                <div style="margin-bottom: 0.5rem;">
                    <span style="font-weight: bold;">Format:</span> {{ $deck->format }}
                </div>
            @endif

            <div style="margin-bottom: 0.5rem;">
                <span style="font-weight: bold;">Cards:</span> {{ $deck->total_cards }} in main deck
                @if($deck->total_sideboard_cards > 0)
                    • {{ $deck->total_sideboard_cards }} in sideboard
                @endif
            </div>

            @if($deck->description)
                <div style="margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                    <h3 style="font-size: 1rem; font-weight: bold; margin-bottom: 0.5rem;">Description</h3>
                    <p style="white-space: pre-line;">{{ $deck->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
        <!-- Main Deck -->
        <div
            style="background-color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <h2
                style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Main Deck ({{ $deck->total_cards }})
            </h2>

            @if(empty($mainDeckByType))
                <p style="text-align: center; padding: 2rem; color: #718096;">
                    No cards in the main deck yet.
                    <a href="{{ route('cards.index') }}" style="color: #4299e1; text-decoration: none;">Browse cards</a>
                    to add some.
                </p>
            @else
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                    @foreach($mainDeckByType as $type => $cards)
                        <div style="margin-bottom: 1rem;">
                            <h3
                                style="font-size: 1.25rem; font-weight: 500; margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; color: #4a5568; display: flex; align-items: center; gap: 0.5rem;">
                                @if($type == 'Creatures')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3">
                                        </path>
                                    </svg>
                                @elseif($type == 'Spells')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                    </svg>
                                @elseif($type == 'Artifacts')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5"></path>
                                        <path d="M2 12l10 5 10-5"></path>
                                    </svg>
                                @elseif($type == 'Enchantments')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                @elseif($type == 'Planeswalkers')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                    </svg>
                                @elseif($type == 'Lands')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 22v-2a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2"></path>
                                        <path d="M3 18v-2a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2"></path>
                                        <rect x="3" y="10" width="18" height="4" rx="1"></rect>
                                        <circle cx="12" cy="5" r="2"></circle>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                @endif
                                {{ $type }} ({{ count($cards) }})
                            </h3>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem;">
                                @foreach($cards as $cardData)
                                    <div style="display: flex; align-items: center; background-color: #f7fafc; padding: 0.5rem 0.75rem; border-radius: 0.375rem; transition: all 0.2s ease;"
                                        onmouseover="this.style.backgroundColor='#edf2f7'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.1)';"
                                        onmouseout="this.style.backgroundColor='#f7fafc'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                        <span
                                            style="margin-right: 0.75rem; font-weight: 600; color: #4a5568; min-width: 24px; text-align: center;">{{ $cardData['quantity'] }}x</span>
                                        <a href="{{ route('cards.show', $cardData['card']->scryfall_id) }}"
                                            style="color: #4299e1; text-decoration: none; flex: 1; font-size: 0.95rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                            title="{{ $cardData['card']->name }} - {{ $cardData['card']->type_line }}">
                                            {{ $cardData['card']->name }}
                                        </a>
                                        <form action="{{ route('decks.remove-card', $deck) }}" method="POST"
                                            style="margin-left: 0.5rem;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="card_id" value="{{ $cardData['card']->id }}">
                                            <input type="hidden" name="location" value="main">
                                            <button type="submit"
                                                style="background: none; border: none; cursor: pointer; color: #e53e3e; font-size: 1.25rem; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s ease; padding: 0; hover:background-color: #FED7D7;"
                                                onmouseover="this.style.backgroundColor='#FED7D7'; this.style.transform='scale(1.1)'"
                                                onmouseout="this.style.backgroundColor='transparent'; this.style.transform='scale(1)'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sideboard -->
        @if(!empty($sideboardByType) || $deck->total_sideboard_cards > 0)
            <div
                style="background-color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h2
                    style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                    Sideboard ({{ $deck->total_sideboard_cards }})
                </h2>

                @if(empty($sideboardByType))
                    <p style="text-align: center; padding: 1rem; color: #718096;">No cards in the sideboard yet.</p>
                @else
                    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                        @foreach($sideboardByType as $type => $cards)
                            <div style="margin-bottom: 1rem;">
                                <h3
                                    style="font-size: 1.25rem; font-weight: 500; margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; color: #4a5568; display: flex; align-items: center; gap: 0.5rem;">
                                    @if($type == 'Creatures')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3">
                                            </path>
                                        </svg>
                                    @elseif($type == 'Spells')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                        </svg>
                                    @elseif($type == 'Artifacts')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                            <path d="M2 17l10 5 10-5"></path>
                                            <path d="M2 12l10 5 10-5"></path>
                                        </svg>
                                    @elseif($type == 'Enchantments')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                        </svg>
                                    @elseif($type == 'Planeswalkers')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                                        </svg>
                                    @elseif($type == 'Lands')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 22v-2a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2"></path>
                                            <path d="M3 18v-2a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2"></path>
                                            <rect x="3" y="10" width="18" height="4" rx="1"></rect>
                                            <circle cx="12" cy="5" r="2"></circle>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                    @endif
                                    {{ $type }} ({{ count($cards) }})
                                </h3>
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.75rem;">
                                    @foreach($cards as $cardData)
                                        <div style="display: flex; align-items: center; background-color: #f7fafc; padding: 0.5rem 0.75rem; border-radius: 0.375rem; transition: all 0.2s ease;"
                                            onmouseover="this.style.backgroundColor='#edf2f7'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.1)';"
                                            onmouseout="this.style.backgroundColor='#f7fafc'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <span
                                                style="margin-right: 0.75rem; font-weight: 600; color: #4a5568; min-width: 24px; text-align: center;">{{ $cardData['quantity'] }}x</span>
                                            <a href="{{ route('cards.show', $cardData['card']->scryfall_id) }}"
                                                style="color: #4299e1; text-decoration: none; flex: 1; font-size: 0.95rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                title="{{ $cardData['card']->name }} - {{ $cardData['card']->type_line }}">
                                                {{ $cardData['card']->name }}
                                            </a>
                                            <form action="{{ route('decks.remove-card', $deck) }}" method="POST"
                                                style="margin-left: 0.5rem;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="card_id" value="{{ $cardData['card']->id }}">
                                                <input type="hidden" name="location" value="sideboard">
                                                <button type="submit"
                                                    style="background: none; border: none; cursor: pointer; color: #e53e3e; font-size: 1.25rem; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s ease; padding: 0; hover:background-color: #FED7D7;"
                                                    onmouseover="this.style.backgroundColor='#FED7D7'; this.style.transform='scale(1.1)'"
                                                    onmouseout="this.style.backgroundColor='transparent'; this.style.transform='scale(1)'">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
