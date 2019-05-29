CREATE OR REPLACE FUNCTION "corres"."ft_asistente_permisos_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_asistente_permisos_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tasistente_permisos'
 AUTOR: 		 (admin)
 FECHA:	        04-01-2019 12:01:52
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				04-01-2019 12:01:52								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tasistente_permisos'	
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'corres.ft_asistente_permisos_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_ASIPER_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		04-01-2019 12:01:52
	***********************************/

	if(p_transaccion='CORRES_ASIPER_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						asiper.id_asistente_permisos,
						asiper.estado_reg,
						asiper.id_asistente,
						asiper.id_funcionarios_permitidos,
						asiper.estado,
						asiper.permitir_todo,
						asiper.fecha_reg,
						asiper.usuario_ai,
						asiper.id_usuario_reg,
						asiper.id_usuario_ai,
						asiper.id_usuario_mod,
						asiper.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from corres.tasistente_permisos asiper
						inner join segu.tusuario usu1 on usu1.id_usuario = asiper.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = asiper.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ASIPER_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		04-01-2019 12:01:52
	***********************************/

	elsif(p_transaccion='CORRES_ASIPER_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_asistente_permisos)
					    from corres.tasistente_permisos asiper
					    inner join segu.tusuario usu1 on usu1.id_usuario = asiper.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = asiper.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
	end if;
					
EXCEPTION
					
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "corres"."ft_asistente_permisos_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
