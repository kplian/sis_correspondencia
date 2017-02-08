--------------- SQL ---------------

CREATE OR REPLACE FUNCTION corres.f_get_uo_correspondencia (
  fl_id_unidad_organizacional integer
)
RETURNS integer AS
$body$
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_correspondencia_ime
 DESCRIPCION:   Busca de manera recursisa la uo marcada para recibir correpondecia 
 				apartir de alguno de sus nodos hijos
 AUTOR: 		 KPLIAN RAC
 FECHA:	        24-01-2012 21:00
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/
DECLARE
  g_id_uo 					integer;
  g_correspondencia			varchar;
  g_id_aux					integer;
  v_resp					varchar;
  v_nombre_funcion			varchar;
BEGIN
	select uo.correspondencia
    into g_correspondencia
    from orga.tuo uo
    where uo.id_uo=fl_id_unidad_organizacional;
    
    if(g_correspondencia='si')then
    	return fl_id_unidad_organizacional;
    else
    	select eo.id_uo_padre
        into g_id_uo
        from orga.testructura_uo eo
        where eo.id_uo_hijo=fl_id_unidad_organizacional;
                
        g_id_aux=corres.f_get_uo_correspondencia(g_id_uo);
    	return g_id_aux;
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