CREATE OR REPLACE FUNCTION corres.ft_asistente_permisos_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_asistente_permisos_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tasistente_permisos'
 AUTOR: 		 (admin)
 FECHA:	        04-01-2019 12:01:52
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				04-01-2019 12:01:52								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tasistente_permisos'	
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_asistente_permisos	integer;
			    
BEGIN

    v_nombre_funcion = 'corres.ft_asistente_permisos_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_ASIPER_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-01-2019 12:01:52
	***********************************/

	if(p_transaccion='CORRES_ASIPER_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into corres.tasistente_permisos(
			estado_reg,
			id_asistente,
			id_funcionarios_permitidos,
			estado,
			permitir_todo,
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod,
			fecha_mod
          	) values(
			'activo',
			v_parametros.id_asistente,
			v_parametros.id_funcionarios_permitidos,
			v_parametros.estado,
			v_parametros.permitir_todo,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null,
			null
							
			
			
			)RETURNING id_asistente_permisos into v_id_asistente_permisos;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Permisos Asistente almacenado(a) con exito (id_asistente_permisos'||v_id_asistente_permisos||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_asistente_permisos',v_id_asistente_permisos::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ASIPER_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-01-2019 12:01:52
	***********************************/

	elsif(p_transaccion='CORRES_ASIPER_MOD')then

		begin
			--Sentencia de la modificacion
			update corres.tasistente_permisos set
			id_asistente = v_parametros.id_asistente,
			id_funcionarios_permitidos = v_parametros.id_funcionarios_permitidos,
			estado = v_parametros.estado,
			permitir_todo = v_parametros.permitir_todo,
			id_usuario_mod = p_id_usuario,
			fecha_mod = now(),
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai
			where id_asistente_permisos=v_parametros.id_asistente_permisos;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Permisos Asistente modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_asistente_permisos',v_parametros.id_asistente_permisos::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_ASIPER_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		04-01-2019 12:01:52
	***********************************/

	elsif(p_transaccion='CORRES_ASIPER_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from corres.tasistente_permisos
            where id_asistente_permisos=v_parametros.id_asistente_permisos;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Permisos Asistente eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_asistente_permisos',v_parametros.id_asistente_permisos::varchar);
              
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