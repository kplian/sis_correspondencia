CREATE OR REPLACE FUNCTION corres.ft_correspondencia_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/************************************************************************** SISTEMA: Correspondencia
 FUNCION:         corres.ft_correspondencia_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tcorrespondencia'
 AUTOR:          (rac)
 FECHA:            13-12-2011 16:13:21
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
****************************************************************************/

DECLARE

  v_nro_requerimiento integer;
  v_parametros record;
  v_id_requerimiento integer;
  v_resp varchar;
  v_nombre_funcion text;
  v_mensaje_error text;
  v_id_correspondencia integer;

  v_num_corre varchar;
  v_id_gestion integer;
  v_codigo_documento varchar;
  v_id_periodo integer;
  v_resp_cm varchar;
  v_origen varchar;
  v_nombre_funcionario varchar;
  v_datos_maestro record;
  v_estado varchar;

  v_id_uo integer [ ];
  v_id_depto INTEGER;

  v_estado_aux VARCHAR;
  v_clase_reporte	varchar;
  v_rec_co         	record;
  v_rec_co_1        record;
  v_id_origen INTEGER;
  v_id_correspondencias_asociadas_aux varchar;
  v_id_correspondencias_asociadas	INTEGER [];
  v_anular	integer;
 --  v_id_funcionario                integer;
    v_id_alarma                     INTEGER[];
    v_id_usuario_reg                integer;
    v_numero                        varchar;
    v_desc_usuario                  varchar;
    v_referencia                    varchar;
    v_fecha_documento                date;
    v_fecha_reg                      date;
    v_remitente                   varchar;
    --v_ins_envia                     varchar;
    vacciones                     varchar;
    v_tipo                         varchar;
    g_registros                    record;
    v_mensaje                      varchar;
    v_fecha_creacion_documento     timestamp;  
    v_id                           integer;
    v_fecha_ini                    date;
    v_fecha_fin                    date;
    v_fecha_ultima                 date;
    v_id_correspondencia_detalle   integer;
    v_id_funcionario               integer;
    v_estado_ant                   varchar;
    v_responsable                  varchar;
    --v_estado                       varchar;
    v_id_usuario                    integer;
    version_origen                  integer;
    v_ce_estado                     varchar;
    v_id_alarma_reg                 integer;
    v_observaciones_archivado       text;
    v_estado_corre                  varchar;
           
   
BEGIN

  v_nombre_funcion = 'corres.ft_correspondencia_ime';
 
  v_parametros = pxp.f_get_record(p_tabla);

  /*********************************
     #TRANSACCION:  'CO_COR_INS'
     #DESCRIPCION:    Insercion de corresppondecia externa e interna
     #AUTOR:        rac
     #FECHA:        13-12-2011 16:13:21
    ***********************************/

  if(p_transaccion='CO_COR_INS')then

    begin
      --obtener el uo del funcionario que esta reenviando
