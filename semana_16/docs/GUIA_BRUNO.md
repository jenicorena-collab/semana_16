# Bruno — configuración de prueba

1. Crea una colección `Semana 16 - TaskBoard`.
2. Define `host` como `http://127.0.0.1:8000/api`.
3. Ejecuta `POST {{host}}/login` con el correo y contraseña de Ana o Carlos.
4. Copia el valor `token` de la respuesta y configúralo como variable `token`.
5. En las solicitudes protegidas agrega `Authorization: Bearer {{token}}` y `Accept: application/json`.
6. Crea carpetas `4. Filtro`, `5. MT-013` y `6. MT-014` y ejecuta los casos de la tabla del README.

Prueba de estado HTTP en la pestaña Tests de Bruno:

```javascript
 test("La validación devuelve 422", function () {
   expect(res.getStatus()).to.equal(422);
 });
```

Para F-2 y F-3, inspecciona que los registros pertenezcan a la identidad autenticada. Repite con Ana y Carlos; no compartas tokens reales.
