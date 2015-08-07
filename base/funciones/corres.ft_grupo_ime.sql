CREATE OR REPLACE FUNCTION corres.ft_grupo_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS'
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_grupo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla ''corres.tgrupo''
 AUTOR: 		 (rac)
 FECHA:	        10-01-2012 15:55:06
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
	v_id_grupo	integer;
			    
BEGIN

    v_nombre_funcion = ''corres.ft_grupo_ime'';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  ''CO_GRU_INS''
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 15:55:06
	***********************************/

	if(p_transaccion=''CO_GRU_INS'')then
					
        begin
        	--Sentencia de la insercion
        	insert into corres.tgrupo(
			nombre,
			estado_reg,
			obs,
			correo,
			id_usuario_reg,
			fecha_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.nombre,
			''activo'',
			v_parametros.obs,
			v_parametros.correo,
			p_id_usuario,
			now(),
			null,
			null
			)RETURNING id_grupo into v_id_grupo;
               
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,''mensaje'',''Grupo almacenado(a) con exito (id_grupo''||v_id_grupo||'')''); 
            v_resp = pxp.f_agrega_clave(v_resp,''id_grupo'',v_id_grupo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  ''CO_GRU_MOD''
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 15:55:06
	***********************************/

	elsif(p_transaccion=''CO_GRU_MOD'')then

		begin
			--Sentencia de la modificacion
			update corres.tgrupo set
			nombre = v_parametros.nombre,
			obs = v_parametros.obs,
			correo = v_parametros.correo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario
			where id_grupo=v_parametros.id_grupo;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,''mensaje'',''Grupo modificado(a)''); 
            v_resp = pxp.f_agrega_clave(v_resp,''id_grupo'',v_parametros.id_grupo::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  ''CO_GRU_ELI''
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 15:55:06
	***********************************/

	elsif(p_transaccion=''CO_GRU_ELI'')then

		begin
			--Sentencia de la eliminacion
			delete from corres.tgrupo
            where id_grupo=v_parametros.id_grupo;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,''mensaje'',''Grupo eliminado(a)''); 
            v_resp = pxp.f_agrega_clave(v_resp,''id_grupo'',v_parametros.id_grupo::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception ''Transaccion inexistente: %'',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='''';
		v_resp = pxp.f_agrega_clave(v_resp,''mensaje'',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,''codigo_error'',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,''procedimientos'',v_nombre_funcion);
		raise exception ''%'',v_resp;
				        
END;
'LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;