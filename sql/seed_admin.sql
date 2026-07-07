-- ============================================================
-- seed_admin.sql
-- Ya NO es necesario si corres HUERTICA_BD_MYSQL.sql, porque ese
-- script ya inserta el usuario admin (admin@huertica.com / admin123).
-- Este archivo queda solo por si necesitas re-crear o resetear
-- ese usuario puntualmente, sin volver a correr todo el script.
-- ============================================================

USE Huertica;

INSERT INTO USUARIO (ID_ROL, ID_ESTADO, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, CONTRASENA, CORREO)
VALUES (1, 1, 'Admin', 'Huertica', 'Sistema', MD5('admin123'), 'admin@huertica.com');

-- Si el correo ya existe y solo quieres resetear la clave:
-- UPDATE USUARIO SET CONTRASENA = MD5('admin123') WHERE CORREO = 'admin@huertica.com';
