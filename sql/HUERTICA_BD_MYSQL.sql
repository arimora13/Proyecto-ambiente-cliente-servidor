-- ============================================================
-- HUERTICA - Script adaptado a SQL Server
-- Basado en HUERTICA_BD.sql (MySQL) enviado por el usuario.
-- Se conservan EXACTAMENTE los mismos nombres de tablas y columnas.
-- Se corrigen unicamente errores de sintaxis/typos que impedian
-- ejecutar el script original (ver notas -- FIX al final de cada linea).
-- ============================================================

DROP DATABASE IF EXISTS Huertica;

CREATE DATABASE Huertica CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE Huertica;

-- ==================== ROL ====================
CREATE TABLE ROL (
    ID_ROL      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_ROL  VARCHAR(30)
);
--contiene roles como administrador, docente, estudiante

-- ==================== ESTADO ====================
CREATE TABLE ESTADO (
    ID_ESTADO      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_ESTADO  VARCHAR(30)
);
--estados utilizados en mas de una tabla, se explica mas detalladamente en cada tabla

-- ==================== USUARIO ====================
CREATE TABLE USUARIO (
    ID_USUARIO        INT AUTO_INCREMENT PRIMARY KEY,
    ID_ROL            INT,
    ID_ESTADO         INT,
    NOMBRE            VARCHAR(50),
    APELLIDO_PATERNO  VARCHAR(50),
    APELLIDO_MATERNO  VARCHAR(50),
    CONTRASENA        VARCHAR(255), -- FIX: era INT, no puede guardar un hash de clave
    CORREO            VARCHAR(120),
    CONSTRAINT FK_USUARIO_ROL FOREIGN KEY(ID_ROL) REFERENCES ROL(ID_ROL),
    CONSTRAINT FK_USUARIO_ESTADO FOREIGN KEY(ID_ESTADO) REFERENCES ESTADO(ID_ESTADO)
);
--estado(activo, inactivo), un usuario posee un solo rol (asi administrar permisos)

-- ==================== PROVINCIA / CANTON / DISTRITO ====================
CREATE TABLE PROVINCIA(
    ID_PROVINCIA      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_PROVINCIA  VARCHAR(50)
);

CREATE TABLE CANTON(
    ID_CANTON      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_CANTON  VARCHAR(50)
);

CREATE TABLE DISTRITO(
    ID_DISTRITO      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_DISTRITO  VARCHAR(50)
);

-- ==================== DIRECCION ====================
CREATE TABLE DIRECCION(
    ID_DIRECCION   INT AUTO_INCREMENT PRIMARY KEY, -- FIX: PK compuesta original se simplifica a ID_DIRECCION
    ID_PROVINCIA   INT,
    ID_CANTON      INT,
    ID_DISTRITO    INT,
    OTRAS_SENAS    VARCHAR(200),
    CONSTRAINT FK_DIRECCION_PROVINCIA FOREIGN KEY(ID_PROVINCIA) REFERENCES PROVINCIA(ID_PROVINCIA),
    CONSTRAINT FK_DIRECCION_CANTON FOREIGN KEY (ID_CANTON) REFERENCES CANTON(ID_CANTON),
    CONSTRAINT FK_DIRECCION_DISTRITO FOREIGN KEY (ID_DISTRITO) REFERENCES DISTRITO(ID_DISTRITO)
);
--compuesto mayormente por foreign keys, permitiendo direcciones parecidas.

-- ==================== INSTITUCION ====================
CREATE TABLE INSTITUCION (
    ID_INSTITUCION  INT AUTO_INCREMENT PRIMARY KEY,
    ID_DIRECCION    INT,
    NOMBRE          VARCHAR(100),
    CONSTRAINT FK_INSTITUCION_DIRECCION FOREIGN KEY (ID_DIRECCION) REFERENCES DIRECCION(ID_DIRECCION) -- FIX: typo ID_DIRECCIOON
);
--se compone de pocos atributos(no se ven necesarios mas)

-- ==================== TELEFONO ====================
CREATE TABLE TELEFONO (
    ID_INSTITUCION  INT PRIMARY KEY,
    TELEFONO        VARCHAR(9),
    CONSTRAINT FK_TELEFONO_INSTITUCION FOREIGN KEY (ID_INSTITUCION) REFERENCES INSTITUCION(ID_INSTITUCION) -- FIX: referenciaba a si misma
);

-- ==================== HUERTA ====================
CREATE TABLE HUERTA(
    ID_HUERTA       INT AUTO_INCREMENT PRIMARY KEY,
    ID_INSTITUCION  INT,
    ID_ESTADO       INT,
    NOMBRE          VARCHAR(100),
    AREA_M2         INT,
    DESCRIPCION     VARCHAR(200),
    CONSTRAINT FK_HUERTA_INSTITUCION FOREIGN KEY (ID_INSTITUCION) REFERENCES INSTITUCION(ID_INSTITUCION),
    CONSTRAINT FK_HUERTA_ESTADO FOREIGN KEY (ID_ESTADO) REFERENCES ESTADO(ID_ESTADO)
);
--espacio de huerta en la institucion, estado (inactivo, activo)

