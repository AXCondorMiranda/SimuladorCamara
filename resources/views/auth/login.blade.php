<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_header.png')  }}">
    <title>CAMARA FORTALEZA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(90deg,#dbdce2, #9293ac,#5c6488);
        }

        .login-container {
            margin-top: 100px;
        }

        .login-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
        }
        .custom-btn {
            background-color: #000000;
            color: #ffffff;
            border-color: #2b2e55;
        }

        .custom-btn:hover {
            background-color: #2b2e55;
            border-color: #2b2e55;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 login-container">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <img style="height: 100px" src="{{asset('img/auth/asocalef_inicio.png')}}" alt="Logo" class="img-fluid">
                    </div>
                    <h3 class="text-center mb-4">Iniciar Sesión</h3>
                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block custom-btn">Iniciar Sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
