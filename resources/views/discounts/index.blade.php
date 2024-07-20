@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Discounts</h1>
    <hr>
    <form id="discountImportForm" enctype="multipart/form-data" class='row g-3 mb-3'>
        @csrf
        <div class="col-9">
            <input type="file" name="item_upload" class="form-control w-100" required/>
        </div>
        <div class="col-3">
            <button type="submit" class="btn btn-primary w-100">Import Excel File</button>
        </div>
    </form>
    <table class="table" id="discountTable">
        <thead>
            <tr>
                <th>Discount Code</th>
                <th>Logo</th>
                <th>Percent</th>
                <th>Max Number of Buyers</th>
                <th>Min Order Price</th>
                <th>Max Discount Amount</th>
                <th>Usage</th>
                <th>Start Date</th>
                <th>End Date</th>
                {{-- @if (auth()->check() && auth()->user()->is_admin) --}}
                    <th>Actions</th>
                {{-- @endif --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Modal for Add/Edit Discount -->
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Discount</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="discountForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="discount_code">Discount Code</label>
                            <input type="text" class="form-control" id="discount_code" name="discount_code" required>
                        </div>
                        <div class="form-group">
                            <label for="percent">Percent</label>
                            <input type="number" class="form-control" id="percent" name="percent" required>
                        </div>
                        <div class="form-group">
                            <label for="max_number_buyer">Max Number of Buyers</label>
                            <input type="number" class="form-control" id="max_number_buyer" name="max_number_buyer">
                        </div>
                        <div class="form-group">
                            <label for="min_order_price">Min Order Price</label>
                            <input type="number" class="form-control" id="min_order_price" name="min_order_price">
                        </div>
                        <div class="form-group">
                            <label for="max_discount_amount">Max Discount Amount</label>
                            <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount">
                        </div>
                        <div class="form-group">
                            <label for="is_one_time_use">Usage</label>
                            <select class="form-control" id="is_one_time_use" name="is_one_time_use" required>
                                <option value="1">One Time</option>
                                <option value="0">Multiple</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="discount_start">Start Date</label>
                            <input type="date" class="form-control" id="discount_start" name="discount_start" required>
                        </div>
                        <div class="form-group">
                            <label for="discount_end">End Date</label>
                            <input type="date" class="form-control" id="discount_end" name="discount_end" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary ms-auto" id="discountSubmit">Submit</button>
                        <button type="button" class="btn btn-primary ms-auto" id="discountUpdate" style="display:none;">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/discount.js') }}" defer></script>

@endsection