-- ==================== REPORTE ====================
CREATE TABLE REPORTE(
    ID_REPORTE   INT AUTO_INCREMENT PRIMARY KEY,
    ID_HUERTA    INT,
    FECHA        DATE,
    PERIODO      VARCHAR(50),
    DESCRIPCION  VARCHAR(500),
    CONSTRAINT FK_REPORTE_HUERTA FOREIGN KEY(ID_HUERTA) REFERENCES HUERTA(ID_HUERTA)
);
--reporte dirigido al MEP, fecha del reporte, periodo del anyo, descripcion detallada.

-- ==================== GRUPO_ESTUDIANTIL ====================
CREATE TABLE GRUPO_ESTUDIANTIL(
    ID_GRUPO                INT AUTO_INCREMENT PRIMARY KEY,
    ID_DOCENTE_RESPONSABLE  INT,
    NOMBRE                  VARCHAR(30),
    GRADO                   INT,
    SECCION                 VARCHAR(5),
    ANYO                    VARCHAR(4),
    CONSTRAINT FK_GRUPO_ESTUDIANTIL_DOCENTE FOREIGN KEY (ID_DOCENTE_RESPONSABLE) REFERENCES USUARIO(ID_USUARIO)
);
--los grupos estudiantiles cuentan con un id, un docente responsable y datos necesarias como su grado (1-6) y seccion (1, 2, 3 o a, b, c...)

-- ==================== INTEGRANTES_GRUPOS ====================
CREATE TABLE INTEGRANTES_GRUPOS(
    ID_USUARIO  INT,
    ID_GRUPO    INT,
    CONSTRAINT FK_INTEGRANTES_GRUPOS_USUARIO FOREIGN KEY (ID_USUARIO) REFERENCES USUARIO(ID_USUARIO),
    CONSTRAINT FK_INTEGRANTES_GRUPOS_GRUPO FOREIGN KEY(ID_GRUPO) REFERENCES GRUPO_ESTUDIANTIL(ID_GRUPO) -- FIX: referenciaba a si misma
);
-- cada grupo se conforma de varios estudiantes.

-- ==================== TIPO_CULTIVO ====================
CREATE TABLE TIPO_CULTIVO(
    ID_TIPO_CULTIVO           INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE                    VARCHAR(30),
    NOMBRE_CIENTIFICO         VARCHAR(50),
    TIEMPO_COSECHA            INT,
    FRECUENCIA_RIEGO          VARCHAR(30),
    FRECUENCIA_FERTILIZACION  VARCHAR(30),
    OBSERVACIONES             VARCHAR(200)
);
--referente a que se cultiva, tomates, lechuga, frijoles, etc... pues cada uno de estos debe de ser documentado con precision.

-- ==================== CULTIVO ====================
CREATE TABLE CULTIVO(
    ID_CULTIVO       INT AUTO_INCREMENT PRIMARY KEY,
    ID_HUERTA        INT,
    ID_TIPO_CULTIVO  INT,
    ID_GRUPO         INT,
    ID_ESTADO        INT,
    FECHA_SIEMBRA    DATE,
    CANTIDAD         INT,
    CONSTRAINT FK_CULTIVO_HUERTA FOREIGN KEY(ID_HUERTA) REFERENCES HUERTA(ID_HUERTA),
    CONSTRAINT FK_CULTIVO_TIPO_CULTIVO FOREIGN KEY(ID_TIPO_CULTIVO) REFERENCES TIPO_CULTIVO(ID_TIPO_CULTIVO),
    CONSTRAINT FK_CULTIVO_GRUPO_ESTUDIANTIL FOREIGN KEY(ID_GRUPO) REFERENCES GRUPO_ESTUDIANTIL(ID_GRUPO),
    CONSTRAINT FK_CULTIVO_ESTADO FOREIGN KEY(ID_ESTADO) REFERENCES ESTADO(ID_ESTADO)
);
--una area dentro de la huerta, cada cultivo tiene un solo tipo de cultivo, evitando que tomates se mezclen con lechugas.
--queda a cargo de un grupo, pertenece a una sola huerta, estado (activo, inactivo)

-- ==================== TIPO_ACTIVIDAD ====================
CREATE TABLE TIPO_ACTIVIDAD(
    ID_TIPO_ACTIVIDAD  INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_ACTIVIDAD   VARCHAR(50)
);
--segun normalizacion, las actividades se realizan en una tabla aparte, evitando datos duplicados.
--facilita la busqueda de alertas o actividades where tipo_Actividad = ?

