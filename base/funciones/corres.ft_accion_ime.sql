/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_accion_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.taccion'
 AUTOR: 		 (rac)
 FECHA:	        13-12-2011 13:49:30
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
	v_id_accion	integer;
			    
BEGIN

    v_nombre_funcion = 'corres.ft_accion_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CO_ACCO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 13:49:30
	***********************************/

	if(p_transaccion='CO_ACCO_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into corres.taccion(
			estado_reg,
			nombre,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			'activo',
			v_parametros.nombre,
			now(),
			p_id_usuario,
			null,
			null
			)RETURNING id_accion into v_id_accion;
               
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Accion almacenado(a) con exito (id_accion'||v_id_accion||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_accion',v_id_accion::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CO_ACCO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 13:49:30
	***********************************/

	elsif(p_transaccion='CO_ACCO_MOD')then

		begin
			--Sentencia de la modificacion
			update corres.taccion set
			nombre = v_parametros.nombre,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario
			where id_accion=v_parametros.id_accion;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Accion modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_accion',v_parametros.id_accion::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CO_ACCO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		rac	
 	#FECHA:		13-12-2011 13:49:30
	***********************************/

	elsif(p_transaccion='CO_ACCO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from corres.taccion
            where id_accion=v_parametros.id_accion;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Accion eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_accion',v_parametros.id_accion::varchar);
              
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