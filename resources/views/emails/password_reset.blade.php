{{-- resources/views/emails/password_reset.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperación de contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fb; color: #1f2937; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);">
        <h1 style="margin-top: 0; font-size: 24px; color: #0f172a;">Recuperación de contraseña</h1>
        <p style="font-size: 16px; line-height: 1.75; color: #334155;">
            Hola {{ $name ?? 'usuario' }},
        </p>
        <p style="font-size: 16px; line-height: 1.75; color: #334155;">
            Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el botón de abajo para continuar.
        </p>
        <a href="{{ $url }}" style="display: inline-block; padding: 12px 24px; background: #1d4ed8; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600;">
            Restablecer contraseña
        </a>
        <p style="margin-top: 24px; font-size: 14px; color: #64748b;">
            Si no solicitaste este cambio, ignora este correo.
        </p>
        <p style="font-size: 14px; color: #64748b;">Lorent Inmobiliaria</p>
    </div>
</body>
</html>
