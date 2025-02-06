<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_header.png') }}">
    <title>CAMARA FORTALEZA</title>
    <!-- CDNs de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- CDNs de Font-Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link type="text/css" href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    <div class="row color-pnp">
        <div class="col-md-1 d-flex justify-content-center align-items-center">
            <img src="{{ asset('images/emblema-pnp.png') }}" class="rounded-circle" width="90" height="120">
        </div>
        <div class="col-md-10">
            <h5 class="text-white text-center">POLICÍA NACIONAL DEL PERÚ</h5>
            <h5 class="text-white text-center">Sistema de Evaluación del Conocimiento Policial - SIECOPOL</h5>
            <p class="text-white text-center">Módulo de Examen Virtual</p>
            <p class="text-white text-center">SIMULACRO - PROCESO DE ASCENSO DEL AÑO 2024, PROMOCIÓN 2025</p>
        </div>
        <div class="col-md-1 d-flex justify-content-center align-items-center">
            <p id="current-date" class="text-white">13/05/2024</p>
        </div>
    </div>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var currentDateElement = document.getElementById('current-date');
            var currentDate = new Date().toLocaleDateString();
            currentDateElement.textContent = currentDate;
        });
    </script>
</body>

</html>
