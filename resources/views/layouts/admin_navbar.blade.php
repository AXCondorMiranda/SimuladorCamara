<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{asset('images/logoAsocalef.png')}}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" style="color:white" aria-current="page" href="{{route('examen.index')}}">Examenes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="color:white" aria-current="page"
                        href="{{ route('admin.practica.index') }}">Prácticas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="color:white" aria-current="page"
                        href="{{ route('preguntas.index') }}">Preguntas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" style="color:white" aria-current="page"
                        href="{{route('usuario.index')}}">Usuarios</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('images/sticker.png')}}" alt="Avatar" class="rounded-circle" width="50"
                                height="60">
                            Hola {{auth()->user()->name}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="{{route('logout')}}"><i class="bi bi-person-slash"></i>
                                    Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>