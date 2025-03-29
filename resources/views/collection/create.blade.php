@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ url()->previous() }}"
            style="color: #4a5568; text-decoration: none; display: inline-flex; align-items: center;">
            <span style="margin-right: 0.25rem;">←</span> Back to card
        </a>
    </div>

    <!-- Add notification div that will be shown on form submission -->
    <div id="notification"
        style="display: none; background-color: #c6f6d5; color: #2f855a; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; align-items: center; justify-content: space-between; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                style="width: 1.5rem; height: 1.5rem; margin-right: 0.75rem;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span style="font-weight: 500;">Adding <span id="card-quantity">1</span> × {{ $card->name }} to your
                collection...</span>
        </div>
    </div>

    <div
        style="background-color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; padding: 1.5rem;">
        <h1 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: bold;">Add to Collection:
            {{ $card->name }}
        </h1>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div>
                @if($card->image_uri_normal)
                    <img src="{{ $card->image_uri_normal }}" alt="{{ $card->name }}"
                        style="width: 100%; border-radius: 0.375rem;">
                @else
                    <div
                        style="height: 350px; display: flex; align-items: center; justify-content: center; background-color: #e2e8f0; color: #4a5568; border-radius: 0.375rem;">
                        No Image
                    </div>
                @endif
            </div>

            <div>
                <form id="collection-form" action="{{ route('collection.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="card_id" value="{{ $card->id }}">

                    <div style="margin-bottom: 1.5rem;">
                        <label for="quantity"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Quantity</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="search-input"
                            style="width: 100%;" required onchange="updateQuantity(this.value)">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="condition"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Condition</label>
                        <select name="condition" id="condition" class="search-input" style="width: 100%;" required>
                            <option value="mint">Mint</option>
                            <option value="near_mint" selected>Near Mint</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="light_played">Light Played</option>
                            <option value="played">Played</option>
                            <option value="poor">Poor</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="foil" value="1" style="margin-right: 0.5rem;">
                            <span>Foil</span>
                        </label>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="purchase_price"
                            style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Purchase Price
                            (optional)</label>
                        <input type="number" name="purchase_price" id="purchase_price" step="0.01" class="search-input"
                            style="width: 100%;" placeholder="0.00">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="acquired_date" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Date
                            Acquired (optional)</label>
                        <input type="date" name="acquired_date" id="acquired_date" class="search-input" style="width: 100%;"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="notes" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Notes
                            (optional)</label>
                        <textarea name="notes" id="notes" class="search-input" style="width: 100%; height: 5rem;"
                            placeholder="Add any notes about this card..."></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn" style="padding: 0.75rem 1.5rem; font-weight: 500;"
                            onclick="showNotification()">Add to
                            Collection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateQuantity(value) {
            document.getElementById('card-quantity').textContent = value;
        }

        function showNotification() {
            document.getElementById('notification').style.display = 'flex';

            // Submit the form after a brief delay to show the notification
            setTimeout(function () {
                document.getElementById('collection-form').submit();
            }, 300);
        }

        // Prevent default form submission to allow our custom handler
        document.getElementById('collection-form').addEventListener('submit', function (e) {
            e.preventDefault();
            showNotification();
        });
    </script>
@endsection