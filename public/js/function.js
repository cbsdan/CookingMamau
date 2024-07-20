// //Login
// $(function () {
//     $('#login_form').submit(function (e) {
//         e.preventDefault();
//         $("#login_btn").val('Please Wait...');

//         $.ajax({
//             url: $(this).attr('action'), // Use form action attribute as URL
//             method: 'post',
//             data: $(this).serialize(),
//             dataType: 'json',
//             success: function (res) {
//                 if (res.status === 422) {
//                     showError('email', res.message.email);
//                     showError('password', res.message.password);
//                     $("#login_btn").val('Login');
//                 } else if (res.status === 401) {
//                     $("#login_alert").html(showMessage('danger', res.message));
//                     $("#login_btn").val('Login');
//                 } else if (res.status === 200 && res.message === 'Login Successful') {
//                     window.location.href = res.redirect; // Redirect to profile page
//                 }
//             },
//             error: function (err) {
//                 console.error('Error:', err);
//                 $("#login_alert").html(showMessage('danger', 'An error occurred while logging in.'));
//                 $("#login_btn").val('Login');
//             }
//         });
//     });
// })

function showError(field,message)
{
    if(!message){
        $("#" + field)
        .addClass("is-valid")
        .removeClass("is-invalid")
        .siblings(".invalid-feedback")
        .text("")
    } else {
        $("#" + field)
        .addClass("is-invalid")
        .removeClass("is-valid")
        .siblings(".invalid-feedback")
        .text(message);
    }
}

function removeValidationClasses(form){
    $(form).each(function(){
        $(form).find(':input').removeClass("is-valid is-invalid");
    })
}

function showMessage(type, message){
    return `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
  <strong>${message}</strong> You should check in on some of those fields below.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>`;
}


function logout() {
    $.ajax({
        url: '/api/logout',  // Adjust the URL if needed
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Ensure CSRF token is sent
        },
        success: function (response) {
            console.log('Logged out successfully');
            window.location.href = '/login';  // Redirect to login page or desired route
        },
        error: function (xhr, status, error) {
            console.error('Error logging out:', error);
        }
    });
}
