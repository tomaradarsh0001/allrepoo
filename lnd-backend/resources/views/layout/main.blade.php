<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from www.bootstrapdash.com/demo/purple-admin-free/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Mar 2023 04:36:04 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Land and Development Office | Ministry of Housing & Urban Affairs</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('theme/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/vendors/css/vendor.bundle.base.css')}}">
    <script src="{{asset('jquery/jquery-3.7.1.min.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('theme/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/css/custom.css')}}"> <!--Modifications-->
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('theme/assets/images/logoldo-mini.jpg')}}" />
</head>

<body>
    <div class="container-scroller">
        @include('layout.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layout.sidebar')
            @yield('content')
        </div>
    </div>
    <!-- plugins:js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{asset('theme/assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('theme/assets/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('theme/assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('theme/assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('theme/assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('theme/assets/js/misc.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('theme/assets/js/dashboard.js')}}"></script>
    <script src="{{asset('theme/assets/js/todolist.js')}}"></script>
    <!-- End custom js for this page -->
    <script src="//cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myDataTable').DataTable();
        } );
    </script>
</body>

</html>