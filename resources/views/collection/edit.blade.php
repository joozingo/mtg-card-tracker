@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('collection.index') }}"
            style="color: #4a5568; text-decoration: none; display: inline-flex; align-items: center;">
            <span style="margin-right: 0.25rem;">←</span> Back to collection
        </a>
    </div>

    <div
        style="background-color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; padding: 1.5rem;">
        <h1 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: bold;">Edit Collection:
            {{ $collection->card->name }}
        </h1>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div>
                @if($collection->card->image_uri_normal)
                    <img src="{{ $collection->card->image_uri_normal }}" alt="{{ $collection->card->name }}"
                        style="width: 100%; border-radius: 0.375rem;">
                @else
                    <div
                        style="height: 350px; display: flex; align-items: center; justify-content: center; background-color: #e2e8f0; color: #4a5568; border-radius: 0.375rem;">
                        No Image
                    </div>
                @endif
            </div>

            <div>
                <form action="{{ route('collection.update', $collection->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1.5rem;">
                        <label for="quantity"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="{{ $collection->quantity }}" min="1"
                            class="search-input" style="width: 100%;" required>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="condition"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Condition</label>
                        <select name="condition" id="condition" class="search-input" style="width: 100%;" required>
                            <option value="mint" {{ $collection->condition == 'mint' ? 'selected' : '' }}>Mint</option>
                            <option value="near_mint" {{ $collection->condition == 'near_mint' ? 'selected' : '' }}>Near Mint
                            </option>
                            <option value="excellent" {{ $collection->condition == 'excellent' ? 'selected' : '' }}>Excellent
                            </option>
                            <option value="good" {{ $collection->condition == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="light_played" {{ $collection->condition == 'light_played' ? 'selected' : '' }}>
                                Light Played</option>
                            <option value="played" {{ $collection->condition == 'played' ? 'selected' : '' }}>Played</option>
                            <option value="poor" {{ $collection->condition == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="foil" value="1" style="margin-right: 0.5rem;" {{ $collection->foil ? 'checked' : '' }}>
                            <span>Foil</span>
                        </label>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="purchase_price"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Purchase Price
                            (optional)</label>
                        <input type="number" name="purchase_price" id="purchase_price" step="0.01" class="search-input"
                            style="width: 100%;" placeholder="0.00" value="{{ $collection->purchase_price }}">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="acquired_date" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Date
                            Acquired (optional)</label>
                        <input type="date" name="acquired_date" id="acquired_date" class="search-input" style="width: 100%;"
                            value="{{ $collection->acquired_date ? $collection->acquired_date->format('Y-m-d') : '' }}">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="notes" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Notes
                            (optional)</label>
                        <textarea name="notes" id="notes" class="search-input" style="width: 100%; height: 5rem;"
                            placeholder="Add any notes about this card...">{{ $collection->notes }}</textarea>
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn" style="padding: 0.75rem 1.5rem; font-weight: 500;">Update
                            Collection</button>
                        <a href="{{ route('collection.index') }}"
                            style="padding: 0.75rem 1.5rem; background-color: #718096; color: white; border-radius: 0.25rem; text-decoration: none; font-weight: 500;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection