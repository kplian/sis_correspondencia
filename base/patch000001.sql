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
  CONSTRAINT pk_tcorrespondencia__id_adjunto PRIMARY KEY(id_adjunto),
  CONSTRAINT tadjunto_fk FOREIGN KEY (id_correspondencia_origen)
    REFERENCES corres.tcorrespondencia(id_correspondencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
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
  CONSTRAINT pk_tcorrespondencia__id_comision PRIMARY KEY(id_comision),
  CONSTRAINT fk_tcomision__id_correspondencia FOREIGN KEY (id_correspondencia)
    REFERENCES corres.tcorrespondencia(id_correspondencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcomision__id_funcionario FOREIGN KEY (id_funcionario)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcomision__id_usuario FOREIGN KEY (id_usuario_reg)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
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
  CONSTRAINT tcorrespondencia__estado__chk CHECK (((estado)::text = 'borrador_detalle_recibido'::text) OR ((estado)::text = 'pendiente_recibido'::text) OR ((estado)::text = 'recibido'::text) OR ((estado)::text = 'recibido_derivacion'::text) OR ((estado)::text = 'borrador_envio'::text) OR ((estado)::text = 'enviado'::text) OR ((estado)::text = 'borrador_recepcion_externo'::text) OR ((estado)::text = 'pendiente_recepcion_externo'::text) OR ((estado)::text = 'anulado'::text)),
  CONSTRAINT fk_tcorrespondencia__id_clasificacor FOREIGN KEY (id_clasificador)
    REFERENCES segu.tclasificador(id_clasificador)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_codumento FOREIGN KEY (id_documento)
    REFERENCES param.tdocumento(id_documento)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_correspondencia FOREIGN KEY (id_correspondencia_fk)
    REFERENCES corres.tcorrespondencia(id_correspondencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_depto FOREIGN KEY (id_depto)
    REFERENCES param.tdepto(id_depto)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_funcionario FOREIGN KEY (id_funcionario)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_gestion FOREIGN KEY (id_gestion)
    REFERENCES param.tgestion(id_gestion)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_periodo FOREIGN KEY (id_periodo)
    REFERENCES param.tperiodo(id_periodo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_persona FOREIGN KEY (id_persona)
    REFERENCES segu.tpersona(id_persona)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_uo FOREIGN KEY (id_uo)
    REFERENCES orga.tuo(id_uo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia__id_usuario FOREIGN KEY (id_usuario_reg)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
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

CREATE TRIGGER trig_correspondencia_estado
  AFTER INSERT OR UPDATE 
  ON corres.tcorrespondencia FOR EACH ROW 
  EXECUTE PROCEDURE corres.trig_correspondencia();
  
  CREATE TABLE corres.tcorrespondencia_estado (
  id_correspondencia_estado SERIAL,
  id_correspondencia INTEGER,
  estado VARCHAR(50) NOT NULL,
  estado_ant VARCHAR(50),
  observaciones_estado TEXT,
  CONSTRAINT pk_tcorrespondencia__id_correspondencia_estado PRIMARY KEY(id_correspondencia_estado),
  CONSTRAINT fk_tcorrespondencia_estado__id_correspondencia FOREIGN KEY (id_correspondencia)
    REFERENCES corres.tcorrespondencia(id_correspondencia)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT fk_tcorrespondencia_estado__id_usuario_reg FOREIGN KEY (id_usuario_reg)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
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
  CONSTRAINT pk_tcorrespondencia__id_grupo PRIMARY KEY(id_grupo),
  CONSTRAINT tgrupo__id_usuario_fk FOREIGN KEY (id_usuario_reg)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tgrupo__is_usuario_mod_fk FOREIGN KEY (id_usuario_mod)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);

CREATE TABLE corres.tgrupo_funcionario (
  id_grupo_funcionario SERIAL,
  id_grupo INTEGER,     
  id_funcionario INTEGER,
  CONSTRAINT pk_tcorrespondencia__id_grupo_funcionario PRIMARY KEY(id_grupo_funcionario),
  CONSTRAINT tgrupo_funcionario__id_grupo_fk FOREIGN KEY (id_grupo)
    REFERENCES corres.tgrupo(id_grupo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tgrupo_funcionario__id_usuaio_mod_fk FOREIGN KEY (id_usuario_mod)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tgrupo_funcionario__if_funcionario_fk FOREIGN KEY (id_funcionario)
    REFERENCES orga.tfuncionario(id_funcionario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE,
  CONSTRAINT tgrupo_funcionario_id_usuario_reg_fk FOREIGN KEY (id_usuario_reg)
    REFERENCES segu.tusuario(id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
    NOT DEFERRABLE
) INHERITS (pxp.tbase)

WITH (oids = false);


/***********************************F-SCP-AVQ-CORRES-0-28/08/2018*****************************************/