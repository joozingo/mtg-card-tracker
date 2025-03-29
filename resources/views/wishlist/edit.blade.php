@extends('layouts.app')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
            <a href="{{ route('wishlist.index') }}"
                style="margin-right: 0.75rem; color: #4299E1; text-decoration: none; display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                <span style="margin-left: 0.25rem;">Back to Wishlist</span>
            </a>
        </div>

        <h1 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 1.5rem;">Edit Wishlist Item</h1>

        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <div style="flex: 1; min-width: 250px;">
                @if($wishlist->card->image_uri_normal)
                    <img src="{{ $wishlist->card->image_uri_normal }}" alt="{{ $wishlist->card->name }}"
                        style="width: 100%; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                @else
                    <div
                        style="height: 350px; display: flex; align-items: center; justify-content: center; background-color: #EDF2F7; color: #4A5568; border-radius: 0.5rem;">
                        No Image
                    </div>
                @endif

                <div
                    style="margin-top: 1rem; background-color: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $wishlist->card->name }}
                    </h2>
                    <p style="color: #718096; margin-bottom: 0.75rem;">{{ $wishlist->card->type_line }}</p>

                    @if($wishlist->card->price_usd)
                        <div style="margin-top: 0.5rem; font-weight: bold;">
                            Current Price: ${{ number_format($wishlist->card->price_usd, 2) }}
                        </div>
                    @endif
                </div>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <div
                    style="background-color: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);">
                    <form action="{{ route('wishlist.update', $wishlist) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div style="margin-bottom: 1.5rem;">
                            <label for="priority"
                                style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4A5568;">Priority</label>
                            <div style="display: flex; gap: 1rem;">
                                <div style="flex: 1;">
                                    <input type="radio" id="priority_high" name="priority" value="1" class="hidden-radio" {{ $wishlist->priority == 1 ? 'checked' : '' }}>
                                    <label for="priority_high" class="priority-label high"
                                        style="display: flex; flex-direction: column; align-items: center; padding: 0.75rem; text-align: center; background-color: #FEF2F2; border: 2px solid #F3F4F6; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            style="width: 24px; height: 24px; margin-bottom: 0.5rem;" class="priority-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                        High
                                    </label>
                                </div>
                                <div style="flex: 1;">
                                    <input type="radio" id="priority_medium" name="priority" value="2" class="hidden-radio"
                                        {{ $wishlist->priority == 2 ? 'checked' : '' }}>
                                    <label for="priority_medium" class="priority-label medium"
                                        style="display: flex; flex-direction: column; align-items: center; padding: 0.75rem; text-align: center; background-color: #FEF3C7; border: 2px solid #F3F4F6; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            style="width: 24px; height: 24px; margin-bottom: 0.5rem;" class="priority-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        Medium
                                    </label>
                                </div>
                                <div style="flex: 1;">
                                    <input type="radio" id="priority_low" name="priority" value="3" class="hidden-radio" {{ $wishlist->priority == 3 ? 'checked' : '' }}>
                                    <label for="priority_low" class="priority-label low"
                                        style="display: flex; flex-direction: column; align-items: center; padding: 0.75rem; text-align: center; background-color: #F3F4F6; border: 2px solid #F3F4F6; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            style="width: 24px; height: 24px; margin-bottom: 0.5rem;" class="priority-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                        </svg>
                                        Low
                                    </label>
                                </div>
                            </div>
                            @error('priority')
                                <p style="color: #E53E3E; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="max_price"
                                style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4A5568;">Maximum
                                Price Willing to Pay</label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 0.75rem; top: 0.75rem; color: #718096;">$</span>
                                <input type="number" id="max_price" name="max_price" step="0.01" min="0"
                                    style="width: 100%; padding: 0.75rem; padding-left: 1.75rem; border: 1px solid #E2E8F0; border-radius: 0.375rem;"
                                    placeholder="Leave blank if no max price" value="{{ $wishlist->max_price }}">
                            </div>
                            @error('max_price')
                                <p style="color: #E53E3E; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="notes"
                                style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4A5568;">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 0.375rem; resize: vertical;"
                                placeholder="Any additional notes about this card">{{ $wishlist->notes }}</textarea>
                            @error('notes')
                                <p style="color: #E53E3E; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 2rem;">
                            <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST" style="display: inline;"
                                id="delete-form"
                                onsubmit="return confirm('Are you sure you want to remove this card from your wishlist?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="width: 100%; padding: 0.75rem 0.5rem; background-color: #F56565; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; height: 100%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                    </svg>
                                    Remove
                                </button>
                            </form>

                            <a href="{{ route('wishlist.index') }}"
                                style="width: 100%; padding: 0.75rem 0.5rem; background-color: #E2E8F0; color: #4A5568; border-radius: 0.375rem; text-decoration: none; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 0.5rem; height: 100%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Cancel
                            </a>

                            <button type="submit"
                                style="width: 100%; padding: 0.75rem 0.5rem; background-color: #4299E1; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; height: 100%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Save
                            </button>

                            <form action="{{ route('wishlist.move-to-collection', $wishlist) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    style="width: 100%; padding: 0.75rem 0.5rem; background-color: #48BB78; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 0.5rem; height: 100%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                    To Collection
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hidden-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .priority-label {
            opacity: 0.7;
            transform: scale(0.95);
        }

        .priority-label:hover {
            opacity: 0.9;
            transform: scale(0.98);
        }

        .hidden-radio:checked+.priority-label {
            opacity: 1;
            transform: scale(1);
            border-color: currentColor;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .hidden-radio:checked+.priority-label.high {
            color: #B91C1C;
            border-color: #EF4444;
        }

        .hidden-radio:checked+.priority-label.medium {
            color: #B45309;
            border-color: #F59E0B;
        }

        .hidden-radio:checked+.priority-label.low {
            color: #1F2937;
            border-color: #6B7280;
        }
    </style>
@endsection
