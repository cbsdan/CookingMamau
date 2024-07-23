@extends('layouts.app')

@section('content')
    <h1>My Orders</h1>
    <hr>

    <script>
        var buyerId = "{{ auth()->user()->buyer->id }}";

        $.ajax({
            type: "GET",
            url: `/api/order/${buyerId}/user`, // Ensure this renders correctly
            dataType: "json",
            success: function(data) {
                console.log(data);
            },
            error: function(error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Orders failed to fetch.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
@endsection
