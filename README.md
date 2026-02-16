# IntraCEC

Sistema de gestion academica y administrativa del Centro de Capacitacion CEC. Plataforma intranet para la operacion interna de planteles, cobranza, estudiantes y personal.

| Entorno | URL |
|---------|-----|
| Produccion | `https://intranet.capacitacioncec.edu.mx` |
| Desarrollo | `https://intranet-dev.capacitacioncec.edu.mx` |

## Stack tecnologico

| Componente | Tecnologia |
|------------|------------|
| Backend | PHP 8.1 + Laravel 10 |
| Base de datos | MySQL / MariaDB |
| Frontend | Blade + assets estaticos en `public/assets` |
| PDF | TCPDF + endroid/qr-code |
| Testing | PHPUnit + SQLite (in-memory) |

## Modulos

### Autenticacion y usuarios
- Login, cambio y recuperacion de contrasena
- Invitacion de nuevos usuarios por correo
- Gestion de usuarios con roles (Admin, Manager, Staff, Docente, Coordinador)

### Estudiantes
- Perfiles con documentos, observaciones y tutores
- Solicitudes de cambio de colegiatura
- Calendario de examenes y ventanas de evaluacion

### Cobranza y facturacion
- Recibos con generacion de PDF y codigo QR
- Vales de gasto (paybills)
- Solicitudes de cambio de importe para recibos y vales
- Estadisticas de facturacion con filtros por plantel, fecha, tipo de pago y tipo de recibo
- Cobranza y seguimiento de pagos de colegiaturas

### Reportes e informes
- Reportes operativos por periodo (mensual, bimestral, semestral, anual)
- Asignacion de responsables y seguimiento de estatus
- Descuentos de inscripcion y validacion de montos

### Sistema de solicitudes
- Flujo de aprobacion/rechazo con confirmacion por contrasena
- Tipos: descuento de inscripcion, correo institucional, cambio de colegiatura, cambio de importe
- Edicion de solicitudes pendientes antes de aprobar
- Visibilidad filtrada por plantel para roles no-admin

### Catalogos
- **Costos**: inscripciones por curso, generacion automatica, creacion de tipos personalizados
- **Cursos**: gestion de cursos activos por plantel
- **Percepciones**: configuracion de percepciones salariales del personal
- **Preguntas**: banco de preguntas para evaluaciones

### Calendarios
- Calendario academico con asignacion de horarios
- Gestion de horarios por plantel y curso

### Mesa de ayuda
- Tickets con categorias y prioridades
- Mensajes con adjuntos de imagenes
- Seguimiento de estatus (abierto, en proceso, cerrado)

### Contenido web
- Administracion de carousel, mision/vision/valores y opiniones

### Personal
- Listados (rosters) de staff por plantel
- Ajustes salariales y costos departamentales

## Estructura del proyecto

```
app/
  Http/
    Controllers/       # 23 controllers organizados por modulo
    Middleware/         # CheckRole, ForceUtf8, autenticacion
    Requests/          # Form Requests con validacion
  Models/              # 42 modelos Eloquent
database/
  migrations/          # Migraciones versionadas
  seeders/             # Datos iniciales
public/
  assets/
    js/                # JavaScript separado de las vistas Blade
    css/               # Estilos propios
resources/
  views/
    admin/             # Vistas de administracion
    system/            # Vistas operativas
    layout/            # Layout principal
    includes/          # Componentes reutilizables (modales, etc.)
routes/
  web.php             # Rutas publicas y agrupacion
  admin_*.php          # Rutas de administracion por modulo
  system_*.php         # Rutas operativas
  tickets.php          # Rutas de mesa de ayuda
tests/
  Feature/
    Admin/             # RequestTest, StaffTest, UserManagementTest
    Auth/              # Login, passwords, roles (6 archivos)
    Catalogues/        # AmountTest, CourseTest, PerceptionTest, QuestionTest
    Students/          # StudentAccessTest, StudentDocumentsAndCalendarTest
    System/            # CollectionTest, ReportTest
    Tickets/           # TicketFlowTest
```

## Roles y permisos

