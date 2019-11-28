-- ====================================================================================================================
-- BASE DE DATOS SQLITE3, CREAR CON EL NOMBRE DE sistriego_db 
-- SCRIPT SQL DE TABLAS PARA sistriego_db
-- ====================================================================================================================

-- ====================================================================================================================
-- ESPECIES DE CULTIVO
-- ====================================================================================================================
CREATE TABLE especies (
    id_especie       INTEGER CONSTRAINT id_especie_pk PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                             UNIQUE
                             NOT NULL,
    nombre           VARCHAR NOT NULL,
    descripcion      VARCHAR NOT NULL,
    riego_mililitros INTEGER NOT NULL,
    riego_frecuencia INTEGER NOT NULL,
    ta_min           INTEGER NOT NULL,
    ta_max           INTEGER NOT NULL,
    hs_min           INTEGER NOT NULL,
    hs_max           INTEGER NOT NULL,
    ls_min           INTEGER NOT NULL,
    ls_max           INTEGER NOT NULL
);

CREATE UNIQUE INDEX id_especie_pk ON especies (
    id_especie
);

-- ====================================================================================================================
-- ESPECIES CON RIEGOS REALIZADOS
-- ====================================================================================================================
CREATE TABLE especies_riegos (
    id_especie_riego         INTEGER  CONSTRAINT id_especie_riego_pk PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                                      NOT NULL
                                      UNIQUE ON CONFLICT ROLLBACK,
    id_especie               INTEGER  REFERENCES especies (id_especie) ON DELETE RESTRICT
                                                                       ON UPDATE RESTRICT
                                      NOT NULL,
    fecha_hora_inicio        DATETIME NOT NULL,
    fecha_hora_actualizacion DATETIME NOT NULL,
    fecha_hora_fin           DATETIME,
    ta_inicio                INTEGER  NOT NULL,
    ta_fin                   INTEGER,
    hs_inicio                INTEGER  NOT NULL,
    hs_fin                   INTEGER,
    ls_inicio                INTEGER  NOT NULL,
    ls_fin                   INTEGER,
    lluvia_detectada         INTEGER  NOT NULL
                                      DEFAULT (0),
    cantidad_maceta          INTEGER  NOT NULL
                                      DEFAULT (0),
    total_duracion           INTEGER  NOT NULL
                                      DEFAULT (0),
    total_agua_suministrada  INTEGER  DEFAULT (0) 
                                      NOT NULL
);

CREATE UNIQUE INDEX id_especie_riego ON especies_riegos (
    id_especie_riego
);

-- ====================================================================================================================
-- CONFIGURACIONES PARA EL DISPOSITIVO
-- ====================================================================================================================
CREATE TABLE configuraciones (
    id_configuracion      INTEGER CONSTRAINT id_configuracion_pk PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                                  NOT NULL
                                  UNIQUE ON CONFLICT ROLLBACK,
    id_especie            INTEGER REFERENCES especies (id_especie) ON DELETE RESTRICT
                                                                   ON UPDATE RESTRICT
                                                                   MATCH SIMPLE DEFERRABLE INITIALLY DEFERRED
                                  NOT NULL ON CONFLICT ROLLBACK,
    descripcion           VARCHAR NOT NULL,
    maceta_tipo           VARCHAR NOT NULL,
    maceta_alto           INTEGER NOT NULL
                                  DEFAULT (0),
    maceta_largo          INTEGER NOT NULL
                                  DEFAULT (0),
    maceta_ancho          INTEGER NOT NULL
                                  DEFAULT (0),
    maceta_volumen        INTEGER,
    maceta_cantidad       INTEGER NOT NULL,
    gotero_caudal         INTEGER NOT NULL,
    riego_inicio          TIME    NOT NULL
                                  DEFAULT (time('00:00:00') ),
    riego_fin             TIME    DEFAULT (time('23:59:59') ) 
                                  NOT NULL,
    riego_minutos_activo  DECIMAL NOT NULL
                                  DEFAULT (1),
    riego_minutos_espera  INTEGER NOT NULL
                                  DEFAULT (1),
    resumen_activar       INTEGER DEFAULT (1) 
                                  NOT NULL,
    resumen_hora_envio    TIME    NOT NULL
                                  DEFAULT (time('00:00:00') ),
    alerta_activar        INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_riego_inicio   INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_riego_fin      INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_hs_min         INTEGER DEFAULT (1) 
                                  NOT NULL,
    alerta_hs_max         INTEGER DEFAULT (1) 
                                  NOT NULL,
    alerta_ta_min         INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_ta_max         INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_ls_max         INTEGER NOT NULL
                                  DEFAULT (1),
    alerta_lluvia         INTEGER NOT NULL
                                  DEFAULT (1),
    webcam_activar        INTEGER NOT NULL
                                  DEFAULT (1),
    webcam_tamanio_imagen VARCHAR NOT NULL,
    webcam_tamanio_video  VARCHAR NOT NULL,
    webcam_fps_video      INTEGER DEFAULT (14),
    email_smtp_activar    INTEGER NOT NULL
                                  DEFAULT (1),
    email_smtp_servidor   VARCHAR,
    email_smtp_puerto     INTEGER DEFAULT (465),
    email_smtp_ssl        INTEGER,
    email_smtp_usuario    VARCHAR,
    email_smtp_clave      VARCHAR,
    dispositivo_activar   INTEGER NOT NULL
                                  DEFAULT (1),
    configuracion_activar INTEGER NOT NULL
                                  DEFAULT (0) 
);

