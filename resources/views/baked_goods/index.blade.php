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
        <div class="container">
            <button id="createNewBakedGoodBtn" class="btn btn-primary">Create New Baked Good</button>
        </div>

        <!-- Modal structure -->
        <div class="modal fade" id="bakedGoodModal" tabindex="-1" role="dialog" aria-labelledby="bakedGoodModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    {{-- <form action="{{ route('baked_goods.store') }}" method="POST" enctype="multipart/form-data"> --}}
                    <form id='createBakedGoodForm' method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bakedGoodModalLabel">New Baked Good</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <div class="modal-body">
                        <!-- Form for creating a new baked good -->
                            @csrf

                            <!-- Name field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" >
                            </div>

                            <!-- Price field -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Price:</label>
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" >
                            </div>

                            <!-- Availability field -->
                            <div class="mb-3">
                                <label for="is_available" class="form-label">Available:</label>
                                <select class="form-select" id="is_available" name="is_available" >
                                    <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <!-- Description field -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description: (Optional)</label>
                                <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                            </div>

                            <!-- Weight field -->
                            <div class="mb-3">
                                <label for="weight_gram" class="form-label">Weight in grams (Optional):</label>
                                <input type="number" class="form-control" id="weight_gram" name="weight_gram" value="{{ old('weight_gram') }}">
                            </div>

                            <!-- Image field -->
                            <div class="mb-3">
                                <label for="images" class="form-label">Images: (Multiple)</label>
                                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                            </div>

                            <!-- Submit button -->
                            {{-- <button type="submit" class="btn btn-primary">Create</button> --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id='createBakedGood'>Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        {{-- <div class="mt-3">
            <a href="{{ route('baked_goods.create') }}" class="btn btn-primary mb-3">Create New Baked Good</a>
        </div> --}}
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <div id="tableContainer">
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
</div>

<script>
    $(document).ready(function(){
        $('#createNewBakedGoodBtn').click(function(){
            $('#bakedGoodModal').modal('show');
        });

        var table = $('#myDataTable').DataTable();
        console.log(table);
        $('#createBakedGood').click(function(event){
            event.preventDefault();

            const form = $('#createBakedGoodForm')[0];

            const formData = new FormData(form);

            console.log(formData);
            // Initialize DataTable

            $.ajax({
                url: '{{ route('baked_goods.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#bakedGoodModal').modal('hide');
                    alert('Baked Good created successfully!');
                    fetchAllEmployees();
                    // table.ajax.reload();  // Reload the DataTable
                    // Optionally, refresh the page or update the table with new data
                },
                error: function(xhr) {
                    alert('An error occurred while creating the baked good.');
                    // Optionally, handle validation errors and display them to the user
                }
            });
        })
    });

    function fetchAllEmployees() {
        $.ajax({
          url: '{{ route('baked_goods.fetchAll') }}',
          method: 'get',
          success: function(response) {
            $("#tableContainer").html(response);
            $("table").DataTable({
              order: [0, 'desc']
            });
          }
        });
      }
</script>

@endsection


