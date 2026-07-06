# Huertica - Aplicación web (PHP + SQL Server + Bootstrap)

Aplicación sencilla en PHP plano (sin frameworks) para la gestión de huertas
escolares, construida exactamente sobre la estructura de archivos y el modelo
de base de datos que se entregó.

## 1. Base de datos

Ejecuta `sql/HUERTICA_BD_SQLSERVER.sql` en SQL Server Management Studio (o
`sqlcmd`). Es tu mismo modelo `HUERTICA_BD.sql`, adaptado a T-SQL, con estas
correcciones puntuales (necesarias para que el script pudiera ejecutarse):

| Tabla        | Problema en el original                              | Corrección |
|--------------|-------------------------------------------------------|------------|
| USUARIO      | `CONTRASENA` era `INT` (no cabe un hash de clave)     | Se cambió a `VARCHAR(255)` |
| DIRECCION    | Llave primaria compuesta innecesaria                   | PK simple `ID_DIRECCION` |
| INSTITUCION  | FK apuntaba a `ID_DIRECCIOON` (typo)                   | `ID_DIRECCION` |
| TELEFONO     | FK se referenciaba a sí misma (`TELEFONO.ID_INSTUTUCION`) | FK correcta a `INSTITUCION` |
| INTEGRANTES_GRUPOS | FK de grupo se referenciaba a sí misma           | FK correcta a `GRUPO_ESTUDIANTIL` |
| ALERTA       | Tenía `CONSTRAINT FK_ALERTA_USUARIO` pero la columna `ID_USUARIO` no existía; tampoco tenía `DESCRIPCION` | Se agregaron ambas columnas |

**No se creó ninguna tabla nueva ni "otra base de datos".** Se usan exactamente
los nombres del modelo entregado: `ROL, ESTADO, USUARIO, CULTIVO, ACTIVIDAD,
TIPO_ACTIVIDAD, ALERTA, REPORTE, HUERTA, GRUPO_ESTUDIANTIL`, etc.

### Nota importante: Riegos, Fertilizaciones y Plagas

El modelo que enviaste **no incluye tablas propias** `RIEGO`, `FERTILIZACION`
ni `PLAGA`. Según ese mismo modelo, esos tres registros son tipos de
**ACTIVIDAD** (tabla `TIPO_ACTIVIDAD`, con valores `'Riego'`,
`'Fertilizacion'` y `'Control de plagas'`, ya cargados como datos semilla).

Por eso los módulos `riegos/`, `fertilizaciones/` y `plagas/` trabajan sobre
la misma tabla `ACTIVIDAD`, filtrando siempre por su tipo correspondiente.
Esto respeta la instrucción de "no inventar otra base de datos" y usa
exactamente los nombres de tabla que ya existían.

## 2. Conexión

Edita `conexion.php` con los datos de tu servidor:

```php
$servidor  = "localhost";
$baseDatos = "Huertica";
$usuarioBD = "sa";
$claveBD   = "TU_PASSWORD";
```

Requiere tener instalada la extensión `sqlsrv` / `pdo_sqlsrv` de PHP
(driver de Microsoft para SQL Server).

## 3. Usuario de prueba

El script de base de datos crea un usuario administrador:

- Correo: `admin@huertica.com`
- Clave: `admin123`

## 4. Estructura de carpetas

```
index.php          -> redirige a login o menu segun la sesion
login.php           -> formulario de acceso (valida contra USUARIO/ROL)
menu.php             -> menu principal + dashboard con contadores
logout.php           -> cierra sesion
conexion.php         -> conexion PDO + funciones validarSesion()/validarRol()
menu_incluido.php    -> barra de navegacion reutilizable en los modulos
css/estilos.css
js/validaciones.js    -> validaciones de formularios en el cliente
clases/               -> Usuario, Cultivo, Riego, Fertilizacion, Plaga, Reporte, Alerta
procesos/             -> guardarUsuario, editarUsuario, eliminarUsuario
usuarios/             -> listar, registrar, editar   (solo Administrador)
cultivos/             -> listar (+ seguimiento de crecimiento), registrar, editar, eliminar
riegos/               -> listar, registrar, editar, eliminar
fertilizaciones/      -> listar, registrar, editar, eliminar
plagas/               -> listar, registrar, editar, eliminar
reportes/             -> listar, registrar, editar, eliminar (Administrador/Docente)
alertas/              -> listar, registrar, editar (atender), eliminar (Administrador/Docente)
sql/HUERTICA_BD_SQLSERVER.sql
```

## 5. Funcionalidades cubiertas

- Login por roles (Administrador, Docente, Estudiante) usando `$_SESSION`.
- Menú que muestra solo las opciones permitidas según `$_SESSION['nombre_rol']`.
- CRUD completo de usuarios y de cultivos.
- Registro de riegos, fertilizaciones y control de plagas (sobre `ACTIVIDAD`).
- Alertas con cambio de estado (Pendiente → Atendida).
- Reportes por huerta.
- Seguimiento del crecimiento: en `cultivos/listar.php`, el botón
  "Seguimiento" muestra el historial de actividades (riegos, fertilizaciones,
  plagas, etc.) de ese cultivo ordenado por fecha.
- Dashboard con contadores (usuarios, cultivos, huertas, alertas pendientes).

Todo se hizo con PHP plano + PDO (driver `sqlsrv`), sesiones, formularios
HTML, Bootstrap 5 (vía CDN) y JavaScript de validación simple — sin
Composer, sin Laravel, sin AJAX/APIs, siguiendo el estilo de clase pedido
(`guardar()`, `editar()`, `eliminar()`, `listar()`).
