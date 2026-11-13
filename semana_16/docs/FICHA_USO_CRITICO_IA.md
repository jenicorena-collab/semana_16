# Ficha de uso crítico de IA — Semana 16

**Estudiante:** ____________________  **Fecha:** ____________________

**Herramienta de IA usada:** ____________________

## Caso A — Resumen de cobros (Laboratorio jueves)

**Pedido:** Crea un endpoint en Laravel que devuelva el resumen de cobros de un comercio: el total cobrado y cuántas transacciones hay por estado.

**Hallazgos (mínimo seis):**

| Hallazgo | Riesgo | Cambio propuesto | Justificación |
|---|---|---|---|
| No comprueba que el comercio exista | Error/500 al acceder a relaciones de null | Buscar con `findOrFail` | Responder 404 si no existe |
| No verifica que el comercio pertenezca al usuario | Exposición de datos ajenos | Filtrar por `user_id` autenticado | Aislamiento multiusuario |
| SQL concatena directamente `$id` | Inyección SQL | Eloquent o bindings | Consulta parametrizada |
| Suma todas las transacciones | Total incorrecto | Sumar solo estado `Completada` | Solo cobros completados cuentan como cobrados |
| No define moneda ni formato del total | Contrato ambiguo | Documentar formato decimal | Respuesta consistente |
| No usa `TransaccionResource` ni contrato estructurado | Respuesta inconsistente | Usar Resource | Mantener contrato de API |
| Ruta no muestra middleware en el fragmento | Riesgo si queda pública | Dentro de `auth:sanctum` | Exigir token |

**Paso más útil:** Seguridad y probar, porque revelan exposición de datos y errores de reglas de negocio.

## Caso B — Filtro de transacciones (jueves)

**Pedido completo:** crear `GET /api/transacciones/filtrar` en Laravel 11 con Sanctum; filtrar por estado y fechas, validar parámetros, usar Eloquent y `TransaccionResource`, colocar ruta protegida antes de `apiResource` e incluir todo el día en `hasta`.

**Resumen:** se implementa filtro autenticado y limitado al usuario mediante `whereHas` sobre comercio; se validan estado y fechas; se devuelve `TransaccionResource::collection`.

**Hallazgos / decisiones:** revisar que la ruta esté dentro de Sanctum y antes de `apiResource`, no usar SQL crudo, aplicar filtro por usuario, validar fechas y usar Resource.

**Pruebas Bruno:** registrar aquí los códigos y resultados realmente observados: ____________________

## Caso C — MT-013 (viernes)

**Tipo:** Correctivo. **¿Usé IA y para qué?:** ____________________

**Cambio:** validar `estado` en `index()` con `nullable` y `Rule::in(['Iniciada', 'Completada', 'Fallida'])`.

**Decisión C-4:** `completada` en minúsculas se rechaza con 422 para mantener los valores de estado definidos y evitar aceptar variantes no acordadas.

**Pruebas reales C-1 a C-4:** ____________________

**Impacto:** cambio de comportamiento para estados inválidos; los estados válidos y las consultas sin estado conservan el comportamiento esperado. Semáforo amarillo porque cambia 200 por 422 para entradas inválidas.

## Caso D — MT-014 (viernes)

**Tipo:** Evolutivo.

**Pedido:** agregar `monto_min` y `monto_max` opcionales, numéricos y >= 0; si ambos vienen, máximo >= mínimo; no modificar nada más.

**Aceptado:** reglas de validación condicional y `where('monto', '>=', ...)` / `where('monto', '<=', ...)`.

**Rechazado:** cambios en fechas, orden, nombres de campos o filtros no solicitados.

**Pruebas reales M-1 a M-6 y regresión:** ____________________

**Impacto:** parámetro opcional y compatible; semáforo verde mientras no cambie el contrato existente.

## Declaración
Declaro que revisé, entendí y probé el código incluido y que puedo explicar cada cambio realizado.

**Firma:** ____________________
