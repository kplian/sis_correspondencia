--------------- SQL ---------------

CREATE OR REPLACE FUNCTION corres.ft_correspondencia_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_correspondencia_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tcorrespondencia'
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

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_correspondencia	integer;

    v_num_corre   varchar;
    v_id_gestion integer;
    v_codigo_documento varchar;
    v_id_periodo integer;
    v_resp_cm varchar;
    v_origen varchar;
    v_nombre_funcionario varchar;
    v_datos_maestro      record;
    v_estado varchar;

  v_id_uo integer[];
  v_id_depto INTEGER;


  v_estado_aux VARCHAR;

  v_id_origen INTEGER;

BEGIN

    v_nombre_funcion = 'corres.ft_correspondencia_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'CO_COR_INS'
 	#DESCRIPCION:	Insercion de corresppondecia externa e interna
 	#AUTOR:		rac
 	#FECHA:		13-12-2011 16:13:21
	***********************************/


	if(p_transaccion='CO_COR_INS')then

        begin




          --obtener el uo del funcionario que esta reenviando
          v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_parametros.id_funcionario, array ['activo', 'suplente']);

          --v_id_uo[2] es el id_uo

          --obtener el departamento
          SELECT dep.id_depto
          INTO
            v_id_depto
          FROM param.tdepto_uo duo
            INNER JOIN segu.tsubsistema sis
              ON sis.codigo = 'CORRES'
          INNER JOIN param.tdepto dep
          ON dep.id_depto = duo.id_depto
          WHERE duo.id_uo = ANY (v_id_uo);








          --   obtener documento
         SELECT d.codigo
          into   v_codigo_documento
          FROM param.tdocumento d
          WHERE d.id_documento = v_parametros.id_documento;



        --0) Obtener numero de requerimiento en funcion del depto de legal
        /*
                par_codigo_documento varchar,
                par_id integer, // si se mnada se crea para un periodo o especifica especifico si no la obtiene de la fecha
                par_id_uo integer,
                par_id_depto integer,
                par_id_usuario integer,
                par_codigo_subsistema varchar,
                par_formato varchar
        */

        --  raise exception '%                 %         %                  %                  %             %     %',v_codigo_documento,NULL,v_parametros.id_uo,v_parametros.id_depto,p_id_usuario,'CORRES',NULL;
        v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,NULL,v_id_uo[2],v_id_depto,p_id_usuario,'CORRES',NULL);


          --1)obtiene el identificador de la gestion

         select g.id_gestion
         into v_id_gestion
         from param.tgestion g
         where g.estado_reg='activo' and g.gestion=to_char(now()::date,'YYYY')::integer;

         --2 obtener el identificar del periodo


          select p.id_periodo
          into v_id_periodo
          from param.tperiodo p
          inner join param.tgestion ges on ges.id_gestion = p.id_gestion and ges.estado_reg ='activo'
          where p.estado_reg='activo' and  now()::date between p.fecha_ini and p.fecha_fin ;




        --3 Sentencia de la insercion
        	insert into corres.tcorrespondencia(
			estado,
			estado_reg,
			fecha_documento,
			--fecha_fin,
			--id_acciones,

			--id_correspondencia_fk,
			id_correspondencias_asociadas,
			id_depto,
			id_documento,
			id_funcionario,
			id_gestion,
			--id_institucion,
			id_periodo,
			--id_persona,
			id_uo,
			mensaje,
			nivel,
			nivel_prioridad,
			numero,
			--observaciones_estado,
			referencia,
			--respuestas,
			--sw_responsable,
			tipo,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
            id_clasificador
          	) values(
			'borrador_envio',
			'activo',
			v_parametros.fecha_documento,
			--v_parametros.fecha_fin,
			--v_parametros.id_acciones,

			--v_parametros.id_correspondencia_fk,
			string_to_array(v_parametros.id_correspondencias_asociadas,',')::integer[],
      v_id_depto,
			v_parametros.id_documento,
			v_parametros.id_funcionario,
			v_id_gestion,
			--v_parametros.id_institucion,
			v_id_periodo,
		--	v_parametros.id_persona,
      v_id_uo[2],
			v_parametros.mensaje,
			0,--nivel de anidamiento del arbol
			v_parametros.nivel_prioridad,
			v_num_corre,
			--v_parametros.observaciones_estado,
			v_parametros.referencia,
			--v_parametros.respuestas,
			--v_parametros.sw_responsable,
			v_parametros.tipo,
			now(),
			p_id_usuario,
			null,
			null,
            v_parametros.id_clasificador
			)RETURNING id_correspondencia into v_id_correspondencia;



          v_id_origen = v_id_correspondencia;
          UPDATE corres.tcorrespondencia set id_origen = v_id_correspondencia
          where id_correspondencia = v_id_correspondencia;


            --determinamos el origen

            if( v_parametros.tipo='interna' or v_parametros.tipo='saliente' ) then
                --si es de tipo interna el origen siempre sera un empleado
                  SELECT f.desc_funcionario1
                  into v_nombre_funcionario
                  FROM orga.vfuncionario f
                  WHERE f.id_funcionario =v_parametros.id_funcionario;

                  v_origen = v_nombre_funcionario;


             else
                 --en caso contratio sera del tipo entrante
                 --y el orgine es una persona o una institucion

                 /* POR EL MOMENTO ESTA FUNCIONA SOLO ES PARA INTERNAS Y SALIENTES*/
                 raise exception 'POR EL MOMENTO ESTA FUNCIONA SOLO ES PARA INTERNAS Y SALIENTES';

             end if;


           --si la correspondencia es del tipo internea analiza combo de empleado
           if( v_parametros.tipo='interna') THEN

                 --analiza el combo de funcionarios destinos para hacer la insercion de hijos
                 --analiza que no tenga otros derivaciones duplicadas

                 v_resp_cm=corres.f_proc_mul_cmb_empleado(
                                v_parametros.id_funcionarios,
                                v_id_correspondencia,
                                v_parametros.mensaje::varchar,
                                p_id_usuario,
                                v_parametros.id_documento,
                                v_num_corre,
                                v_parametros.tipo,
                                v_parametros.referencia,
                                v_parametros.id_acciones,
                                v_id_periodo,
                                v_id_gestion,
                                1,
                                v_parametros.id_clasificador,
                                 NULL,-- v_parametros.cite,
                                v_parametros.nivel_prioridad,
                                v_origen,
                                v_parametros.fecha_documento,
                                    v_id_origen,
                                v_id_depto
                                );
              ELSE
               --si es del tipo saliente detalle para persona o institucion
               -- verifica que tenga o una persona o una institucion
                  IF(v_parametros.id_persona_destino is NULL and v_parametros.id_institucion_destino is NULL) THEN
                     raise exception 'Debe especificar por los menos una persona o un institución destino';
                  END IF;

                  --raise exception '%',v_parametros.id_institucion_destino;

                  --inserta della hijo
                  insert into corres.tcorrespondencia(
                  estado,
                  estado_reg,
                  fecha_documento,
                  --fecha_fin,
                  id_acciones,

                  id_correspondencia_fk,
                  id_correspondencias_asociadas,
                  id_depto,
                  id_documento,

                  id_gestion,
                  id_institucion,
                  id_periodo,
                  id_persona,

                  mensaje,
                  nivel,
                  nivel_prioridad,
                  numero,
                  --observaciones_estado,
                  referencia,
                  --respuestas,
                  --sw_responsable,
                  tipo,
                  fecha_reg,
                  id_usuario_reg,
                  fecha_mod,
                  id_usuario_mod,
                  id_clasificador
                  ) values(
                  'borrador_detalle_recibido',
                  'activo',
                  v_parametros.fecha_documento,
                  --v_parametros.fecha_fin,
                  string_to_array(v_parametros.id_acciones,',')::integer[],

                  v_id_correspondencia,
                  string_to_array(v_parametros.id_correspondencias_asociadas,',')::integer[],
                  v_parametros.id_depto,
                  v_parametros.id_documento,

                  v_id_gestion,
                  v_parametros.id_institucion_destino,
                  v_id_periodo,
                  v_parametros.id_persona_destino,

                  v_parametros.mensaje,
                  1,--nivel de anidamiento del arbol
                  v_parametros.nivel_prioridad,
                  v_num_corre,
                  --v_parametros.observaciones_estado,
                  v_parametros.referencia,
                  --v_parametros.respuestas,
                  --v_parametros.sw_responsable,
                  v_parametros.tipo,
                  now(),
                  p_id_usuario,
                  null,
                  null,
                  v_parametros.id_clasificador
                  );






              END IF;


			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia almacenado(a) con exito (id_correspondencia'||v_id_correspondencia||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_id_correspondencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'CO_COR_MOD'
 	#DESCRIPCION:	Modificacion de correspondencia
 	#AUTOR:		rac
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_COR_MOD')then

		begin
           --obtenemos estado de correpondencia
           select estado into v_estado
           from corres.tcorrespondencia c
           where c.id_correspondencia= v_parametros.id_correspondencia;

           raise exception '%',v_parametros.id_clasificador;
           --if si estado borrador_envio
           if(v_estado = 'borrador_envio') then

                  --Sentencia de la modificacion
                  update corres.tcorrespondencia set
                 -- estado = v_parametros.estado,
                 -- fecha_documento = v_parametros.fecha_documento,
                  --fecha_fin = v_parametros.fecha_fin,
                --  id_acciones = v_parametros.id_acciones,

                --  id_correspondencia_fk = v_parametros.id_correspondencia_fk,
                  id_correspondencias_asociadas =  string_to_array(v_parametros.id_correspondencias_asociadas,',')::integer[],
                 -- id_depto = v_parametros.id_depto,
                  --id_documento = v_parametros.id_documento,
                  --id_funcionario = v_parametros.id_funcionario,
                  --id_gestion = v_parametros.id_gestion,
                  --id_institucion = v_parametros.id_institucion,
                  --id_periodo = v_parametros.id_periodo,
                  --id_persona = v_parametros.id_persona,
                  --id_uo = v_parametros.id_uo,
                  mensaje = v_parametros.mensaje,
                  --nivel = v_parametros.nivel,
                  nivel_prioridad = v_parametros.nivel_prioridad,
                  --numero = v_parametros.numero,
                  --observaciones_estado = v_parametros.observaciones_estado,
                  referencia = v_parametros.referencia,
                  --respuestas = v_parametros.respuestas,
                  --sw_responsable = v_parametros.sw_responsable,
                  --tipo = v_parametros.tipo,
                  fecha_mod = now(),
                  id_usuario_mod = p_id_usuario,
                  id_clasificador=v_parametros.id_clasificador
                  where id_correspondencia=v_parametros.id_correspondencia;

            elseif(v_estado = 'enviado') then
            --  si ya fue enviado solo se puede modificar las correspodencia asociada
             --Sentencia de la modificacion
                update corres.tcorrespondencia set
                id_correspondencias_asociadas =  string_to_array(v_parametros.id_correspondencias_asociadas,',')::integer[],
                fecha_mod = now(),
                id_usuario_mod = p_id_usuario
                where id_correspondencia=v_parametros.id_correspondencia;

            else

            raise exception 'estado no reconodico';


            end if;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
     /*********************************
 	#TRANSACCION:  'CO_ARCHCOR_MOD'
 	#DESCRIPCION:	Actualiza archivo de correspodencia escaneado
 	#AUTOR:		rac
 	#FECHA:		21-12-2011 17:25:24
	***********************************/

	elsif(p_transaccion='CO_ARCHCOR_MOD')then

		begin

        --raise exception 'VERSION %',v_parametros.version;

           update corres.tcorrespondencia set
           ruta_archivo = v_parametros.ruta_archivo,
           version = v_parametros.version,
           id_usuario_mod = p_id_usuario,
           fecha_mod = now()
           where id_correspondencia=v_parametros.id_correspondencia;


           --TODO en cadena recursivamente modificar  ruta_archivo
           /*pendiente */


            --Definicion de la respuesta
           v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Archivo escaneado de correspondencia modificado(a)');
           v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

            --Devuelve la respuesta
           return v_resp;

        end;



	/*********************************
 	#TRANSACCION:  'CO_COR_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		rac
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_COR_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from corres.tcorrespondencia
            where id_correspondencia=v_parametros.id_correspondencia;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'CO_CORDER_UPD'
 	#DESCRIPCION:	Derivacion de correspondecia
 	#AUTOR:		rac
 	#FECHA:		13-12-2011 16:13:21
	***********************************/

	elsif(p_transaccion='CO_CORDER_UPD')then

		begin
            /*
            verifica que tenga hijos con estado borrador detalle recibido


            */


            IF(not exists (select 1 from corres.tcorrespondencia c
                        where c.id_correspondencia_fk = v_parametros.id_correspondencia
                        and c.estado = 'borrador_detalle_recibido')) THEN

               raise exception 'No existen envios pendientes';

            END IF;




      select estado into v_estado from corres.tcorrespondencia
      where id_correspondencia=v_parametros.id_correspondencia;


      --actualiza padre
      if v_estado = 'recibido_derivacion'
        THEN

          update corres.tcorrespondencia
          set estado = 'recibido'
          where id_correspondencia=v_parametros.id_correspondencia;

        ELSE

          update corres.tcorrespondencia
          set estado = 'enviado'
          where id_correspondencia=v_parametros.id_correspondencia;

      END IF;



            -- actualiza hijos pendientes de envio
            update corres.tcorrespondencia
              set estado = 'pendiente_recibido'
            where id_correspondencia_fk=v_parametros.id_correspondencia
              and estado = 'borrador_detalle_recibido';



            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia derivada(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

     /*********************************
 	#TRANSACCION:  'CO_CORUNDO_UPD'
 	#DESCRIPCION:	Corregir correspondecia si no tiene hijos abiertos
 	#AUTOR:		rac
 	#FECHA:  27-02-2012 21:00
	***********************************/

	elsif(p_transaccion='CO_CORUNDO_UPD')then

		begin
            /*
            verifica que tenga hijos con estado borrador detalle recibido


            */

            IF(exists (select 1 from corres.tcorrespondencia c
                        where c.id_correspondencia_fk = v_parametros.id_correspondencia
                        and c.estado = 'recibido' or c.estado = 'borrador_deribado')) THEN

               raise exception 'Existen destinatarios que ya recibieron la correpondencia no se puede corregir';

            END IF;



      select c.estado into v_estado_aux
      from corres.tcorrespondencia c
      where c.id_correspondencia = v_parametros.id_correspondencia;

      RAISE EXCEPTION '%',v_estado_aux;



			--actualiza padre
			update corres.tcorrespondencia
            set estado = 'borrador_envio'
            where id_correspondencia=v_parametros.id_correspondencia;

            -- actualiza hijos pendientes de envio
            update corres.tcorrespondencia
            set estado = 'borrador_detalle_recibido'
            where id_correspondencia_fk=v_parametros.id_correspondencia
            and estado = 'pendiente_recibido';

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia corregida');
            v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

    /*********************************
 	#TRANSACCION:  'CO_CORDET_INS'
 	#DESCRIPCION:	Insercion de registros como detalle de correspondencia
 	#AUTOR:		mzm
 	#FECHA:		    14-02-2012 20:43:21
	***********************************/

  elsif(p_transaccion='CO_CORDET_INS')then

    begin


      select * into v_datos_maestro from corres.tcorrespondencia
      where id_correspondencia=v_parametros.id_correspondencia_fk;

      --RAISE EXCEPTION '%',v_datos_maestro.estado;

      if v_datos_maestro.estado = 'pendiente_recibido'
        THEN
        RAISE EXCEPTION '%','No puedes agregar nuevos por que aun no finalizaste esta correspondencia';
      END IF ;

      if v_datos_maestro.estado = 'recibido'
        THEN

        update corres.tcorrespondencia set estado = 'recibido_derivacion'
        where id_correspondencia = v_parametros.id_correspondencia_fk;

      end if;

      select id_origen into v_id_origen from corres.tcorrespondencia
        where id_correspondencia = v_parametros.id_correspondencia_fk;

      		--raise exception 'llega aqui%',v_parametros.id_funcionario;





      --obtener el uo del funcionario que esta reenviando
      v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_parametros.id_funcionario_usuario::INTEGER, array ['activo', 'suplente']);

      --v_id_uo[2] es el id_uo


      --obtener el departamento
      SELECT dep.id_depto
      INTO
        v_id_depto
      FROM param.tdepto_uo duo
        INNER JOIN segu.tsubsistema sis
          ON sis.codigo = 'CORRES'
        INNER JOIN param.tdepto dep
          ON dep.id_depto = duo.id_depto
      WHERE duo.id_uo = ANY (v_id_uo);





      v_resp_cm=corres.f_proc_mul_cmb_empleado(
          v_parametros.id_funcionario,
          v_parametros.id_correspondencia_fk::INTEGER,
          v_parametros.mensaje::varchar,
          p_id_usuario,
          v_datos_maestro.id_documento,
          v_datos_maestro.numero,
          v_datos_maestro.tipo,
          v_datos_maestro.referencia,
          v_parametros.id_acciones,
          v_datos_maestro.id_periodo,
          v_datos_maestro.id_gestion,
          1,
          v_datos_maestro.id_clasificador,
          NULL,-- v_datos_maestro.cite,
          v_datos_maestro.nivel_prioridad,
          v_datos_maestro.origen,
          v_datos_maestro.fecha_documento,
              v_id_origen,
          v_id_depto

      );



      -- raise exception 'resp%',v_resp_cm;

--Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia eliminado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia_fk::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

  /*********************************
#TRANSACCION:  'CO_CORFIN_INS'
#DESCRIPCION:	Insercion de registros como detalle de correspondencia
#AUTOR:		mzm
#FECHA:		    14-02-2012 20:43:21
***********************************/

  elsif(p_transaccion='CO_CORFIN_INS')then

    begin



      UPDATE corres.tcorrespondencia set estado = 'recibido'
        WHERE id_correspondencia = v_parametros.id_correspondencia;


      -- raise exception 'resp%',v_resp_cm;

--Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia eliminado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
  /*********************************
#TRANSACCION:  'CO_CORARCH_INS'
#DESCRIPCION:	archiva o desarchiva la correspondencia
#AUTOR:		favio figueroa
#FECHA:		    14-02-2016 20:43:21
***********************************/

  elsif(p_transaccion='CO_CORARCH_INS')then

    begin



      UPDATE corres.tcorrespondencia set sw_archivado = v_parametros.sw_archivado
      WHERE id_correspondencia = v_parametros.id_correspondencia;


      -- raise exception 'resp%',v_resp_cm;

--Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia archivado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
  /*********************************
 #TRANSACCION:  'CO_CORESTFIS_INS'
 #DESCRIPCION:	cambia el estado de la correspondencia fisica
 #AUTOR:		favio figueroa
 #FECHA:		    27-04-2016 20:43:21
 ***********************************/

  elsif(p_transaccion='CO_CORESTFIS_INS')then

    begin


     /* if v_parametros.estado_fisico = 'pendiente' then
        v_estado = 'despachado';
        ELSIF v_parametros.estado_fisico = 'despachado' THEN
        v_estado = ''
      end IF ;*/


      UPDATE corres.tcorrespondencia set estado_fisico = v_parametros.estado_fisico
      WHERE id_correspondencia = v_parametros.id_correspondencia;


      -- raise exception 'resp%',v_resp_cm;

--Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia fisico(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;


    /*********************************
 #TRANSACCION:  'CO_COREXT_INS'
 #DESCRIPCION:	inserta el mensajero la correpondencia externa
 #AUTOR:		favio figueroa
 #FECHA:		    27-04-2016 20:43:21
 ***********************************/

  elsif(p_transaccion='CO_COREXT_INS')then

    begin




      --   obtener documento
      SELECT d.codigo
      into   v_codigo_documento
      FROM param.tdocumento d
      WHERE d.id_documento = v_parametros.id_documento;

      --obtener el uo del funcionario que esta reenviando
      v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_parametros.id_funcionario_usuario, array ['activo', 'suplente']);

      --v_id_uo[2] es el id_uo

      --obtener el departamento
      SELECT dep.id_depto
      INTO
        v_id_depto
      FROM param.tdepto_uo duo
        INNER JOIN segu.tsubsistema sis
          ON sis.codigo = 'CORRES'
        INNER JOIN param.tdepto dep
          ON dep.id_depto = duo.id_depto
      WHERE duo.id_uo = ANY (v_id_uo);



      v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,NULL,v_id_uo[2],v_id_depto,p_id_usuario,'CORRES',NULL);





      --1)obtiene el identificador de la gestion

      select g.id_gestion
      into v_id_gestion
      from param.tgestion g
      where g.estado_reg='activo' and g.gestion=to_char(now()::date,'YYYY')::integer;

      --2 obtener el identificar del periodo


      select p.id_periodo
      into v_id_periodo
      from param.tperiodo p
        inner join param.tgestion ges on ges.id_gestion = p.id_gestion and ges.estado_reg ='activo'
      where p.estado_reg='activo' and  now()::date between p.fecha_ini and p.fecha_fin ;




      --3 Sentencia de la insercion
      insert into corres.tcorrespondencia(
        estado,
        estado_reg,
        fecha_documento,
        --fecha_fin,
        --id_acciones,

        --id_correspondencia_fk,
        id_correspondencias_asociadas,
        id_depto,
        id_documento,
        id_funcionario,
        id_gestion,
        id_institucion,
        id_periodo,
        id_persona,
        id_uo,
        mensaje,
        nivel,
        nivel_prioridad,
        numero,
        --observaciones_estado,
        referencia,
        --respuestas,
        --sw_responsable,
        tipo,
        fecha_reg,
        id_usuario_reg,
        fecha_mod,
        id_usuario_mod,
        id_clasificador
      ) values(
        'borrador_recepcion_externo',
        'activo',
        v_parametros.fecha_documento,
        --v_parametros.fecha_fin,
        --v_parametros.id_acciones,

        --v_parametros.id_correspondencia_fk,
        string_to_array(v_parametros.id_correspondencias_asociadas,',')::integer[],
        v_id_depto,
        v_parametros.id_documento,
        v_parametros.id_funcionario_usuario,
        v_id_gestion,
        v_parametros.id_institucion_remitente,
        v_id_periodo,
        v_parametros.id_persona_remitente,
        v_id_uo[2],
        v_parametros.mensaje,
        0,--nivel de anidamiento del arbol
        v_parametros.nivel_prioridad,
        v_num_corre,
        --v_parametros.observaciones_estado,
        v_parametros.referencia,
        --v_parametros.respuestas,
        --v_parametros.sw_responsable,
        'externa',
        now(),
        p_id_usuario,
        null,
        null,
        v_parametros.id_clasificador
      )RETURNING id_correspondencia into v_id_correspondencia;


      v_id_origen = v_id_correspondencia;
      UPDATE corres.tcorrespondencia set id_origen = v_id_correspondencia
      where id_correspondencia = v_id_correspondencia;






      -- raise exception 'resp%',v_resp_cm;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia externa recepcionada(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;


    /*********************************
#TRANSACCION:  'CO_COREXTEST_INS'
#DESCRIPCION:	camba el estado al finalizar la recepcion de la correspondencia externa
#AUTOR:		favio figueroa
#FECHA:		    27-04-2016 20:43:21
***********************************/

  elsif(p_transaccion='CO_COREXTEST_INS')then

    begin


      UPDATE corres.tcorrespondencia set estado = v_parametros.estado
        where id_correspondencia = v_parametros.id_correspondencia;


      -- raise exception 'resp%',v_resp_cm;


      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia recepcionada finalizado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

	else

    	raise exception 'Transaccion inexistente: %',p_transaccion;

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