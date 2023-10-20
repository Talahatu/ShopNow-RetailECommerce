<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>ShopNow</title>
    <!-- MDB icon -->
    <link rel="icon" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ asset('mdb5/css/mdb.min.css') }}" />
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @laravelPWA
</head>

<body>
    @include('layouts.navbar')
    <!-- Start your project here-->
    <div class="container">
        @yield('content')
    </div>
    <!-- End your project here-->

    <!-- MDB -->
    <script type="text/javascript" src="{{ asset('mdb5/js/mdb.min.js') }}"></script>
    <!-- Custom scripts -->
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("Ready");
            $(document).on("click", "#logout", function() {
                $(this).parent().submit()
            })
        });
    </script>
</body>

</html>
