@extends('layouts.app')

@section('content')
    <style>
        .added-ingredient-container {
            display: flex;
            align-items: center;
        }
        #ingredientsList, #qtyIngredient {
            border: 1px solid black;

        }
        .ingredient-container {
            display: flex;
            align-items: center;
        }
    </style>
    <div class="container">
        <h1>Baked Goods</h1>
        <hr>
        <!-- Modal -->
        <form id="bakedGoodImportForm" enctype="multipart/form-data" class='row g-3 mb-3'>
            @csrf
            <div class="col-9">
                <input type="file" name="item_upload" class="form-control w-100" required/>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary w-100">Import Excel File</button>
            </div>
        </form>
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
                            <div id=""></div>
                            <label for="ingredientsList">Ingredients</label>
                            <div id='added-ingredient-container'>
                            </div>
                            <div id="add-ingredient-container" class='row px-3 gap-2'>
                                <select name="ingredientsList" id="ingredientsList" class='p-2 col-5'>
                                    <option value='false'>Select an Ingredient</option>
                                </select>
                                <input type='number' name='qty' id="qtyIngredient" class='p-2 col-2' placeholder="qty">
                                <input type='text' placeholder="unit" class='p-2 col-2' id='unitIngredient' disabled>
                                <button type="button" class="btn btn-success col-2" id="addIngredientBtn" disabled>Add</button>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="addNewIngredientBtn">Add New Ingredient</button>
                            <div class="mb-3">
                                <label for="images" class="form-label">Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple>
                            </div>
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
                                <label for="name" class="control-label">Name</label>
                                <input type="text" class="form-control ingredient_name" id="name" name="name">
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
                        <button id="ingredientSubmit" type="submit" class="btn btn-primary">Save</button>
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

    <script src="{{ asset('js/bakedgood.js') }}" defer></script>
@endsection
