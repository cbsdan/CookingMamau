@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Baked Goods</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->check() && auth()->user()->is_admin)
        <div class="mt-3">
            <a href="{{ route('baked_goods.create') }}" class="btn btn-primary mb-3">Create New Baked Good</a>
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <!-- Table to display baked goods -->
    <table class="table" id="myDataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Weight</th>
                <th>Available</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bakedGoods as $bakedGood)
            <tr>
                <td>{{ $bakedGood->id }}</td>
                <td class='col-1'>
                    @php
                        $thumbnail_path = $bakedGood->thumbnailImage->image_path ?? 'uploaded_files/default-profile.png';
                    @endphp
                    <img src="{{ asset($thumbnail_path) }}" class="img-thumbnail" alt="Profile" >   
                </td>
                <td>{{ $bakedGood->name }}</td>
                <td>{{ $bakedGood->price }}</td>
                <td>{{ $bakedGood->weight_gram . "g" }}</td>
                <td>{{ $bakedGood->is_available ? 'Yes' : 'No' }}</td>
                <td>{{ $bakedGood->updated_at}}</td>
                <td>
                    <!-- View action -->
                    <a href="{{ route('baked_goods.show', $bakedGood->id) }}" class="btn btn-sm btn-primary">View</a>
                    
                    @if (auth()->check() && auth()->user()->is_admin)
                        <a href="{{ route('baked_goods.edit', $bakedGood->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                        
                        <!-- Delete action -->
                        <form action="{{ route('baked_goods.destroy', $bakedGood->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this baked good?')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
