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

  v_filtro            varchar;
  v_id_origen				INTEGER;
  v_id_funcionario_origen integer;
  v_permiso VARCHAR;
  v_deptos VARCHAR;
  v_tipo_correspondencia varchar;
  v_id_usuario_reg INTEGER;
  v_id_persona INTEGER;
  v_cargo      varchar;
  v_id_depto   integer;
  v_permitir_todo varchar;
  v_fecha_consulta  date;
  v_id_funcionario  integer;
   --v_id_funcionario  integer;
  v_id_funcionarios_permitidos   INTEGER[];
  v_id_asistente     INTEGER;
			    
			    
BEGIN

	v_nombre_funcion = 'corres.ft_correspondencia_sel';
    v_parametros = pxp.f_get_record(p_tabla);
    
    SELECT id_funcionario
    INTO v_id_funcionario
    FROM segu.tusuario us
    INNER JOIN orga.tfuncionario fun on fun.id_persona=us.id_persona
    WHERE us.id_usuario=p_id_usuario and fun.estado_reg='activo';
    
    --raise exception '%',p_transaccion;
    
    /*********************************    
 	#TRANSACCION:  'CO_CORSIM_SEL'
 	#DESCRIPCION:	Consulta de Correspodencia simplificada
 	#AUTOR:		rac	
 	#FECHA:		29-02-2012 16:13:21
	***********************************/

	if(p_transaccion='CO_CORSIM_SEL')then
     				
    	begin   
              v_filtro=' and 0 = 0 ';
              
             
          
           IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                             v_filtro=v_filtro||'and cor.id_funcionario IN (select fun.id_funcionario
                                                                          from corres.tasistente_permisos asper
                                                                          inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                                          where asper.id_asistente='||v_id_asistente||')';
                          END IF;	
                      ELSE 
                         v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' )';
                         
                    END IF;           
                       
                END IF;
          
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
						coalesce(funcionario.desc_funcionario1,coalesce(insti.nombre,per.nombre)) as desc_funcionario,
                        cor.id_origen
                        
                        from corres.tcorrespondencia cor
						left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
               		    left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join segu.tpersona per on per.id_persona=cor.id_persona
                     where cor.estado in (''enviado'',''recibido'')  '||v_filtro||' and ';
			
			
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
              v_filtro=' and 0 = 0 ';
      
               
           IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                             v_filtro=v_filtro||'and cor.id_funcionario IN (select fun.id_funcionario
                                                                          from corres.tasistente_permisos asper
                                                                          inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                                          where asper.id_asistente='||v_id_asistente||')';
                          END IF;	
                      ELSE 
                         v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' )';
                         
                    END IF;           
                       
                END IF;
        
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					     from corres.tcorrespondencia cor
					    left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
               		    left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join segu.tpersona per on per.id_persona=cor.id_persona
                   where cor.estado in (''enviado'',''recibido'') '||v_filtro||' and ';
			
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
                 v_filtro='0= 0 ';
              
              	IF pxp.f_existe_parametro(p_tabla,'interface') THEN
                   IF v_parametros.interface = 'externa' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''pendiente_recepcion_externo'',''borrador_recepcion_externo'',''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''externa''  and vista = ''externos'' ';
                            ELSIF  v_parametros.interface = 'derivacion_externa' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''pendiente_recepcion_externo'',''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''externa''  and vista = ''externos'' ';
                            ELSIF v_parametros.interface = 'interna' THEN
                               
                                v_filtro= v_filtro || ' and cor.estado in (''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''interna''';
                            ELSIF v_parametros.interface = 'saliente' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''saliente''';
                         END IF;
                         IF v_parametros.interface = 'recibida' THEN
                            v_filtro= v_filtro || ' and cor.estado in (''pendiente_recibido'',''pendiente_recepcion_externo'',''recibido'') ';
                         END IF;
                        
                        -- IF v_parametros.interface = 'interna' THEN
                          
                         --END IF;
				END IF;
                
                v_filtro= v_filtro || ' and cor.id_correspondencia_fk is null ';
               --  raise exception '%',v_filtro;
             IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                              v_filtro=v_filtro||'and (cor.id_funcionario IN (select fun.id_funcionario
                                                  from corres.tasistente_permisos asper
                                                  inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                    where asper.id_asistente='||v_id_asistente||') or cor.id_usuario_reg = '||p_id_usuario||') ';
                          END IF;	      	

                      ELSE 
                          v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_parametros.id_funcionario_usuario::varchar || ' or cor.id_usuario_reg = '||p_id_usuario||')';
          
                    END IF;           
                       
                END IF;
                
		
    		--Sentencia de la consulta
			v_consulta:='select
                            cor.id_origen,
                            cor.id_correspondencia,
                            cor.estado,
                            cor.estado_reg,
                            cor.fecha_documento,
                            cor.fecha_fin,
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
                            doc.descripcion as desc_documento,
                            depto.nombre as desc_depto,
                            funcionario.desc_funcionario1 as desc_funcionario,
                            cor.ruta_archivo,
                            cor.version,                            
                            uo.codigo ||''-''|| uo.nombre_unidad as desc_uo,
                            clasif.descripcion as desc_clasificador,
                            cor.id_clasificador,
                            doc.ruta_plantilla as desc_ruta_plantilla_documento,
                            orga.f_get_cargo_x_funcionario_str(cor.id_funcionario,cor.fecha_documento,''oficial'') as desc_cargo,
                            cor.sw_archivado,
                            lower(substring(split_part(person.nombre,'' '',1),1,1)||''''||substring(split_part(person.nombre,'' '',2),1,1)||''''||substring(split_part(person.nombre,'' '',3),1,1)||''''||substring(person.ap_paterno,1,1)||''''||substring(person.ap_materno,1,1)) as iniciales,
                            insti.nombre as desc_insti,
                            coalesce(persona.nombre_completo1,'' ''),
                            cor.id_institucion as id_institucion_destino,
                            cor.id_persona as id_persona_destino,
                            cor.otros_adjuntos,
                            coalesce (insti.direccion,'''')||'' ''||coalesce(insti.telefono1,'''')||'' ''||coalesce(insti.telefono2,'''')||'' ''||coalesce(insti.celular1,'''')||'' ''||coalesce(insti.celular2,''''),
                            initcap(person.nombre)||'' ''||initcap(person.ap_paterno)||'' ''||initcap(person.ap_materno) as desc_funcionario_de_plantilla,
                              (SELECT count(adjun.id_adjunto) FROM corres.tadjunto adjun WHERE adjun.id_correspondencia_origen=cor.id_origen) as adjunto,
                              cor.fecha_creacion_documento,
                                 
                        (CASE WHEN (cor.id_acciones is not null) then

                                  (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
                                      (
                                       SELECT  pxp.list(acor.nombre)   
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( cor.id_acciones))
                                    END )
                                END )AS  acciones,
                        array_to_string(cor.id_acciones,'','') as id_acciones,
                         pxp.f_fecha_literal(cor.fecha_documento) as fecha_documento_literal,
                         cor.fecha_ult_derivado,
                         INITCAP(coalesce(persona.nombre_completo1,'' ''))::text as nombre_persona_plantilla,
                         observaciones_archivado 
                       	from corres.tcorrespondencia cor						
                        inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        inner join orga.tfuncionario fun on fun.id_funcionario=cor.id_funcionario
                        inner join segu.vpersona person on person.id_persona=fun.id_persona
				       
                        inner join orga.tuo uo on uo.id_uo= cor.id_uo
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                        left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                         left join segu.vpersona persona on persona.id_persona=cor.id_persona                         
				        where 
                         '||v_filtro||' 
                        and 
                        ';

   
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			if (pxp.f_existe_parametro(p_tabla,'id_correspondencia_fk')) then
			   v_consulta:= v_consulta || ' and cor.id_correspondencia_fk='|| v_parametros.id_correspondencia_fk;
			end if;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
           --raise exception '%',v_consulta;
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
             v_filtro='0= 0 ';
              	IF pxp.f_existe_parametro(p_tabla,'interface') THEN
                   IF v_parametros.interface = 'externa' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''pendiente_recepcion_externo'',''borrador_recepcion_externo'',''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''externa''  and vista = ''externos'' ';
                            ELSIF  v_parametros.interface = 'derivacion_externa' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''pendiente_recepcion_externo'',''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''externa''  and vista = ''externos'' ';
                            ELSIF v_parametros.interface = 'interna' THEN
                               
                                v_filtro= v_filtro || ' and cor.estado in (''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''interna''';
                            ELSIF v_parametros.interface = 'saliente' THEN
                                v_filtro= v_filtro || ' and cor.estado in (''borrador_envio'',''enviado'',''recibido'') and cor.tipo = ''saliente''';
                         END IF;
                         IF v_parametros.interface = 'recibida' THEN
                            v_filtro= v_filtro || ' and cor.estado in (''pendiente_recibido'',''pendiente_recepcion_externo'',''recibido'') ';
                         END IF;
                        
                      
				END IF;
                v_filtro= v_filtro || ' and cor.id_correspondencia_fk is null ';
               IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                             v_filtro=v_filtro||'and (cor.id_funcionario IN (select fun.id_funcionario
                                                                          from corres.tasistente_permisos asper
                                                                          inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                                          where asper.id_asistente='||v_id_asistente||') or cor.id_usuario_reg = '||p_id_usuario||') ';
                          END IF;	
                      ELSE 
                           v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_parametros.id_funcionario_usuario::varchar || ' or cor.id_usuario_reg = '||p_id_usuario||')';
          
                    END IF;           
                       
                END IF;
                 
                 
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					     from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        inner join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        inner join orga.tfuncionario fun on fun.id_funcionario=cor.id_funcionario
                        inner join segu.vpersona person on person.id_persona=fun.id_persona
				       
                        inner join orga.tuo uo on uo.id_uo= cor.id_uo
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                        left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                         left join segu.vpersona persona on persona.id_persona=cor.id_persona  where  '||v_filtro||' and ';
                        
                  
				      
            if (pxp.f_existe_parametro(p_tabla,'id_correspondencia_fk')) then
			   v_consulta:= v_consulta || ' and cor.id_correspondencia_fk='|| v_parametros.id_correspondencia_fk;
			end if;
			           
			
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
						cor.id_origen,
						cor.id_correspondencia,
						cor.estado,
						cor.estado_reg,
						cor.fecha_documento,
						cor.fecha_fin,
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
                        insti.nombre as desc_institucion,
                        
                        (CASE WHEN (cor.id_acciones is not null) then

                                  (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
                                      (
                                       SELECT  pxp.list(acor.nombre)   
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( cor.id_acciones))
                                    END )
                                END )AS  acciones,
                        array_to_string(cor.id_acciones,'','') as id_acciones,
                        orga.f_get_cargo_x_funcionario_str(cor.id_funcionario,cor.fecha_documento,''oficial'') as desc_cargo,
                        pxp.f_fecha_literal(cor.fecha_documento) as fecha_documento_literal,
                        initcap(person.nombre)||'' ''||initcap(person.ap_paterno)||'' ''||initcap(person.ap_materno) as desc_funcionario_plantilla,
                        cor.estado_corre
                        from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        left join segu.vpersona persona on persona.id_persona=cor.id_persona
                        left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                        left join orga.tfuncionario fun on fun.id_funcionario=cor.id_funcionario
                        left join segu.vpersona person on person.id_persona=fun.id_persona
				        where cor.estado in (''borrador_detalle_recibido'',''pendiente_recibido'',''recibido'',''borrador_derivado'',''recibido_derivacion'',''enviado'') and ';
			
			--Definicion de la respuesta
			           v_consulta:=v_consulta||v_parametros.filtro;
			
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
                        insti.nombre,
                        person.nombre,
                        person.ap_paterno,
                        person.ap_materno
                      
                        
                        ';
			
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
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        left join orga.vfuncionario funcionario on funcionario.id_funcionario=cor.id_funcionario
                        left join segu.vpersona persona on persona.id_persona=cor.id_persona
                        left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
				        where cor.estado in (''borrador_detalle_recibido'',''pendiente_recibido'',''recibido'',''borrador_derivado'',''recibido_derivacion'') and ';
			
			
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
         select dep.cargo,dep.id_depto
            into v_cargo,v_id_depto
         from orga.tfuncionario fun
         inner join segu.tusuario us on us.id_persona=fun.id_persona
         inner join param.tdepto_usuario dep on dep.id_usuario =us.id_usuario
         where id_funcionario=v_parametros.id_funcionario_usuario; 
         
         
         
         IF  v_parametros.tipo = 'externa' THEN
			    v_filtro=  '  cor.tipo in (''externa'') ';
			 ELSIF v_parametros.tipo = 'interna' THEN
				v_filtro=  '  cor.tipo in (''interna'') ';
			 ELSE
            
                v_filtro='   cor.tipo in (''saliente'')';
             END IF;
             
          IF (v_parametros.interface = 'recibida_archivada')THEN
              v_filtro= v_filtro||' and   cor.estado in (''recibido'',''enviado'')  ';
               v_filtro= v_filtro || ' and  0=0 ';
          ELSE
              v_filtro= v_filtro||' and   cor.estado in (''pendiente_recibido'',''recibido'',''enviado'')  ';
               v_filtro= v_filtro || ' and cor.id_correspondencia_fk is not null ';
       
          END IF;  
          
          -- raise exception '%',v_parametros.interface; 
           
       -- v_filtro='';
        -- end if;
            -- raise exception '%','backt'||v_filtro;
    
        
           IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                             v_filtro=v_filtro||'and cor.id_funcionario IN (select fun.id_funcionario
                                                                          from corres.tasistente_permisos asper
                                                                          inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                                          where asper.id_asistente='||v_id_asistente||')';
                          END IF;	
                      ELSE 
                         
                      	IF v_parametros.tipo = 'saliente' THEN
                        	v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' or cor.id_usuario_reg = '|| p_id_usuario ||'  )';
                        ELSE
                         v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' )';
                         end if;
                         
                    END IF;           
                       
                END IF;
        
 
  		--Sentencia de la consulta
			v_consulta:='select
						cor.id_origen,
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
						cor.id_usuario_mod  ,
						usu1.cuenta as usr_reg ,
						usu2.cuenta as usr_mod,
                        doc.descripcion as desc_documento	,
                        cor.origen,
                        emp_recepciona1.desc_funcionario1 as desc_funcionario,
                        emp_recepciona.desc_funcionario1 as desc_funcionario_origen,
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
                                uop.codigo||''-''||uop.nombre_unidad as desc_uo,
                                ges.gestion as desc_gestion ,
                                per.periodo as desc_periodo,
                                coalesce(persona.nombre_completo1,'''') as desc_persona,
                                 coalesce (insti.nombre,'''') as desc_institucion,
                                (select cor1.version from corres.tcorrespondencia cor1 where cor1.id_correspondencia=cor.id_correspondencia_fk) as version,
                                (select cor1.ruta_archivo from corres.tcorrespondencia cor1 where cor1.id_correspondencia=cor.id_correspondencia_fk) as ruta_archivo,
                                cor.sw_archivado,
                                (SELECT count(adjun.id_adjunto) FROM corres.tadjunto adjun WHERE adjun.id_correspondencia_origen=cor.id_origen) as adjunto,
                                array_to_string(cor.id_acciones,'','') as id_acciones,
                                 cor.fecha_creacion_documento,
                                 cor.fecha_ult_derivado,
                                 cor.observaciones_archivado,
                                 coror.cite,
                                 coror.otros_adjuntos,
                                 coror.nro_paginas,
                                 coalesce(
                        (CASE WHEN (coror.id_correspondencias_asociadas is not null) then

                                      (
                                       SELECT   pxp.list(corr.numero) 
                                       FROM corres.tcorrespondencia corr
                                       WHERE corr.id_correspondencia = ANY ( coror.id_correspondencias_asociadas))
                                   END ),'' '')AS  correspondencias_asociadas
                                
                                
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
                        inner join corres.tcorrespondencia coror on coror.id_correspondencia=cor.id_origen
                       	left join segu.vpersona persona on persona.id_persona=coror.id_persona
						left join param.tinstitucion insti on insti.id_institucion=coror.id_institucion
                        left join corres.tcorrespondencia corp on corp.id_correspondencia=cor.id_correspondencia_fk 
                       	left join orga.vfuncionario emp_recepciona1 on emp_recepciona1.id_funcionario=corp.id_funcionario
                        
                        left join orga.tuo uop on uop.id_uo=corp.id_uo
						where  '||v_filtro||' and   
                       
                         ';
			--cor.estado in (''pendiente_recibido'',''recibido'',''recibido_derivacion'',''enviado'') and 
            --            cor.tipo in (''saliente'') 
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by  ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            --raise exception '%','backtone    '||v_consulta;
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
          
   -- raise exception '%','dfgasdf';
        
          select dep.cargo,dep.id_depto
            into v_cargo,v_id_depto
         from orga.tfuncionario fun
         inner join segu.tusuario us on us.id_persona=fun.id_persona
         inner join param.tdepto_usuario dep on dep.id_usuario =us.id_usuario
         where id_funcionario=v_parametros.id_funcionario_usuario; 
         
         
         IF  v_parametros.tipo = 'externa' THEN
			    v_filtro=  '  cor.tipo in (''externa'') ';
			 ELSIF v_parametros.tipo = 'interna' THEN
				v_filtro=  '  cor.tipo in (''interna'') ';
			 ELSE
            
                v_filtro='   cor.tipo in (''saliente'')';
             END IF;
             
          IF (v_parametros.interface = 'recibida_archivada')THEN
              v_filtro= v_filtro||' and   cor.estado in (''recibido'',''enviado'')  ';
               v_filtro= v_filtro || ' and  0=0 ';
          ELSE
              v_filtro= v_filtro||' and   cor.estado in (''pendiente_recibido'',''recibido'',''enviado'')  ';
               v_filtro= v_filtro || ' and cor.id_correspondencia_fk is not null ';
       
          END IF; 
        
        
     
           IF p_administrador = 1 THEN
                  v_filtro =v_filtro|| ' and 0=0';
                ELSE
                     
                     SELECT permitir_todo,asper.id_funcionarios_permitidos,asper.id_asistente
                            INTO v_permitir_todo,v_id_funcionarios_permitidos,v_id_asistente
                     FROM corres.tasistente_permisos asper
                                  inner join param.tasistente asis on asis.id_asistente = asper.id_asistente
                                  inner join orga.tfuncionario func on func.id_funcionario=asis.id_funcionario
                                  inner join segu.tusuario usua on usua.id_persona=func.id_persona
                                  where usua.id_usuario=p_id_usuario;
                                
                        IF (v_permitir_todo ='si') THEN
                          
                           
                           v_fecha_consulta=now()::date;
                           IF (v_id_funcionarios_permitidos is null) THEN
                            
                             v_filtro=v_filtro||' and cor.id_funcionario IN (select * 
                                                FROM orga.f_get_funcionarios_x_usuario_asistente('''|| v_fecha_consulta||''','||p_id_usuario||') AS (id_funcionario INTEGER))';
        				   ELSE
                             v_filtro=v_filtro||'and cor.id_funcionario IN (select fun.id_funcionario
                                                                          from corres.tasistente_permisos asper
                                                                          inner join orga.tfuncionario fun on fun.id_funcionario= ANY (asper.id_funcionarios_permitidos)
                                                                          where asper.id_asistente='||v_id_asistente||')';
                          END IF;	
                      ELSE 
                         
                      	IF v_parametros.tipo = 'saliente' THEN
                        	v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' or cor.id_usuario_reg = '|| p_id_usuario ||'  )';
                        ELSE
                         v_filtro = v_filtro||' and (cor.id_funcionario = ' ||v_id_funcionario || ' )';
                         end if;
                         
                    END IF;           
                       
                END IF;
        
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(cor.id_correspondencia)
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
						left join segu.vpersona persona on persona.id_persona=cor.id_persona
						left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join corres.tcorrespondencia corp on corp.id_correspondencia=cor.id_correspondencia_fk 
						left join orga.vfuncionario emp_recepciona1 on emp_recepciona1.id_funcionario=corp.id_funcionario
                        left join orga.tuo uop on uop.id_uo=corp.id_uo
						where  '||v_filtro||' and ';
			
			--Definicion de la respuesta		
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;


  /*********************************
#TRANSACCION:  'CO_CORDOC_SEL'
#DESCRIPCION:	Ver Archivo de correspondencia con id_origen
#AUTOR:		    Favio Figueroa
#FECHA:		    11-03-2016
***********************************/
  elsif(p_transaccion='CO_CORDOC_SEL')then

    begin


      --Sentencia de la consulta
      v_consulta:='  select
      							cor.ruta_archivo
      							 from corres.tcorrespondencia cor
     								 where cor.id_correspondencia = ';

			v_consulta:=v_consulta||v_parametros.id_origen;

      --Devuelve la respuesta
      return v_consulta;

    end;

  /*********************************
 #TRANSACCION:  'CO_CORDOC_CONT'
 #DESCRIPCION:	Conteo de registros de ver Documento
 #AUTOR:
 #FECHA:		    11-03-2016 16:13:21
***********************************/

  elsif(p_transaccion='CO_CORDOC_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
      v_consulta:='select count(*) from corres.tcorrespondencia cor
     								 where cor.id_correspondencia = ';

      --Definicion de la respuesta
      v_consulta:=v_consulta||v_parametros.id_origen;

      --Devuelve la respuesta
      return v_consulta;

    end;


  /*********************************
#TRANSACCION:  'CO_CORHOJ_SEL'
#DESCRIPCION:	Ver Archivo de correspondencia con id_origen
#AUTOR:		    Favio Figueroa
#FECHA:		    21-04-2016
***********************************/
  elsif(p_transaccion='CO_CORHOJ_SEL')then

    begin


      select id_origen, tipo, id_usuario_reg
      into v_id_origen, v_tipo_correspondencia, v_id_usuario_reg
      from corres.tcorrespondencia
      where id_correspondencia = v_parametros.id_correspondencia;
      
	  SELECT id_persona INTO v_id_persona
      FROM segu.tusuario
      WHERE id_usuario=v_id_usuario_reg;
      
		IF (v_tipo_correspondencia='interna')THEN
			select id_funcionario
			into v_id_funcionario_origen
				from corres.tcorrespondencia
					where id_correspondencia = v_id_origen;
        ELSE
        	SELECT fun.id_funcionario INTO v_id_funcionario_origen
            FROM orga.tfuncionario fun
            WHERE fun.id_persona=v_id_persona;
        END IF;  
        IF (v_id_funcionario_origen is null)THEN
         raise exception '%', 'La correspondencia no ha sido registrada por un funcionario.';
        END IF;          

 --  raise exception '%',v_id_usuario_reg;
			--obtenemos el id_origen de la correspondencia
			v_consulta = '
			WITH RECURSIVE correspondencia_detalle(id_correspondencia) AS (
  select cor.id_correspondencia
  from corres.tcorrespondencia cor
  where id_origen = '||v_id_origen||'
  UNION
      SELECT cor2.id_correspondencia
        from corres.tcorrespondencia cor2,correspondencia_detalle cordet
    where cor2.id_correspondencia_fk = cordet.id_correspondencia

)
SELECT cor.numero,
  cor.id_correspondencia_fk,
  coalesce(initcap(per_fk.nombre_completo2),usu1.cuenta) as desc_person_fk,
   upper(orga.f_get_cargo_x_funcionario_str(cor_fk.id_funcionario,cor_fk.fecha_documento,''oficial'')) as desc_cargo_fk,

cor.id_correspondencia,
  initcap(per.nombre_completo2) as desc_person,
   upper(orga.f_get_cargo_x_funcionario_str(cor.id_funcionario,cor.fecha_documento,''oficial'')) as desc_cargo,

  cor.mensaje,
  cor.referencia,
  (CASE WHEN (cor.id_acciones is not null) then

    (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
      (
        SELECT  pxp.list(acor.nombre)
        FROM corres.taccion acor
        WHERE acor.id_accion = ANY ( cor.id_acciones))
     END )
   END )AS  acciones,
  usu1.cuenta,
  '||v_id_origen||' as desc_id_origen,
  '||v_id_funcionario_origen||' as desc_id_funcionario_origen,
  cor.estado,
  cor.fecha_documento,
 -- cor.fecha_mod,
  cor.fecha_ult_derivado,
 /* coalesce((select fecha_reg ::text
  from corres.tcorrespondencia_estado corest
  where corest.id_correspondencia=cordet.id_correspondencia and estado=''recibido'')::text,''''::text) as fecha_recepcion
*/(select fecha_reg 
  from corres.tcorrespondencia_estado corest
  where corest.id_correspondencia=cordet.id_correspondencia and estado=''recibido''
  order by corest.id_correspondencia_estado asc limit 1)::timestamp as fecha_recepcion


FROM correspondencia_detalle cordet
INNER JOIN corres.tcorrespondencia cor on cor.id_correspondencia = cordet.id_correspondencia
INNER JOIN orga.tfuncionario fun on fun.id_funcionario = cor.id_funcionario
inner join segu.vpersona per on per.id_persona = fun.id_persona
 inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg

  INNER JOIN corres.tcorrespondencia cor_fk on cor_fk.id_correspondencia = cor.id_correspondencia_fk
left JOIN orga.tfuncionario fun_fk on fun_fk.id_funcionario = cor_fk.id_funcionario
left join segu.vpersona per_fk on per_fk.id_persona = fun_fk.id_persona
	WHERE cor.estado not in (''borrador_detalle_recibido'',''anulado'')
ORDER BY  id_correspondencia ASC ';

--raise exception '%','llega'||v_id_funcionario_origen;
      --Devuelve la respuesta
  
      return v_consulta;

    end;


  /*********************************
#TRANSACCION:  'CO_CORFIEM_SEL'
#DESCRIPCION:	Ver correspondencia que ira  se enviara a otro departamento
#AUTOR:		    Favio Figueroa
#FECHA:		    11-03-2016
***********************************/
  elsif(p_transaccion='CO_CORFIEM_SEL')then

    begin

      v_permiso = 'no';

      IF p_administrador = 1 THEN

        v_permiso = 'si';
				v_deptos = '';

      ELSE

        IF EXISTS (SELECT 0 FROM param.tdepto_usuario depus
					inner join param.tdepto dep on dep.id_depto = depus.id_depto
					inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
										where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
										and sis.codigo = 'CORRES')
        THEN
        --stuff here


					select pxp.list(depus.id_depto::VARCHAR)
					into v_deptos
          from param.tdepto_usuario depus
            inner join param.tdepto dep on dep.id_depto = depus.id_depto
            inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
          where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
                and sis.codigo = 'CORRES';




					v_permiso = 'si';

          ELSE
            RAISE EXCEPTION '%','no eres responsable ni axuliar de ningun departamento';
        END IF;



      END IF;


			--si es administrador o es axuliar o responsable de un departamento corres


      --Sentencia de la consulta
      v_consulta:='
       select tiene,
      id_correspondencia,
      numero,fecha_documento,
      ruta_archivo,
      estado_fisico
      from corres.vcorrespondencia_fisica_emitida
where tiene is not null ';

			IF v_deptos != '' THEN
				v_consulta = v_consulta || ' and id_depto  in ('||v_deptos||') ';
				end if;



      --Devuelve la respuesta
      return v_consulta;


    end;

  /*********************************
 #TRANSACCION:  'CO_CORFIEM_CONT'
 #DESCRIPCION:	Conteo de registros de ver fisicos emitidos
 #AUTOR:
 #FECHA:		    11-03-2016 16:13:21
***********************************/

  elsif(p_transaccion='CO_CORFIEM_CONT')then

    begin
      --Sentencia de la consulta de conteo de registros
      v_consulta:='select count(*)
       from corres.vcorrespondencia_fisica_emitida cor ';


      --Devuelve la respuesta
      return v_consulta;

    end;



		/*********************************
 	#TRANSACCION:  'CO_COREXTE_SEL'
 	#DESCRIPCION:	Consulta de datos PARA correspondencias externas
 	#AUTOR:		rac
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	ELSEIF(p_transaccion='CO_COREXTE_SEL')then

		begin
             
			IF p_administrador = 1 THEN
				v_filtro = '0=0 and';
				v_deptos = '';
			ELSE
            	--v_filtro = ' cor.id_funcionario = ' ||v_parametros.id_funcionario_usuario::varchar;
                v_filtro = '0=0  ';

				IF EXISTS (SELECT 0 FROM param.tdepto_usuario depus
					inner join param.tdepto dep on dep.id_depto = depus.id_depto
					inner join segu.tsubsistema sis 	on sis.id_subsistema = dep.id_subsistema
				where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
							and sis.codigo = 'CORRES')
				THEN
				
					select pxp.list(depus.id_depto::VARCHAR)
					into v_deptos
					from param.tdepto_usuario depus
						inner join param.tdepto dep on dep.id_depto = depus.id_depto
						inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
					where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
								and sis.codigo = 'CORRES';


					v_filtro = v_filtro || ' and cor.id_depto  in ('||v_deptos||')   and';
					v_permiso = 'si';

				ELSE
           			RAISE EXCEPTION '%','no eres responsable ni auxiliar de ningun departamento';
				END IF;


			END IF;


	--Sentencia de la consulta
			v_consulta:='select
                            cor.id_origen,
                            cor.id_correspondencia,
                            cor.estado,
                            cor.estado_reg,
                            cor.fecha_documento,
                            cor.fecha_fin,
                            cor.id_acciones,
                            coror.cite,
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
                            cor.ruta_archivo,
                            cor.version,
                            clasif.descripcion as desc_clasificador,
                            cor.id_clasificador,
                            doc.ruta_plantilla as desc_ruta_plantilla_documento,
                            cor.sw_archivado,
							coalesce (insti.nombre,'''') as desc_institucion,
                            insti.id_institucion as id_institucion_remitente,
                            coror.nro_paginas,
                            cor.id_persona as id_persona_remitente,
                            coalesce(persona.nombre_completo1,'''') as desc_persona,
                            coror.otros_adjuntos,
                            (SELECT count(adjun.id_adjunto) FROM corres.tadjunto adjun WHERE adjun.id_correspondencia_origen=cor.id_correspondencia) as adjunto,
                            cor.sw_fisico,
                            cor.fecha_creacion_documento,
                            cor.observaciones_archivado,
                            cor.estado_corre,
                            coalesce(emp_recepciona1.desc_funcionario1,''Recepcionista'') as desc_funcionario,
                           (CASE WHEN (cor.id_acciones is not null) then

                                  (CASE WHEN (array_upper(cor.id_acciones,1) is  not null) then
                                      (
                                       SELECT   pxp.list(acor.nombre) 
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( cor.id_acciones))
                                    END )
                                END )AS  acciones


                        from corres.tcorrespondencia cor
                        inner join corres.tcorrespondencia coror on coror.id_correspondencia=cor.id_origen
          				inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        left join param.tinstitucion insti on insti.id_institucion=coror.id_institucion
                        left join segu.vpersona persona on persona.id_persona=coror.id_persona                     
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                       	left join orga.vfuncionario emp_recepciona1 on emp_recepciona1.id_funcionario=cor.id_funcionario
                      
				        where   '||v_filtro||'
                         ';


			--Definicion de la respuesta
           	v_consulta:=v_consulta||v_parametros.filtro;
            if (v_parametros.ordenacion='numero') THEN
                v_consulta:=v_consulta||' order by fecha_reg ' ;
            ELSE
                v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' ;
            end if;

			v_consulta:=v_consulta || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;


			
			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
     #TRANSACCION:  'CO_COREXTE_CONT'
     #DESCRIPCION:	Conteo de registros
     #AUTOR:		rac
     #FECHA:		13-12-2011 16:13:21
    ***********************************/

	elsif(p_transaccion='CO_COREXTE_CONT')then

		begin
            IF p_administrador = 1 THEN
				v_filtro = '0=0 and';
				v_deptos = '';
			ELSE

		        v_filtro = '0=0 ';

				IF EXISTS (SELECT 0 FROM param.tdepto_usuario depus
					inner join param.tdepto dep on dep.id_depto = depus.id_depto
					inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
				where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
							and sis.codigo = 'CORRES')
				THEN
					--stuff here


					select pxp.list(depus.id_depto::VARCHAR)
					into v_deptos
					from param.tdepto_usuario depus
						inner join param.tdepto dep on dep.id_depto = depus.id_depto
						inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
					where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
								and sis.codigo = 'CORRES';


					v_filtro = v_filtro || ' and cor.id_depto  in ('||v_deptos||') and ';
					v_permiso = 'si';

				ELSE
                
					RAISE EXCEPTION '%','no eres responsable ni auxiliar de ningun departamento';
                    
				END IF;


			END IF;


      	
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_correspondencia)
					     from corres.tcorrespondencia cor
						inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                        inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                        inner join  param.tdepto depto on depto.id_depto=cor.id_depto
                        left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                        left join segu.vpersona persona on persona.id_persona=cor.id_persona                     
                        inner join segu.tclasificador clasif on clasif.id_clasificador=cor.id_clasificador
						left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
				        where '||v_filtro||'  ';

			--Definicion de la respuesta
            --cor.id_correspondencia_fk is null and
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

/*********************************
#TRANSACCION:  'CO_HOJORIG_SEL'
#DESCRIPCION:	Obtener Correspondencia Principal 
#AUTOR:		    AVQ
#FECHA:		    29/10/2018
***********************************/
  elsif(p_transaccion='CO_HOJORIG_SEL')then

    begin


      --Sentencia de la consulta
      v_consulta:=' select
                    numero,
                    fecha_creacion_documento,
                    tipo,
                    insti.nombre as desc_insti,
                    persona.nombre_completo1 as nombre_persona,
                    fun.desc_funcionario1 as desc_funcionario,
                    otros_adjuntos,
                    referencia,
                    mensaje                                                    
                    from corres.tcorrespondencia cor
                   left join param.tinstitucion insti on insti.id_institucion=cor.id_institucion
                   left join segu.vpersona persona on persona.id_persona=cor.id_persona
                   left join orga.vfuncionario fun on fun.id_funcionario=cor.id_funcionario
                   where ';

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