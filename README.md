# Huertica - Aplicación web (PHP + SQL Server + Bootstrap)

Aplicación para la gestión de huertas escolares, construida exactamente sobre la estructura de archivos y el modelos vistos
en clase.

## 1. Base de datos

| Tabla        | Problema en el original                              | Corrección |
|--------------|-------------------------------------------------------|------------|
| USUARIO      | `CONTRASENA` era `INT` (no cabe un hash de clave)     | Se cambió a `VARCHAR(255)` |
| DIRECCION    | Llave primaria compuesta innecesaria                   | PK simple `ID_DIRECCION` |
| INSTITUCION  | FK apuntaba a `ID_DIRECCIOON` (typo)                   | `ID_DIRECCION` |
| TELEFONO     | FK se referenciaba a sí misma (`TELEFONO.ID_INSTUTUCION`) | FK correcta a `INSTITUCION` |
| INTEGRANTES_GRUPOS | FK de grupo se referenciaba a sí misma           | FK correcta a `GRUPO_ESTUDIANTIL` |
| ALERTA       | Tenía `CONSTRAINT FK_ALERTA_USUARIO` pero la columna `ID_USUARIO` no existía; tampoco tenía `DESCRIPCION` | Se agregaron ambas columnas |

## 2. Usuario de prueba

El script de base de datos crea un usuario administrador:

- Correo: `admin@huertica.com`
- Clave: `admin123`

## 3. Estructura de carpetas

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
