<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Abriendo la app...</title>
  <style>
    body {
      font-family: sans-serif;
      background-color: #fff;
      text-align: center;
      padding: 100px 20px;
      color: #333;
    }

    .spinner {
      margin-top: 30px;
      width: 40px;
      height: 40px;
      border: 4px solid #ccc;
      border-top-color: #000;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      display: inline-block;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <h2>Redirigiendo a la app...</h2>
  <p>Si no se abre automáticamente, serás enviado a la tienda en unos segundos.</p>
  <div class="spinner"></div>

  <script>
    (function () {
      const friendId = "{{ $friendId }}";
      const deepLink = `rico-guide://friend/${friendId}`;

      const userAgent = navigator.userAgent || navigator.vendor || window.opera;
      const isAndroid = /Android/i.test(userAgent);
      const isIOS = /iPad|iPhone|iPod/.test(userAgent);

      const fallback = isIOS
        ? "https://apps.apple.com/app/idTU_APP_ID" // Reemplaza con tu app store ID real
        : "https://play.google.com/store/apps/details?id=com.tuempresa.ricoapp"; // Reemplaza con tu ID de paquete Android

      // Intentar abrir la app
      window.location.href = deepLink;

      // Redirigir a la tienda después de 2 segundos si no se abrió la app
      setTimeout(() => {
        window.location.href = fallback;
      }, 2000);
    })();
  </script>
</body>
</html>