CREATE UNIQUE INDEX id_configuracion_pk ON configuraciones (
    id_configuracion
);

-- ====================================================================================================================
-- MOTIVOS DE HISTORICOS CON DATOS PRECARGADOS
-- ====================================================================================================================
CREATE TABLE historicos_motivos (
    id_historico_motivo INTEGER CONSTRAINT id_historico_motivo_pk PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                                NOT NULL
                                UNIQUE ON CONFLICT ROLLBACK,
    descripcion         VARCHAR NOT NULL
);

CREATE UNIQUE INDEX id_historico_motivo ON historicos_motivos (
    id_historico_motivo
);

INSERT INTO historicos_motivos (descripcion,id_historico_motivo) VALUES 
('DISPOSITIVO INICIADO',1),
('DISPOSITIVO ACCEDIDO',2),
('DISPOSITIVO FECHA/HORA ACTUALIZADO',3),
('DATOS CONFIGURACIONES ABM',4),
('DATOS ESPECIES ABM',5),
('DATOS USUARIOS ABM',6),
('RIEGO ACTIVADO',7),
('RIEGO FINALIZADO',8),
('CAPTURA DE IMAGEN REALIZADO',9),
('VIDEO EN LÍNEA ACTIVADO',10),
('TEMPERATURA MÁXIMA DETECTADA',11),
('TEMPERATURA MÍNIMA DETECTADA',12),
('HUMEDAD SUELO MÁXIMA DETECTADA',13),
('HUMEDAD SUELO MÍNIMA DETECTADA',14),
('ILUMINACIÓN MÁXIMA DETECTADA',15),
('LLUVIA DETECTADA',16),
('RESUMEN DIARIO',17),
('BORRADO FILTRADO HISTÓRICOS',18),
('BORRADO TOTAL HISTÓRICOS',19),
('DATOS TARIFAS AGUA ABM',20);

-- ====================================================================================================================
-- HISTORICOS DEL DISPOSITIVO (logs)
-- ====================================================================================================================
CREATE TABLE historicos (
    id_historico        INTEGER  CONSTRAINT id_historico_pk PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                                 NOT NULL
                                 UNIQUE ON CONFLICT ROLLBACK,
    id_historico_motivo INTEGER  REFERENCES historicos_motivos (id_historico_motivo) ON DELETE RESTRICT
                                                                                     ON UPDATE RESTRICT
                                 NOT NULL,
    fecha_hora          DATETIME NOT NULL,
    detalle             TEXT     NOT NULL,
    notificado          INTEGER  NOT NULL
                                 DEFAULT (0) 
);

CREATE UNIQUE INDEX id_historico_pk ON historicos (
    id_historico
);

-- ====================================================================================================================
-- TARIFAS DEL AGUA SEGUN RANGO DE FECHAS
-- ====================================================================================================================
CREATE TABLE tarifas_agua (
    id_tarifa_agua INTEGER PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                           UNIQUE ON CONFLICT ROLLBACK,
    fecha_inicio   DATE    NOT NULL,
    fecha_fin      DATE,
    tarifa         DECIMAL NOT NULL
);

CREATE UNIQUE INDEX id_tarifa_agua_pk ON tarifas_agua (
    id_tarifa_agua
);

-- ====================================================================================================================
-- USUARIOS DEL DISPOSITIVO
-- ====================================================================================================================
CREATE TABLE usuarios (
    id_usuario    INTEGER CONSTRAINT id_usuario PRIMARY KEY ASC ON CONFLICT ROLLBACK AUTOINCREMENT
                          NOT NULL
                          UNIQUE ON CONFLICT ROLLBACK,
    nombres       VARCHAR NOT NULL,
    apellidos     VARCHAR NOT NULL,
    usuario       VARCHAR NOT NULL,
    clave         VARCHAR NOT NULL,
    email         VARCHAR NOT NULL,
    administrador INTEGER NOT NULL
                          DEFAULT (0),
    activo        INTEGER NOT NULL
                          DEFAULT (1) 
);

CREATE UNIQUE INDEX id_usuario_pk ON usuarios (
    id_usuario
);

-- ====================================================================================================================
-- VISTA COMBINADA DE CONFIGURACION ACTIVA CON ESPECIE PARA RIEGO
-- ====================================================================================================================
CREATE VIEW vista_configuracion_especie AS
    SELECT c.id_configuracion,
           c.id_especie,
           c.maceta_cantidad,
           e.riego_mililitros,
           c.gotero_caudal,
           e.ta_min,
           e.ta_max,
           e.hs_min,
           e.hs_max,
           e.ls_min,
           e.ls_max,
           c.riego_inicio,
           c.riego_fin,
           c.riego_minutos_activo,
           c.riego_minutos_espera,
           c.resumen_activar,
           c.resumen_hora_envio,
           c.alerta_activar,
           c.alerta_riego_inicio,
           c.alerta_riego_fin,
           c.alerta_hs_min,
           c.alerta_hs_max,
           c.alerta_ta_min,
           c.alerta_ta_max,
           c.alerta_lluvia,
           c.email_smtp_activar,
           c.dispositivo_activar
      FROM configuraciones c
           LEFT JOIN
           especies e ON e.id_especie = c.id_especie
     WHERE c.configuracion_activar = 1;