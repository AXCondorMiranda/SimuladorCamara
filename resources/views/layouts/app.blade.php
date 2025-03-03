<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_header.png')  }}">
    <title>CAMARA FORTALEZA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">
</head>

<body>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div>
    @include('layouts.navbar')
</div>
<div style="min-height: 50vh !important;">
    @yield('content')
</div>

<div>
    @include('layouts.footer')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>
