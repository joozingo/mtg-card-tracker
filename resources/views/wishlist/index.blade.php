@extends('layouts.app')

@section('content')
    <div class="wishlist-header"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1 style="margin: 0; font-size: 1.875rem; font-weight: bold;">My Wishlist</h1>
        <a href="{{ route('cards.search') }}" class="btn" style="background-color: #4299e1; color: white;">
            Find Cards to Add
        </a>
    </div>

    @if(session('success'))
        <div
            style="background-color: #C6F6D5; border-left: 4px solid #38A169; color: #276749; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.25rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div
            style="background-color: #FED7D7; border-left: 4px solid #E53E3E; color: #C53030; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.25rem;">
            {{ session('error') }}
        </div>
    @endif

    @if($wishlistItems->isEmpty())
        <div
            style="text-align: center; padding: 3rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
            <div style="font-size: 1.25rem; color: #4A5568; margin-bottom: 1rem;">Your wishlist is empty</div>
            <p style="color: #718096; margin-bottom: 1.5rem;">Start adding cards you'd like to acquire to your wishlist.</p>
            <a href="{{ route('cards.search') }}" class="btn" style="background-color: #4299e1; color: white;">
                Browse Cards
            </a>
        </div>
    @else
        <div class="wishlist-container">
            <div class="wishlist-cards"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-card"
                        style="background-color: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); transition: transform 0.2s, box-shadow 0.2s;">
                        <div style="position: relative;">
                            <a href="{{ route('cards.show', $item->card->scryfall_id) }}">
                                @if($item->card->image_uri_normal)
                                    <img src="{{ $item->card->image_uri_normal }}" alt="{{ $item->card->name }}"
                                        style="width: 100%; height: auto; display: block;">
                                @else
                                    <div
                                        style="height: 350px; display: flex; align-items: center; justify-content: center; background-color: #EDF2F7; color: #4A5568;">
                                        No Image
                                    </div>
                                @endif
                            </a>

                            <!-- Priority badge -->
                            <div style="position: absolute; top: 10px; right: 10px; border-radius: 9999px; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
                                                        background-color: {{ $item->priority == 1 ? '#F56565' : ($item->priority == 2 ? '#ED8936' : '#A0AEC0') }};
                                                        color: white;">
                                {{ $item->priority_label }} Priority
                            </div>

                            <!-- Price info -->
                            <div
                                style="position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.7); padding: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                <div style="color: white; font-size: 0.875rem;">
                                    @if($item->card->price_usd)
                                        <div style="display: flex; align-items: center;">
                                            <span style="font-weight: bold;">Current:
                                                ${{ number_format($item->card->price_usd, 2) }}</span>
                                        </div>
                                    @else
                                        <span>Price: N/A</span>
                                    @endif
                                </div>

                                @if($item->max_price)
                                    <div style="color: white; font-size: 0.875rem; text-align: right;">
                                        <span>Max: ${{ number_format($item->max_price, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div style="padding: 1rem;">
                            <h3 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: bold; color: #2D3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                title="{{ $item->card->name }}">
                                {{ $item->card->name }}
                            </h3>

                            <div style="color: #718096; font-size: 0.875rem; margin-bottom: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                title="{{ $item->card->type_line }}">
                                {{ $item->card->type_line }}
                            </div>

                            @if($item->notes)
                                <div
                                    style="margin-bottom: 1rem; font-size: 0.875rem; color: #4A5568; background-color: #F7FAFC; padding: 0.5rem; border-radius: 0.25rem; white-space: normal; max-height: 60px; overflow-y: auto;">
                                    {{ $item->notes }}
                                </div>
                            @endif

                            <div style="display: flex; justify-content: space-between; gap: 0.5rem;">
                                <div style="flex: 1; display: flex; justify-content: center;">
                                    <a href="{{ route('wishlist.edit', $item) }}"
                                        style="display: inline-flex; align-items: center; justify-content: center; padding: 0.375rem 0.75rem; background-color: #4299E1; color: white; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; width: 100%; text-align: center;">
                                        Edit
                                    </a>
                                </div>

                                <div style="flex: 1; display: flex; justify-content: center;">
                                    <form action="{{ route('wishlist.move-to-collection', $item) }}" method="POST"
                                        style="width: 100%;">
                                        @csrf
                                        <button type="submit"
                                            style="width: 100%; padding: 0.375rem 0.75rem; background-color: #48BB78; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500; cursor: pointer;">
                                            Acquire
                                        </button>
                                    </form>
                                </div>

                                <div style="flex: 1; display: flex; justify-content: center;">
                                    <form action="{{ route('wishlist.destroy', $item) }}" method="POST" style="width: 100%;"
                                        onsubmit="return confirm('Are you sure you want to remove this card from your wishlist?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="width: 100%; padding: 0.375rem 0.75rem; background-color: #F56565; color: white; border: none; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 500; cursor: pointer;">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination" style="margin-top: 2rem;">
                {{ $wishlistItems->links() }}
            </div>
        </div>
    @endif

    <style>
        .wishlist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .wishlist-cards {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 1rem;
            }

            .wishlist-card h3 {
                font-size: 0.875rem;
            }

            .wishlist-card .card-type {
                font-size: 0.75rem;
            }

            .wishlist-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
@endsection