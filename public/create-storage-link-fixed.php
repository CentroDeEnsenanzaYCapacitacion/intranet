<?php
/**
 * Script para crear enlace simbólico cuando Laravel está fuera de public_html
 * ELIMINAR DESPUÉS DE USAR
 */

// Laravel está en: /home/u427060244/.../intranet_dev/
// Public está en: /home/u427060244/.../public_html/intranet_dev/

// Desde public_html/intranet_dev necesitamos ir a ../../intranet_dev/storage/app/public
$target = dirname(dirname(__DIR__)) . '/intranet_dev/storage/app/public';
$link = __DIR__ . '/storage';

echo "<h2>Creando enlace simbólico en el servidor</h2>";
echo "<p><strong>Script ubicado en:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Target (absoluto):</strong> " . realpath(dirname($target)) . '/' . basename($target) . "</p>";
echo "<p><strong>Link:</strong> $link</p>";
echo "<hr>";

// Verificar que el target existe
if (!is_dir($target)) {
    echo "❌ <strong>Error: La carpeta storage/app/public no existe en la ruta:</strong><br>";
    echo "<code>$target</code><br>";
    echo "<p>Verifica la estructura del proyecto.</p>";
    
    // Intentar encontrar storage
    echo "<p>Intentando buscar storage...</p>";
    $possiblePaths = [
        dirname(dirname(__DIR__)) . '/storage/app/public',
        dirname(dirname(dirname(__DIR__))) . '/intranet_dev/storage/app/public',
    ];
    
    foreach ($possiblePaths as $path) {
        echo "Probando: <code>$path</code> - ";
        if (is_dir($path)) {
            echo "✅ EXISTE<br>";
            $target = $path;
            break;
        } else {
            echo "❌ No existe<br>";
        }
    }
    
    if (!is_dir($target)) {
        exit;
    }
}

echo "<p>✅ Carpeta storage encontrada en: <code>$target</code></p>";

// Verificar si el link ya existe
if (is_link($link)) {
    echo "⚠️ El enlace ya existe. Eliminando...<br>";
    unlink($link);
}

if (file_exists($link) && !is_link($link)) {
    echo "⚠️ Existe un archivo/carpeta con ese nombre. Eliminando...<br>";
    if (is_dir($link)) {
        @rmdir($link);
    } else {
        unlink($link);
    }
}

// Crear el enlace simbólico
if (symlink($target, $link)) {
    echo "<hr>";
    echo "✅ <strong style='color: green; font-size: 18px;'>Enlace simbólico creado correctamente</strong><br>";
    echo "<p>Enlace: <code>$link</code> → <code>$target</code></p>";
    echo "<p><strong>Ahora las imágenes deberían verse.</strong></p>";
    echo "<p>Prueba accediendo a un ticket con imágenes.</p>";
} else {
    echo "<hr>";
    echo "❌ <strong>Error al crear el enlace simbólico</strong><br>";
    echo "<p>El servidor no permite crear symlinks desde PHP.</p>";
    echo "<p><strong>Contacta a soporte de Hostinger</strong> para que lo hagan manualmente.</p>";
}

echo "<hr>";
echo "<p style='color: red; font-weight: bold; font-size: 16px;'>⚠️ ELIMINA ESTE ARCHIVO INMEDIATAMENTE</p>";
?>
