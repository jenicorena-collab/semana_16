# Registro de cambios · TaskBoard API

## 2026-11-13

### Corregido
- **MT-013:** `GET /api/transacciones` devuelve 422 cuando `estado` no pertenece a `Iniciada`, `Completada` o `Fallida`; anteriormente podía responder 200 con una lista vacía.
- El listado valida el estado y mantiene el comportamiento cuando no se envía el parámetro.

### Agregado
- **MT-014:** se agregan los parámetros opcionales `monto_min` y `monto_max` a `GET /api/transacciones/filtrar`.
- Ambos parámetros aceptan valores numéricos mayores o iguales a cero; cuando se envían juntos, `monto_max` debe ser mayor o igual a `monto_min`.
- Se conservan los filtros previos por estado y fechas y el aislamiento por usuario autenticado.

### Seguridad y compatibilidad
- Los endpoints de transacciones requieren `auth:sanctum`.
- Las consultas se limitan al comercio asociado al usuario del token.
- Se conserva el campo `cliente` de la respuesta.
