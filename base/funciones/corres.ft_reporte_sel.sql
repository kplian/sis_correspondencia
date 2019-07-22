CREATE OR REPLACE FUNCTION corres.ft_reporte_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Correspondencia
 FUNCION: 		corres.ft_reporte_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tcorrespondencia'
 AUTOR: 		 (JMH)
 FECHA:	        23-11-2018 15:15:11
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
 DESCRIPCION:	Reporte General de Correspondencias
 AUTOR:		Marcela Garcia	
 FECHA:		02-07-2019
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_where varchar;
    v_join varchar;
    v_filtro_adi		varchar;
			    
BEGIN

	v_nombre_funcion = 'corres.ft_reporte_sel';
    v_parametros = pxp.f_get_record(p_tabla);
    
    if(p_transaccion='CO_REPCOR_SEL')then
     				
    	begin
            --raise exception '%',v_parametros.filtro;
    		--Sentencia de la consulta
			v_consulta:=' SELECT *
                          FROM (
                          select cor.id_correspondencia,
                                                         cor.estado,
                                                         cor.fecha_documento,
                                                         cor.mensaje,
                                                         cor.nivel_prioridad,
                                                         cor.numero,
                                                         cor.referencia,
                                                         (select usu.cuenta from segu.tusuario usu where usu.id_usuario = cor.id_usuario_reg) as usr_reg,
                                                         (select funcionario.desc_funcionario1 from orga.vfuncionario funcionario where funcionario.id_funcionario = cor.id_funcionario) as desc_funcionario, --destinatario
                                                         cor.version,
                                                         (select clasif.descripcion from segu.tclasificador clasif where clasif.id_clasificador = cor.id_clasificador) as desc_clasificador,
                                                         cor.sw_archivado,
                                                         --(select doc.tipo from param.tdocumento doc where doc.id_documento = cor.id_documento),
                                                         cor.tipo,
                                                         (select doc.descripcion from param.tdocumento doc where doc.id_documento = cor.id_documento) as documento, 
                                                         cor.origen as persona_remi, --remitente
                                                         (CASE WHEN (cor.id_acciones is not null) then (CASE
                                                               WHEN (array_upper(cor.id_acciones, 1) is not null) then (
                                                                   SELECT pxp.list (acor.nombre ) FROM corres.taccion acor
                                                                   WHERE acor.id_accion = ANY (cor.id_acciones)) END)
                                                          END) AS acciones,
                                                          cor.fecha_reg,
                                                          cor.id_uo,
                                                          cor.id_documento,
                                                          cor.id_usuario_reg
                                                 from corres.tcorrespondencia cor
                                                 where cor.id_correspondencia_fk is not null
                                                 
                                                 
                          UNION

                          select cor.id_correspondencia,
                                                         cor.estado,
                                                         cor.fecha_documento,
                                                         cor.mensaje,
                                                         cor.nivel_prioridad,
                                                         cor.numero,
                                                         cor.referencia,
                                                         (select usu.cuenta from segu.tusuario usu where usu.id_usuario = cor.id_usuario_reg) as usr_reg,
                                                         (select insti.nombre from param.tinstitucion insti where insti.id_institucion = cor.id_institucion) as desc_funcionario, --destinatario
                                                         cor.version,
                                                         (select clasif.descripcion from segu.tclasificador clasif where clasif.id_clasificador = cor.id_clasificador) as desc_clasificador,
                                                         cor.sw_archivado,
                                                         --(select doc.tipo from param.tdocumento doc where doc.id_documento = cor.id_documento),
                                                         cor.tipo,
                                                         (select doc.descripcion from param.tdocumento doc where doc.id_documento = cor.id_documento) as documento, 
                                                         (select funcionario.desc_funcionario1 from orga.vfuncionario funcionario where funcionario.id_funcionario = cor.id_funcionario) as persona_remi, --remitente
                                                         (CASE WHEN (cor.id_acciones is not null) then (CASE
                                                               WHEN (array_upper(cor.id_acciones, 1) is not null) then (
                                                                   SELECT pxp.list (acor.nombre ) FROM corres.taccion acor
                                                                   WHERE acor.id_accion = ANY (cor.id_acciones)) END)
                                                          END) AS acciones,
                                                          cor.fecha_reg,
                                                          cor.id_uo,
                                                          cor.id_documento,
                                                          cor.id_usuario_reg
                                                 from corres.tcorrespondencia cor
                                                 where (cor.tipo = ''saliente'' and cor.id_correspondencia_fk is null) 
                           ) T 
                           WHERE ' || v_parametros.filtro ||' ORDER BY T.fecha_reg';
                       
			
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
         
    /*********************************    
 	#TRANSACCION:  'CO_REPCOR_CONT'
 	#DESCRIPCION:	Reporte 
 	#AUTOR:		Marcela Garcia	
 	#FECHA:		02-07-2019
	***********************************/

	elsif(p_transaccion='CO_REPCOR_CONT')then
     				
    	begin
        
    		--Sentencia de la consulta
			v_consulta:=' SELECT *
                            FROM (
                            		select COUNT(cor.id_correspondencia)
                                     from corres.tcorrespondencia cor
                                     where cor.id_correspondencia_fk is not null
                                                   
                                                   
                            UNION

                            select COUNT(cor.id_correspondencia)
                                         from corres.tcorrespondencia cor
                                         where (cor.tipo = ''saliente'' and cor.id_correspondencia_fk is null) 
                             ) T     
                             WHERE   ' || v_parametros.filtro ;
          
      
      --Devuelve la respuesta
      return v_consulta;
            
    end;     
	
     /*******************************
   #TRANSACCION:  CO_REPUSU_SEL
   #DESCRIPCION:	Listar usuarios activos de sistema cuando es administrador y filtrar cuando es usuario normal
   #AUTOR:		Marcela Garcia
   #FECHA:		02/07/2019
  ***********************************/

     elsif(p_transaccion='CO_REPUSU_SEL')then

          --consulta:=';
          BEGIN
          		--raise exception 'Usuario % Administrador %', p_id_usuario, p_administrador;
                v_filtro_adi = '';
                IF (p_administrador != 1) THEN
                   v_filtro_adi = ' USUARI.id_usuario = '|| p_id_usuario||' and ';
                END IF;
                
                v_consulta:='SELECT USUARI.id_usuario,
                                     PERSON.nombre_completo2 as desc_person
                              FROM segu.tusuario USUARI
                              INNER JOIN segu.vpersona PERSON on PERSON.id_persona = USUARI.id_persona
                              WHERE USUARI.estado_reg = ''activo'' and '||v_filtro_adi;   
                                  
				v_consulta:=v_consulta||v_parametros.filtro;
                v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' OFFSET ' || v_parametros.puntero;

			   raise notice 'que esta pasando: %',v_consulta;
               return v_consulta;


         END;
       /*******************************
     #TRANSACCION:  CO_REPUSU_CONT
     #DESCRIPCION:	Contar usuarios activos de sistema
     #AUTOR:		Marcela Garcia
     #FECHA:		02/07/2019
    ***********************************/
     elsif(p_transaccion='CO_REPUSU_CONT')then

          --consulta:=';
          BEGIN
          		v_filtro_adi = '';
                IF (p_administrador != 1) THEN
                   v_filtro_adi = ' USUARI.id_usuario = '|| p_id_usuario||' and ';
                END IF;
                
                v_consulta:='SELECT COUNT(USUARI.id_usuario)
                              FROM segu.tusuario USUARI
                              INNER JOIN segu.vpersona PERSON on PERSON.id_persona = USUARI.id_persona
                              WHERE USUARI.estado_reg = ''activo'' and '||v_filtro_adi;   
                
               v_consulta:=v_consulta||v_parametros.filtro;
               
               raise notice 'que esta pasando: %',v_consulta;
               return v_consulta;
         END;
         
	/*********************************    
 	#TRANSACCION:  'CO_REPCOR_SEL'
 	#DESCRIPCION:	Reporte Tickets atendidos
 	#AUTOR:		JMH	
 	#FECHA:		22-11-2018 15:15:11
	***********************************/

	/*if(p_transaccion='CO_REPCOR_SEL')then
     				
    	begin
            --raise exception '%',v_parametros.filtro;
    		--Sentencia de la consulta
			v_consulta:='select cor.id_correspondencia,
                               cor.estado,
                               cor.fecha_documento,
                               cor.mensaje,
                               cor.nivel_prioridad,
                               cor.numero,
                               cor.observaciones_estado,
                               cor.referencia,
                               usu1.cuenta as usr_reg,
                               usu2.cuenta as usr_mod,
                               funcionario.desc_funcionario1 as desc_funcionario,
                               cor.version,
                               clasif.descripcion as desc_clasificador,
                                /*orga.f_get_cargo_x_funcionario_str(cor.id_funcionario,
                                cor.fecha_documento, ''oficial'') as desc_cargo,*/
                               cor.sw_archivado,
                               insti.nombre as desc_insti,
                               doc.tipo,
                               doc.descripcion as documento,
                               doc.id_documento,
                               coalesce(persona.nombre_completo1, ' ') as persona_remi,
                               (CASE WHEN (cor.id_acciones is not null) then (CASE
                                     WHEN (array_upper(cor.id_acciones, 1) is not null) then (
                                         SELECT pxp.list (acor.nombre ) FROM corres.taccion acor
                                         WHERE acor.id_accion = ANY (cor.id_acciones)) END)
                                END) AS acciones
                                ,cor.id_uo
                                ,cor.id_correspondencia_fk
                  	   from corres.tcorrespondencia cor
                       inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                       inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                       inner join param.tdepto depto on depto.id_depto = cor.id_depto
                       inner join orga.vfuncionario funcionario on funcionario.id_funcionario = cor.id_funcionario
                       inner join orga.tfuncionario fun on fun.id_funcionario = cor.id_funcionario
                       inner join segu.vpersona person on person.id_persona = fun.id_persona
                       inner join orga.tuo uo on uo.id_uo = cor.id_uo
                       inner join segu.tclasificador clasif on clasif.id_clasificador = cor.id_clasificador
                       left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                       left join param.tinstitucion insti on insti.id_institucion = cor.id_institucion
                       left join segu.vpersona persona on persona.id_persona = cor.id_persona
                	   where cor.id_correspondencia_fk is null and  ' || v_parametros.filtro;
                       
			
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
         
    /*********************************    
 	#TRANSACCION:  'CO_REPCOR_CONT'
 	#DESCRIPCION:	Reporte 
 	#AUTOR:		JMH	
 	#FECHA:		23-11-2018 11:15:11
	***********************************/

	elsif(p_transaccion='CO_REPCOR_CONT')then
     				
    	begin
        
    		--Sentencia de la consulta
			v_consulta:=' select count (cor.id_correspondencia)
            			from corres.tcorrespondencia cor
                       inner join segu.tusuario usu1 on usu1.id_usuario = cor.id_usuario_reg
                       inner join param.tdocumento doc on doc.id_documento = cor.id_documento
                       inner join param.tdepto depto on depto.id_depto = cor.id_depto
                       inner join orga.vfuncionario funcionario on funcionario.id_funcionario = cor.id_funcionario
                       inner join orga.tfuncionario fun on fun.id_funcionario = cor.id_funcionario
                       inner join segu.vpersona person on person.id_persona = fun.id_persona
                       inner join orga.tuo uo on uo.id_uo = cor.id_uo
                       inner join segu.tclasificador clasif on clasif.id_clasificador = cor.id_clasificador
                       left join segu.tusuario usu2 on usu2.id_usuario = cor.id_usuario_mod
                       left join param.tinstitucion insti on insti.id_institucion = cor.id_institucion
                       left join segu.vpersona persona on persona.id_persona = cor.id_persona
                  where cor.id_correspondencia_fk is null and ' ||v_parametros.filtro ;
          
      
      --Devuelve la respuesta
      return v_consulta;
            
    end;     */
    
                  
   /*********************************    
  #TRANSACCION:  'CO_REPCOR_ESTA'
  #DESCRIPCION: Reporte 
  #AUTOR:   HPG 
  #FECHA:   13-03-2019 11:11:11
  ***********************************/

  elsif(p_transaccion='CO_REPCOR_ESTA')then
            
      begin
        
        --Sentencia de la consulta
      v_consulta:=' select sum(count) as cantidad ,nombre,id_accion, tipo 
                            from (
                            select count(cor.id_acciones[1]), ac.nombre, ac.id_accion, cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[1]
                            WHERE cor.id_acciones[1] is not null
                            group by cor.id_acciones[1],ac.nombre, ac.id_accion, cor.tipo

                            union

                            select count(cor.id_acciones[2]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[2]
                            WHERE cor.id_acciones[2] is not null
                            group by cor.id_acciones[2],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[3]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[3]
                            WHERE cor.id_acciones[3] is not null
                            group by cor.id_acciones[3],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[4]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[4]
                            WHERE cor.id_acciones[4] is not null
                            group by cor.id_acciones[4],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[5]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[5]
                            WHERE cor.id_acciones[5] is not null
                            group by cor.id_acciones[5],ac.nombre, ac.id_accion , cor.tipo

                            UNION

                            select count(cor.id_acciones[6]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[6]
                            WHERE cor.id_acciones[6] is not null
                            group by cor.id_acciones[6],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[7]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[7]
                            WHERE cor.id_acciones[7] is not null
                            group by cor.id_acciones[7],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[8]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[8]
                            WHERE cor.id_acciones[8] is not null
                            group by cor.id_acciones[8],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[9]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[9]
                            WHERE cor.id_acciones[9] is not null
                            group by cor.id_acciones[9],ac.nombre, ac.id_accion , cor.tipo

                            UNION

                            select count(cor.id_acciones[10]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[10]
                            WHERE cor.id_acciones[10] is not null
                            group by cor.id_acciones[10],ac.nombre, ac.id_accion , cor.tipo

                            UNION

                            select count(cor.id_acciones[11]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[11]
                            WHERE cor.id_acciones[11] is not null
                            group by cor.id_acciones[11],ac.nombre, ac.id_accion , cor.tipo

                            UNION

                            select count(cor.id_acciones[12]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[12]
                            WHERE cor.id_acciones[12] is not null
                            group by cor.id_acciones[12],ac.nombre, ac.id_accion , cor.tipo

                            union

                            select count(cor.id_acciones[13]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[13]
                            WHERE cor.id_acciones[13] is not null
                            group by cor.id_acciones[13],ac.nombre, ac.id_accion , cor.tipo

                            UNION

                            select count(cor.id_acciones[14]), ac.nombre, ac.id_accion , cor.tipo
                            from corres.tcorrespondencia as cor
                            inner join corres.taccion as ac on ac.id_accion = cor.id_acciones[14]
                            WHERE cor.id_acciones[14] is not null
                            group by cor.id_acciones[14],ac.nombre, ac.id_accion , cor.tipo
                            ) as tablanueva
                            group by id_accion,nombre, tipo
                            order by sum(count) desc, nombre,tipo' ;
          
      
      --Devuelve la respuesta
            
            --raise notice '%',v_consulta;
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