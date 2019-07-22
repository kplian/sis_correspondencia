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
                      
        v_consulta:='
        WITH RECURSIVE corres_asoc(id_correspondencia,id_correspondencia_asociada) AS (
          select cor.id_correspondencia,
                 cor.id_correspondencias_asociadas
          from corres.tcorrespondencia cor
          where '||v_parametros.filtro||'
          UNION
          SELECT cor2.id_correspondencia,cor2.id_correspondencias_asociadas
          FROM corres.tcorrespondencia cor2--,corres_asoc ca
          WHERE cor2.id_correspondencia_fk = cor2.id_correspondencia--ca.id_correspondencia

        )
         select
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
              from corres_asoc ca
              inner join corres.tadjunto adj on adj.id_correspondencia_origen= ca.id_correspondencia --or ca.id_correspondencia_asociada)
              
              inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
              left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
              inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia_origen
              where adj.estado_reg=''activo''
        UNION ALL
           select
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
              from corres_asoc ca
              inner join corres.tadjunto adj on adj.id_correspondencia_origen=ANY(ca.id_correspondencia_asociada)
              inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
              left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
              inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia_origen
              where adj.estado_reg=''activo''
         UNION ALL
          select
                                  1 as id_correspondencia,
                                  ''PDF'' as extension,
                                  cor.id_correspondencia,
                                  cor.numero,
                                  ''activo'' as estado_reg,
                                  cor.ruta_archivo,
                                  cor.id_usuario_ai,
                                  cor.id_usuario_reg,
                                  cor.fecha_reg,
                                  cor.usuario_ai,
                                  cor.id_usuario_mod,
                                  cor.fecha_mod,
                                  usu1.cuenta as usr_reg,
                                  usu2.cuenta as usr_mod,
                                  cor.numero   
            from corres_asoc ca
               inner join corres.tcorrespondencia cor on cor.id_correspondencia= ANY(ca.id_correspondencia_asociada)
               inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
              left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
               
           
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
         v_consulta:=             
          'WITH RECURSIVE corres_asoc(id_correspondencia,id_correspondencia_asociada) AS (
          		select cor.id_correspondencia,
                cor.id_correspondencias_asociadas
                from corres.tcorrespondencia cor
                where '||v_parametros.filtro||'
                
                UNION
                
                SELECT cor2.id_correspondencia,cor2.id_correspondencias_asociadas
                FROM corres.tcorrespondencia cor2--,corres_asoc ca
                WHERE cor2.id_correspondencia_fk = cor2.id_correspondencia--ca.id_correspondencia
        	)
        
        	select count(adjuntos.id)::bigint
            from                                
            (select
            adj.id_adjunto as id
                                                        
            from corres_asoc ca
            inner join corres.tadjunto adj on adj.id_correspondencia_origen= ca.id_correspondencia --or ca.id_correspondencia_asociada)
                                                  
            inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
            left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
            inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia_origen
            UNION ALL
            select
            adj.id_adjunto as id
                                                      
            from corres_asoc ca
            inner join corres.tadjunto adj on adj.id_correspondencia_origen=ANY(ca.id_correspondencia_asociada)
            inner join segu.tusuario usu1 on usu1.id_usuario = adj.id_usuario_reg
            left join segu.tusuario usu2 on usu2.id_usuario = adj.id_usuario_mod
            inner join corres.tcorrespondencia cor on cor.id_correspondencia= adj.id_correspondencia_origen
            UNION ALL
            select
            1 as id
                                                                     
            from corres_asoc ca
            inner join corres.tcorrespondencia cor on cor.id_correspondencia= ANY(ca.id_correspondencia_asociada)
            inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
            left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod)as adjuntos
           
    ';
     
			
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