--------------- SQL ---------------

CREATE OR REPLACE FUNCTION corres.trigfl_correspondencia (
)
RETURNS trigger AS
$body$
DECLARE
    v_nombre_bd       varchar;
    v_nombre_usuario  varchar;
    v_id_usuario      integer;
    g_consulta        varchar;
BEGIN
    v_nombre_usuario:= (select current_user);
    v_nombre_bd:=(select current_database());
    v_nombre_usuario:= replace(v_nombre_usuario,v_nombre_bd||'_','');
    
    select id_usuario
    into v_id_usuario
    from sss.tsg_usuario u
    where u.login=v_nombre_usuario;
    
    if(v_id_usuario is not null)then
    
        IF TG_OP = 'INSERT' THEN
            BEGIN
               
                g_consulta:='insert into corres.tfcorrespondencia_estado(
                    id_correspondencia,
                    estado,
                    id_usuario,
                    fecha_reg,
                    observaciones_estado,
                    estado_reg)
                values ('||
                    NEW.id_correspondencia||',
                    '''||NEW.estado||''',
                    '''||v_id_usuario||''',
                    '||'
                    now(),'''||
                    coalesce(NEW.observaciones_estado,''::text)||''',
                    ''activo'')';
                execute(g_consulta);
            END;
        
        ELSIF TG_OP = 'UPDATE' THEN
        BEGIN
            if(OLD.estado!=NEW.estado)then
            
            update flujo.tfl_correspondencia_estado set estado_reg='inactivo'
            where id_correspondencia=NEW.id_correspondencia;
        
            g_consulta:='insert into corres.tcorrespondencia_estado(
                    id_correspondencia,
                    estado,
                    estado_ant,
                    id_usuario,
                    fecha_reg,
                    observaciones_estado,
                    estado_reg)
                values ('||
                    NEW.id_correspondencia||',
                    '''||NEW.estado||''',
                    '''||OLD.estado||''',
                    '''||v_id_usuario||''',
                    '||'
                    now(),'''||
                    coalesce(NEW.observaciones_estado,''::text)||''',
                    ''activo'')';
                execute(g_consulta);
            
              if (OLD.estado='borrador_detalle_recibido'   and NEW.estado='pendiente_recibido')then
              
              	update corres.tcorrespondencia set fecha_ult_derivado=now() where id_correspondencia=NEW.id_correspondencia; 
              	
              end if;
            end if;
        
        END;
        END IF;
    end if;

  RETURN NULL;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;