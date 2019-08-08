/***********************************I-SCP-AVQ-CORRES-0-28/08/2018****************************************/
CREATE TABLE corres.taccion (
  id_accion SERIAL,
  nombre VARCHAR(30),
  CONSTRAINT pk_tcorrespondencia__id_accion PRIMARY KEY(id_accion)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tadjunto (
  id_adjunto SERIAL,
  ruta_archivo VARCHAR(255),
  nombre_archivo VARCHAR(255),
  extension VARCHAR(255),
  id_correspondencia_origen INTEGER,
  CONSTRAINT pk_tcorrespondencia__id_adjunto PRIMARY KEY(id_adjunto)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tarchivo (
  id_archivo SERIAL,
  data BYTEA,
  CONSTRAINT pk_tcorrespondencia__id_archivo PRIMARY KEY(id_archivo)
) 
WITH (oids = false);

CREATE TABLE corres.tcomision (
  id_comision SERIAL,
  id_funcionario INTEGER,
  id_correspondencia INTEGER,
  CONSTRAINT pk_tcorrespondencia__id_comision PRIMARY KEY(id_comision)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tcorrespondencia (
  id_correspondencia SERIAL,
  id_correspondencia_fk INTEGER,
  id_funcionario INTEGER,
  id_institucion INTEGER,
  id_persona INTEGER,
  id_uo INTEGER,
  id_depto INTEGER,
  id_documento INTEGER,
  numero VARCHAR NOT NULL,
  referencia VARCHAR(500),
  mensaje TEXT,
  tipo VARCHAR(20),
  version INTEGER DEFAULT 0 NOT NULL,
  estado VARCHAR(30) NOT NULL,
  nivel INTEGER NOT NULL,
  id_gestion INTEGER NOT NULL,
  fecha_documento DATE NOT NULL,
  id_periodo INTEGER NOT NULL,
  id_acciones INTEGER [],
  id_correspondencias_asociadas INTEGER [],
  respuestas VARCHAR(500),
  fecha_fin DATE,
  nivel_prioridad VARCHAR(20) NOT NULL,
  sw_responsable VARCHAR(2) DEFAULT 'no'::character varying NOT NULL,
  observaciones_estado TEXT,
  origen VARCHAR(500),
  id_clasificador INTEGER NOT NULL,
  ruta_archivo VARCHAR(250),
  cite VARCHAR(250),
  id_origen INTEGER,
  sw_archivado VARCHAR(2) DEFAULT 'no'::character varying,
  estado_fisico VARCHAR(255) DEFAULT 'pendiente'::character varying NOT NULL,
  vista VARCHAR(255),
  fecha_ult_derivado TIMESTAMP WITHOUT TIME ZONE,
  nro_paginas INTEGER,
  otros_adjuntos VARCHAR(2000),
  sw_fisico VARCHAR(2) DEFAULT 'si'::character varying NOT NULL,
  fecha_creacion_documento TIMESTAMP WITHOUT TIME ZONE,
  CONSTRAINT pk_tcorrespondencia__id_correspondencia PRIMARY KEY(id_correspondencia),
  CONSTRAINT tcorrespondencia__estado__chk CHECK (((estado)::text = 'borrador_detalle_recibido'::text) OR ((estado)::text = 'pendiente_recibido'::text) OR ((estado)::text = 'recibido'::text) OR ((estado)::text = 'recibido_derivacion'::text) OR ((estado)::text = 'borrador_envio'::text) OR ((estado)::text = 'enviado'::text) OR ((estado)::text = 'borrador_recepcion_externo'::text) OR ((estado)::text = 'pendiente_recepcion_externo'::text) OR ((estado)::text = 'anulado'::text))
  
) INHERITS (pxp.tbase)

WITH (oids = false);

COMMENT ON COLUMN corres.tcorrespondencia.origen
IS 'este campo recibe la descripcion de la persona, la institucion o funcionario  que origina la correspondencia';

COMMENT ON COLUMN corres.tcorrespondencia.nro_paginas
IS 'Este campo sirve para indicar el numero de paginas de un documento recibido';

COMMENT ON COLUMN corres.tcorrespondencia.otros_adjuntos
IS 'Descripcion de adjuntos que no pueden escanear';

COMMENT ON COLUMN corres.tcorrespondencia.sw_fisico
IS 'Indica quien tiene el fisico';

COMMENT ON COLUMN corres.tcorrespondencia.fecha_creacion_documento
IS 'Fecha de Creación del Documento';

  
  CREATE TABLE corres.tcorrespondencia_estado (
  id_correspondencia_estado SERIAL,
  id_correspondencia INTEGER,
  estado VARCHAR(50) NOT NULL,
  estado_ant VARCHAR(50),
  observaciones_estado TEXT,
  CONSTRAINT pk_tcorrespondencia__id_correspondencia_estado PRIMARY KEY(id_correspondencia_estado)  
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tdocumento_fisico (
  id_documento_fisico SERIAL,
  id_correspondencia INTEGER,
  id_correspondencia_padre INTEGER,
  estado VARCHAR(255),
  CONSTRAINT pk_tdocumentofisico__id_documento_fisico PRIMARY KEY(id_documento_fisico)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tgrupo (
  id_grupo SERIAL,
  nombre VARCHAR(250),
  correo VARCHAR(100),
  obs TEXT,  
  CONSTRAINT pk_tcorrespondencia__id_grupo PRIMARY KEY(id_grupo)
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tgrupo_funcionario (
  id_grupo_funcionario SERIAL,
  id_grupo INTEGER,     
  id_funcionario INTEGER,
  CONSTRAINT pk_tcorrespondencia__id_grupo_funcionario PRIMARY KEY(id_grupo_funcionario)
) INHERITS (pxp.tbase)

WITH (oids = false);


/***********************************F-SCP-AVQ-CORRES-0-28/08/2018*****************************************/
/***********************************I-SCP-AVQ-CORRES-0-15/10/2018*****************************************/
CREATE TABLE corres.tasistente_permisos (
  id_asistente_permisos INTEGER DEFAULT nextval('corres.tasistente_permisos_id_asistente_permisos_seq'::text::regclass) NOT NULL,
  permitir_todo VARCHAR DEFAULT 'no'::character varying,
  estado VARCHAR DEFAULT 'activo'::character varying,
  id_asistente INTEGER,
  CONSTRAINT tasistente_permisos_pkey PRIMARY KEY(id_asistente_permisos)
) INHERITS (pxp.tbase)

WITH (oids = false);

COMMENT ON COLUMN corres.tasistente_permisos.permitir_todo
IS 'Permitir ver todo o solamente lo que la persona a registrado.';
/***********************************F-SCP-AVQ-CORRES-0-15/10/2018*****************************************/
/***********************************I-SCP-AVQ-CORRES-0-27/12/2018*****************************************/
ALTER TABLE corres.tcorrespondencia ADD COLUMN estado_corre  VARCHAR(30);
/***********************************F-SCP-AVQ-CORRES-0-27/12/2018*****************************************/
/***********************************I-SCP-AVQ-CORRES-0-31/12/2018*****************************************/
ALTER TABLE corres.tcorrespondencia
  ADD COLUMN tipo_documento VARCHAR(100);

ALTER TABLE corres.tcorrespondencia
  ALTER COLUMN tipo_documento SET DEFAULT 'otros';

ALTER TABLE corres.tcorrespondencia
  ADD COLUMN persona_firma VARCHAR(100);
/***********************************F-SCP-AVQ-CORRES-0-31/12/2018*****************************************/
/***********************************I-SCP-AVQ-CORRES-0-15/01/2019*****************************************/
ALTER TABLE corres.tcorrespondencia
  ADD COLUMN id_alarma Integer;

ALTER TABLE corres.tcorrespondencia
  ADD COLUMN observaciones_archivado TEXT;

/***********************************F-SCP-AVQ-CORRES-0-15/01/2019*****************************************/
  
/***********************************I-SCP-AVQ-CORRES-0-25/04/2019*****************************************/
ALTER TABLE corres.tasistente_permisos
  ADD COLUMN id_funcionarios_permitidos INTEGER [];
  
/***********************************F-SCP-AVQ-CORRES-0-25/04/2019*****************************************/

/***********************************I-SCP-MCGH-CORRES-0-08/08/2019*****************************************/
ALTER TABLE corres.tcorrespondencia ADD persona_remitente VARCHAR(250) null;
COMMENT ON COLUMN corres.tcorrespondencia.persona_remitente
IS 'Campo que registra el nombre de la persona que remite la correspondencia';
ALTER TABLE corres.tcorrespondencia ADD persona_destino VARCHAR(250) null;
COMMENT ON COLUMN corres.tcorrespondencia.persona_destino
IS 'Campo que registra el nombre de la persona destinataria la correspondencia';
ALTER TABLE corres.tcorrespondencia ADD fecha_envio DATE null;
COMMENT ON COLUMN corres.tcorrespondencia.fecha_envio
IS 'Campo que registra la fecha de envio de la correspondencia';
ALTER TABLE corres.tcorrespondencia
  ALTER COLUMN id_clasificador DROP NOT NULL;
/***********************************F-SCP-MCGH-CORRES-0-08/08/2019*****************************************/

