/***********************************I-DAT-FRH-CORRES-0-24/01/2013*****************************************/

/*
*	Author: Freddy Rojas FRH
*	Date: 24/01/2013
*	Description: Build the menu definition and the composition
*/
/*

Para  definir la la metadata, menus, roles, etc

1) sincronize ls funciones y procedimientos del sistema
2)  verifique que la primera linea de los datos sea la insercion del sistema correspondiente
3)  exporte los datos a archivo SQL (desde la interface de sistema en sis_seguridad), 
    verifique que la codificacion  se mantenga en UTF8 para no distorcionar los caracteres especiales
4)  remplaze los sectores correspondientes en este archivo en su totalidad:  (el orden es importante)  
                             menu, 
                             funciones, 
                             procedimietnos

*/

insert into segu.tsubsistema(codigo,nombre,prefijo,nombre_carpeta) values
('CORRES','Sistema de documentos','CORRES','Correspondencia');
	


-------------------------------------
--DEFINICION DE INTERFACES
-----------------------------------

select pxp.f_insert_tgui ('SISTEMA DE DOCUMENTOS', '', 'CORRES', 'si', 1, '', 1, '', '', 'CORRES');

select pxp.f_insert_testructura_gui ('CORRES', 'SISTEMA');

----------------------------------
--COPY LINES TO data.sql FILE  
---------------------------------

