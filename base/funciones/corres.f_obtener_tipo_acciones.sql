CREATE OR REPLACE FUNCTION corres.f_obtener_tipo_acciones (
  fl_acciones varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE FLUJO ()
***************************************************************************
 SCRIPT: 		flujo.f_tfl_atributo_iud
 DESCRIPCIÓN: 	Obtiene los nombre de las acciones 
                a partir de una cadena de identificadores
                divididos por comas
 AUTOR: 		Rensi Arteaga Copari
 FECHA:			18/02/2011
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:			

******************/

DECLARE
 
  v_consulta varchar;
  g_registros record;
  retorno varchar;
  v_resp varchar;
  v_nombre_funcion varchar;
  
BEGIN

v_consulta := 'SELECT  
                 ta.nombre 
              FROM corres.taccion ta 
              WHERE ta.id_accion in ('||fl_acciones||')'; 
                                             
              
       
retorno ='';       
 FOR g_registros in EXECUTE (v_consulta)
 LOOP
    
       retorno = retorno || g_registros.nombre ||'; ';
    
 END LOOP;           
 
  
return retorno;

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