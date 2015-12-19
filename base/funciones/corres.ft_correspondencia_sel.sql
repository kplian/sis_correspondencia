--------------- SQL ---------------

CREATE OR REPLACE FUNCTION corres.ft_correspondencia_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_correspondencia_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tcorrespondencia'
 AUTOR: 		 (rac)
 FECHA:	        13-12-2011 16:13:21
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

	v_nombre_funcion = 'corres.ft_correspondencia_sel';
    v_parametros = pxp.f_get_record(p_tabla);
    
    
    /*********************************    
 	#TRANSACCION:  'CO_CORSIM_SEL'
 	#DESCRIPCION:	Consulta de Correspodencia simplificada
 	#AUTOR:		rac	
 	#FECHA:		29-02-2012 16:13:21
	***********************************/

	if(p_transaccion='CO_CORSIM_SEL')then
     				
    	begin
          
          --Sentencia de la consulta
			v_consulta:='select
						cor.id_correspondencia,
						cor.estado,
						cor.nivel,
						cor.nivel_prioridad,
						cor.numero,
						cor.referencia,
						cor.tipo,
						cor.fecha_reg,						
						funcionario.desc_funcionario1 as desc_funcionario
                        from corres.tcorrespondencia cor
						inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        where cor.estado in (''enviado'') and nivel = 0 and ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			if (pxp.f_existe_parametro(p_tabla,'id_correspondencia_fk')) then
			   v_consulta:= v_consulta || ' and cor.id_correspondencia_fk='|| v_parametros.id_correspondencia_fk;
			end if;
			
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CO_CORSIM_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_CORSIM_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					   from corres.tcorrespondencia cor
						inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                       where cor.estado in (''enviado'') and  nivel = 0 and';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
    

	/*********************************    
 	#TRANSACCION:  'CO_COR_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	ELSEIF(p_transaccion='CO_COR_SEL')then
     				
    	begin
            
        
    		--Sentencia de la consulta
			v_consulta:='select
						cor.id_correspondencia,
						cor.estado,
						cor.estado_reg,
						cor.fecha_documento,
						cor.fecha_fin,
						cor.id_acciones,
						--cor.id_archivo,
						cor.id_correspondencia_fk,
						cor.id_correspondencias_asociadas,
						cor.id_depto,
						cor.id_documento,
						cor.id_funcionario,
						cor.id_gestion,
						cor.id_institucion,
						cor.id_periodo,
						cor.id_persona,
						cor.id_uo,
						cor.mensaje,
						cor.nivel,
						cor.nivel_prioridad,
						cor.numero,
						cor.observaciones_estado,
						cor.referencia,
						cor.respuestas,
						cor.sw_responsable,
						cor.tipo,
						cor.fecha_reg,
						cor.id_usuario_reg,
						cor.fecha_mod,
						cor.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        doc.descripcion as desc_documento	,
                        depto.nombre as desc_depto,
                        funcionario.desc_funcionario1 as desc_funcionario,
                        cor.ruta_archivo,
                        cor.version,
                        uo.codigo ||''-''|| uo.nombre_unidad as desc_uo,
                        clasif.descripcion as desc_clasificador,
                        cor.id_clasificador
                        
                        from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        
                        inner join orga.tuo uo on uo.id_uo= cor.id_uo
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
				        where cor.estado in (''borrador_envio'',''enviado'',''recibido'') and ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			if (pxp.f_existe_parametro(p_tabla,'id_correspondencia_fk')) then
			   v_consulta:= v_consulta || ' and cor.id_correspondencia_fk='|| v_parametros.id_correspondencia_fk;
			end if;
			
		
			
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CO_COR_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_COR_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					    from corres.tcorrespondencia cor
					    inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
					    inner join param.tdocumento doc on doc.id_documento = cor.id_documento
					    inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
					    inner join orga.tuo uo on uo.id_uo= cor.id_uo
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
					    where cor.estado in (''borrador_envio'',''enviado'') and ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
        
     /*********************************    
 	#TRANSACCION:  'CO_CORDET_SEL'
 	#DESCRIPCION:	Consulta de resgistro de correspondecia detalle
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elseif(p_transaccion='CO_CORDET_SEL')then
     				
    	begin
            
        
    		--Sentencia de la consulta
			v_consulta:='select
						cor.id_correspondencia,
						cor.estado,
						cor.estado_reg,
						cor.fecha_documento,
						cor.fecha_fin,
						cor.id_acciones as id_accion,
						--cor.id_archivo,
						cor.id_correspondencia_fk,
						cor.id_correspondencias_asociadas,
						cor.id_depto,
						cor.id_documento,
						cor.id_funcionario,
						cor.id_gestion,
						cor.id_institucion,
						cor.id_periodo,
						cor.id_persona,
						cor.id_uo,
						cor.mensaje,
						cor.nivel,
						cor.nivel_prioridad,
						cor.numero,
						cor.observaciones_estado,
						cor.referencia,
						cor.respuestas,
						cor.sw_responsable,
						cor.tipo,
						cor.fecha_reg,
						cor.id_usuario_reg,
						cor.fecha_mod,
						cor.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        doc.descripcion as desc_documento	,
                        depto.nombre as desc_depto,
                        funcionario.desc_funcionario1 as desc_funcionario,
                        cor.ruta_archivo,
                        cor.version,
                        persona.nombre_completo1 as desc_persona,
                        institucion.nombre as desc_institucion,
                        
                        (CASE WHEN (cor.id_acciones is not null) then

                                  (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
                                      (
                                       SELECT  pxp.list(acor.nombre)   
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( cor.id_acciones))
                                    END )
                                END )AS  acciones,
                        pxp.list(cor.id_acciones::text) as id_acciones

                        from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        left join segu.vpersona persona on persona.id_persona=cor.id_persona
                        left join param.tinstitucion institucion on institucion.id_institucion=cor.id_institucion
                        left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
				        where cor.estado in (''borrador_detalle_recibido'',''pendiente_recibido'',''recibido'',''borrador_derivado'') and ';
			
			--Definicion de la respuesta
			           v_consulta:=v_consulta||v_parametros.filtro;
			
			
			
			           v_consulta:= v_consulta || ' and cor.id_correspondencia_fk='|| v_parametros.id_correspondencia_fk;
		v_consulta:=v_consulta||'      GROUP BY cor.id_correspondencia,
						cor.estado,
						cor.estado_reg,
						cor.fecha_documento,
						cor.fecha_fin,
						cor.id_acciones ,
						
						cor.id_correspondencia_fk,
						cor.id_correspondencias_asociadas,
						cor.id_depto,
						cor.id_documento,
						cor.id_funcionario,
						cor.id_gestion,
						cor.id_institucion,
						cor.id_periodo,
						cor.id_persona,
						cor.id_uo,
						cor.mensaje,
						cor.nivel,
						cor.nivel_prioridad,
						cor.numero,
						cor.observaciones_estado,
						cor.referencia,
						cor.respuestas,
						cor.sw_responsable,
						cor.tipo,
						cor.fecha_reg,
						cor.id_usuario_reg,
						cor.fecha_mod,
						cor.id_usuario_mod,
						usu1.cuenta ,
						usu2.cuenta ,
                        doc.descripcion 	,
                        depto.nombre,
                        funcionario.desc_funcionario1 ,
                        cor.ruta_archivo,
                        cor.version,
                        persona.nombre_completo1 ,
                        institucion.nombre';
			
			           v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CO_CORDET_CONT'
 	#DESCRIPCION:	Conteo de registros de correspondencia detalle
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_CORDET_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					    from corres.tcorrespondencia cor
					    inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
					    inner join param.tdocumento doc on doc.id_documento = cor.id_documento

                        left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        left join segu.vpersona persona on persona.id_persona=cor.id_persona
                        left join param.tinstitucion institucion on institucion.id_institucion=cor.id_institucion

						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
					    where cor.estado in (''borrador_envio'',''enviado'') and ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;   
    /*********************************
 	#TRANSACCION:  'CO_CORREC_SEL'
 	#DESCRIPCION:	Listado de correspondencia recibida (tanto interna como entrante)
 	#AUTOR:		    Mercedes Zambrana
 	#FECHA:		    13-12-2011
	***********************************/
    elsif(p_transaccion='CO_CORREC_SEL')then
     				
    	begin


    		--Sentencia de la consulta
			v_consulta:='select
						cor.id_correspondencia,
						cor.estado,
						cor.estado_reg,
						cor.fecha_documento,
						cor.fecha_fin,
						--cor.id_acciones,
						--cor.id_archivo,
						cor.id_correspondencia_fk,
						cor.id_correspondencias_asociadas,
						cor.id_depto,
						cor.id_documento,
						cor.id_funcionario,
						cor.id_gestion,
						cor.id_institucion,
						cor.id_periodo,
						cor.id_persona,
						cor.id_uo ,
						cor.mensaje,
						cor.nivel,
						cor.nivel_prioridad,
						cor.numero,
						cor.observaciones_estado,
						cor.referencia,
						cor.respuestas,
						cor.sw_responsable,
						cor.tipo,
						cor.fecha_reg,
						cor.id_usuario_reg,
						cor.fecha_mod,
						cor.id_usuario_mod  ,
						usu1.cuenta as usr_reg ,
						usu2.cuenta as usr_mod,
                        doc.descripcion as desc_documento	,
                        cor.origen as desc_funcionario ,
                        (CASE WHEN (cor.id_acciones is not null) then

                                  (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
                                      (
                                       SELECT   pxp.list(acor.nombre) 
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( cor.id_acciones))
                                    END )
                                END )AS  acciones,
                                depto.codigo||''-''||depto.nombre as desc_depto,
                                --docume.codigo as desc_documento,
                                uo.codigo||''-''||uo.nombre_unidad as desc_uo,
                                ges.gestion as desc_gestion ,
                                per.periodo as desc_periodo,
                                persona_envia.nombre_completo1 as desc_persona,
                                ins_envia.nombre as desc_institucion,
                                (select cor1.version from corres.tcorrespondencia cor1 where cor1.id_correspondencia=cor.id_correspondencia_fk) as version,
                                (select cor1.ruta_archivo from corres.tcorrespondencia cor1 where cor1.id_correspondencia=cor.id_correspondencia_fk) as ruta_archivo
						from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
						inner join orga.vfuncionario emp_recepciona on emp_recepciona.id_funcionario=cor.id_funcionario
						inner join param.tdepto depto on depto.id_depto=cor.id_depto
						--inner join param.tdocumento docume on docume.id_documento=cor.id_documento
						inner join orga.tuo uo on uo.id_uo=cor.id_uo
						inner join param.tgestion ges on ges.id_gestion=cor.id_gestion
						inner join param.tperiodo per on per.id_periodo=cor.id_periodo
						left join segu.vpersona persona_envia on persona_envia.id_persona=cor.id_persona
						left join param.tinstitucion ins_envia on ins_envia.id_institucion=cor.id_institucion
						where cor.estado in (''pendiente_recibido'',''recibido'') and ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

    /*********************************
 	#TRANSACCION:  'CO_CORREC_CONT'
 	#DESCRIPCION:	Conteo de registros de correspondencia detalle
 	#AUTOR:			
 	#FECHA:		    07-03-2012 16:13:21
	***********************************/

	elsif(p_transaccion='CO_CORREC_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					    from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
						inner join orga.vfuncionario emp_recepciona on emp_recepciona.id_funcionario=cor.id_funcionario
						inner join param.tdepto depto on depto.id_depto=cor.id_depto
						--inner join param.tdocumento docume on docume.id_documento=cor.id_documento
						inner join orga.tuo uo on uo.id_uo=cor.id_uo
						inner join param.tgestion ges on ges.id_gestion=cor.id_gestion
						inner join param.tperiodo per on per.id_periodo=cor.id_periodo
						left join segu.vpersona persona_envia on persona_envia.id_persona=cor.id_persona
						left join param.tinstitucion ins_envia on ins_envia.id_institucion=cor.id_institucion
					    where cor.estado in (''pendiente_recibido'',''recibido'') and ';
			
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