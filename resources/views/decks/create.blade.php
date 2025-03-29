@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h1 style="font-size: 2rem; font-weight: bold;">Create New Deck</h1>
        <a href="{{ route('decks.index') }}" class="btn">Back to Decks</a>
    </div>

    <div style="background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <form action="{{ route('decks.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Deck Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem;">
                @error('name')
                    <p style="color: #e53e3e; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="format" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Format
                    (optional)</label>
                <select id="format" name="format"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem;">
                    <option value="">Select a format (optional)</option>
                    <option value="Standard" {{ old('format') == 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Modern" {{ old('format') == 'Modern' ? 'selected' : '' }}>Modern</option>
                    <option value="Legacy" {{ old('format') == 'Legacy' ? 'selected' : '' }}>Legacy</option>
                    <option value="Vintage" {{ old('format') == 'Vintage' ? 'selected' : '' }}>Vintage</option>
                    <option value="Commander" {{ old('format') == 'Commander' ? 'selected' : '' }}>Commander</option>
                    <option value="Pioneer" {{ old('format') == 'Pioneer' ? 'selected' : '' }}>Pioneer</option>
                    <option value="Pauper" {{ old('format') == 'Pauper' ? 'selected' : '' }}>Pauper</option>
                    <option value="Brawl" {{ old('format') == 'Brawl' ? 'selected' : '' }}>Brawl</option>
                    <option value="Limited" {{ old('format') == 'Limited' ? 'selected' : '' }}>Limited</option>
                    <option value="Other" {{ old('format') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('format')
                    <p style="color: #e53e3e; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Description
                    (optional)</label>
                <textarea id="description" name="description" rows="4"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.25rem; resize: vertical;">{{ old('description') }}</textarea>
                @error('description')
                    <p style="color: #e53e3e; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}
                        style="margin-right: 0.5rem;">
                    <span>Make this deck public</span>
                </label>
                @error('is_public')
                    <p style="color: #e53e3e; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn" style="background-color: #4CAF50;">Create Deck</button>
        </form>
    </div>
@endsection