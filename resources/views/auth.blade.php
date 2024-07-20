<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="register">
        <h2>Register</h2>
        <form id="register_form">
            <input type="text" id="name" name="name" placeholder="Name" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit" id="register_btn">Register</button>
        </form>
    </div>

    <div id="login">
        <h2>Login</h2>
        <form id="login_form">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" id="login_btn">Login</button>
        </form>
        <div id="login_alert"></div>
    </div>

    <div id="profile" style="display:none;">
        <h2>Profile</h2>
        <p id="profile_info"></p>
        <button id="logout_btn">Logout</button>
    </div>

    <script>
        $('#register_form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/register',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    alert('Registration successful!');
                },
                error: function(err) {
                    alert('Registration failed.');
                    console.log(err);
                }
            });
        });

        $('#login_form').submit(function(e) {
            e.preventDefault();
            $("#login_btn").val('Please Wait...');
            $.ajax({
                url: '/api/login',
                method: 'post',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.token) {
                        localStorage.setItem('authToken', res.token);
                        window.location.href = res.redirect;
                    }
                },
                error: function(err) {
                    $("#login_alert").html('<p style="color:red;">Login failed.</p>');
                    $("#login_btn").val('Login');
                    console.log(err);
                }
            });
        });

        function getProfile() {
            $.ajax({
                url: '/api/profile',
                method: 'get',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                success: function(res) {
                    $('#profile_info').text('Name: ' + res.name + ', Email: ' + res.email);
                    $('#profile').show();
                    $('#login').hide();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        $('#logout_btn').click(function() {
            $.ajax({
                url: '/api/logout',
                method: 'post',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                success: function(res) {
                    localStorage.removeItem('authToken');
                    alert('Logged out successfully');
                    $('#profile').hide();
                    $('#login').show();
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $(document).ready(function() {
            if (localStorage.getItem('authToken')) {
                getProfile();
            }
        });
    </script>
</body>
</html>
