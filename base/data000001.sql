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