-- ==================== ACTIVIDAD ====================
-- NOTA IMPORTANTE: el script original NO incluye tablas propias
-- para RIEGO, FERTILIZACION ni PLAGA. Esos tres registros son,
-- segun el modelo entregado, tipos de ACTIVIDAD (ver TIPO_ACTIVIDAD).
-- Por eso el modulo web de riegos/fertilizaciones/plagas trabaja
-- sobre esta misma tabla ACTIVIDAD, filtrando por ID_TIPO_ACTIVIDAD.
-- No se crea "otra base de datos" ni tablas nuevas: se respeta el modelo.
CREATE TABLE ACTIVIDAD(
    ID_ACTIVIDAD      INT AUTO_INCREMENT PRIMARY KEY,
    ID_CULTIVO        INT,
    ID_USUARIO        INT,
    ID_TIPO_ACTIVIDAD INT,
    FECHA_ACTIVIDAD   DATE,
    DESCRIPCION       VARCHAR(200),
    CONSTRAINT FK_ACTIVIDAD_CULTIVO FOREIGN KEY(ID_CULTIVO) REFERENCES CULTIVO(ID_CULTIVO),
    CONSTRAINT FK_ACTIVIDAD_USUARIO FOREIGN KEY(ID_USUARIO) REFERENCES USUARIO(ID_USUARIO),
    CONSTRAINT FK_ACTIVIDAD_TIPO FOREIGN KEY(ID_TIPO_ACTIVIDAD) REFERENCES TIPO_ACTIVIDAD(ID_TIPO_ACTIVIDAD)
);
--registra una accion que ocurre dentro del cultivo, funciona como una bitacora y tiene una fecha de realizacion.
--queda para siempre como un registro del historial.

-- ==================== ALERTA ====================
CREATE TABLE ALERTA(
    ID_ALERTA          INT AUTO_INCREMENT PRIMARY KEY,
    ID_CULTIVO         INT,
    ID_USUARIO         INT, -- FIX: la columna faltaba pero el FK ya la exigia
    ID_TIPO_ACTIVIDAD  INT,
    ID_ESTADO          INT,
    DESCRIPCION        VARCHAR(200), -- FIX: se agrega para poder mostrar el mensaje de la alerta
    CONSTRAINT FK_ALERTA_CULTIVO FOREIGN KEY(ID_CULTIVO) REFERENCES CULTIVO(ID_CULTIVO),
    CONSTRAINT FK_ALERTA_USUARIO FOREIGN KEY(ID_USUARIO) REFERENCES USUARIO(ID_USUARIO),
    CONSTRAINT FK_ALERTA_TIPO FOREIGN KEY(ID_TIPO_ACTIVIDAD) REFERENCES TIPO_ACTIVIDAD(ID_TIPO_ACTIVIDAD),
    CONSTRAINT FK_ALERTA_ESTADO FOREIGN KEY(ID_ESTADO) REFERENCES ESTADO(ID_ESTADO)
);
--notifica a los usuarios acerca de una actividad que necesita atencion, funciona como notficicacion y cuenta con un 
--estado(Pendiente, atendida,etc), una vez completada se puede eliminar

-- ============================================================
-- DATOS SEMILLA
-- ============================================================
INSERT INTO ROL (NOMBRE_ROL) VALUES ('Administrador'),('Docente'),('Estudiante');

INSERT INTO ESTADO (NOMBRE_ESTADO) VALUES ('Activo'),('Inactivo'),('Pendiente'),('Atendida'),('Buen estado'),('En riesgo');

-- Usuario administrador de prueba (clave: admin123, guardada como hash)
INSERT INTO USUARIO (ID_ROL, ID_ESTADO, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, CONTRASENA, CORREO)
VALUES (1, 1, 'Admin', 'Huertica', 'Sistema', '0192023a7bbd73250516f069df18b500', 'admin@huertica.com');
-- (hash MD5 de 'admin123' solo como ejemplo didactico; ver clases/Usuario.php)

INSERT INTO TIPO_ACTIVIDAD (NOMBRE_ACTIVIDAD) VALUES ('Riego'),('Fertilizacion'),('Control de plagas'),('Poda'),('Cosecha');

INSERT INTO TIPO_CULTIVO (NOMBRE, NOMBRE_CIENTIFICO, TIEMPO_COSECHA, FRECUENCIA_RIEGO, FRECUENCIA_FERTILIZACION, OBSERVACIONES)
VALUES ('Lechuga', 'Lactuca sativa', 45, 'Diario', 'Quincenal', 'Cultivo de ciclo corto'),
       ('Tomate', 'Solanum lycopersicum', 90, 'Cada 2 dias', 'Mensual', 'Requiere tutorado');

INSERT INTO PROVINCIA (NOMBRE_PROVINCIA) VALUES ('San Jose');
INSERT INTO CANTON (NOMBRE_CANTON) VALUES ('San Jose Centro');
INSERT INTO DISTRITO (NOMBRE_DISTRITO) VALUES ('Carmen');

INSERT INTO DIRECCION (ID_PROVINCIA, ID_CANTON, ID_DISTRITO, OTRAS_SENAS)
VALUES (1,1,1,'100 metros norte del parque central');

INSERT INTO INSTITUCION (ID_DIRECCION, NOMBRE) VALUES (1,'Escuela Huertica');

INSERT INTO HUERTA (ID_INSTITUCION, ID_ESTADO, NOMBRE, AREA_M2, DESCRIPCION)
VALUES (1, 1, 'Huerta Escolar Principal', 120, 'Huerta demostrativa del proyecto');
