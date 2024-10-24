<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="{{asset('theme/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('theme/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/css/form.css')}}">
</head>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid content-wrapper">
            <div class="row justify-content-md-center">
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Registration form</h4>
                        </div>
                        <form class="forms-sample" action="/createUser" method="post">
                            <div class="card-body">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email"> email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-gradient-primary mr-2">Save User</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        <!-- <a href="/register"> Register User</a> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>