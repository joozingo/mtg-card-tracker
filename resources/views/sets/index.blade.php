@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Browse Card Sets</h1>
    <div class="search-form mb-4">
        <form action="{{ route('sets.index') }}" method="GET" class="flex items-center gap-2 w-full">
            <input type="text" name="search" class="search-input" placeholder="Search sets..." value="{{ $search ?? '' }}">
            @if(!empty($search))
                <a href="{{ route('sets.index') }}" class="btn bg-gray-600 hover:bg-gray-700">Reset</a>
            @endif
            <div class="flex items-center gap-2">
                <label for="sort" class="text-sm text-gray-700" style="width:50px;">Sort by:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" class="search-input" style="width:auto;">
                    <option value="name_asc" {{ (isset($sort) && $sort === 'name_asc') ? 'selected' : '' }}>Name (A-Z)
                    </option>
                    <option value="name_desc" {{ (isset($sort) && $sort === 'name_desc') ? 'selected' : '' }}>Name (Z-A)
                    </option>
                    <option value="code_asc" {{ (isset($sort) && $sort === 'code_asc') ? 'selected' : '' }}>Code (A-Z)
                    </option>
                    <option value="code_desc" {{ (isset($sort) && $sort === 'code_desc') ? 'selected' : '' }}>Code (Z-A)
                    </option>
                    <option value="count_asc" {{ (isset($sort) && $sort === 'count_asc') ? 'selected' : '' }}>Count (Low to
                        High)
                    </option>
                    <option value="count_desc" {{ (isset($sort) && $sort === 'count_desc') ? 'selected' : '' }}>Count (High to
                        Low)</option>
                </select>
            </div>
            <button type="submit" class="btn ml-auto" style="margin-bottom:0.5rem;">Search</button>
        </form>
    </div>

    @if($sets->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($sets as $set)
                <div class="set-item p-4 bg-white rounded shadow hover:shadow-lg transition">
                    <a href="{{ route('sets.show', $set->set) }}" class="block">
                        <h2 class="text-xl font-semibold">{{ $set->set_name }}</h2>
                        <p class="text-sm text-gray-600 uppercase">{{ $set->set }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ $set->card_count }} cards</p>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p>No sets found.</p>
    @endif
@endsection