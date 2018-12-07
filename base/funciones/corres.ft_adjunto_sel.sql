CREATE OR REPLACE FUNCTION corres.ft_adjunto_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_adjunto_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tadjunto'
 AUTOR: 		 (admin)
 FECHA:	        22-04-2016 23:13:29
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

	v_nombre_funcion = 'corres.ft_adjunto_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_ADJ_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		22-04-2016 23:13:29
	***********************************/

	if(p_transaccion='CORRES_ADJ_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
      adj.id_adjunto,
      adj.extension,
      adj.id_correspondencia_origen,
      adj.nombre_archivo,
      adj.estado_reg,
      adj.ruta_archivo,
      adj.id_usuario_ai,
      adj.id_usuario_reg,
      adj.fecha_reg,
      adj.usuario_ai,
      adj.id_usuario_mod,
      adj.fecha_mod,
      usu1.cuenta as usr_reg,
      usu2.cuenta as usr_mod,
      cor.numero   
from corres.tadjunto adj
inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia
  where  '||v_parametros.filtro||'  and adj.estado_reg=''activo''
UNION
select adj.id_adjunto,
      adj.extension,
      adj.id_correspondencia_origen,
      adj.nombre_archivo,
      adj.estado_reg,
      adj.ruta_archivo,
      adj.id_usuario_ai,
      adj.id_usuario_reg,
      adj.fecha_reg,
      adj.usuario_ai,
      adj.id_usuario_mod,
      adj.fecha_mod,
      usu1.cuenta as usr_reg,
      usu2.cuenta as usr_mod,
      cor.numero
from 
corres.tadjunto adj
inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia
where  adj.id_correspondencia_origen in (
                                 select
                                      cor1.id_correspondencia
                                from corres.tcorrespondencia cor
                                inner join corres.tcorrespondencia cor1 on cor1.id_correspondencia= ANY( cor.id_correspondencias_asociadas) 
                               where    '||v_parametros.filtro||')
        and adj.estado_reg=''activo''                        
                               ';
			
			--Definicion de la respuesta
			--v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ADJ_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		22-04-2016 23:13:29
	***********************************/

	elsif(p_transaccion='CORRES_ADJ_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:=' select count(adjuntos.id)::bigint
                          from (select adj.id_adjunto as id
					       from corres.tadjunto adj
                          inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
                          inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia
                            where  '||v_parametros.filtro||'
                            and adj.estado_reg=''activo''     
                          UNION
                          select adj.id_adjunto as id
                          from 
                          corres.tadjunto adj
                          inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
                          inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia
                          where  adj.id_correspondencia_origen in (
                                                          select
                                      cor1.id_correspondencia
                                from corres.tcorrespondencia cor
                                inner join corres.tcorrespondencia cor1 on cor1.id_correspondencia= ANY( cor.id_correspondencias_asociadas) 
                               where    '||v_parametros.filtro||')
                          and adj.estado_reg=''activo''  
                               
                               ) as adjuntos ';
			
			--Definicion de la respuesta		    
			--v_consulta:=v_consulta||v_parametros.filtro;

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