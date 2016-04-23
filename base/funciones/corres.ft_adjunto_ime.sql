CREATE OR REPLACE FUNCTION "corres"."ft_adjunto_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

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
              FROM json_populate_recordset(NULL :: corres.json_adjuntos_ins, v_parametros.arra_json :: JSON))
          LOOP

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
              fecha_mod
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
              null



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
			update corres.tadjunto set
			extension = v_parametros.extension,
			id_correspondencia_origen = v_parametros.id_correspondencia_origen,
			nombre_archivo = v_parametros.nombre_archivo,
			ruta_archivo = v_parametros.ruta_archivo,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_adjunto=v_parametros.id_adjunto;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Adjunto modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_adjunto',v_parametros.id_adjunto::varchar);
               
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
			delete from corres.tadjunto
            where id_adjunto=v_parametros.id_adjunto;
               
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
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "corres"."ft_adjunto_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
