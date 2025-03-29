@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom: 1rem; font-size: 1.875rem; font-weight: bold;">My Collection</h1>

    @if(session('success'))
        <div
            style="background-color: #c6f6d5; color: #2f855a; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
            <div style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span style="font-weight: 500;">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.style.display='none'"
                style="background: none; border: none; cursor: pointer; color: #2f855a;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    @if($collection->count() > 0)
        <!-- Desktop view (table) -->
        <div class="desktop-table"
            style="display: none; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden; margin-bottom: 1.5rem;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f7fafc; text-align: left;">
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Card</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Set</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Quantity</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Condition</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Foil</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Price</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collection as $item)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; align-items: center;">
                                    @if($item->card->image_uri_small)
                                        <img src="{{ $item->card->image_uri_small }}" alt="{{ $item->card->name }}"
                                            style="width: 40px; height: auto; margin-right: 0.75rem; border-radius: 0.25rem;">
                                    @endif
                                    <a href="{{ route('cards.show', $item->card->scryfall_id) }}"
                                        style="color: #4a5568; font-weight: 500; text-decoration: none;">
                                        {{ $item->card->name }}
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 0.75rem 1rem;">{{ $item->card->set_name }} ({{ $item->card->set }})</td>
                            <td style="padding: 0.75rem 1rem;">{{ $item->quantity }}</td>
                            <td style="padding: 0.75rem 1rem; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item->condition) }}
                            </td>
                            <td style="padding: 0.75rem 1rem;">{{ $item->foil ? 'Yes' : 'No' }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                @if($item->purchase_price)
                                    ${{ number_format($item->purchase_price, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('collection.edit', $item->id) }}" title="Edit"
                                        style="color: #4299e1; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 0.25rem; transition: all 0.2s ease;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" style="width: 18px; height: 18px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('collection.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to remove this card from your collection?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove from collection"
                                            style="background: none; border: none; color: #f56565; text-decoration: none; cursor: pointer; padding: 0; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 0.25rem; transition: all 0.2s ease;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" style="width: 18px; height: 18px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile view (cards) -->
        <div class="mobile-cards"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            @foreach($collection as $item)
                <div
                    style="background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div style="display: flex; padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                        <div style="flex-shrink: 0; margin-right: 1rem;">
                            @if($item->card->image_uri_small)
                                <img src="{{ $item->card->image_uri_small }}" alt="{{ $item->card->name }}"
                                    style="width: 70px; height: auto; border-radius: 0.25rem;">
                            @endif
                        </div>
                        <div>
                            <h3 style="font-weight: 600; margin-bottom: 0.25rem;">
                                <a href="{{ route('cards.show', $item->card->scryfall_id) }}"
                                    style="color: #4a5568; text-decoration: none;">
                                    {{ $item->card->name }}
                                </a>
                            </h3>
                            <p style="font-size: 0.875rem; color: #718096; margin-bottom: 0.25rem;">{{ $item->card->set_name }}
                                ({{ $item->card->set }})</p>
                            <div style="display: flex; font-size: 0.875rem; margin-top: 0.5rem;">
                                <span
                                    style="background-color: #e2e8f0; padding: 0.25rem 0.5rem; border-radius: 0.25rem; margin-right: 0.5rem;">
                                    {{ $item->quantity }}x
                                </span>
                                <span
                                    style="background-color: #e2e8f0; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-transform: capitalize; margin-right: 0.5rem;">
                                    {{ str_replace('_', ' ', $item->condition) }}
                                </span>
                                @if($item->foil)
                                    <span
                                        style="background-color: #feebc8; padding: 0.25rem 0.5rem; border-radius: 0.25rem; margin-right: 0.5rem;">
                                        Foil
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 1rem; background-color: #f7fafc;">
                        <div>
                            @if($item->purchase_price)
                                <span style="font-weight: 500;">Price: ${{ number_format($item->purchase_price, 2) }}</span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 1rem;">
                            <a href="{{ route('collection.edit', $item->id) }}" title="Edit"
                                style="color: #4299e1; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 0.25rem; transition: all 0.2s ease;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('collection.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this card from your collection?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Remove from collection"
                                    style="background: none; border: none; color: #f56565; text-decoration: none; cursor: pointer; padding: 0; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 0.25rem; transition: all 0.2s ease;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        style="width: 18px; height: 18px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $collection->links() }}
        </div>
    @else
        <div
            style="text-align: center; padding: 3rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem;">Your collection is empty</h2>
            <p style="color: #718096; margin-bottom: 1.5rem;">Start building your collection by adding cards from the database.
            </p>
            <a href="{{ route('cards.index') }}"
                style="display: inline-block; padding: 0.5rem 1rem; background-color: #4299e1; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 500;">
                Browse Cards
            </a>
        </div>
    @endif

    <style>
        @media (min-width: 769px) {
            .desktop-table {
                display: block !important;
            }

            .mobile-cards {
                display: none !important;
            }
        }

        /* Action button hover effects */
        a[style*="width: 32px"],
        button[style*="width: 32px"] {
            background-color: rgba(0, 0, 0, 0.05);
        }

        a[style*="width: 32px"]:hover {
            background-color: rgba(66, 153, 225, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        button[style*="width: 32px"]:hover {
            background-color: rgba(245, 101, 101, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection