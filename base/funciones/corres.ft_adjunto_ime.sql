CREATE OR REPLACE FUNCTION corres.ft_adjunto_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_adjunto_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tadjunto'
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

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_adjunto	integer;
  v_registros_json RECORD;
    v_estado                 varchar;
    v_archivado              varchar;
    v_adjunto                record;
    v_adjuntos               record;
    v_estado_corre           varchar;
			    
BEGIN

    v_nombre_funcion = 'corres.ft_adjunto_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_ADJ_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-04-2016 23:13:29
	***********************************/

	if(p_transaccion='CORRES_ADJ_INS')then
					
        begin
            

          FOR v_registros_json
          IN (SELECT *
              FROM json_populate_recordset(NULL::corres.json_adjuntos_ins, v_parametros.arra_json::JSON))

          LOOP
          
               --Verificar los adjuntos que 
              FOR v_adjuntos
              IN (SELECT *
                  FROM corres.tadjunto
                  WHERE id_correspondencia_origen=v_registros_json.id_correspondencia_origen)
              LOOP
                 IF (v_adjuntos.ruta_archivo=v_registros_json.ruta_archivo)THEN
                      RAISE EXCEPTION '%','EL DOCUMENTO A SUBIR YA SE ENCUENTRA EN EL REPOSITORIO.  ';
                 END IF;
              END LOOP;
          

             SELECT estado,sw_archivado,estado_corre
             INTO v_estado,v_archivado,v_estado_corre
             FROM corres.tcorrespondencia
             WHERE id_correspondencia=v_registros_json.id_correspondencia;
             
             IF ((v_estado='enviado' or v_archivado ='si') and (v_estado_corre!='borrador_corre')) THEN 
                 RAISE EXCEPTION '%','EL DOCUMENTO EMITIDO YA HA SIDO REMITIDO, CONSULTE CON ADMINISTRACIÓN DE CORRESPONDENCIA.  ';
             END IF;
             SELECT estado,sw_archivado
             INTO v_estado,v_archivado
             FROM corres.tcorrespondencia
             WHERE id_correspondencia=v_registros_json.id_correspondencia;
                    --  raise exception 'ddd%', v_registros_json.id_correspondencia;
            --Sentencia de la insercion
            insert into corres.tadjunto(
              extension,
              id_correspondencia_origen,
              nombre_archivo,
              estado_reg,
              ruta_archivo,
              id_usuario_ai,
              id_usuario_reg,
              fecha_reg,
              usuario_ai,
              id_usuario_mod,
              fecha_mod,
              id_correspondencia
              
            ) values(
              v_registros_json.extension,
              v_registros_json.id_correspondencia_origen,
              v_registros_json.nombre_archivo,
              'activo',
              v_registros_json.ruta_archivo,
              v_parametros._id_usuario_ai,
              p_id_usuario,
              now(),
              v_parametros._nombre_usuario_ai,
              null,
              null,
              v_registros_json.id_correspondencia
             );

			END LOOP ;

			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Adjunto almacenado(a) con exito ()');
            v_resp = pxp.f_agrega_clave(v_resp,'id_adjunto',v_id_adjunto::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ADJ_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-04-2016 23:13:29
	***********************************/

	elsif(p_transaccion='CORRES_ADJ_MOD')then

		begin
			--Sentencia de la modificacion
           -- raise exception '%','asdfasdfasdf';
          FOR v_registros_json
          IN (SELECT *
              FROM json_populate_recordset(NULL :: corres.json_adjuntos_mod, v_parametros.arra_json :: JSON))
          LOOP
              SELECT estado,sw_archivado
             INTO v_estado,v_archivado
             FROM corres.tcorrespondencia
             WHERE id_correspondencia=v_registros_json.id_correspondencia;
             
             IF (v_estado='enviado' or v_archivado='si') THEN 
                 RAISE EXCEPTION '%','EL DOCUMENTO EMITIDO YA HA SIDO REMITIDO, CONSULTE CON ADMINISTRACIÓN DE CORRESPONDENCIA.  ';
             END IF;
             
             select *
             INTO  v_adjunto
             from corres.tadjunto
             where id_adjunto = v_registros_json.id_adjunto;
              
             IF (v_adjunto.id_correspondencia != v_registros_json.id_correspondencia) THEN 
                 RAISE EXCEPTION '%','EL Adjunto no puede ser Modificado ya que corresponde a otra Derivación, CONSULTE CON ADMINISTRACIÓN DE CORRESPONDENCIA.  ';
             END IF;
             
              update corres.tadjunto set
              extension = v_registros_json.extension,
              id_correspondencia_origen = v_registros_json.id_correspondencia_origen,
              nombre_archivo = v_registros_json.nombre_archivo,
              ruta_archivo = v_registros_json.ruta_archivo,
              id_usuario_mod = p_id_usuario,
              fecha_mod = now()
              where id_adjunto=v_registros_json.id_adjunto;
           END LOOP;    
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Adjunto modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_adjunto',v_registros_json.id_adjunto::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ADJ_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		22-04-2016 23:13:29
	***********************************/

	elsif(p_transaccion='CORRES_ADJ_ELI')then

		begin
			--Sentencia de la eliminacion
        /*    FOR v_registros_json
          IN (SELECT *
              FROM json_populate_recordset(NULL :: corres.json_adjuntos_mod, v_parametros.arra_json :: JSON))
          LOOP*/
             select *
             INTO  v_adjunto
             from corres.tadjunto
             where id_adjunto = v_parametros.id_adjunto;
             
            /* SELECT estado,sw_archivado
             INTO v_estado,v_archivado
             FROM corres.tcorrespondencia
             WHERE id_correspondencia=v_adjunto.id_correspondencia;
                raise exception '%',''||v_adjunto.id_correspondencia;
             IF (v_estado='enviado' or v_archivado='si') THEN 
                 RAISE EXCEPTION '%','EL DOCUMENTO EMITIDO YA HA SIDO REMITIDO, CONSULTE CON ADMINISTRACIÓN DE CORRESPONDENCIA.  ';
             END IF;*/
             
             
              
             IF (v_adjunto.id_usuario_reg != p_id_usuario) THEN 
                 RAISE EXCEPTION '%','EL Adjunto no puede ser Eliminado ya que corresponde a otra persona, CONSULTE CON ADMINISTRACIÓN DE CORRESPONDENCIA.  ';
             END IF;
             
                UPDATE corres.tadjunto
                SET estado_reg='inactivo',
                ruta_archivo=v_adjunto.ruta_archivo||'Anulado'
                WHERE id_adjunto=v_parametros.id_adjunto;
               
         --   END LOOP;       
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Adjunto eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_adjunto',v_parametros.id_adjunto::varchar);
              
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