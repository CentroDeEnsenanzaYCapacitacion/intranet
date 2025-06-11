<?php

namespace App\Http\Controllers;

class DeepLinkController extends Controller
{
    public function friend($friendId)
    {
        $appStoreUrl = 'https://apps.apple.com/app/idTU_APP_ID';
        $playStoreUrl = 'https://play.google.com/store/apps/details?id=com.tuempresa.ricoapp';

        $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redirigiendo...</title>
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
      const deepLink = "rico-guide://friend/{$friendId}";
      const fallback = /iPad|iPhone|iPod/.test(navigator.userAgent)
        ? "{$appStoreUrl}"
        : "{$playStoreUrl}";

      window.location.href = deepLink;

      setTimeout(() => {
        window.location.href = fallback;
      }, 10000);
    })();
  </script>
</body>
</html>
HTML;

        return response($html)->header('Content-Type', 'text/html');
    }
}
