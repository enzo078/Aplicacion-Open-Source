
# 📝 TODO - Proyecto Gestor de Tareas (CodeIgniter 4)

## 🏁 Primera Etapa - Configuración Inicial
- [x] Configurar `baseURL` en `App.php`
- [x] Configurar conexión a la base de datos en `Database.php`
- [ ] Crear base de datos MySQL (nombre sugerido: gestor_tareas)
- [ ] Agregar `.htaccess` para eliminar `index.php` de la URL

## 👤 Módulo de Usuarios
- [ ] Crear migración y modelo para la tabla `usuarios`
- [ ] Crear sistema de registro con validación de email y contraseña
- [ ] Crear sistema de login con sesiones
- [ ] Cierre de sesión
- [ ] Middleware para proteger rutas (usuarios autenticados)

## 📋 Módulo de Tareas
- [ ] Crear migración y modelo `TareaModel`
- [ ] Crear CRUD de tareas (crear, editar, eliminar, ver)
- [ ] Agregar campos: asunto, descripción, prioridad, estado, vencimiento, recordatorio, color
- [ ] Cambiar estado de la tarea desde la vista (definido, en proceso, completado)
- [ ] Resaltar visualmente tareas de alta prioridad
- [ ] Lógica para archivar tareas completadas
- [ ] Sección de tareas archivadas

## 🧩 Módulo de Subtareas
- [ ] Crear migración y modelo `SubtareaModel`
- [ ] Asociar subtareas a tareas (1:N)
- [ ] CRUD de subtareas dentro del detalle de la tarea
- [ ] Validar reglas:
  - [ ] Tarea completada solo si todas las subtareas están completadas
  - [ ] Si una subtarea se marca como completada, la tarea pasa a "En proceso"

## 🤝 Colaboración
- [ ] Crear tabla `colaboradores` para compartir tareas con otros usuarios
- [ ] Permitir asignar usuarios a tareas
- [ ] Validar que solo el dueño puede eliminar tarea o subtareas
- [ ] Los colaboradores pueden cambiar estado solo de subtareas que les pertenecen

## 📧 Notificaciones
- [ ] Enviar correo si se acerca la fecha de recordatorio
- [ ] Crear tabla `notificaciones` para registrar alertas

## 🧪 Validaciones y Seguridad
- [ ] Validar formularios (required, formatos, etc.)
- [ ] Validar acceso según roles/propietarios
- [ ] Evitar edición si la tarea está archivada o completada (excepto admin o dueño)

## 🎨 Estética y UX
- [ ] Usar Bootstrap/Tailwind para el panel de tareas
- [ ] Mostrar tareas en formato lista ordenable por fecha o prioridad
- [ ] Etiquetas de colores por prioridad y estados
- [ ] Modal o vista para ver detalle completo de una tarea

## 🔄 Extras (si hay tiempo)
- [ ] Filtro de búsqueda por estado, prioridad o palabra clave
- [ ] Sistema de etiquetas o categorías para tareas
- [ ] Exportar tareas a PDF o CSV
- [ ] API REST para tareas y subtareas

---

### 🗓️ Sugerencia: Primeras tareas para hoy
- [ ] Configurar entorno (baseURL, base de datos)
- [ ] Crear estructura base del proyecto (controladores, modelos, vistas)
- [ ] Empezar con el registro de usuarios

---
