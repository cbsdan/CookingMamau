@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Baked Goods</h1>
        <hr>
        <!-- Modal -->
        <div class="modal fade" id="bakedGoodModal" tabindex="-1" aria-labelledby="bakedGoodModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bakedGoodModalLabel">Baked Good</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bakedGoodForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="weight_gram" class="form-label">Weight (g)</label>
                                <input type="number" class="form-control" id="weight_gram" name="weight_gram">
                            </div>
                            <div class="mb-3">
                                <label for="is_available" class="form-label">Availability</label>
                                <select class="form-select" id="is_available" name="is_available">
                                    <option value="1">Available</option>
                                    <option value="0">Not Available</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="images" class="form-label">Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                            </div>
                            <div id=""></div>
                            <label for="ingredientsList">Ingredients</label>
                            <div id="">
                                <select name="ingredientsList" id="ingredientsList">
                                    <option value='1'>Polvoron</option>
                                </select>

                            </div>
                            <button type="button" class="btn btn-secondary" id="addNewIngredient">Add New Ingredient</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="bakedGoodSubmit">Save</button>
                        <button type="button" class="btn btn-primary" id="bakedGoodUpdate">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ingredientModal" role="dialog" style="">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create new ingredient</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="ingredientForm" method="POST" action="#" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="ingredientId" class="control-label">Ingredient ID</label>
                                <input type="text" class="form-control" id="ingredientId" name="id" readonly>
                            </div>

                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" class="form-control " id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="unit" class="control-label">Unit</label>
                                <input type="text" class="form-control " id="unit" name="unit">
                            </div>

                            <div class="form-group">
                                <label for="image" class="control-label">Image</label>
                                <input type="file" class="form-control" id="img" name="image" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" id="footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="bakedGoodSubmit" type="submit" class="btn btn-primary">Save</button>
                        <button id="bakedGoodUpdate" type="submit" class="btn btn-primary">update</button>
                    </div>

                </div>
            </div>
        </div>

        <div id='bakedgoods' class='container'>
            <div class="table-responsive">
                <table id="bakedGoodsTable" class="table table-striped table-hover w-100">
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
                    <tbody id="bakedGoodsBody"></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

<script>

</script>
{{-- @foreach ($bakedGoods as $bakedGood)
    <tr>
        <td>{{ $bakedGood->id }}</td>
        <td class='col-1'>
            @php
                $thumbnail_path =
                    $bakedGood->thumbnailImage->image_path ?? 'uploaded_files/default-profile.png';
            @endphp
            <img src="{{ asset($thumbnail_path) }}" class="img-thumbnail" alt="Profile">
        </td>
        <td>{{ $bakedGood->name }}</td>
        <td>{{ $bakedGood->price }}</td>
        <td>{{ $bakedGood->weight_gram . 'g' }}</td>
        <td>{{ $bakedGood->is_available ? 'Yes' : 'No' }}</td>
        <td>{{ $bakedGood->updated_at }}</td>
        <td>
            <!-- View action -->
            <a href="{{ route('baked_goods.show', $bakedGood->id) }}"
                class="btn btn-sm btn-primary">View</a>

            @if (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('baked_goods.edit', $bakedGood->id) }}"
                    class="btn btn-sm btn-secondary">Edit</a>

                <!-- Delete action -->
                <form action="{{ route('baked_goods.destroy', $bakedGood->id) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this baked good?')">Delete</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach --}}