--        raise exception '%','ASDFASDF'||v_parametros.tipo;
     
         --obtener el departamento
         IF( v_parametros.tipo='saliente') THEN
               v_id_uo= ARRAY[1];
               v_id_uo =array_append(v_id_uo,v_parametros.id_uo);
               v_id_funcionario=v_parametros.id_funcionario_saliente;
         ELSE 
           	 v_id_uo = corres.f_get_uo_correspondencia_funcionario(
	         v_parametros.id_funcionario, array ['activo', 'suplente'],
	         v_parametros.fecha_documento);
	         v_id_funcionario=v_parametros.id_funcionario;
         END IF;
              
     /*  v_id_uo= ARRAY[1];
      -- v_id_uo =array_append(v_id_uo,v_parametros.id_uo);
            v_id_uo = corres.f_get_uo_correspondencia_funcionario(
            v_id_funcionario, array ['activo', 'suplente'],
            v_parametros.fecha_documento);*/
              
      SELECT dep.id_depto
      INTO v_id_depto
      FROM param.tdepto_uo duo
      INNER JOIN segu.tsubsistema sis ON sis.codigo = 'CORRES'
      INNER JOIN param.tdepto dep ON dep.id_depto = duo.id_depto
      WHERE duo.id_uo = ANY (v_id_uo);
      
    
     IF v_id_depto is NULL THEN

        raise exception
         
         'Verifique que la UO %, este configurada en el Departamento de Correspondencia',
         v_id_uo;

      END IF;
  
      --   obtener documento

      SELECT d.codigo
      into v_codigo_documento
      FROM param.tdocumento d
      WHERE d.id_documento = v_parametros.id_documento;

     --1)obtiene el identificador de la gestion

      select g.id_gestion
      into v_id_gestion
      from param.tgestion g
      where g.estado_reg = 'activo' and
            g.gestion = to_char(now()::date, 'YYYY')::integer;

      --2 obtener el identificar del periodo
      IF (now()::date=v_parametros.fecha_documento)THEN
          v_fecha_documento=now();
      ELSE
          v_fecha_documento=v_parametros.fecha_documento;
      END IF;
      --Verificacion  si la vista de Administracion
      IF (v_parametros.vista='CorrespondenciaAdministracion') then
            select p.id_periodo, p.fecha_ini, p.fecha_fin,ges.id_gestion
            into v_id,v_fecha_ini,v_fecha_fin,v_id_gestion
            from param.tperiodo p
            inner join param.tgestion ges 
            on ges.id_gestion = p.id_gestion 
            and ges.estado_reg ='activo'
            where p.estado_reg='activo' and
           v_parametros.fecha_documento between p.fecha_ini and p.fecha_fin ;
           
            --Validar la fecha del Documento.
            IF (EXISTS(select 1
            			from corres.tcorrespondencia cor
           				where cor.fecha_documento > v_fecha_documento
                		and  cor.id_uo=v_id_uo[2] and cor.id_correspondencia_fk is null and  

                        tipo=v_parametros.tipo and id_documento=v_parametros.id_documento and  cor.fecha_documento between v_fecha_ini and v_fecha_fin
                    ))THEN
                 RAISE EXCEPTION '%', 'Existe un Documento Mayor a la fecha '||v_parametros.fecha_documento;
            END IF;  
            
          
             v_fecha_creacion_documento=now();
             v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,v_id,v_id_uo
                            [2],v_id_depto,p_id_usuario,'CORRES',NULL);
             v_fecha_documento=v_parametros.fecha_documento;
        ELSE     
             v_fecha_documento=now();  
             v_fecha_creacion_documento=now();
             v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,NULL,v_id_uo
                           [2],v_id_depto,p_id_usuario,'CORRES',NULL);
                            
        END IF;
        
        select p.id_periodo,p.fecha_ini, p.fecha_fin
        into v_id_periodo,v_fecha_ini,v_fecha_fin
        from param.tperiodo p
        inner join param.tgestion ges on ges.id_gestion = p.id_gestion and
               ges.estado_reg = 'activo'
        where p.estado_reg = 'activo' and
             v_fecha_documento::date between p.fecha_ini and
              p.fecha_fin;     
      
      

      --3 Sentencia de la insercion
        insert into corres.tcorrespondencia(estado,estado_reg, fecha_documento,
                  
                    id_depto, id_documento,
                    id_funcionario, id_gestion,
                    id_institucion,
                    id_periodo,
                    id_persona,
                    id_uo, mensaje, nivel, nivel_prioridad, numero,
                    referencia,
                    tipo, fecha_reg, id_usuario_reg, fecha_mod, id_usuario_mod,
                      id_clasificador,id_correspondencias_asociadas,fecha_creacion_documento)
        values ( 'borrador_envio','activo', 
              v_fecha_documento,
              -- now()::date,
               v_id_depto, v_parametros.id_documento,
               v_id_funcionario, v_id_gestion,
               v_parametros.id_institucion_destino,
               v_id_periodo,
               v_parametros.id_persona_destino,
               v_id_uo [ 2 ], v_parametros.mensaje, 0,
               --nivel de anidamiento del arbol
               v_parametros.nivel_prioridad, v_num_corre,
               v_parametros.referencia,
               v_parametros.tipo, now(), p_id_usuario, null, null,
               v_parametros.id_clasificador,case when v_parametros.id_correspondencias_asociadas='' THEN
                                                       NULL
                                                 ELSE
                string_to_array(v_parametros.id_correspondencias_asociadas, ',')::integer [ ]
                END,
                now()
               ) RETURNING id_correspondencia
        into v_id_correspondencia;
    
        v_id_origen = v_id_correspondencia;
         
         UPDATE corres.tcorrespondencia
         set id_origen = v_id_correspondencia
         where id_correspondencia = v_id_correspondencia;

         
        if( v_parametros.tipo='interna' or v_parametros.tipo='saliente' ) then
          --si es de tipo interna el origen siempre sera un empleado

          SELECT f.desc_funcionario1
          into v_nombre_funcionario
          FROM orga.vfuncionario f
          WHERE f.id_funcionario = v_id_funcionario;

          v_origen = v_nombre_funcionario;

          else
          --en caso contrario sera del tipo entrante
          --y el orgine es una persona o una institucion

          /* POR EL MOMENTO ESTA FUNCIONA SOLO ES PARA INTERNAS Y SALIENTES*/
          raise exception
            'POR EL MOMENTO ESTA FUNCIONA SOLO ES PARA INTERNAS Y SALIENTES';

        end if;

      --si la correspondencia es del tipo interna analiza combo de empleado
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
          v_fecha_documento,
          v_id_origen,
          v_id_depto
        );
        ELSE
        --si es del tipo saliente detalle para persona o institucion
        -- verifica que tenga o una persona o una institucion
        IF(v_parametros.id_persona_destino is NULL and
          v_parametros.id_institucion_destino is NULL) THEN
          raise exception
            'Debe especificar por los menos una persona o un institución destino';
        END IF;

    

      END IF;
      
      -- Inserta estados a la tabla corres.tcorrespondencia_estado.
  

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia almacenado(a) con exito (id_correspondencia'||
        v_id_correspondencia||')');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

    /*********************************
     #TRANSACCION:  'CO_COR_MOD'
     #DESCRIPCION:    Modificacion de correspondencia
     #AUTOR:        rac
     #FECHA:        13-12-2011 16:13:21
    ***********************************/

    elsif(p_transaccion='CO_COR_MOD')then

    begin
      --obtenemos estado de correpondencia
	    
      
      select estado
      into v_estado
      from corres.tcorrespondencia c
      where c.id_correspondencia = v_parametros.id_correspondencia;

       --if si estado borrador_envio
      if(v_estado = 'borrador_envio') then

        --Sentencia de la modificacion
          
        IF( v_parametros.tipo='interna') THEN  
            update corres.tcorrespondencia
            set id_correspondencias_asociadas = case when v_parametros.id_correspondencias_asociadas='' THEN
                                                     NULL
                                               ELSE
              string_to_array(v_parametros.id_correspondencias_asociadas, ',')::integer [ ]
              END,
                mensaje = v_parametros.mensaje,
                nivel_prioridad = v_parametros.nivel_prioridad,
                referencia = v_parametros.referencia,
                fecha_mod = now(),
                id_usuario_mod = p_id_usuario,
                id_clasificador = v_parametros.id_clasificador
            where id_correspondencia = v_parametros.id_correspondencia;
         ELSE
            update corres.tcorrespondencia
            set 
                id_correspondencias_asociadas = string_to_array(
                v_parametros.id_correspondencias_asociadas, ',')::integer [ ],
                id_institucion=v_parametros.id_institucion_destino,
                id_persona=v_parametros.id_persona_destino,
                id_uo=v_parametros.id_uo,
                --  id_funcionario=v_parametros.id_funcionario,
                otros_adjuntos=v_parametros.otros_adjuntos,
                nivel_prioridad = v_parametros.nivel_prioridad,
                referencia = v_parametros.referencia,
                fecha_mod = now(),
                id_usuario_mod = p_id_usuario,
                id_clasificador = v_parametros.id_clasificador,
                mensaje = v_parametros.mensaje
               
            where id_correspondencia = v_parametros.id_correspondencia;
          END IF;
          
          UPDATE corres.tcorrespondencia
          SET nivel_prioridad = v_parametros.nivel_prioridad,
              referencia=v_parametros.referencia,
              id_clasificador = v_parametros.id_clasificador,
              fecha_mod = now(),
              id_usuario_mod = p_id_usuario
          WHERE id_correspondencia_fk=v_parametros.id_correspondencia;

     
        else

        raise exception 'No se puede editar, el estado no es Borrador';

      end if;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia modificado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
    /*********************************
     #TRANSACCION:  'CO_ARCHCOR_MOD'
     #DESCRIPCION:    Actualiza archivo de correspodencia escaneado
     #AUTOR:        rac
     #FECHA:        21-12-2011 17:25:24
    ***********************************/

    elsif(p_transaccion='CO_ARCHCOR_MOD')then

    begin
     -- raise exception 'ruta archivo %',v_parametros.ruta_archivo;
      --raise notice 'id_usuario %',p_id_usuario;
      
      --raise exception 'VERSION %',v_parametros.version;
      
       /* 20/08/2018
      *Verifica si sus derivaciones tienen el estado de enviado 
      * si es asi  modificar el estado del detalle a borrador_detalle_recibido
      */
       
    /*  FOR g_registros IN (SELECT id_correspondencia,estado 
                           FROM corres.tcorrespondencia 
                           WHERE id_correspondencia_fk=v_parametros.id_correspondencia 
                           AND estado NOT LIKE 'borrador_detalle_recibido')LOOP
                           
                           UPDATE  corres.tcorrespondencia
                           SET
                              estado='borrador_detalle_recibido'
                           WHERE
                           id_correspondencia=g_registros.id_correspondencia;
                           
        END LOOP;*/
     

      update corres.tcorrespondencia
      set ruta_archivo = v_parametros.ruta_archivo,
          version = v_parametros.version,
          id_usuario_mod = p_id_usuario,
          fecha_mod = now()
      where id_correspondencia = v_parametros.id_correspondencia;

      --TODO en cadena recursivamente modificar  ruta_archivo
      /*pendiente */

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Archivo escaneado de correspondencia modificado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);
      --raise exception '%',v_resp;
      --Devuelve la respuesta
      return v_resp;

    end;

    /*********************************
     #TRANSACCION:  'CO_COR_ELI'
     #DESCRIPCION:    Eliminacion de registros
     #AUTOR:        rac
     #FECHA:        13-12-2011 16:13:21
    ***********************************/

    elsif(p_transaccion='CO_COR_ELI')then

    begin
      -- Obtenemos el estado de la correspondencia
      select estado, estado_corre
      into v_estado,v_estado_corre
      from corres.tcorrespondencia c
      where c.id_correspondencia = v_parametros.id_correspondencia;
      -- Buscamos su estado de la correspondencia para saber si en algun momento esta correspondencia fue enviada.
      SELECT ce.estado
      INTO v_ce_estado
      FROM corres.tcorrespondencia_estado ce
      WHERE ce.id_correspondencia = v_parametros.id_correspondencia and ce.estado='enviado';
            --Si estado es Borrador_envio se puede eliminar

      IF ((v_estado = 'borrador_envio' OR v_estado = 'borrador_recepcion_externo' OR v_estado = 'borrador_detalle_recibido' OR v_estado='pendiente_recepcion_externo')or( v_estado_corre='borrador_corre' )) THEN
      
      IF EXISTS (SELECT 
            FROM corres.tcorrespondencia
            WHERE id_correspondencia_fk=v_parametros.id_correspondencia and estado='enviado' ) THEN 
            
             	RAISE EXCEPTION '%','NO SE PUEDE ELIMINAR, EL DESTINATARIO SE ENCUENTRA EN ESTADO ENVIADO, FAVOR MODIFICAR AL DESTINATARIO';
     
      ELSE 
            --Si el estado  alguna vez ha sido enviado entonces enviar una alarma al funcionario para que notifique que se ha anulado el cite.
        IF (v_ce_estado is not null)THEN
             --Eliminar las alarmas porque al enviar generá una alarma.
             
        Delete 
        from param.talarma 
        where id_alarma in 
        (select id_alarma from corres.tcorrespondencia where id_correspondencia_fk=v_parametros.id_correspondencia);

    	 FOR g_registros IN ( select co.id_funcionario,co.id_usuario_reg,co.numero,
                            vus.desc_persona,
                              coalesce(co.referencia,'') as referencia,
                              co.fecha_documento,
                              co.fecha_reg,
                              coalesce(co.origen,'') as remitente,
                              (CASE WHEN (co.id_acciones is not null) then

                                  (CASE WHEN (array_upper(co.id_acciones,1) is  not null) then
                                      (
                                       SELECT   pxp.list(acor.nombre) 
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( co.id_acciones))
                                    END )
                                END )AS  acciones,
                                co.id_correspondencia,
                                co.tipo
            	from corres.tcorrespondencia co
                left join segu.vpersona pers on pers.id_persona=co.id_persona
                left join param.tinstitucion ins on ins.id_institucion=co.id_institucion
                inner join segu.vusuario vus on vus.id_usuario=co.id_usuario_reg
                 where id_correspondencia_fk = v_parametros.id_correspondencia and co.estado ='pendiente_recibido') LOOP
            
                v_tipo:='';
                IF (g_registros.tipo='interna')THEN
                   v_tipo:='INTERNA';
                ELSE
                   v_tipo:='EXTERNA';
                END IF;
               v_id_alarma[1]:=param.f_inserta_alarma(g_registros.id_funcionario,
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.referencia||'</font></font><br>
                                                      <br><b>&nbsp;</b>Estimad@:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <br>
                                                      <br><b>&nbsp;</b>Anulación de la Correspondencia '||v_tipo||' con los siguientes datos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;  <br>
                                                      <b>&nbsp;</b>Nro:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.numero||' </b> <br> 
                                                       <b>&nbsp;</b>Remitente:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.remitente||'</b>  <br> 
                                                      <b>&nbsp;</b>Referencia:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.referencia||' </b> <br> 
                                                      <b>&nbsp;</b>Fecha de Documento:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.fecha_documento||'</b>  <br> 
                                                      <b>&nbsp;</b>Acción:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'||g_registros.acciones||' </b>  <br>',    --descripcion alarmce
                                                
                                                         --descripcion alarmce
                                                    '../../../sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php',--acceso directo
                                                    now()::date,
                                                    'notificacion',
                                                    '',   -->
                                                    g_registros.id_usuario_reg,
                                                    '',
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.numero||'</font></font>',--titulo
                                                    'parametros',
                                                    g_registros.id_usuario_reg,--id_usuario
                                                    'Anulación de la Correspondencia '||v_tipo||': '||g_registros.numero,
                                                    'anulacion@gmail.com','',NULL,null,NULL,'si');
                                                   
     
     		 END LOOP;
         END IF;
         	--Sentencia de la eliminacion de la correspondencia detalle

        UPDATE
           corres.tcorrespondencia
        SET
           estado='anulado',
            id_usuario_mod = p_id_usuario,
          fecha_mod = now()
        WHERE id_correspondencia_fk = v_parametros.id_correspondencia;
        
        UPDATE
           corres.tcorrespondencia
        SET
           estado='anulado',
            id_usuario_mod = p_id_usuario,
          fecha_mod = now()
        WHERE id_correspondencia = v_parametros.id_correspondencia;

      END IF;
      ELSE
      	RAISE EXCEPTION 'NO SE PUEDE ELIMINAR, EL ESTADO NO ES BORRADOR';
      END IF;
      
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia eliminado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

    /*********************************
     #TRANSACCION:  'CO_CORDER_UPD'
     #DESCRIPCION:  Derivacion de correspondecia
     #AUTOR:        rac
     #FECHA:        13-12-2011 16:13:21
    ***********************************/

    elsif(p_transaccion='CO_CORDER_UPD')then

    begin
      /*
        verifica que tenga hijos con estado borrador detalle recibido
	
	
	       */
      select estado_ant
      into v_estado_aux
      from corres.tcorrespondencia_estado 
      where estado_reg='activo'
      and id_correspondencia=v_parametros.id_correspondencia;
       -- raise exception '%',''||v_parametros.id_correspondencia;
      IF (v_estado_aux!='enviado')THEN
      
        IF(not exists (
          select 1
          from corres.tcorrespondencia c
          where c.id_correspondencia_fk = v_parametros.id_correspondencia and
                c.estado = 'borrador_detalle_recibido')) THEN

          raise exception 'No existen envios pendientes';

        END IF;
      
      END IF;
     
      select estado,tipo
      into v_estado,v_tipo
      from corres.tcorrespondencia
      where id_correspondencia = v_parametros.id_correspondencia;
      
      

      --actualiza padre
        
         /*Adición la derivación, adición de la alarma para el envio del usuario al que va a enviar. 
	 EAQ: agregacion de parametros a insertar en talarma, para acceso directo
        */
    
   FOR g_registros IN ( select co.id_funcionario,co.id_usuario_reg,co.numero,
                              vus.desc_persona,
                              coalesce(co.referencia,'') as referencia,
                              co.fecha_documento,
                              co.fecha_reg,
                              coalesce(co.origen,'') as remitente,
                              (CASE WHEN (co.id_acciones is not null) then

                                  (CASE WHEN (array_upper(co.id_acciones,1) is  not null) then
                                      (
                                       SELECT   pxp.list(acor.nombre) 
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( co.id_acciones))
                                    END )
                                END )AS  acciones,
                                co.id_correspondencia,
                                co.tipo
            	from corres.tcorrespondencia co
                left join segu.vpersona pers on pers.id_persona=co.id_persona
                left join param.tinstitucion ins on ins.id_institucion=co.id_institucion
                inner join segu.vusuario vus on vus.id_usuario=co.id_usuario_reg
                 where id_correspondencia_fk = v_parametros.id_correspondencia and estado='borrador_detalle_recibido') LOOP
                v_tipo:='';
                IF (g_registros.tipo='interna')THEN
                   v_tipo:='INTERNA';
                ELSE
                   v_tipo:='EXTERNA';
                END IF;

               v_id_alarma[1]:=param.f_inserta_alarma(g_registros.id_funcionario,
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.referencia||'</font></font><br>
                                                      <br><b>&nbsp;</b>Estimad@:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <br>
                                                      <br><b>&nbsp;</b>Usted tiene Correspondencia '||v_tipo||' con los siguientes datos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <br>
                                                      <b>&nbsp;</b>Nro:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.numero||' </b> <br> 
                                                       <b>&nbsp;</b>Remitente:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.remitente||'</b>  <br> 
                                                      <b>&nbsp;</b>Referencia:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.referencia||' </b> <br> 
                                                      <b>&nbsp;</b>Fecha de Documento:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.fecha_documento||'</b>  <br> 
                                                      <b>&nbsp;</b>Acción:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'||g_registros.acciones||' </b>  <br>',    --descripcion alarmce
                                                
                                                         --descripcion alarmce
                                                    '../../../sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php',--acceso directo
                                                    now()::date,
                                                    'notificacion',
                                                    '',   -->
                                                    g_registros.id_usuario_reg,
                                                    'CorrespondenciaRecibida',--clase
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.numero||'</font></font>',--titulo
                                                   
                                                    --'parametros',
                                                    --{filtro_directo:{campo:"plapa.id_proceso_wf",valor:"116477"}} --para sistemas con workflow
                                                    '{filtro_directo:{campo:"cor.id_correspondencia_fk",valor:"'||v_parametros.id_correspondencia||'"},"aux":"'||g_registros.tipo||'"}',
                                                    g_registros.id_usuario_reg,--id_usuario
                                                    'Nueva Correspondencia '||v_tipo||': '||g_registros.numero,
                                                    'rosanavq@gmail.com','',NULL,null,NULL,'si');
   
   
      --busqueda de la alarma generada
      SELECT al.id_alarma
      INTO v_id_alarma_reg
      FROM param.talarma al
      WHERE id_funcionario=g_registros.id_funcionario and titulo ilike '%'||g_registros.numero||'%'
      order by id_alarma desc limit 1;
      
      UPDATE corres.tcorrespondencia
      SET  id_alarma=v_id_alarma_reg
      Where id_correspondencia=g_registros.id_correspondencia;
      
      END LOOP;
      /*Mod Ana Maria por el estado nunca llega */
      --Añadir  un control para verificar si existe algun documento de origen.
      SELECT version
      INTO 	version_origen
      FROM corres.tcorrespondencia
      WHERE id_origen= v_parametros.id_origen;
      
     /*IF (version_origen=0)THEN
      RAISE EXCEPTION '%','FAVOR SUBIR EL DOCUMENTO ORIGINAL ANTES DE REALIZAR LA DERIVACIÓN';
      END IF;*/
     
      IF v_estado = 'borrador_detalle_recibido'
        THEN
          update corres.tcorrespondencia
          set estado = 'pendiente_recibido',
          fecha_ult_derivado = now()::timestamp,
          id_usuario_mod = p_id_usuario,
          fecha_mod = now()
          where id_correspondencia = v_parametros.id_correspondencia;
      
        ELSE
          update corres.tcorrespondencia

          set estado = 'enviado',
     
         -- fecha_ult_derivado = now()::timestamp,
           id_usuario_mod = p_id_usuario,
          fecha_mod = now()
          
          where id_correspondencia = v_parametros.id_correspondencia;
      END IF;

      -- actualiza hijos pendientes de envio

      update corres.tcorrespondencia
      set estado = 'pendiente_recibido',
      fecha_ult_derivado = now()::timestamp,
      id_usuario_mod = p_id_usuario,
      fecha_mod = now()
      where id_correspondencia_fk = v_parametros.id_correspondencia and
            estado = 'borrador_detalle_recibido';

     
     
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia derivada(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);
    
      return v_resp;

    end;

    /*********************************
     #TRANSACCION:  'CO_CORUNDO_UPD'
     #DESCRIPCION:    Corregir correspondecia si no tiene hijos abiertos
     #AUTOR:        rac
     #FECHA:  27-02-2012 21:00
    ***********************************/

    elsif(p_transaccion='CO_CORUNDO_UPD')then

    begin
          
    
      /*  verifica que tenga hijos con estado borrador detalle recibido
    */
          IF(exists (
            select 1
            from corres.tcorrespondencia c
            where c.id_correspondencia_fk = v_parametros.id_correspondencia and
                  (c.estado = 'recibido' or c.estado='enviado')
                  )) THEN

            raise exception
              'Existen destinatarios que ya recibieron la correpondencia no se puede corregir'
              ;

          END IF;
     
     select estado_ant
      into v_estado_aux
      from corres.tcorrespondencia_estado 
      where estado_reg='activo'
      and id_correspondencia=v_parametros.id_correspondencia;

      update corres.tcorrespondencia
      set estado = v_estado_aux,
      id_usuario_mod = p_id_usuario,
      fecha_mod = now()
      where id_correspondencia = v_parametros.id_correspondencia;

      -- actualiza hijos pendientes de envio

     

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia corregida');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
 /*********************************
     #TRANSACCION:  'CO_CORUNDOEXT_UPD'
     #DESCRIPCION:    Corregir correspondecia externa si no tiene hijos abiertos
     #AUTOR:        avq
     #FECHA:  11/04/2018 14:00
    ***********************************/

    elsif(p_transaccion='CO_CORUNDOEXT_UPD')then

    begin
      /*
            verifica que tenga hijos con estado borrador detalle recibido


            */
     IF (v_parametros.interfaz !='administrador')THEN
      IF(exists (
        select 1
        from corres.tcorrespondencia c
        where c.id_correspondencia_fk = v_parametros.id_correspondencia and
              c.estado = 'pendiente_recibido')) THEN

        raise exception
          'Existen destinatarios que ya recibieron la correspondencia NO SE PUEDE CORREGIR'
          ;

      END IF;
     END IF;
     
      --actualiza padre
 IF (v_parametros.interfaz !='administrador')THEN
      
      update corres.tcorrespondencia
      set estado = 'borrador_recepcion_externo',
          id_usuario_mod = p_id_usuario,
          fecha_mod = now()     
      where id_correspondencia = v_parametros.id_correspondencia;

      -- actualiza hijos pendientes de envio
     
      update corres.tcorrespondencia
      set estado = 'borrador_detalle_recibido',
           id_usuario_mod = p_id_usuario,
          fecha_mod = now()
      where id_correspondencia_fk = v_parametros.id_correspondencia and
            estado = 'pendiente_recibido';
            
     ELSE
          update corres.tcorrespondencia
          set estado = 'pendiente_recepcion_externo', 
              observaciones_estado=observaciones_estado ||'-'||v_parametros.observaciones,
              id_usuario_mod = p_id_usuario,
              fecha_mod = now()           
          where id_correspondencia = v_parametros.id_correspondencia;
         
       
          
      END IF;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia corregida');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

    /*********************************
     #TRANSACCION:  'CO_CORDET_INS'
     #DESCRIPCION:    Insercion de registros como detalle de correspondencia
     #AUTOR:        mzm
     #FECHA:            14-02-2012 20:43:21
    ***********************************/

    elsif(p_transaccion='CO_CORDET_INS')then

    begin
  
      select *
      into v_datos_maestro
      from corres.tcorrespondencia
      where id_correspondencia = v_parametros.id_correspondencia;

      --RAISE EXCEPTION '%',v_datos_maestro.estado;
     
      if v_datos_maestro.estado = 'pendiente_recibido'
      --if v_datos_maestro.estado != 'enviado'  
        THEN
        RAISE EXCEPTION '%',
          'No puedes agregar nuevos por que aun no finalizaste esta correspondencia'
          ;
      END IF ;
       
     /* if v_datos_maestro.estado = 'recibido'
        THEN
        update corres.tcorrespondencia
        set estado = 'recibido_derivacion'
        where id_correspondencia = v_parametros.id_correspondencia_fk;

      end if;*/
      select id_origen
      into v_id_origen
      from corres.tcorrespondencia
      where id_correspondencia = v_parametros.id_correspondencia;
   --  RAISE EXCEPTION '%','id_funcionario '|| v_datos_maestro.id_depto;
 
      v_resp_cm=corres.f_proc_mul_cmb_empleado(
        v_parametros.id_funcionario,
        v_parametros.id_correspondencia::INTEGER,
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
        v_datos_maestro.id_depto

      );
 
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia eliminado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
  /*********************************
     #TRANSACCION:  'CO_CORDET_MOD'
     #DESCRIPCION:    Modificación de registros como detalle de correspondencia
     #AUTOR:          fpc
     #FECHA:          14-12-2017 20:43:21
    ***********************************/

    elsif(p_transaccion='CO_CORDET_MOD')then

    begin
         select estado,estado_corre
         into v_estado,v_estado_corre
         from corres.tcorrespondencia
         where estado_reg='activo'
         and id_correspondencia = v_parametros.id_correspondencia;
         
         select vus.desc_persona,dus.id_usuario
         into v_responsable,v_id_usuario
         from param.tdepto_usuario dus
         inner join segu.vusuario vus on vus.id_usuario= dus.id_usuario
         where dus.estado_reg='activo';
       -- raise exception '%',v_estado;
   
     -- IF (v_id_usuario != p_id_usuario)THEN  
          IF (v_estado = 'enviado' and v_estado!='borrador_corre')
            THEN
            RAISE EXCEPTION '%',
              'No puede editar el registro porque ya se derivo la correspondencia, comuniquese con el Administrador de Correspondencia '||v_responsable;
          END IF ;
     -- END IF;
       
       update corres.tcorrespondencia
        set mensaje = v_parametros.mensaje,
            id_acciones = string_to_array(v_parametros.id_acciones,',')::integer[],
            fecha_mod = now(),
            id_funcionario=v_parametros.id_funcionario,
            id_usuario_mod = p_id_usuario
        where id_correspondencia = v_parametros.id_correspondencia;
        v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia modificado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;   

    /*********************************
#TRANSACCION:  'CO_CORFIN_INS'
#DESCRIPCION:    Insercion de registros como detalle de correspondencia
#AUTOR:        mzm
#FECHA:            14-02-2012 20:43:21
***********************************/

    elsif(p_transaccion='CO_CORFIN_INS')then

    begin
      UPDATE corres.tcorrespondencia
      set estado = 'recibido',
          id_usuario_mod = p_id_usuario,
          fecha_mod = now()
      WHERE id_correspondencia = v_parametros.id_correspondencia;
      --AVQ
      --Correspondencia Recibida eso significa que se actualizará la alarma en un estado inactivo para que no le muestre en la ventanitas de alarma
      
   --   raise exception '%',v_parametros.id_correspondencia;
     /* UPDATE param.talarma
      SET tipo='comunicado'
      WHERE id_alarma  in (select id_alarma 
                           from corres.tcorrespondencia
                           where id_correspondencia=v_parametros.id_correspondencia);*/
                           
      
      -- raise exception 'resp%',v_resp_cm;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia eliminado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
