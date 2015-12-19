/*
*	Author: Freddy Rojas FRH
*	Date: 24-01-2013
*	Description: Test data
*/

-- Data for table param.tdepto (LIMIT 0,3)
/*
INSERT INTO param.tdepto (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto, id_subsistema, codigo, nombre, nombre_corto)
VALUES (1, 1, '2011-06-04 00:00:00', '2011-06-04 21:26:26', 'activo', 1, 5, 'DC', 'Departamento de Cont', 'CBTE');

INSERT INTO param.tdepto (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto, id_subsistema, codigo, nombre, nombre_corto)
VALUES (1, 1, '2011-10-19 00:00:00', '2011-10-19 14:14:29', 'activo', 3, 5, 'DPE', 'Departamento de Personal', 'DEP-PER');

INSERT INTO param.tdepto (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto, id_subsistema, codigo, nombre, nombre_corto)
VALUES (1, 1, '2011-10-19 00:00:00', '2012-03-15 15:13:42', 'activo', 2, 5, 'COR', 'Departamento de Correspondencia.', 'DEP-COR');
*/

-- Data for table param.tdocumento  (LIMIT 0,12)
/*
INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, 1, '2011-12-13 00:00:00', '2011-12-13 10:13:29', 'activo', 1, 5, 'IN', 'Informe', 'periodo', 'interna', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 03:18:18', 'activo', 9, 5, 'ME', 'Memoramdum', 'periodo', 'interna', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 03:18:35', 'activo', 10, 5, 'CI', 'Comunicacion Interna', 'periodo', 'interna', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 03:19:38', 'activo', 11, 5, 'IT', 'Informe Tecnico', 'periodo', 'interna', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 03:19:52', 'activo', 12, 5, 'CO', 'Comunicado', 'periodo', 'interna', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 03:23:58', 'activo', 13, 5, 'PLA', 'Planilla', 'gestion', '', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2012-04-18 00:00:00', '2012-04-18 00:19:03', 'activo', 18, 5, 'asdasd', 'sadsadsad', 'periodo', '', 'depto', '');

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2011-12-25 00:00:00', '2011-12-25 07:22:25', 'activo', 15, 5, 'CAR', 'Carta recibida', 'periodo', 'entrante', 'depto', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, NULL, '2011-12-29 00:00:00', '2011-12-29 10:59:45', 'activo', 16, 5, 'RE', 'Recibo', 'periodo', 'entrante', 'uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, 1, '2011-12-25 00:00:00', '2012-04-18 00:20:30', 'activo', 5, 5, 'CA', 'CARTA', 'periodo', 'saliente', 'depto_uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, Null, '2012-04-18 00:00:00', '2012-04-18 00:19:33', 'activo', 19, 5, 'aaa', 'aaa', 'periodo', 'saliente', 'uo', NULL);

INSERT INTO param.tdocumento (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_documento, id_subsistema, codigo, descripcion, periodo_gestion, tipo, tipo_numeracion, formato)
VALUES (1, 1, '2012-04-18 00:00:00', '2012-04-18 01:20:06', 'activo', 20, 5, 'aaaaa', 'aaaaa', 'gestion', 'saliente', 'uo', '');

*/
-- Data for table param.tdepto_uo (LIMIT 0,6)
/*
INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, Null, '2011-12-25 00:06:40', NULL, 'activo', 4, 1, 3);

INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, NULL, '2012-03-15 15:03:28', NULL, 'activo', 6, 3, 5);

INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, 1, '2011-12-13 10:07:19', '2012-03-15 15:03:48', 'activo', 3, 2, 7);

INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, 1, '2011-10-19 09:26:14', '2012-03-15 15:20:47', 'activo', 1, 1, 2);

INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, NULL, '2012-04-14 23:06:11', NULL, 'activo', 7, 2, 5);

INSERT INTO param.tdepto_uo (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_uo, id_depto, id_uo)
VALUES (1, NULL, '2012-04-14 23:06:15', NULL, 'activo', 8, 2, 7);
*/
--
-- Data for table param.tdepto_usuario (OID = 429601) (LIMIT 0,4)
--
/*
INSERT INTO param.tdepto_usuario (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_usuario, id_depto, id_usuario, funcion, cargo)
VALUES (1, NULL, '2012-03-14 09:46:54', NULL, 'activo', 1, 2, 24, NULL, NULL);

INSERT INTO param.tdepto_usuario (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_usuario, id_depto, id_usuario, funcion, cargo)
VALUES (1, NULL, '2012-03-15 14:55:08', NULL, 'activo', 2, 2, 18, NULL, '');

INSERT INTO param.tdepto_usuario (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_usuario, id_depto, id_usuario, funcion, cargo)
VALUES (1, NULL, '2012-03-15 14:55:21', NULL, 'activo', 3, 3, 18, NULL, '');

INSERT INTO param.tdepto_usuario (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_depto_usuario, id_depto, id_usuario, funcion, cargo)
VALUES (1, 1, '2012-03-15 15:20:09', '2012-03-15 15:20:18', 'activo', 4, 1, 21, NULL, 'administrador');
*/

/* Data for table corres.taccion  (LIMIT 0,5) */
/*
INSERT INTO corres.taccion (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_accion, nombre)
VALUES (1, NULL, '2011-12-13 09:35:24', NULL, 'activo', 1, 'aprobar');

INSERT INTO corres.taccion (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_accion, nombre)
VALUES (1, NULL, '2011-12-13 09:35:36', NULL, 'activo', 3, 'revisar');

INSERT INTO corres.taccion (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_accion, nombre)
VALUES (1, NULL, '2011-12-13 09:35:42', NULL, 'activo', 4, 'archivar');

INSERT INTO corres.taccion (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_accion, nombre)
VALUES (1, 2, '2011-12-13 09:35:31', '2011-12-13 09:36:22', 'activo', 2, 'proceder');

INSERT INTO corres.taccion (id_usuario_reg, id_usuario_mod, fecha_reg, fecha_mod, estado_reg, id_accion, nombre)
VALUES (1, NULL, '2011-12-13 09:37:05', NULL, 'activo', 5, 'responder');
*/

