<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Abriendo la app...</title>
  <script>
    const friendId = "{{ $friendId }}";
    const deepLink = `rico-guide://friend/${friendId}`;
    window.location.href = deepLink;

    // Fallback tras 2 segundos (opcional)
    setTimeout(() => {
      window.location.href = "https://google.com";
    }, 2000);
  </script>
</head>
<body>
  <p>Redirigiendo a la app...</p>
</body>
</html>
