<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fafafa; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .logo-text { font-family: 'Bebas Neue', Arial, sans-serif; font-size: 2.5rem; color: #2b2b2b; }
        .tagline { display: block; font-size: 0.65rem; font-weight: bold; letter-spacing: 0.2em; text-transform: uppercase; color: #3d9c3a; margin-top: 5px; }
        h1 { color: #2b2b2b; font-size: 1.8rem; margin-top: 30px; margin-bottom: 10px; }
        p { color: #4a4a4a; font-size: 1rem; line-height: 1.6; margin-bottom: 25px; }
        .code-box { background-color: #2b2b2b; color: #3d9c3a; padding: 20px; border-radius: 8px; font-size: 2.5rem; font-weight: bold; letter-spacing: 12px; text-align: center; display: inline-block; margin: 10px 0 30px 0; }
        .footer { font-size: 0.8rem; color: #888888; margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <div class="logo-text">AVIS</div>
            <span class="tagline">El Futuro Es AVIS</span>
        </div>
        
        <h1>Recuperación de Contraseña</h1>
        <p>Has solicitado restablecer tu contraseña. Usa el siguiente código de verificación de 6 dígitos en la aplicación:</p>
        
        <div class="code-box">
            {{ $code }}
        </div>
        
        <p>Si no has solicitado este cambio, por favor ignora este correo. <br>Por seguridad, este código expirará en poco tiempo.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} AVIS. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
