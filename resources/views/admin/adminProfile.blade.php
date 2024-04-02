
@include('adminNavPanel')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooking Mamau</title>


</head>
<body class="">
<div class="container light-style flex-grow-1 container-p-y ">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <div class="card overflow-hidden ">
            <div class="row no-gutters row-bordered row-border-light  ">
      
                <div class="col-md-3 pt-0 border-right  ">

                <div class="bg-purple-300">
    <div class="card-body media align-items-center">
        <img src="images/logo.png" alt="" class="d-block w-80 rounded-full ">
    
    </div>
    <div class="list-group list-group-flush account-settings-links">
        <div class="ml-4 flex items-center justify-center">
            <label class="bg-yellow-300 rounded-3xl py-3 px-8 font-medium inline-block mr-4 hover:bg-transparent hover:border-yellow-300 hover:text-black duration-300 hover border border-transparent">
                Edit Profile photo
                <input type="file" class="account-settings-fileinput">
            </label>
            <br>
        </div>
        <div class="flex items-center justify-center text-gray-500 small">Allowed JPG, GIF or PNG.(Max size of 800K)</div>

        <a class="list-group-item list-group-item-action active mt-10" data-toggle="list" href="#account-general">General</a>
        <a class="list-group-item list-group-item-action mb-10" data-toggle="list" href="#account-change-password">Change password</a>
    </div>
</div>
</div>
                <div class="col-md-9  bg-purple-100">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            
                            <hr class="border-light m-0">
                            <div class="flex flex-col h-full  bg-purple-100">
                             <!-- Your content before the card-body -->
    
                            <div class="flex-grow"> <!-- This will push the card-body to the bottom -->

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" value="">
                                </div>
                            </div>
                            </div>
                        </div>

                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <button type="button" class="bg-yellow-300 rounded-3xl py-3 px-8 font-medium inline-block mr-4 hover:bg-transparent hover:border-yellow-300 hover:text-black duration-300 hover border border-transparent">Save changes</button>&nbsp;
            <button type="button" class="bg-gray-500 text-white rounded-3xl py-3 px-8 font-medium inline-block mr-4 hover:bg-transparent hover:border-pink-900 hover:text-gray-800 duration-300 hover border border-transparent">Cancel</button>
        </div>
    </div>
       
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

    </script>
</body>

</html>
