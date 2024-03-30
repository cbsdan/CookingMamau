@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ingredients</h1>
    
    <!-- Search form -->
    <form action="{{ route('ingredients.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by name of baked good" value="{{ request()->input('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>
    @if(auth()->check() && auth()->user()->is_admin)
        <div class="mt-3">
            <a href="{{ route('ingredients.create') }}" class="btn btn-primary mb-3">Create New Baked Good Ingredient</a>
        </div>
    @endif
    <!-- Table to display ingredients -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Image</th>
                <th>Quantity</th>
                <th>Baked Good</th>
                <th>Added on</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingredients as $ingredient)
            <tr>
                <td>{{ $ingredient->name }}</td>
                <td class='col-1'>
                    @php
                        $thumbnail_path = $ingredient->image_path ?? 'uploaded_files/default-profile.png';
                    @endphp
                    <img src="{{ asset($thumbnail_path) }}" class="img-thumbnail" alt="Ingredient" >   
                <td>{{ $ingredient->qty . $ingredient->unit}}</td>
                <td>
                    @if($ingredient->bakedGood)
                    <a href="{{ route('baked_goods.edit', $ingredient->bakedGood->id) }}">{{ $ingredient->bakedGood->name }}</a>
                    @else
                    N/A
                    @endif
                    <td>{{ $ingredient->created_at }}</td>
                </td>
                <td>
                    @if(auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