select pxp.f_insert_tgui ('SISTEMA DE DOCUMENTOS', '', 'CORRES', 'si', 1, '', 1, '', '', 'CORRES');
select pxp.f_insert_tgui ('Configuración', 'Configuración', 'CORCONF', 'si', 1, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Bandejas', 'Bandejas', 'BANCOR', 'si', 2, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Externa', 'Correspondencia Externa', 'COREXTE', 'si', 3, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Documentos Fisicos', 'Documentos Fisicos', 'DOCFISCA', 'si', 4, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Acciones', 'Acciones', 'ACCCOR', 'si', 1, 'sis_correspondencia/vista/accion/Accion.php', 3, '', 'Accion', 'CORRES');
select pxp.f_insert_tgui ('Grupos de Correspondencia', 'Grupos de Correspondencia', 'GRUPCOR', 'si', 2, 'sis_correspondencia/vista/grupo/Grupo.php', 3, '', 'Grupo', 'CORRES');
select pxp.f_insert_tgui ('Emitida', 'Correspondencia Emitida', 'CEMITIDA', 'si', 2, 'sis_correspondencia/vista/correspondencia/CorrespondenciaEmitida.php', 3, '', 'CorrespondenciaEmitida', 'CORRES');
select pxp.f_insert_tgui ('Recibida', 'Recibida', 'CRECI', 'si', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php', 3, '', 'CorrespondenciaRecibida', 'CORRES');
select pxp.f_insert_tgui ('Recibida Archivada', 'Recibida Archivada', 'COREAR', 'si', 3, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibidaArchivada.php', 3, '', 'CorrespondenciaRecibidaArchivada', 'CORRES');
select pxp.f_insert_tgui ('Recepcionar Externos', 'Recepcionar Externos', 'RECEPEXTE', 'si', 1, 'sis_correspondencia/vista/correspondencia/RecepcionCorrespondenciaExterna.php', 3, '', 'RecepcionCorrespondenciaExterna', 'CORRES');
select pxp.f_insert_tgui ('Derivar Correspondencia Externa', 'Derivar Correspondencia Externa', 'DEVCOREX', 'si', 2, 'sis_correspondencia/vista/correspondencia/DerivacionCorrespondenciaExterna.php', 3, '', 'DerivacionCorrespondenciaExterna', 'CORRES');
select pxp.f_insert_tgui ('Despachar', 'Despachar', 'DESPCH', 'si', 1, 'sis_correspondencia/vista/documento_fisico/DocumentoFisicoDespachar.php', 3, '', 'DocumentoFisicoDespachar', 'CORRES');
select pxp.f_insert_tgui ('Recepcionar', 'Recepcionar', 'DOCFISREC', 'si', 2, 'sis_correspondencia/vista/documento_fisico/DocumentoFisicoRecepcionar.php', 3, '', 'DocumentoFisicoRecepcionar', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Fisica Emitida', 'Correspondencia Fisica Emitida', 'CORFISEM', 'si', 3, 'sis_correspondencia/vista/correspondencia/CorrespondenciaFisicaEmitida.php', 3, '', 'CorrespondenciaFisicaEmitida', 'CORRES');
----------------------------------
--COPY LINES TO dependencies.sql FILE  
---------------------------------

select pxp.f_insert_testructura_gui ('CORRES', 'SISTEMA');
select pxp.f_insert_testructura_gui ('CORCONF', 'CORRES');
select pxp.f_insert_testructura_gui ('BANCOR', 'CORRES');
select pxp.f_insert_testructura_gui ('COREXTE', 'CORRES');
select pxp.f_insert_testructura_gui ('DOCFISCA', 'CORRES');
select pxp.f_insert_testructura_gui ('ACCCOR', 'CORCONF');
select pxp.f_insert_testructura_gui ('GRUPCOR', 'CORCONF');
select pxp.f_insert_testructura_gui ('CEMITIDA', 'BANCOR');
select pxp.f_insert_testructura_gui ('CRECI', 'BANCOR');
select pxp.f_insert_testructura_gui ('COREAR', 'BANCOR');
select pxp.f_insert_testructura_gui ('RECEPEXTE', 'COREXTE');
select pxp.f_insert_testructura_gui ('DEVCOREX', 'COREXTE');
select pxp.f_insert_testructura_gui ('DESPCH', 'DOCFISCA');
select pxp.f_insert_testructura_gui ('DOCFISREC', 'DOCFISCA');
select pxp.f_insert_testructura_gui ('CORFISEM', 'DOCFISCA');





-------------------------------------
--DEFINICION DE OTROS DATOS
-----------------------------------

COMMENT ON COLUMN corres.tcorrespondencia.origen IS 'este campo recibe la descripcion de la persona, la institucion o funcionario  que origina la correspondencia';

/***********************************F-DAT-FRH-CORRES-0-24/01/2013*****************************************/


/***********************************I-DAT-MANU-CORRES-0-06/10/2017*****************************************/ 
INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'corres_clase_reporte_codigo', E'RCodigoQRCORR', E'nombre de la clase utilizada para imprimir el codigo de correspondencia, el codigo de la clase debe acomodarce dentro del archivo');

INSERT INTO pxp.variable_global ("variable", "valor", "descripcion")
VALUES (E'corres_clase_reporte_codigo_v1', E'RCodigoQRCORR_v1', E'nombre de la clase utilizada para imprimir el codigo de correspondencia, el codigo de la clase debe acomodarce dentro del archivo');

/***********************************F-DAT-MANU-CORRES-0-06/10/2017*****************************************/ 


/***********************************I-DAT-JMH-CORRES-0-12/12/2018*****************************************/
select pxp.f_insert_tgui ('Configuración', 'Configuración', 'CORCONF', 'si', 1, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Interna', 'Correspondencia Interna', 'BANCOR', 'si', 3, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Externa', 'Correspondencia Entrante o  Externa  Recibida', 'COREXTE', 'si', 2, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Documentos Fisicos', 'Documentos Fisicos', 'DOCFISCA', 'si', 5, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Acciones', 'Acciones', 'ACCCOR', 'si', 1, 'sis_correspondencia/vista/accion/Accion.php', 3, '', 'Accion', 'CORRES');
select pxp.f_insert_tgui ('Grupos de Correspondencia', 'Grupos de Correspondencia', 'GRUPCOR', 'si', 2, 'sis_correspondencia/vista/grupo/Grupo.php', 3, '', 'Grupo', 'CORRES');
select pxp.f_insert_tgui ('Emitida Interna', 'Correspondencia Emitida', 'CEMITIDA', 'si', 2, 'sis_correspondencia/vista/correspondencia/CorrespondenciaEmitida.php', 3, '', 'CorrespondenciaEmitida', 'CORRES');
select pxp.f_insert_tgui ('Recibida  Interna', 'Correspondencia Recibida tanto Interna como Externa', 'CRECI', 'si', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php', 3, '', 'CorrespondenciaRecibida', 'CORRES');
select pxp.f_insert_tgui ('Recibida Archivada', 'Recibida Archivada', 'COREAR', 'no', 3, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibidaArchivada.php', 3, '', 'CorrespondenciaRecibidaArchivada', 'CORRES');
select pxp.f_insert_tgui ('Recepcionar Externos', 'Recepcionar Externos', 'RECEPEXTE', 'si', 1, 'sis_correspondencia/vista/correspondencia/RecepcionCorrespondenciaExterna.php', 3, '', 'RecepcionCorrespondenciaExterna', 'CORRES');
select pxp.f_insert_tgui ('Derivar Correspondencia Externa', 'Derivar Correspondencia Externa', 'DEVCOREX', 'si', 2, 'sis_correspondencia/vista/correspondencia/DerivacionCorrespondenciaExterna.php', 3, '', 'DerivacionCorrespondenciaExterna', 'CORRES');
select pxp.f_insert_tgui ('Despachar', 'Despachar', 'DESPCH', 'si', 1, 'sis_correspondencia/vista/documento_fisico/DocumentoFisicoDespachar.php', 3, '', 'DocumentoFisicoDespachar', 'CORRES');
select pxp.f_insert_tgui ('Recepcionar', 'Recepcionar', 'DOCFISREC', 'si', 2, 'sis_correspondencia/vista/documento_fisico/DocumentoFisicoRecepcionar.php', 3, '', 'DocumentoFisicoRecepcionar', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Fisica Emitida', 'Correspondencia Fisica Emitida', 'CORFISEM', 'si', 3, 'sis_correspondencia/vista/correspondencia/CorrespondenciaFisicaEmitida.php', 3, '', 'CorrespondenciaFisicaEmitida', 'CORRES');
select pxp.f_insert_tgui ('Funcionarios Agrupados', 'Funcionarios Agrupados', 'GRUPCOR.1', 'no', 0, 'sis_correspondencia/vista/grupo_funcionario/GrupoFuncionario.php', 4, '', 'GrupoFuncionario', 'CORRES');
select pxp.f_insert_tgui ('Funcionarios', 'Funcionarios', 'GRUPCOR.1.1', 'no', 0, 'sis_organigrama/vista/funcionario/Funcionario.php', 5, '', 'funcionario', 'CORRES');
select pxp.f_insert_tgui ('Cuenta Bancaria del Empleado', 'Cuenta Bancaria del Empleado', 'GRUPCOR.1.1.1', 'no', 0, 'sis_organigrama/vista/funcionario_cuenta_bancaria/FuncionarioCuentaBancaria.php', 6, '', 'FuncionarioCuentaBancaria', 'CORRES');
select pxp.f_insert_tgui ('Especialidad del Empleado', 'Especialidad del Empleado', 'GRUPCOR.1.1.2', 'no', 0, 'sis_organigrama/vista/funcionario_especialidad/FuncionarioEspecialidad.php', 6, '', 'FuncionarioEspecialidad', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'GRUPCOR.1.1.3', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'GRUPCOR.1.1.1.1', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 7, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'GRUPCOR.1.1.1.1.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 8, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'GRUPCOR.1.1.1.1.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 9, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'GRUPCOR.1.1.1.1.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 9, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'GRUPCOR.1.1.1.1.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 10, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'GRUPCOR.1.1.1.1.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 10, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Subir Correspondencia', 'Subir Correspondencia', 'CEMITIDA.1', 'no', 0, 'sis_correspondencia/vista/correspondencia/subirCorrespondencia.php', 4, '', 'subirCorrespondencia', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'CEMITIDA.2', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CEMITIDA.3', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CEMITIDA.4', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CEMITIDA.5', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CEMITIDA.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CEMITIDA.2.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'CEMITIDA.2.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'CEMITIDA.2.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CEMITIDA.2.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'CEMITIDA.2.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CEMITIDA.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'CRECI.1', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CRECI.2', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CRECI.3', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CRECI.4', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CRECI.1.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CRECI.1.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'CRECI.1.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'CRECI.1.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CRECI.1.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'CRECI.1.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CRECI.1.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'COREAR.1', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'COREAR.2', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'COREAR.3', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'COREAR.4', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'COREAR.1.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'COREAR.1.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'COREAR.1.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'COREAR.1.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'COREAR.1.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'COREAR.1.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'COREAR.1.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Subir Correspondencia', 'Subir Correspondencia', 'RECEPEXTE.1', 'no', 0, 'sis_correspondencia/vista/correspondencia/subirCorrespondencia.php', 4, '', 'subirCorrespondencia', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'RECEPEXTE.2', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'RECEPEXTE.3', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'RECEPEXTE.4', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'RECEPEXTE.5', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'RECEPEXTE.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'RECEPEXTE.2.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'RECEPEXTE.2.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'RECEPEXTE.2.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'RECEPEXTE.2.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'RECEPEXTE.2.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'RECEPEXTE.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Subir Correspondencia', 'Subir Correspondencia', 'DEVCOREX.1', 'no', 0, 'sis_correspondencia/vista/correspondencia/subirCorrespondencia.php', 4, '', 'subirCorrespondencia', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'DEVCOREX.2', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'DEVCOREX.3', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'DEVCOREX.4', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'DEVCOREX.5', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'DEVCOREX.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'DEVCOREX.2.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'DEVCOREX.2.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'DEVCOREX.2.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'DEVCOREX.2.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'DEVCOREX.2.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'DEVCOREX.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('CorrespondenciaDetalle', 'CorrespondenciaDetalle', 'CORFISEM.1', 'no', 0, 'sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php', 4, '', 'Detalle de Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CORFISEM.2', 'no', 0, 'sis_correspondencia/vista/adjunto/Adjunto.php', 4, '', 'Adjunto', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CORFISEM.3', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 4, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CORFISEM.4', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 4, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CORFISEM.1.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 5, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Instituciones', 'Instituciones', 'CORFISEM.1.2', 'no', 0, 'sis_parametros/vista/institucion/Institucion.php', 5, '', 'Institucion', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'CORFISEM.1.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 6, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'CORFISEM.1.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'CORFISEM.1.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 7, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'CORFISEM.1.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 7, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CORFISEM.1.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 6, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'GRUPCOR.1.1.4', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 6, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'GRUPCOR.1.1.1.1.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 8, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'GRUPCOR.1.1.1.1.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 9, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('Subir foto', 'Subir foto', 'GRUPCOR.1.1.1.1.2.1.1', 'no', 0, 'sis_seguridad/vista/persona/subirFotoPersona.php', 10, '', 'subirFotoPersona', 'CORRES');
select pxp.f_insert_tgui ('Archivo', 'Archivo', 'GRUPCOR.1.1.1.1.2.1.2', 'no', 0, 'sis_parametros/vista/archivo/Archivo.php', 10, '', 'Archivo', 'CORRES');
select pxp.f_insert_tgui ('Interfaces', 'Interfaces', 'GRUPCOR.1.1.1.1.2.1.2.1', 'no', 0, 'sis_parametros/vista/archivo/upload.php', 11, '', 'subirArchivo', 'CORRES');
select pxp.f_insert_tgui ('ArchivoHistorico', 'ArchivoHistorico', 'GRUPCOR.1.1.1.1.2.1.2.2', 'no', 0, 'sis_parametros/vista/archivo/ArchivoHistorico.php', 11, '', 'ArchivoHistorico', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'CEMITIDA.2.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CEMITIDA.2.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'CRECI.1.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CRECI.1.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'COREAR.1.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'COREAR.1.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'RECEPEXTE.2.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'RECEPEXTE.2.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'DEVCOREX.2.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'DEVCOREX.2.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_insert_tgui ('InstitucionPersona', 'InstitucionPersona', 'CORFISEM.1.2.2', 'no', 0, 'sis_parametros/vista/institucion_persona/InstitucionPersona.php', 6, '', 'Persona Institucion', 'CORRES');
select pxp.f_insert_tgui ('Personas', 'Personas', 'CORFISEM.1.2.2.1', 'no', 0, 'sis_seguridad/vista/persona/Persona.php', 7, '', 'persona', 'CORRES');
select pxp.f_delete_tgui ('CS');
select pxp.f_insert_tgui ('Emitida Externa', 'Cartas Emitida a Instituciones Externas', 'EMEXTE', 'si', 3, 'sis_correspondencia/vista/correspondencia/CorrespondenciaSaliente.php', 3, '', 'CorrespondenciaSaliente', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Archivada', 'Correspondencia Archivada', 'CORARC', 'si', 4, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Archivada', 'Correspondencia Archivada', 'CORARCH', 'si', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibidaArchivada.php', 3, '', 'CorrespondenciaRecibidaArchivada', 'CORRES');
select pxp.f_insert_tgui ('Recibida Externa', 'Correspondencia Recibida Externa', 'COREXT', 'si', 4, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php', 3, '', 'CorrespondenciaRecibida', 'CORRES');
select pxp.f_insert_tgui ('Administración', 'Referente a la Administración', 'CORADM', 'si', 5, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Administración Externa', 'Administración de Correspondencia', 'CORADMG', 'si', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaAdministracion.php', 3, '', 'CorrespondenciaAdministracion', 'CORRES');
select pxp.f_insert_tgui ('Administración Interna', 'Administración de Correspondencia Interna', 'ADMCORINT', 'si', 2, 'sis_correspondencia/vista/correspondencia/CorrespondenciaAdministracion.php', 3, '', 'CorrespondenciaAdministracion', 'CORRES');
select pxp.f_insert_tgui ('Reporte', 'reporte correspondencia', 'REPCOR', 'si', 7, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Reporte Correspondencia', 'Reporte Correspondencia', 'RECODT', 'si', 1, 'sis_correspondencia/vista/reportes/FormReporteCorrespondencia.php', 3, '', 'FormReporteCorrespondencia', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_arma_arbol', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_arma_arbol_inicia', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_encuentra_raiz', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_get_uo_correspondencia', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_get_uo_correspondencia_funcionario', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_obtener_tipo_acciones', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.f_proc_mul_cmb_empleado', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_accion_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_accion_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_adjunto_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_adjunto_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_correspondencia_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_correspondencia_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_documento_fisico_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_documento_fisico_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_grupo_funcionario_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_grupo_funcionario_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_grupo_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.ft_grupo_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('corres.trigfl_correspondencia', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tprocedimiento ('CO_ACCO_INS', 'Insercion de registros', 'si', '', '', 'corres.ft_accion_ime');
select pxp.f_insert_tprocedimiento ('CO_ACCO_MOD', 'Modificacion de registros', 'si', '', '', 'corres.ft_accion_ime');
select pxp.f_insert_tprocedimiento ('CO_ACCO_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_accion_ime');
select pxp.f_insert_tprocedimiento ('CO_ACCO_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_accion_sel');
select pxp.f_insert_tprocedimiento ('CO_ACCO_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_accion_sel');
select pxp.f_insert_tprocedimiento ('CORRES_ADJ_INS', 'Insercion de registros', 'si', '', '', 'corres.ft_adjunto_ime');
select pxp.f_insert_tprocedimiento ('CORRES_ADJ_MOD', 'Modificacion de registros', 'si', '', '', 'corres.ft_adjunto_ime');
select pxp.f_insert_tprocedimiento ('CORRES_ADJ_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_adjunto_ime');
select pxp.f_insert_tprocedimiento ('CORRES_ADJ_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_adjunto_sel');
select pxp.f_insert_tprocedimiento ('CORRES_ADJ_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_adjunto_sel');
select pxp.f_insert_tprocedimiento ('CO_COR_INS', 'Insercion de corresppondecia externa e interna', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COR_MOD', 'Modificacion de correspondencia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_ARCHCOR_MOD', 'Actualiza archivo de correspodencia escaneado', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COR_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORDER_UPD', 'Derivacion de correspondecia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORUNDO_UPD', 'Corregir correspondecia si no tiene hijos abiertos', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORDET_INS', 'Insercion de registros como detalle de correspondencia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORFIN_INS', 'Insercion de registros como detalle de correspondencia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORARCH_INS', 'archiva o desarchiva la correspondencia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORESTFIS_INS', 'cambia el estado de la correspondencia fisica', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COREXT_INS', 'inserta el mensajero la correpondencia externa recibida(ENTRANTE)', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COREXTEST_INS', 'camba el estado al finalizar la recepcion de la correspondencia externa', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORSIM_SEL', 'Consulta de Correspodencia simplificada', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORSIM_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_COR_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_COR_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORDET_SEL', 'Consulta de resgistro de correspondecia detalle', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORDET_CONT', 'Conteo de registros de correspondencia detalle', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORREC_SEL', 'Listado de correspondencia recibida (tanto interna como entrante)', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORREC_CONT', 'Conteo de registros de correspondencia detalle', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORDOC_SEL', 'Ver Archivo de correspondencia con id_origen', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORDOC_CONT', 'Conteo de registros de ver Documento', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORHOJ_SEL', 'Ver Archivo de correspondencia con id_origen', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORFIEM_SEL', 'Ver correspondencia que ira  se enviara a otro departamento', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_CORFIEM_CONT', 'Conteo de registros de ver fisicos emitidos', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_COREXTE_SEL', 'Consulta de datos PARA correspondencias externas', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_COREXTE_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFIS_INS', 'Insercion de registros', 'si', '', '', 'corres.ft_documento_fisico_ime');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFIS_MOD', 'Modificacion de registros', 'si', '', '', 'corres.ft_documento_fisico_ime');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFIS_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_documento_fisico_ime');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFISEST_INS', 'cambiar estado', 'si', '', '', 'corres.ft_documento_fisico_ime');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFIS_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_documento_fisico_sel');
select pxp.f_insert_tprocedimiento ('CORRES_DOCFIS_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_documento_fisico_sel');
select pxp.f_insert_tprocedimiento ('CO_FUNA_INS', 'Insercion de registros', 'si', '', '', 'corres.ft_grupo_funcionario_ime');
select pxp.f_insert_tprocedimiento ('CO_FUNA_MOD', 'Modificacion de registros', 'si', '', '', 'corres.ft_grupo_funcionario_ime');
select pxp.f_insert_tprocedimiento ('CO_FUNA_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_grupo_funcionario_ime');
select pxp.f_insert_tprocedimiento ('CO_FUNA_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_grupo_funcionario_sel');
select pxp.f_insert_tprocedimiento ('CO_FUNA_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_grupo_funcionario_sel');
select pxp.f_insert_tprocedimiento ('CO_GRU_INS', 'Insercion de registros', 'si', '', '', 'corres.ft_grupo_ime');
select pxp.f_insert_tprocedimiento ('CO_GRU_MOD', 'Modificacion de registros', 'si', '', '', 'corres.ft_grupo_ime');
select pxp.f_insert_tprocedimiento ('CO_GRU_ELI', 'Eliminacion de registros', 'si', '', '', 'corres.ft_grupo_ime');
select pxp.f_insert_tprocedimiento ('CO_GRU_SEL', 'Consulta de datos', 'si', '', '', 'corres.ft_grupo_sel');
select pxp.f_insert_tprocedimiento ('CO_GRU_CONT', 'Conteo de registros', 'si', '', '', 'corres.ft_grupo_sel');
select pxp.f_insert_tprocedimiento ('SCO_GETQR_MOD', 'Recupera codigo QR segun configuracion de variable global', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('SCO_GETQR_L_MOD', 'Recupera codigo QR segun configuracion de variable global', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COREXT_MOD', 'modifica el mensajero la correpondencia externa recibida(ENTRANTE)', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORDET_MOD', 'Modificación de registros como detalle de correspondencia', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_CORUNDOEXT_UPD', 'Para corregir en correspondencia externa', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_COR_ANU', 'Anular una correspondencia anulada', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_tprocedimiento ('CO_HOJORIG_SEL', 'Listado Hoja Recepción Cabecera.', 'si', '', '', 'corres.ft_correspondencia_sel');
select pxp.f_insert_tprocedimiento ('CO_ALAR_ANU', 'Eliminar la alarma anulada', 'si', '', '', 'corres.ft_correspondencia_ime');
select pxp.f_insert_trol ('Rol de correspondencia para todos los usuarios', 'COR - Interna', 'CORRES');
select pxp.f_insert_trol ('Rol de Correspondencia para usuario recepción externos', 'COR - Externa', 'CORRES');
select pxp.f_insert_trol ('Administracion de Correspondencia', 'COR-ADMIN', 'CORRES');
select pxp.f_insert_trol ('Permiso para Emitida Externa', 'COR-EmitidaExterna', 'CORRES');
/***********************************F-DAT-JMH-CORRES-0-12/12/2018*****************************************/