/*********************************
#TRANSACCION:  'CO_CORARCH_INS'
#DESCRIPCION:    archiva o desarchiva la correspondencia
#AUTOR:        favio figueroa
#FECHA:            14-02-2016 20:43:21
***********************************/

    elsif(p_transaccion='CO_CORARCH_INS')then

    begin
      Select coalesce(observaciones_archivado,'')
      into v_observaciones_archivado
      From corres.tcorrespondencia 
      where id_correspondencia=v_parametros.id_correspondencia;
      
      UPDATE corres.tcorrespondencia
      set sw_archivado = v_parametros.sw_archivado,
      id_usuario_mod = p_id_usuario,
      fecha_mod = now(),
      observaciones_archivado =v_observaciones_archivado||'-'||v_parametros.observaciones_archivado
      WHERE id_correspondencia = v_parametros.id_correspondencia;

      -- raise exception 'resp%',v_resp_cm;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia archivado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
    /*********************************
 #TRANSACCION:  'CO_CORESTFIS_INS'
 #DESCRIPCION:    cambia el estado de la correspondencia fisica
 #AUTOR:        favio figueroa
 #FECHA:            27-04-2016 20:43:21
 ***********************************/

    elsif(p_transaccion='CO_CORESTFIS_INS')then

    begin

    
      UPDATE corres.tcorrespondencia
      set estado_fisico = v_parametros.estado_fisico,
       id_usuario_mod = p_id_usuario,
          fecha_mod = now()
      WHERE id_correspondencia = v_parametros.id_correspondencia;

      -- raise exception 'resp%',v_resp_cm;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia fisico(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;

    /*********************************
 #TRANSACCION:  'CO_COREXT_INS'
 #DESCRIPCION:    inserta el mensajero la correspondencia externa recibida(ENTRANTE)
 #AUTOR:        favio figueroa
 #FECHA:            27-04-2016 20:43:21
 ***********************************/

    elsif(p_transaccion='CO_COREXT_INS')then

    begin

      --TODO, el departamento lo debe definir el usuario en correspondencia externa

      --   obtener documento

      SELECT d.codigo
      into v_codigo_documento
      FROM param.tdocumento d
      WHERE d.id_documento = v_parametros.id_documento;
      --Validar cite 
     IF EXISTS( SELECT 1 
                FROM corres.tcorrespondencia
                WHERE 
                id_institucion=v_parametros.id_institucion_remitente AND

                cite like '%'||v_parametros.cite||'%' and (v_parametros.cite!=null or v_parametros.cite!=''))THEN

      RAISE EXCEPTION '%','EXISTE UN CITE IDENTICO DE LA EMPRESA QUE ACTUALMENTE ESTA REGISTRANDO, FAVOR VERIFICAR DATOS.';

      END IF;
      
      --Validar la fecha del Documento.
      if (v_parametros.fecha_creacion_documento is not null) then
      
            select p.id_periodo, p.fecha_ini, p.fecha_fin
            into v_id,v_fecha_ini,v_fecha_fin
            from param.tperiodo p
            inner join param.tgestion ges 
            on ges.id_gestion = p.id_gestion 
            and ges.estado_reg ='activo'
            where p.estado_reg='activo' and
           v_parametros.fecha_creacion_documento between p.fecha_ini and p.fecha_fin ;
           
            --Validar la fecha del Documento.
            IF (v_parametros.tipo='externa') THEN
               IF (EXISTS(select 1
            			from corres.tcorrespondencia cor
           				where cor.fecha_creacion_documento::date > v_parametros.fecha_creacion_documento::date
                		and 
                        tipo=v_parametros.tipo AND cor.fecha_creacion_documento::date between v_fecha_ini and v_fecha_fin
                    ))THEN
                 RAISE EXCEPTION '%', 'Existe un Documento Mayor a la fecha '||v_parametros.fecha_creacion_documento;
                
                END IF;
               
            ELSE
                IF (EXISTS(select 1
            			from corres.tcorrespondencia cor
           				where cor.fecha_creacion_documento::date > v_parametros.fecha_creacion_documento::date
                		and 
                        tipo=v_parametros.tipo and id_documento=v_parametros.id_documento and  cor.fecha_creacion_documento::date between v_fecha_ini and v_fecha_fin
                    ))THEN
                 RAISE EXCEPTION '%', 'Existe un Documento Mayor a la fecha '||v_parametros.fecha_creacion_documento;
                
                END IF;
            
            END IF;  
            
          
                       v_fecha_creacion_documento=v_parametros.fecha_creacion_documento+('22:25:10.222234');
 
            v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,v_id,NULL,
            v_parametros.id_depto, p_id_usuario,'CORRES',NULL);
            
        
      else
            v_fecha_creacion_documento=now();
             v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,NULL,NULL,
             v_parametros.id_depto, p_id_usuario,'CORRES',NULL);
      end if;
     -- v_num_corre =  param.f_obtener_correlativo(v_codigo_documento,NULL,NULL,
     

      --1)obtiene el identificador de la gestion

      select g.id_gestion
      into v_id_gestion
      from param.tgestion g
      where g.estado_reg = 'activo' and
            g.gestion = to_char(now()::date, 'YYYY')::integer;

      --2 obtener el identificar del periodo
 
      IF (v_id is null) THEN
          select p.id_periodo
            into v_id_periodo
            from param.tperiodo p
                 inner join param.tgestion ges on ges.id_gestion = p.id_gestion and
                   ges.estado_reg = 'activo'
            where p.estado_reg = 'activo' and
                  now()::date between p.fecha_ini and
                  p.fecha_fin;
          
      ELSE
           v_id_periodo:=v_id;
      END IF;
      
      --validar que tenga o persona o intitucion

      IF v_parametros.id_institucion_remitente is null and
        v_parametros.id_persona_remitente is null THEN
        raise exception
          'Por lo menos debe definir una intitución o persona remitente';
      END IF;

      --3 Sentencia de la insercion
      IF v_parametros.id_correspondencias_asociadas = '' THEN
      v_id_correspondencias_asociadas=NULL;
      else
      v_id_correspondencias_asociadas=string_to_array(v_parametros.id_correspondencias_asociadas, ',')::integer [ ];
      END IF;
      -- Obtenemos el origen (institucion o persona)
      IF (v_parametros.id_institucion_remitente is null) THEN
      	SELECT per.nombre_completo1
        into v_origen
        FROM segu.vpersona2 per
        WHERE per.id_persona = v_parametros.id_persona_remitente;
    
      ELSE
      	SELECT insti.nombre
        into v_origen
        FROM param.tinstitucion insti
        WHERE insti.id_institucion = v_parametros.id_institucion_remitente;
      
      END IF;
      
        insert into corres.tcorrespondencia(estado, estado_reg, fecha_documento,
                    id_correspondencias_asociadas, id_depto, id_documento, id_funcionario,
                    -- funcionario peude ser nullo
                    id_gestion, id_institucion, id_periodo, id_persona, id_uo,
                    mensaje, nivel, nivel_prioridad, numero, referencia, tipo,
                    fecha_reg, id_usuario_reg, fecha_mod, id_usuario_mod,
                    id_clasificador, nro_paginas, otros_adjuntos, cite,
                    origen, fecha_creacion_documento,
                    --persona_firma,
                    tipo_documento)
        values ('borrador_recepcion_externo', 'activo',
          		v_parametros.fecha_documento, v_id_correspondencias_asociadas,
          		v_parametros.id_depto, v_parametros.id_documento, NULL,
               	-- en correpondencia externa el funcionario es NULO , v_parametros.id_funcionario_usuario,
               	v_id_gestion, v_parametros.id_institucion_remitente,
                v_id_periodo, v_parametros.id_persona_remitente, v_id_uo [ 2 ],
                v_parametros.mensaje, 0, --nivel de anidamiento del arbol
               	v_parametros.nivel_prioridad, v_num_corre,
                v_parametros.referencia, 'externa', now(), p_id_usuario, null,
                null, v_parametros.id_clasificador, v_parametros.nro_paginas,
                v_parametros.otros_adjuntos, v_parametros.cite, v_origen,
                v_fecha_creacion_documento,
               	--v_parametros.persona_firma,
               	v_parametros.tipo_documento) RETURNING id_correspondencia
        into v_id_correspondencia;

      v_id_origen = v_id_correspondencia;
      
      UPDATE corres.tcorrespondencia
      set id_origen = v_id_correspondencia
      where id_correspondencia = v_id_correspondencia;
  
     --Definicion de la respuesta	
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia externa recepcionada(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
  /*********************************
 #TRANSACCION:  'CO_COREXT_MOD'
 #DESCRIPCION:    modifica el mensajero la correpondencia externa recibida(ENTRANTE)
 #AUTOR:        fprudencio
 #FECHA:            25-11-2017 20:43:21
 ***********************************/    
elsif(p_transaccion='CO_COREXT_MOD')then

    begin
      --obtenemos estado de correpondencia

      select estado,estado_corre
      into v_estado,v_estado_corre
      from corres.tcorrespondencia c
      where c.id_correspondencia = v_parametros.id_correspondencia;

      if(v_estado = 'borrador_recepcion_externo' OR v_estado = 'borrador_envio'  OR v_estado = 'pendiente_recepcion_externo' OR v_estado_corre='borrador_corre') then
	       	
      IF(v_parametros.id_correspondencias_asociadas='')THEN
           v_id_correspondencias_asociadas=NULL;
      ELSE
           v_id_correspondencias_asociadas_aux:=ltrim(rtrim(v_parametros.id_correspondencias_asociadas,'}'),'{');
           v_id_correspondencias_asociadas=string_to_array(v_id_correspondencias_asociadas_aux, ',')::integer [ ];
           
      END IF;
      --Validar cite
      IF EXISTS( SELECT 1 
                FROM corres.tcorrespondencia
                WHERE 
                id_institucion=v_parametros.id_institucion_remitente AND

                cite like '%'||v_parametros.cite||'%' AND id_correspondencia != v_parametros.id_correspondencia and (v_parametros.cite!=null or v_parametros.cite!=''))THEN


      RAISE EXCEPTION '%','EXISTE UN CITE IDENTICO DE LA EMPRESA QUE ACTUALMENTE ESTA REGISTRANDO, FAVOR VERIFICAR DATOS.';

      END IF;
      IF (v_parametros.id_institucion_remitente is null) THEN
      	SELECT per.nombre_completo1
        into v_origen
        FROM segu.vpersona2 per
        WHERE per.id_persona = v_parametros.id_persona_remitente;
    
      ELSE
      	SELECT insti.nombre
        into v_origen
        FROM param.tinstitucion insti
        WHERE insti.id_institucion = v_parametros.id_institucion_remitente;
      
      END IF;
        --Sentencia de la modificacion

        update corres.tcorrespondencia
        set -- tipo = v_parametros.tipo,
           -- id_documento = v_parametros.id_documento,
            fecha_documento = v_parametros.fecha_documento,
            id_institucion = v_parametros.id_institucion_remitente,
            id_persona = v_parametros.id_persona_remitente,
            referencia = v_parametros.referencia,
            mensaje = v_parametros.mensaje,
            id_correspondencias_asociadas = v_id_correspondencias_asociadas,
            nivel_prioridad = v_parametros.nivel_prioridad,
            id_clasificador = v_parametros.id_clasificador,
           -- id_depto = v_parametros.id_depto,
            nro_paginas = v_parametros.nro_paginas,
            otros_adjuntos = v_parametros.otros_adjuntos,
            cite = v_parametros.cite,
            fecha_mod = now(),
            id_usuario_mod = p_id_usuario,
           -- persona_firma=v_parametros.persona_firma,
            tipo_documento=v_parametros.tipo_documento
            --origen = v_origen
        where id_correspondencia = v_parametros.id_correspondencia;

       

        else

        raise exception 'No se puede editar, el estado no es Borrador';

      end if;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia modificado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
    /*********************************    
     #TRANSACCION:  'SCO_GETQR_MOD'
     #DESCRIPCION:  Recupera codigo QR segun configuracion de variable global
     #AUTOR:        MANU
     #FECHA:        10/09/2017
    ***********************************/
	elsif(p_transaccion='SCO_GETQR_MOD')THEN
    	begin
          SELECT docume.descripcion,cor.referencia,to_char(cor.fecha_creacion_documento,'dd-mm-yyyy HH24:MI:SS') as fecha_reg,cor.numero,cor.tipo  
          INTO v_rec_co
          FROM corres.tcorrespondencia cor
          INNER JOIN param.tdocumento docume ON docume.id_documento = cor.id_documento
          WHERE cor.id_correspondencia = v_parametros.id_correspondencia;        
          
          select t.nombre 
          INTO v_rec_co_1
          from param.tempresa t
          limit 1;    
          --Definicion de la respuesta        
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Código recuperado');
          v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'referencia',v_rec_co.referencia::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'fecha_reg',v_rec_co.fecha_reg::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'numero',v_rec_co.numero::varchar);         
          v_resp = pxp.f_agrega_clave(v_resp,'tipo',v_rec_co.tipo::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'nombre',v_rec_co_1.nombre::varchar);
          --Recuperar configuracion del reporte de codigo de barrar por defecto de variable global                 
          v_clase_reporte = pxp.f_get_variable_global('corres_clase_reporte_codigo');
          v_resp = pxp.f_agrega_clave(v_resp,'v_clase_reporte',COALESCE(v_clase_reporte,'RCodigoQRCORR')::varchar); 
          --
        return v_resp;
	end; 
    /*********************************    
     #TRANSACCION:  'SCO_GETQR_L_MOD'
     #DESCRIPCION:  Recupera codigo QR segun configuracion de variable global
     #AUTOR:        MANU
     #FECHA:        10/09/2017
    ***********************************/
	elsif(p_transaccion='SCO_GETQR_L_MOD')THEN
    	begin
          SELECT docume.descripcion,cor.referencia,to_char(cor.fecha_reg,'dd-mm-yyyy HH24:MI:SS') as fecha_reg,cor.numero,cor.tipo
          INTO v_rec_co
          FROM corres.tcorrespondencia cor
          INNER JOIN param.tdocumento docume ON docume.id_documento = cor.id_documento
          WHERE cor.id_correspondencia = v_parametros.id_correspondencia;

          select t.nombre 
          INTO v_rec_co_1
          from param.tempresa t
          limit 1; 
          --Definicion de la respuesta        
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Código recuperado');
          v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',v_parametros.id_correspondencia::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'referencia',v_rec_co.referencia::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'fecha_reg',v_rec_co.fecha_reg::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'numero',v_rec_co.numero::varchar);         
          v_resp = pxp.f_agrega_clave(v_resp,'tipo',v_rec_co.tipo::varchar);
          v_resp = pxp.f_agrega_clave(v_resp,'nombre',v_rec_co_1.nombre::varchar);
          --Recuperar configuracion del reporte de codigo de barrar por defecto de variable global                 
          v_clase_reporte = pxp.f_get_variable_global('corres_clase_reporte_codigo_v1');
          v_resp = pxp.f_agrega_clave(v_resp,'v_clase_reporte',COALESCE(v_clase_reporte,'RCodigoQRCORR_v1')::varchar); 
          --
        return v_resp;
	end;
    /*********************************
#TRANSACCION:  'CO_COREXTEST_INS'
#DESCRIPCION:    camba el estado al finalizar la recepcion de la correspondencia externa
#AUTOR:        favio figueroa
#FECHA:            27-04-2016 20:43:21
***********************************/

    elsif(p_transaccion='CO_COREXTEST_INS')then

    begin
      UPDATE corres.tcorrespondencia
      SET estado = v_parametros.estado,
          id_usuario_mod = p_id_usuario,
          fecha_mod = now()
      WHERE id_correspondencia = v_parametros.id_correspondencia;
      
      --AVQ
      --Correspondencia Recibida eso significa que se actualizará la alarma en un estado inactivo para que no le muestre en la ventanitas de alarma
      -- Inserta en una tabla alarma
      INSERT INTO corres.talarma (SELECT * FROM param.talarma WHERE id_alarma in (select id_alarma 
                          																  from corres.tcorrespondencia
                                                                                          where id_correspondencia_fk=v_parametros.id_correspondencia) );
      -- Elimina la fila de la tabla param.talarma.
      
      DELETE FROM param.talarma where id_alarma in (select id_alarma 
                          							 from corres.tcorrespondencia
                                                     where id_correspondencia=v_parametros.id_correspondencia);
     -- raise exception '%',v_parametros.id_correspondencia;
   
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia recepcionada finalizado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
/*********************************
#TRANSACCION:  'CO_CORHAB_INS'
#DESCRIPCION:    habilita una correspondencia anulada externa
#AUTOR:        fernando
#FECHA:            01/08/2018
***********************************/

    elsif(p_transaccion='CO_CORHAB_INS')then

    begin
       WITH RECURSIVE corres_asoc(
    				id_corres_asoc,
    				estado,
    				id_corres_padre) AS(
  						SELECT c1.id_correspondencia,
         				       c1.estado,
         					   c1.id_correspondencia_fk
  						FROM corres.tcorrespondencia c1
  						WHERE c1.id_correspondencia_fk = v_parametros.id_correspondencia AND
        					  c1.estado::text = 'anulado'::text
  						UNION
  						SELECT c2.id_correspondencia,
         					   c2.estado,
         					   c2.id_correspondencia_fk
  						FROM corres.tcorrespondencia c2,
       						 corres_asoc ca
  						WHERE c2.id_correspondencia_fk = ca.id_corres_asoc AND
        				c2.estado::text = 'anulado'::text)
                        
                        
