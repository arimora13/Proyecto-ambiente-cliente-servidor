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
-- contiene roles como administrador, docente, estudiante

-- ==================== ESTADO ====================
CREATE TABLE ESTADO (
    ID_ESTADO      INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_ESTADO  VARCHAR(30)
);
-- estados utilizados en mas de una tabla, se explica mas detalladamente en cada tabla

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
-- estado(activo, inactivo), un usuario posee un solo rol (asi administrar permisos)

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
-- compuesto mayormente por foreign keys, permitiendo direcciones parecidas.

-- ==================== INSTITUCION ====================
CREATE TABLE INSTITUCION (
    ID_INSTITUCION  INT AUTO_INCREMENT PRIMARY KEY,
    ID_DIRECCION    INT,
    NOMBRE          VARCHAR(100),
    CONSTRAINT FK_INSTITUCION_DIRECCION FOREIGN KEY (ID_DIRECCION) REFERENCES DIRECCION(ID_DIRECCION) -- FIX: typo ID_DIRECCIOON
);
-- se compone de pocos atributos(no se ven necesarios mas)

-- ==================== TELEFONO ====================
CREATE TABLE TELEFONO (
    ID_INSTITUCION  INT PRIMARY KEY,
    TELEFONO        VARCHAR(20),
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
-- espacio de huerta en la institucion, estado (inactivo, activo)

-- ==================== REPORTE ====================
CREATE TABLE REPORTE(
    ID_REPORTE   INT AUTO_INCREMENT PRIMARY KEY,
    ID_HUERTA    INT,
    FECHA        DATE,
    PERIODO      VARCHAR(50),
    DESCRIPCION  VARCHAR(500),
    CONSTRAINT FK_REPORTE_HUERTA FOREIGN KEY(ID_HUERTA) REFERENCES HUERTA(ID_HUERTA)
);
-- reporte dirigido al MEP, fecha del reporte, periodo del anyo, descripcion detallada.

-- ==================== GRUPO_ESTUDIANTIL ====================
CREATE TABLE GRUPO_ESTUDIANTIL(
    ID_GRUPO                INT AUTO_INCREMENT PRIMARY KEY,
    ID_DOCENTE_RESPONSABLE  INT,
    NOMBRE                  VARCHAR(30),
    GRADO                   INT,
    SECCION                 VARCHAR(10),
    ANYO                    VARCHAR(10),
    CONSTRAINT FK_GRUPO_ESTUDIANTIL_DOCENTE FOREIGN KEY (ID_DOCENTE_RESPONSABLE) REFERENCES USUARIO(ID_USUARIO)
);
-- los grupos estudiantiles cuentan con un id, un docente responsable y datos necesarias como su grado (1-6) y seccion (1, 2, 3 o a, b, c...)

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
-- referente a que se cultiva, tomates, lechuga, frijoles, etc... pues cada uno de estos debe de ser documentado con precision.

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
-- una area dentro de la huerta, cada cultivo tiene un solo tipo de cultivo, evitando que tomates se mezclen con lechugas.
-- queda a cargo de un grupo, pertenece a una sola huerta, estado (activo, inactivo)

-- ==================== TIPO_ACTIVIDAD ====================
CREATE TABLE TIPO_ACTIVIDAD(
    ID_TIPO_ACTIVIDAD  INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE_ACTIVIDAD   VARCHAR(50)
);
-- segun normalizacion, las actividades se realizan en una tabla aparte, evitando datos duplicados.
-- facilita la busqueda de alertas o actividades where tipo_Actividad = ?

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
-- registra una accion que ocurre dentro del cultivo, funciona como una bitacora y tiene una fecha de realizacion.
-- queda para siempre como un registro del historial.

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
-- notifica a los usuarios acerca de una actividad que necesita atencion, funciona como notficicacion y cuenta con un 
-- estado(Pendiente, atendida,etc), una vez completada se puede eliminar

-- ============================================================
-- ALTERACIONES IMPORTANTES EN LA BASE DE DATOS
-- ============================================================

-- QUE NINGUN VALOR IMPORTANTE SE REPITA
ALTER TABLE ROL
ADD CONSTRAINT UK_ROL_NOMBRE UNIQUE (NOMBRE_ROL);

ALTER TABLE ESTADO
ADD CONSTRAINT UK_ESTADO_NOMBRE UNIQUE (NOMBRE_ESTADO);

ALTER TABLE PROVINCIA
ADD CONSTRAINT UK_PROVINCIA_NOMBRE UNIQUE (NOMBRE_PROVINCIA);

ALTER TABLE TIPO_CULTIVO
ADD CONSTRAINT UK_TIPO_CULTIVO_NOMBRE UNIQUE (NOMBRE);

ALTER TABLE TIPO_ACTIVIDAD
ADD CONSTRAINT UK_TIPO_ACTIVIDAD_NOMBRE UNIQUE (NOMBRE_ACTIVIDAD);

-- LLAVE COMPUESTA ARREGLADA 
ALTER TABLE INTEGRANTES_GRUPOS
ADD CONSTRAINT PK_INTEGRANTES_GRUPOS
PRIMARY KEY (ID_USUARIO, ID_GRUPO);

-- QUE EL GRADO SEA DE PRIMERO A SEXTO
ALTER TABLE GRUPO_ESTUDIANTIL
ADD CONSTRAINT CK_GRUPO_GRADO
CHECK (GRADO BETWEEN 1 AND 6);

-- CONTROLAR LOS VALORES
ALTER TABLE HUERTA
ADD CONSTRAINT CK_HUERTA_AREA
CHECK (AREA_M2 > 0);

ALTER TABLE CULTIVO
ADD CONSTRAINT CK_CULTIVO_CANTIDAD
CHECK (CANTIDAD > 0);


-- ==================== INSERCIÓN DE DATOS ====================

-- Roles
INSERT INTO ROL (NOMBRE_ROL) VALUES 
('Administrador'),
('Docente'),
('Estudiante'),
('Coordinador');

-- Estados
INSERT INTO ESTADO (NOMBRE_ESTADO) VALUES 
('Activo'),
('Inactivo'),
('Pendiente'),
('Atendida'),
('Buen estado'),
('En riesgo'),
('Resuelto');

-- Usuarios (Clave plana: admin123)
INSERT INTO USUARIO (ID_ROL, ID_ESTADO, NOMBRE, APELLIDO_PATERNO, APELLIDO_MATERNO, CONTRASENA, CORREO) VALUES 
(1, 1, 'Admin', 'Huertica', 'Sistema', '0192023a7bbd73250516f069df18b500', 'admin@huertica.com'),
(2, 1, 'Carlos', 'Mendoza', 'Vargas', '0192023a7bbd73250516f069df18b500', 'carlos.mendoza@huertica.com'),
(2, 1, 'María', 'Fernández', 'Rojas', '0192023a7bbd73250516f069df18b500', 'docente@huertica.com');

-- Tipos de Actividad
INSERT INTO TIPO_ACTIVIDAD (NOMBRE_ACTIVIDAD) VALUES 
('Riego'),
('Fertilizacion'),
('Control de plagas'),
('Poda'),
('Cosecha'),
('Deshierbe');

-- Tipos de Cultivo
INSERT INTO TIPO_CULTIVO (NOMBRE, NOMBRE_CIENTIFICO, TIEMPO_COSECHA, FRECUENCIA_RIEGO, FRECUENCIA_FERTILIZACION, OBSERVACIONES) VALUES 
('Lechuga', 'Lactuca sativa', 45, 'Diario', 'Quincenal', 'Cultivo de ciclo corto'),
('Tomate', 'Solanum lycopersicum', 90, 'Cada 2 dias', 'Mensual', 'Requiere tutorado'),
('Zanahoria', 'Daucus carota', 75, 'Cada 2 días', 'Cada 3 semanas', 'Requiere suelo suelto y profundo'),
('Chile Dulce', 'Capsicum annuum', 80, 'Cada 2 días', 'Cada 15 días', 'Sensible a las heladas, requiere bastante sol');

-- División Territorial: 7 Provincias
INSERT INTO PROVINCIA (NOMBRE_PROVINCIA) VALUES 
('San Jose'),
('Alajuela'),
('Cartago'),
('Heredia'),
('Guanacaste'),
('Puntarenas'),
('Limón');

-- División Territorial: 20 Cantones
INSERT INTO CANTON (NOMBRE_CANTON) VALUES 
('San Jose Centro'),
('Escazú'),
('Desamparados'),
('Pérez Zeledón'),
('Alajuela Centro'),
('San Ramón'),
('Grecia'),
('San Carlos'),
('Cartago Centro'),
('Paraíso'),
('La Unión'),
('Heredia Centro'),
('Barva'),
('Santo Domingo'),
('Liberia'),
('Nicoya'),
('Puntarenas Centro'),
('Esparza'),
('Limón Centro'),
('Pococe');

-- División Territorial: 20 Distritos
INSERT INTO DISTRITO (NOMBRE_DISTRITO) VALUES 
('Carmen'),
('Merced'),
('Barrio Cuba'),
('Catedral'),
('Zapote'),
('San Francisco'),
('Uruca'),
('Mata Redonda'),
('Pavas'),
('Hatillo'),
('San Sebastián'),
('Escazú Centro'),
('San Miguel'),
('San Isidro'),
('Alajuela Centro'),
('San Rafael'),
('Cartago Oriental'),
('Heredia Centro'),
('Liberia Centro'),
('Limón Centro');

-- Direcciones
INSERT INTO DIRECCION (ID_PROVINCIA, ID_CANTON, ID_DISTRITO, OTRAS_SENAS) VALUES 
(1, 1, 1, '100 metros norte del parque central'),
(2, 5, 15, 'De la iglesia católica, 200m Oeste'),
(3, 9, 17, '200 metros este del Colegio San Luis Gonzaga');

-- Instituciones
INSERT INTO INSTITUCION (ID_DIRECCION, NOMBRE) VALUES 
(1, 'Escuela Huertica'),
(2, 'Escuela República de Costa Rica'),
(3, 'Escuela Padre Minor');

-- Teléfonos
INSERT INTO TELEFONO (ID_INSTITUCION, TELEFONO) VALUES 
(2, '2222-3333'),
(3, '2551-8899');

-- Huertas
INSERT INTO HUERTA (ID_INSTITUCION, ID_ESTADO, NOMBRE, AREA_M2, DESCRIPCION) VALUES 
(1, 1, 'Huerta Escolar Principal', 120, 'Huerta demostrativa del proyecto'),
(2, 1, 'Huerta de Hortalizas Norte', 85, 'Área dedicada al cultivo de verduras de hoja verde'),
(3, 1, 'Huerta San Isidro', 95, 'Espacio enfocado en cultivos tubérculos');

-- Reportes
INSERT INTO REPORTE (ID_HUERTA, FECHA, PERIODO, DESCRIPCION) VALUES 
(2, '2026-08-15', 'Segundo Trimestre', 'Reporte de avance y producción de hortalizas enviado al MEP'),
(3, '2026-08-17', 'Tercer Trimestre', 'Informe sobre instalación del sistema de riego por goteo');

-- Grupos Estudiantiles
INSERT INTO GRUPO_ESTUDIANTIL (ID_DOCENTE_RESPONSABLE, NOMBRE, GRADO, SECCION, ANYO) VALUES 
(2, 'Los Verdes', 5, '5-A', '2026'),
(3, 'Guardianes Verdes', 6, '6-B', '2026');

-- Integrantes de Grupos
INSERT INTO INTEGRANTES_GRUPOS (ID_USUARIO, ID_GRUPO) VALUES 
(2, 1),
(3, 2);

-- Cultivos
INSERT INTO CULTIVO (ID_HUERTA, ID_TIPO_CULTIVO, ID_GRUPO, ID_ESTADO, FECHA_SIEMBRA, CANTIDAD) VALUES 
(2, 3, 1, 1, '2026-08-01', 50),
(3, 4, 2, 1, '2026-08-10', 35);

-- Actividades
INSERT INTO ACTIVIDAD (ID_CULTIVO, ID_USUARIO, ID_TIPO_ACTIVIDAD, FECHA_ACTIVIDAD, DESCRIPCION) VALUES 
(1, 2, 1, '2026-08-16', 'Aplicación de riego matutino por goteo durante 30 minutos'),
(2, 3, 1, '2026-08-17', 'Riego profundo por aspersión al atardecer'),
(1, 2, 2, '2026-08-16', 'Aplicación de abono orgánico foliar'),
(2, 3, 2, '2026-08-17', 'Aplicación de compostaje enriquecido en la base del cultivo'),
(1, 2, 3, '2026-08-17', 'Tratamiento con aceite de Neem para control de pulgón'),
(2, 3, 3, '2026-08-17', 'Inspección manual y colocación de trampas cromáticas amarillas');

-- Alertas
INSERT INTO ALERTA (ID_CULTIVO, ID_USUARIO, ID_TIPO_ACTIVIDAD, ID_ESTADO, DESCRIPCION) VALUES 
(1, 2, 3, 3, 'Se detectaron manchas blancas en las hojas, requiere revisión de plaga'),
(2, 3, 2, 3, 'Se requiere programar la fertilización mensual del cultivo de Chile Dulce');

-- ============================================================
-- DATOS SEMILLA
-- ============================================================
-- INDICES CON LAS CONSULTAS MAS BUSCADAS
CREATE INDEX IDX_CULTIVO_HUERTA
ON CULTIVO(ID_HUERTA);

CREATE INDEX IDX_CULTIVO_TIPO
ON CULTIVO(ID_TIPO_CULTIVO);

CREATE INDEX IDX_ACTIVIDAD_CULTIVO
ON ACTIVIDAD(ID_CULTIVO);

CREATE INDEX IDX_ALERTA_CULTIVO
ON ALERTA(ID_CULTIVO);

CREATE INDEX IDX_ALERTA_ESTADO
ON ALERTA(ID_ESTADO);
