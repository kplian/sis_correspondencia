CREATE OR REPLACE FUNCTION "corres"."ft_documento_fisico_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_documento_fisico_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tdocumento_fisico'
 AUTOR: 		 (admin)
 FECHA:	        27-04-2016 16:45:39
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
	v_id_documento_fisico	integer;
			    
BEGIN

    v_nombre_funcion = 'corres.ft_documento_fisico_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_DOCFIS_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		27-04-2016 16:45:39
	***********************************/

	if(p_transaccion='CORRES_DOCFIS_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into corres.tdocumento_fisico(
			id_correspondencia,
			id_correspondencia_padre,
			estado,
			estado_reg,
			id_usuario_ai,
			usuario_ai,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_correspondencia,
			v_parametros.id_correspondencia_padre,
			v_parametros.estado,
			'activo',
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			now(),
			p_id_usuario,
			null,
			null
							
			
			
			)RETURNING id_documento_fisico into v_id_documento_fisico;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento Fisico almacenado(a) con exito (id_documento_fisico'||v_id_documento_fisico||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_documento_fisico',v_id_documento_fisico::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_DOCFIS_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		27-04-2016 16:45:39
	***********************************/

	elsif(p_transaccion='CORRES_DOCFIS_MOD')then

		begin
			--Sentencia de la modificacion
			update corres.tdocumento_fisico set
			id_correspondencia = v_parametros.id_correspondencia,
			id_correspondencia_padre = v_parametros.id_correspondencia_padre,
			estado = v_parametros.estado,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_documento_fisico=v_parametros.id_documento_fisico;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento Fisico modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_documento_fisico',v_parametros.id_documento_fisico::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_DOCFIS_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		27-04-2016 16:45:39
	***********************************/

	elsif(p_transaccion='CORRES_DOCFIS_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from corres.tdocumento_fisico
            where id_documento_fisico=v_parametros.id_documento_fisico;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento Fisico eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_documento_fisico',v_parametros.id_documento_fisico::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;


  /*********************************
#TRANSACCION:  'CORRES_DOCFISEST_INS'
#DESCRIPCION:	cambiar estado
#AUTOR:		admin
#FECHA:		27-04-2016 16:45:39
***********************************/

  elsif(p_transaccion='CORRES_DOCFISEST_INS')then

    begin
      --Sentencia de la eliminacion


     update corres.tdocumento_fisico
			 set estado = v_parametros.estado
			 where id_documento_fisico = v_parametros.id_documento_fisico;

      --Definicion de la respuesta
      v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento Fisico '||v_parametros.estado||'(a)');
      v_resp = pxp.f_agrega_clave(v_resp,'id_documento_fisico',v_parametros.id_documento_fisico::varchar);

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
ALTER FUNCTION "corres"."ft_documento_fisico_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
