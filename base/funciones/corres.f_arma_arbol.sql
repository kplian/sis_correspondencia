--------------- SQL ---------------

CREATE OR REPLACE FUNCTION corres.f_arma_arbol (
  fl_id_correspondencia integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE FLUJO ()
***************************************************************************
 SCRIPT: 		corres.f_arma_arbol
 DESCRIPCIÓN: 	busca todos los hijos y devuelve un listado, 
 				con los datos necesarios
 AUTOR: 		KPLIAN RAC
 FECHA:			13/01/2012
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA  

******************/

DECLARE 
  v_nivel integer;
  v_id_corre integer;
  v_id_corre_fk integer;
  v_raiz integer;


  v_resp     boolean;
  g_registros record;
  v_respuesta				varchar;
  v_nombre_funcion		varchar;
  
  
BEGIN
 
      FOR g_registros in (SELECT co.id_correspondencia,                           
             co.id_funcionario,                          
             co.id_uo,                          
             co.id_institucion,                          
             co.id_persona,
             co.id_correspondencia_fk,
             co.nivel
      FROM   corres.tcorrespondencia co
      WHERE co.id_correspondencia_fk=fl_id_correspondencia
      and co.estado != 'anulado' ) LOOP
      
         v_resp= corres.f_arma_arbol(g_registros.id_correspondencia);
         IF v_resp THEN
         
              INSERT INTO correspondencia_her
               (id_correspondencia,                           
                id_funcionario,                          
                id_uo,                          
                id_institucion,                          
                id_persona,
                id_correspondencia_fk,
                nivel)
                values(
                 g_registros.id_correspondencia,                           
                 g_registros.id_funcionario,                          
                 g_registros.id_uo,                          
                 g_registros.id_institucion,                          
                 g_registros.id_persona,
                 g_registros.id_correspondencia_fk,
                 g_registros.nivel
                );  
         
         
         ELSE
           raise exception 'error en la busqueda recursiva';
         END IF; 
         
              
      
      END LOOP;
 
  RETURN TRUE;
  
EXCEPTION
     				
    WHEN OTHERS THEN
		v_respuesta='';
		v_respuesta = pxp.f_agrega_clave(v_respuesta,'mensaje',SQLERRM);
		v_respuesta = pxp.f_agrega_clave(v_respuesta,'codigo_error',SQLSTATE);
		v_respuesta = pxp.f_agrega_clave(v_respuesta,'procedimientos',v_nombre_funcion);
		raise exception '%',v_respuesta;
 
  
  
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;