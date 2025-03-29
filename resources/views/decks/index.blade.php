@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h1 style="font-size: 2rem; font-weight: bold;">My Decks</h1>
        <a href="{{ route('decks.create') }}" class="btn" style="background-color: #4CAF50;">Create New Deck</a>
    </div>

    @if(session('success'))
        <div style="background-color: #4CAF50; color: white; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($decks->isEmpty())
        <div
            style="text-align: center; padding: 2rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <p style="margin-bottom: 1rem;">You haven't created any decks yet.</p>
            <a href="{{ route('decks.create') }}" class="btn" style="background-color: #4CAF50;">Create Your First Deck</a>
        </div>
    @else
        <div class="card-grid">
            @foreach($decks as $deck)
                <div class="card-item">
                    <div class="card-content">
                        <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $deck->name }}</h2>
                        @if($deck->format)
                            <p style="color: #718096; margin-bottom: 0.5rem;">Format: {{ $deck->format }}</p>
                        @endif
                        <p style="margin-bottom: 0.5rem;">
                            <strong>{{ $deck->total_cards }}</strong> cards in main deck
                            @if($deck->total_sideboard_cards > 0)
                                • <strong>{{ $deck->total_sideboard_cards }}</strong> in sideboard
                            @endif
                        </p>
                        @if($deck->description)
                            <p style="margin-bottom: 1rem; color: #4a5568;">
                                {{ \Illuminate\Support\Str::limit($deck->description, 100) }}
                            </p>
                        @endif
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('decks.show', $deck) }}" class="btn"
                                style="flex: 1; text-align: center; text-decoration: none;">View</a>
                            <a href="{{ route('decks.edit', $deck) }}" class="btn"
                                style="flex: 1; text-align: center; text-decoration: none; background-color: #3182ce;">Edit</a>
                            <form action="{{ route('decks.destroy', $deck) }}" method="POST" style="flex: 1;"
                                onsubmit="return confirm('Are you sure you want to delete this deck?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="width: 100%; background-color: #e53e3e;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $decks->links() }}
        </div>
    @endif
@endsection