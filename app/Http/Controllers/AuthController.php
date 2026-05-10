<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\RegistroActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar login
    public function showLogin()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
{
    $request->validate([
        'correo'    => 'required|email',
        'contrasena'=> 'required',
    ], [
        'correo.required'     => 'El correo es obligatorio.',
        'correo.email'        => 'Ingresa un correo válido.',
        'contrasena.required' => 'La contraseña es obligatoria.',
    ]);

    $usuario = Usuario::where('correo', $request->correo)
                  ->where('contrasena', $request->contrasena)
                  ->first();

    if ($usuario) {
        Auth::loginUsingId($usuario->id);
        $request->session()->regenerate();

        try {
            RegistroActividad::log(
                'Inicio de sesión',
                "El usuario {$usuario->nombre} ({$usuario->rol}) inició sesión."
            );
        } catch (\Exception $e) {}

        return $this->redirigirPorRol();
    }

    try {
        RegistroActividad::log(
            'Intento de sesión fallido',
            "Intento fallido con correo: {$request->correo}"
        );
    } catch (\Exception $e) {}

    return back()->withErrors(['correo' => 'Correo o contraseña incorrectos.']);
}
    // Mostrar registro
    public function showRegistro()
    {
        return view('auth.registro');
    }

    // Procesar registro
    public function registro(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|min:3',
            'correo'    => 'required|email|unique:usuarios,correo',
            'usuario'   => 'required|min:3|unique:usuarios,usuario|regex:/^\S+$/',
            'contrasena'=> 'required|min:6',
        ], [
            'correo.unique'   => 'Este correo ya está registrado.',
            'usuario.unique'  => 'Este nombre de usuario ya existe.',
            'usuario.regex'   => 'El usuario no puede contener espacios.',
            'contrasena.min'  => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        Usuario::create([
            'nombre'     => $request->nombre,
            'correo'     => $request->correo,
            'usuario'    => $request->usuario,
            'contrasena' => $request->contrasena, // Se hashea automáticamente por el cast
            'rol'        => 'cliente',
        ]);

        RegistroActividad::log(
            'Nuevo registro',
            "Se registró el usuario: {$request->usuario} ({$request->correo})"
        );

        return redirect()->route('login')->with('success', 'Usuario registrado. Ya puedes iniciar sesión.');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $user = Auth::user();
        RegistroActividad::log(
            'Cierre de sesión',
            "El usuario {$user->nombre} ({$user->rol}) cerró sesión."
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Redirigir según rol del usuario
    private function redirigirPorRol()
    {
        $user = Auth::user();

        switch ($user->rol) {
            case 'administrador':
                return redirect()->route('admin.dashboard');
            case 'agente':
                return redirect()->route('agente.dashboard');
            case 'asistente':
                return redirect()->route('asistente.dashboard');
            case 'cliente':
                return redirect()->route('cliente.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}
