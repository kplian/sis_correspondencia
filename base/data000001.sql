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
select pxp.f_insert_tgui ('Procesos', 'Procesos', 'CORRES.1', 'si', 1, '', 2, '', '', 'CORRES');
select pxp.f_insert_tgui ('Grupos de Correspondencia', 'Grupos de Correspondencia', 'CORRES.2', 'si', 1, 'sis_correspondencia/vista/grupo/Grupo.php', 2, '', 'Grupo', 'CORRES');

select pxp.f_insert_tgui ('Acciones', 'Acciones', 'CORRES.1.1', 'si', 1, 'sis_correspondencia/vista/accion/Accion.php', 3, '', 'Accion', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Emitida', 'Correspondencia Emitida', 'CORRES.1.2', 'si', 1, 'sis_correspondencia/vista/correspondencia/Correspondencia.php?interface=emitida', 3, '', 'Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Detalle de Correspondencia', 'Detalle de Correspondencia', 'CORRES.1.3', 'no', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaDetalle.php', 3, '', 'CorrespondenciaDetalle', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Entrante', 'Correspondencia Entrante', 'CORRES.1.4', 'si', 1, 'sis_correspondencia/vista/correspondencia/Correspondencia.php?interface=externa', 3, '', 'Correspondencia', 'CORRES');
select pxp.f_insert_tgui ('Correspondencia Recibida', 'Correspondencia Recibida', 'CORRES.1.5', 'si', 1, 'sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php', 3, '', 'CorrespondenciaRecibida', 'CORRES');



select pxp.f_insert_testructura_gui ('CORRES', 'SISTEMA');
select pxp.f_insert_testructura_gui ('CORRES.1', 'CORRES');
select pxp.f_insert_testructura_gui ('CORRES.2', 'CORRES');

select pxp.f_insert_testructura_gui ('CORRES.1.1', 'CORRES.1');
select pxp.f_insert_testructura_gui ('CORRES.1.2', 'CORRES.1');
select pxp.f_insert_testructura_gui ('CORRES.1.3', 'CORRES.1');
select pxp.f_insert_testructura_gui ('CORRES.1.4', 'CORRES.1');
select pxp.f_insert_testructura_gui ('CORRES.1.5', 'CORRES.1');
select pxp.f_insert_testructura_gui ('CORRES.1.2', 'CORRES.2');


----------------------------------------------
--  DEF DE FUNCIONES
--------------------------------------------------
select pxp.f_insert_tfuncion ('CORRES.f_arma_arbol', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_arma_arbol_inicia', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_encuentra_raiz', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_get_uo_correspondencia', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_get_uo_correspondencia_funcionario', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_obtener_tipo_acciones', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.f_proc_mul_cmb_empleado', 'Funcion      ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_accion_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_accion_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_correspondencia_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_correspondencia_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_grupo_funcionario_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_grupo_funcionario_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_grupo_ime', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.ft_grupo_sel', 'Funcion para tabla     ', 'CORRES');
select pxp.f_insert_tfuncion ('CORRES.trigfl_correspondencia', 'Funcion para tabla     ', 'CORRES');



---------------------------------
--DEF DE PROCEDIMIETOS
---------------------------------




-------------------------------------
--DEFINICION DE OTROS DATOS
-----------------------------------

COMMENT ON COLUMN corres.tcorrespondencia.origen IS 'este campo recibe la descripcion de la persona, la institucion o funcionario  que origina la correspondencia';

/***********************************F-DAT-FRH-CORRES-0-24/01/2013*****************************************/