-- Consulta para cambia el estado a inactivo de los descendientes
			  UPDATE corres.tcorrespondencia
              SET estado_reg='activo',
              estado='borrador_detalle_recibido',
              observaciones_estado='Activado por el usuario '||p_id_usuario,
              fecha_mod = now(),
        	  id_usuario_mod = p_id_usuario
              WHERE id_correspondencia IN (SELECT cc.id_corres_asoc FROM corres_asoc cc);

      -- Cambiamos el estado de la raiz a anulado
  
      --Verificacamos si es externa.
       IF (v_parametros.tipo_corres='externa')THEN
     
      	UPDATE corres.tcorrespondencia
        set 
        estado_reg = 'activo',
        estado = 'borrador_recepcion_externo',
        observaciones_estado='Activado por el usuario '||p_id_usuario,
        fecha_mod = now(),
        id_usuario_mod = p_id_usuario
        WHERE id_correspondencia = v_parametros.id_correspondencia;
      -- raise exception 'resp%',v_resp_cm;
      ELSE
        UPDATE corres.tcorrespondencia
        set 
        estado_reg = 'activo',
        estado = 'borrador_envio',
        observaciones_estado='Activado por el usuario '||p_id_usuario,
        fecha_mod = now(),
        id_usuario_mod = p_id_usuario
        WHERE id_correspondencia = v_parametros.id_correspondencia;
      
      END IF;
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia Habilitado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
    
    
 /*********************************
 #TRANSACCION:  'CO_COR_ANU'
 #DESCRIPCION:    cambia el estado de la correspondencia fisica
 #AUTOR:          fpc
 #FECHA:          07-01-2018 20:43:21
 ***********************************/

    elsif(p_transaccion='CO_COR_ANU')then

    begin
	  SELECT estado INTO v_estado
      FROM corres.tcorrespondencia
      WHERE id_correspondencia=v_parametros.id_correspondencia;
      
      if (v_estado != 'enviado') THEN
       raise exception 'No se puede anular el estado no es enviado';
      end IF ;
      -- Consulta recursiva que obitiene todos los nodos descendientes de la raiz.
      
      WITH RECURSIVE corres_asoc(
    				id_corres_asoc,
    				estado,
    				id_corres_padre) AS(
  						SELECT c1.id_correspondencia,
         				       c1.estado,
         					   c1.id_correspondencia_fk
  						FROM corres.tcorrespondencia c1
  						WHERE c1.id_correspondencia_fk = v_parametros.id_correspondencia AND
        					  c1.estado_reg::text = 'activo'::text
  						UNION
  						SELECT c2.id_correspondencia,
         					   c2.estado,
         					   c2.id_correspondencia_fk
  						FROM corres.tcorrespondencia c2,
       						 corres_asoc ca
  						WHERE c2.id_correspondencia_fk = ca.id_corres_asoc AND
        				c2.estado_reg::text = 'activo'::text)
