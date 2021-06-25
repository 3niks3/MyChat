<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <!-- Font-awesomeCSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/all.css" rel="stylesheet">
    <link href="/css/noty_theme.css" rel="stylesheet">

    @stack('style')
    <title>Chat groups App</title>

</head>
<body>

<div class="container-fluid d-flex w-100 h-100 min-vh-100 p-0 g-0">

    <header>
        @if(auth('web')->check())
            <div class="container-fluid  h-100" style="max-width:250px">
                @include('includes.menu.profile.sidebar-profile')
            </div>
        @endif
    </header>

    <div class="container-fluid w-100" style="min-width: 250px">
        <!-- Main Page-->

        <div class="w-100">
            @if(auth()->check())
                @include('includes.menu.profile-menu')
            @else
                @include('includes.menu.home-menu')
            @endif
        </div>

        <main>
            <div class="container-fluid w-100 ps-3 pe-3">
                @yield('content')
            </div>
        </main>


    </div>

@stack('footer')
@stack('modals')

</div>


<script src="/js/app.js"></script>
<script src="/js/all.js"></script>

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js" integrity="sha384-lpyLfhYuitXl2zRZ5Bn2fqnhNAKOAaM/0Kr9laMspuaMiZfGmfwRNFh8HlMy49eQ" crossorigin="anonymous"></script>
-->

<!--  Custom CDN scripts -->
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
@stack('before_scripts')

@stack('scripts')

<script>
    $(document).ready(function () {

        // $("#sidebar").mCustomScrollbar({
        //     theme: "minimal"
        // });

        $('#sidebarCollapse').on('click', function () {
            // open or close navbar
            $('#sidebar').toggleClass('active');
            // close dropdowns
            $('.collapse.in').toggleClass('in');
            // and also adjust aria-expanded attributes we use for the open/closed arrows
            // in our CSS
            $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        });

    });
</script>

@foreach (Alert::getMessages() as $type => $messages)
    @foreach ($messages as $message)

        <script>
            new Noty({
                type: "{{$type}}",
                text: '{{$message}}',
            }).show();
        </script>
    @endforeach
@endforeach

</body>
</html>
