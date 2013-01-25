CREATE OR REPLACE FUNCTION corres.f_get_uo_correspondencia_funcionario (
  fl_id_empleado integer,
  fl_filtro varchar []
)
RETURNS integer [] AS'
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
BEGIN
  g_res=ARRAY[-1];
  --RAC  aumentamos la varialbe fl_filtro en vez de 
   
  for g_registros in (
               SELECT uof.id_uo
               FROM orga.tuo_funcionario  uof
               WHERE (uof.estado_reg=ANY(fl_filtro)) and uof.id_funcionario= fl_id_empleado) loop
    
    	
      g_res=array_append(g_res,corres.f_get_uo_correspondencia(g_registros.id_uo));
   
  end loop;
  return g_res;

END;
'LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;