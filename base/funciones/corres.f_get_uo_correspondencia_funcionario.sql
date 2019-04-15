CREATE OR REPLACE FUNCTION corres.f_get_uo_correspondencia_funcionario (
  fl_id_empleado integer,
  fl_filtro varchar [],
  p_fecha date
)
RETURNS integer [] AS
$body$
/*
Autor: Jaime
fecha: 22/02/11
descripción: obtiene las unidades organizacional 
             del    empleado que maneja correspondecia

*/

DECLARE
  g_registros record;
  g_res			INTEGER[];
  v_consulta    varchar;
  v_id_uo		integer;
  v_nombre_funcion	varchar;
  v_resp			varchar;
  
BEGIN

   v_nombre_funcion  = 'f_get_uo_correspondencia_funcionario';

  g_res=ARRAY[-1];


  --RAC  aumentamos la varialbe fl_filtro en vez de 
  
      select funuo.id_uo into v_id_uo
      from orga.tuo_funcionario funuo
      where funuo.estado_reg = 'activo' 
           and funuo.id_funcionario = fl_id_empleado and
          funuo.fecha_asignacion <= p_fecha and (funuo.fecha_finalizacion is null or funuo.fecha_finalizacion >= p_fecha);
      

      --raise exception '%', v_id_uo;
      if (v_id_uo is null)then
          return ARRAY[-2];
      end if;
  

     
    	
      g_res=array_append(g_res,corres.f_get_uo_correspondencia(v_id_uo));
   
  
  return g_res;
  
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