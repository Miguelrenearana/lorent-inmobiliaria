{{-- resources/views/auth/registro.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Lorent Inmobiliaria</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
<main>
    <div class="contenedor_todo">
        <div class="caja_trasera">
            <div class="caja_trasera_login">
                <h3>¿Ya tienes cuenta?</h3>
                <p>Inicia sesión para entrar en la página</p>
                <button id="btn_iniciar-sesion">Iniciar Sesión</button>
            </div>
            <div class="caja_trasera_register">
                <h3>¿Aún no tienes una cuenta?</h3>
                <p>Regístrate para que puedas iniciar sesión</p>
                <button id="btn_registrarse">Registrarse</button>
            </div>
        </div>

        <div class="contenedor_login-register">

            {{-- LOGIN --}}
            <form action="{{ route('login.post') }}" method="POST" class="formulario_login">
                @csrf
                <h2>Iniciar Sesión</h2>

                @if($errors->any())
                    <div style="color:#e53935;font-size:12px;margin-bottom:10px">
                        {{ $errors->first() }}
                    </div>
                @endif

                <input type="text"     name="correo"    placeholder="Correo Electrónico" value="{{ old('correo') }}">
                <input type="password" name="contrasena" placeholder="Contraseña">
                <button type="submit">Entrar</button>
            </form>

            {{-- REGISTRO --}}
            <form action="{{ route('registro.post') }}" method="POST" class="formulario_register">
                @csrf
                <h2>Registrarse</h2>

                @if($errors->any())
                    <div style="color:#e53935;font-size:12px;margin-bottom:10px">
                        {{ $errors->first() }}
                    </div>
                @endif
                @if(session('success'))
                    <div style="color:#2e7d32;font-size:12px;margin-bottom:10px">
                        {{ session('success') }}
                    </div>
                @endif

                <input type="text"     name="nombre"    placeholder="Nombre Completo" value="{{ old('nombre') }}">
                <input type="text"     name="usuario"   placeholder="Nombre de Usuario" value="{{ old('usuario') }}">
                <input type="email"    name="correo"    placeholder="Correo Electrónico" value="{{ old('correo') }}">
                <input type="password" name="contrasena" placeholder="Contraseña">
                <button type="submit">Registrarse</button>
            </form>

        </div>
    </div>
</main>

<script src="{{ asset('js/scrip.js') }}"></script>
</body>
</html>