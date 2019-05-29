CREATE OR REPLACE FUNCTION corres.ft_grupo_funcionario_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_grupo_funcionario_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tgrupo_funcionario'
 AUTOR: 		 (rac)
 FECHA:	        10-01-2012 16:15:05
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'corres.ft_grupo_funcionario_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CO_FUNA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 16:15:05
	***********************************/

	if(p_transaccion='CO_FUNA_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						funa.id_grupo_funcionario,
						funa.id_grupo,
						funa.estado_reg,
						funa.id_funcionario,
						funa.id_usuario_reg,
						funa.fecha_reg,
						funa.fecha_mod,
						funa.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        fun.desc_funcionario1
                        
						from corres.tgrupo_funcionario funa
						inner join segu.tusuario usu1 on usu1.id_usuario = funa.id_usuario_reg
                        inner join orga.vfuncionario fun on fun.id_funcionario = funa.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = funa.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CO_FUNA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 16:15:05
	***********************************/

	elsif(p_transaccion='CO_FUNA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_grupo_funcionario)
					    from corres.tgrupo_funcionario funa
					    inner join segu.tusuario usu1 on usu1.id_usuario = funa.id_usuario_reg
                        inner join orga.vfuncionario fun on fun.id_funcionario = funa.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = funa.id_usuario_mod
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
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;