-- Consulta para cambia el estado a inactivo de los descendientes
			  UPDATE corres.tcorrespondencia
              SET estado_reg='inactivo',
              estado='anulado',
              observaciones_estado='anulado por el usuario',
              fecha_mod = now(),
        	  id_usuario_mod = p_id_usuario
              WHERE id_correspondencia IN (SELECT cc.id_corres_asoc FROM corres_asoc cc);

      -- Cambiamos el estado de la raiz a anulado

      	UPDATE corres.tcorrespondencia
        set estado = 'anulado',
        estado_reg = 'inactivo',
        observaciones_estado='anulado por el usuario',
        fecha_mod = now(),
        id_usuario_mod = p_id_usuario
        WHERE id_correspondencia = v_parametros.id_correspondencia;
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia fisico(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;
 /*********************************
#TRANSACCION:  'CO_ALAR_ANU'
#DESCRIPCION:    Eliminar todas las alarmas que fueron por anulación
#AUTOR:        Ana Maria Villegas Quispe
#FECHA:           07/11/2013 16:29
***********************************/

    elsif(p_transaccion='CO_ALAR_ANU')then

    begin
        
      --AVQ
      --Correspondencia Recibida eso significa que se actualizará la alarma en un estado inactivo para que no le muestre en la ventanitas de alarma
      -- Inserta en una tabla alarma
      INSERT INTO corres.talarma (SELECT * FROM param.talarma WHERE 
                                   correos ilike '%anulacion@gmail.com%' 
                                   --and estado_envio='exito' 
                                  --and pendiente ='no' and sw_correo=1 
                                    );
      -- Elimina la fila de la tabla param.talarma.
      
      DELETE FROM param.talarma where id_alarma in (SELECT id_alarma FROM param.talarma WHERE 
                                  					correos ilike '%anulacion@gmail.com%' 
                                                    --and estado_envio='exito' 
                                                     --and pendiente ='no' and sw_correo=1
                                                     );
     -- raise exception '%',v_parametros.id_correspondencia;
   
      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje',
        'Correspondencia recepcionada finalizado(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

      --Devuelve la respuesta
      return v_resp;

    end;


    /*********************************
     #TRANSACCION:  'CO_HABCORR_UPD'
     #DESCRIPCION:    Habilitar para la correccion de Administración
     #AUTOR:        avq
     #FECHA:  06/12/2018
    ***********************************/

    elsif(p_transaccion='CO_HABCORR_UPD')then

    begin
          
    
      /*  verifica que sus hijos no hayan derivado la correspondencia
    */
      /*    IF(exists (
            select 1
            from corres.tcorrespondencia c
            where c.id_correspondencia_fk = v_parametros.id_correspondencia and
                  ( c.estado='enviado' and c.estado_corre !='borrador_corre')
                  )) THEN

            raise exception
              'Existen destinatarios que ya Enviarón la correspondencia, es por tal razón que  no se puede corregir'
              ;

          END IF;*/
     
   /*   select estado_ant
      into v_estado_aux
      from corres.tcorrespondencia_estado 
      where estado_reg='activo'
      and id_correspondencia=v_parametros.id_correspondencia;*/

      update corres.tcorrespondencia
      set estado_corre = v_parametros.estado_corre,
      id_usuario_mod = p_id_usuario,
      observaciones_estado=v_parametros.observaciones_estado,
      fecha_mod = now()
      where id_correspondencia = v_parametros.id_correspondencia;
      
      --Actualiza los hijos
      update corres.tcorrespondencia
      set estado_corre = v_parametros.estado_corre,
      id_usuario_mod = p_id_usuario,
      observaciones_estado=v_parametros.observaciones_estado,
     
      fecha_mod = now()
      where id_correspondencia_fk = v_parametros.id_correspondencia;
	
   	  /*aca definimos si esta finalizando, mandara el mail*/	
      if v_parametros.estado_corre = 'corregido' THEN
      
      	FOR g_registros IN ( select co.id_funcionario,co.id_usuario_reg,co.numero,
                              vus.desc_persona,
                              coalesce(co.referencia,'') as referencia,
                              co.fecha_documento,
                              co.fecha_reg,
                              coalesce(co.origen,'') as remitente,
                              (CASE WHEN (co.id_acciones is not null) then

                                  (CASE WHEN (array_upper(co.id_acciones,1) is  not null) then
                                      (
                                       SELECT   pxp.list(acor.nombre) 
                                       FROM corres.taccion acor
                                       WHERE acor.id_accion = ANY ( co.id_acciones))
                                    END )
                                END )AS  acciones,
                                co.id_correspondencia,
                                co.tipo
            	from corres.tcorrespondencia co
                left join segu.vpersona pers on pers.id_persona=co.id_persona
                left join param.tinstitucion ins on ins.id_institucion=co.id_institucion
                inner join segu.vusuario vus on vus.id_usuario=co.id_usuario_reg
                 where id_correspondencia_fk = v_parametros.id_correspondencia and co.estado ='pendiente_recibido') LOOP
            
                v_tipo:='';
                IF (g_registros.tipo='interna')THEN
                   v_tipo:='INTERNA';
                ELSE
                   v_tipo:='EXTERNA';
                END IF;
	       --EAQ: agregacion de parametros a insertar en talarma, para acceso directo
               v_id_alarma[1]:=param.f_inserta_alarma(g_registros.id_funcionario,
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.referencia||'</font></font><br>
                                                      <br><b>&nbsp;</b>Estimad@:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; <br>
                                                      <br><b>&nbsp;</b>Anulación de la Correspondencia '||v_tipo||' con los siguientes datos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;  <br>
                                                      <b>&nbsp;</b>Nro:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.numero||' </b> <br> 
                                                       <b>&nbsp;</b>Remitente:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.remitente||'</b>  <br> 
                                                      <b>&nbsp;</b>Referencia:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.referencia||' </b> <br> 
                                                      <b>&nbsp;</b>Fecha de Documento:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '||g_registros.fecha_documento||'</b>  <br> 
                                                      <b>&nbsp;</b>Acción:<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'||g_registros.acciones||' </b>  <br>',    --descripcion alarmce
                                                
                                                         --descripcion alarmce
                                                    '../../../sis_correspondencia/vista/correspondencia/CorrespondenciaRecibida.php',--acceso directo
                                                    now()::date,
                                                    'notificacion',
                                                    '',   -->
                                                    g_registros.id_usuario_reg,
                                                    'CorrespondenciaRecibida',--clase
                                                    '<font color="99CC00" size="5"><font size="4">'||g_registros.numero||'</font></font>',--titulo
                                                   --'parametros',  
						   --{filtro_directo:{campo:"plapa.id_proceso_wf",valor:"116477"}} --para sistemas con workflow
                                                    '{filtro_directo:{campo:"cor.id_correspondencia_fk",valor:"'||v_parametros.id_correspondencia||'"}}',
                                                    g_registros.id_usuario_reg,--id_usuario
                                                    'Modificación de la Correspondencia '||v_tipo||': '||g_registros.numero,
                                                    'correspondencia@endecorani.bo','',NULL,null,NULL,'si');						   
                                                   
     
     		 END LOOP;
      end if;


      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Correspondencia Habilitada para Corrección');
      v_resp = pxp.f_agrega_clave(v_resp,'id_correspondencia',
        v_parametros.id_correspondencia::varchar);

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
