# Plan de pruebas

## Progreso
- [x] Login básico: éxito, credenciales inválidas, inactivo, redirect de guest.
- [x] Autorización por rol (ejemplo: calendario).
- [ ] Confirmación de contraseña: requiere confirmación en rutas sensibles, respeta ventana.
- [ ] Políticas de contraseña: cambio, set-password y reset-password con reglas fuertes.
- [ ] Reset de contraseña: envío de correo, token inválido/expirado, restablece y limpia tokens.
- [ ] Set-password por invitación: token válido/inválido, marca invitación usada.
- [ ] Cambio de contraseña: password actual incorrecta, nueva diferente, reglas.

## Prioridad y cobertura
### Seguridad y acceso
- [ ] Rate limiting en login y recuperación.
- [ ] Confirmación de contraseña en rutas sensibles.
- [ ] Acceso por rol en rutas admin/system/web (matriz de roles clave).
- [ ] Acceso denegado para usuarios inactivos.
- [ ] Login actualiza last_login.

### Usuarios y roles
- [ ] Crear/editar/bloquear/activar usuarios.
- [ ] Reenvío de invitación.
- [ ] Invitación: token válido/inválido, uso único.
- [ ] Cambio de rol y validación de permisos efectivos.

### Estudiantes
- [ ] Búsqueda y perfil.
- [ ] Actualización de datos y formulario.
- [ ] Imagen de perfil: upload y acceso.
- [ ] Documentos: upload y descarga.
- [ ] Observaciones y solicitud de cambio de colegiatura.
- [ ] Acceso denegado a documentos de otro estudiante.

### Cobranza
- [ ] Buscar colegiaturas.
- [ ] Insertar recibo.
- [ ] Insertar paybill.
- [ ] Reimpresión de recibo.
- [ ] No permite reimpresión sin permisos.

### Reportes y estadísticas
- [ ] Crear reporte.
- [ ] Validar monto.
- [ ] Recibo o solicitud.
- [ ] Estadísticas y facturación (consultas principales).
- [ ] Validación de insumos obligatorios en reportes.

### Catálogos
- [ ] Cursos: alta/edición/baja.
- [ ] Montos: update, generate, clean.
- [ ] Percepciones: alta/baja.
- [ ] Preguntas: alta/edición/baja/activar.
- [ ] Respuestas inválidas no modifican el catálogo.

### Calendarios
- [ ] EUB update.
- [ ] Hour assignments: alta/edición/baja.
- [ ] Conflictos de horarios rechazados.

### Tickets
- [ ] Crear ticket.
- [ ] Mensajes.
- [ ] Cambio de estado.
- [ ] Adjuntos e imágenes.
- [ ] No permite ver adjuntos de tickets ajenos.

### Web admin
- [ ] Carousel: alta/baja/actualización.
- [ ] MVV: actualización.
- [ ] Opiniones: alta/baja/actualización.
- [ ] Validación de archivos en carousel/opiniones.

### PDFs y QR
- [ ] Generación de recibo PDF.
- [ ] Generación de paybill PDF.
- [ ] Respuesta con PDF y headers correctos.

### Correo
- [ ] Reset de contraseña.
- [ ] Invitación de usuario.
- [ ] Notificaciones de tickets.
- [ ] Bienvenida alumno.
- [ ] No se envía correo si usuario inactivo.

## Fuera de alcance (bajo valor)
- HTML/CSS estático sin lógica.
- Librerías de terceros (PDF, QR, frontend).
- Vistas informativas sin acciones.

## Notas de infraestructura
- Usar SQLite en memoria para tests.
- Datos mínimos: roles + crew para crear usuarios.
