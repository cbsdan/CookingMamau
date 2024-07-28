@extends('layouts.app')
@section('title', 'Orders')

@section('content')
<style>
    /* Custom width for the modal */
    .modal-dialog {
        max-width: 900px;
    }
</style>

<div class="container">
    <h1>Orders</h1>
    <hr>
    <div class="mb-3">
        <label for="orderStatusFilter">Filter by Order Status:</label>
        <select id="orderStatusFilter" class="form-control" style="background-color: #FFF3CD">
            <option value="">All</option>
            <option value="Pending">Pending</option>
            <option value="Canceled">Canceled</option>
            <option value="Preparing">Preparing</option>
            <option value="Out for Delivery">Out for Delivery</option>
            <option value="Delivered">Delivered</option>
        </select>

    </div>
    <table id="orders-table" class="table table-striped table-bordered table-warning table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Buyer Name</th>
                <th>Email</th>
                <th>Delivery Address</th>
                <th>Buyer Note</th>
                <th>Discount Code</th>
                <th>Schedule Date</th>
                <th>Order Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="orderModalLabel"><strong>Order Details</strong></h3>
            </div>
            <div class="modal-body">
                <div id="order-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src={{asset('js/order.js')}}></script>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

@endsection