| ID | Rol | Acceso |
|----|-----|--------|
| 1 | Admin | Acceso completo. Aprobacion de solicitudes, gestion de catalogos, estadisticas globales |
| 2 | Manager | Gestion de su plantel. Cobranza, estudiantes, reportes filtrados por plantel |
| 3 | Staff | Operacion basica. Reportes, estudiantes, solicitudes de su plantel |
| 4 | Docente | Acceso limitado a funcionalidades academicas |
| 5 | Coordinador | Solicitudes y seguimiento operativo |

## Configuracion local (Laragon)

### Requisitos previos
- [Laragon](https://laragon.org/) con PHP 8.1+ y MySQL/MariaDB incluidos
- Composer (incluido en Laragon)

### Instalacion

1. Clonar el repositorio en la carpeta de proyectos de Laragon (`C:\laragon\www\`):

```bash
cd C:\laragon\www
git clone <repo-url> intranet
cd intranet
composer install
cp .env.example .env
php artisan key:generate
```

2. Crear la base de datos desde el panel de Laragon o con HeidiSQL (incluido en Laragon):

```sql
CREATE DATABASE intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Configurar `.env` con los datos de conexion local:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intranet
DB_USERNAME=root
DB_PASSWORD=
```

4. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

5. Laragon genera automaticamente un virtual host accesible en `http://intranet.test`. Si no aparece, reiniciar Laragon con **Menu > Apache > Reload** o reiniciar todos los servicios.

## Testing

El proyecto utiliza PHPUnit con SQLite in-memory para pruebas aisladas.

```bash
# Suite completa (216 tests, 515 assertions)
php artisan test

# Por modulo
php artisan test --filter=RequestTest
php artisan test --filter=AmountTest
php artisan test --filter=LoginTest
```

### Cobertura por modulo (conteo de tests, no coverage real)

| Modulo | Tests | Archivo |
|--------|-------|---------|
| Solicitudes | 27 | `Admin/RequestTest` |
| Costos | 25 | `Catalogues/AmountTest` |
| Usuarios | 7 | `Admin/UserManagementTest` |
| Autenticacion | 40 | `Auth/*` |
| Cursos | 12 | `Catalogues/CourseTest` |
| Percepciones | 13 | `Catalogues/PerceptionTest` |
| Preguntas | 18 | `Catalogues/QuestionTest` |
| Estudiantes | 20 | `Students/StudentAccessTest`, `Students/StudentDocumentsAndCalendarTest` |
| Cobranza | 15 | `System/CollectionTest` |
| Reportes | 13 | `System/ReportTest` |
| Tickets | 8 | `Tickets/TicketFlowTest` |
| Personal | 18 | `Admin/StaffTest` |
| Calendarios (parcial) | 9 | `Students/StudentDocumentsAndCalendarTest`, `Auth/RoleAccessTest` |
| Contenido web | 0 | Sin tests Feature dedicados |

Sin cobertura detectada:
- Contenido web (`routes/web_admin.php`) no tiene tests Feature asociados.
- `tests/Unit` no tiene pruebas (solo `.gitkeep`).

## Operacion

### Build de produccion

No aplica pipeline de build frontend en este repositorio. Los assets se sirven desde `public/assets`.

### Limpieza de cache

```
GET /internal/clear-cache?token=<CACHE_CLEAR_TOKEN>
```

### Ramas

| Rama | Proposito |
|------|-----------|
| `main` | Produccion estable |
| `staging` | Pre-produccion / QA |
| `development` | Desarrollo activo |

## Convenciones del proyecto

- **JavaScript**: nunca inline en Blade. Todo en `public/assets/js/` con `<script src="">` en las vistas
- **Acciones sensibles**: protegidas con modal de confirmacion por contrasena (`data-password-confirm`)
- **Modales**: sistema unificado con `modal_utils.js` (`openModal` / `closeModal` / `closeOnOverlay`)
- **Solicitudes**: flujo estandarizado con `SysRequest` + `RequestType` para cualquier cambio que requiera aprobacion
- **Validacion**: Form Requests para operaciones CRUD, validacion inline para flujos simples
