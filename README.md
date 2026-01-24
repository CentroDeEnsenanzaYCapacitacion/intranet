# IntraCEC

Intranet del Centro de Capacitacion CEC. Sistema de gestion academica y administrativa para la operacion interna.

## Modulos principales
- Autenticacion, cambio y recuperacion de contrasena; invitacion de usuarios.
- Gestion de usuarios y roles.
- Gestion de estudiantes: perfiles, documentos, observaciones y solicitudes de cambio de colegiatura.
- Cobranza: colegiaturas, paybills, recibos y reimpresion.
- Reportes y estadisticas operativas y de facturacion.
- Calendarios y asignacion de horarios.
- Catalogos: cursos, montos, percepciones y preguntas.
- Administracion de contenido web (carousel, MVV, opiniones).
- Mesa de ayuda con tickets y mensajes.
- Generacion de PDFs con QR para recibos.

## Stack
- PHP 8.1 + Laravel 10
- MySQL/MariaDB
- Vite (frontend assets)
- TCPDF y endroid/qr-code

## Requisitos
- PHP 8.1+
- Composer
- Node.js + npm
- MySQL/MariaDB

## Configuracion local
Ajusta APP_URL, DB_* y MAIL_* en .env antes de migrar.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

## Operacion
- Construir assets para produccion: `npm run build`.
- Limpiar cache (requiere CACHE_CLEAR_TOKEN en .env): `GET /internal/clear-cache?token=...`.

## Estructura relevante
- `routes/` rutas web y modulos.
- `app/Http/Controllers/` logica de cada modulo.
- `resources/views/` vistas Blade.
- `public/assets/` assets estaticos y JS.
