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
 #ISSUE         FECHA        AUTOR        DESCRIPCION
 #4  	      	02-07-2019   MCGH         Reporte General de Correspondencias
 #5      		21/08/2019   MCGH         Eliminación de Código Basura
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
                                                         usu.cuenta usr_reg,
                                                         COALESCE((select funcionario.desc_funcionario1 from orga.vfuncionario funcionario where funcionario.id_funcionario = cor.id_funcionario),'''') desc_funcionario,
                                                         cor.version,
                                                         cor.sw_archivado,
                                                         cor.tipo,
                                                         doc.descripcion documento,
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
                                                 from corres.tcorrespondencia cor INNER JOIN segu.tusuario usu ON usu.id_usuario = cor.id_usuario_reg
                                                 		INNER JOIN param.tdocumento doc ON doc.id_documento = cor.id_documento
                                                 where (cor.tipo = ''externa'')
                                                 --and cor.fecha_reg = (select max(fecha_reg) from corres.tcorrespondencia where numero=cor.numero)

                          UNION

                          select cor.id_correspondencia,
                                                         cor.estado,
                                                         cor.fecha_documento,
                                                         cor.mensaje,
                                                         cor.nivel_prioridad,
                                                         cor.numero,
                                                         cor.referencia,
                                                         usu.cuenta usr_reg,
                                                         funcionario.desc_funcionario1 desc_funcionario,
                                                         cor.version,
                                                         cor.sw_archivado,
                                                         cor.tipo,
                                                         doc.descripcion documento,
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
                                                 from corres.tcorrespondencia cor INNER JOIN segu.tusuario usu ON usu.id_usuario = cor.id_usuario_reg
                                                 		INNER JOIN orga.vfuncionario funcionario ON funcionario.id_funcionario = cor.id_funcionario
                                                 		INNER JOIN param.tdocumento doc ON doc.id_documento = cor.id_documento
                          						 where (cor.tipo = ''saliente'')
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
    #ISSUE: 	#4
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
   #ISSUE: 	#4
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
     #ISSUE: 	#4
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