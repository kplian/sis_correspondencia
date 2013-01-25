CREATE OR REPLACE FUNCTION corres.f_obtener_tipo_acciones (
  fl_acciones varchar
)
RETURNS varchar AS'
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
  
BEGIN

v_consulta := ''SELECT  
                 ta.nombre 
              FROM corres.taccion ta 
              WHERE ta.id_accion in (''||fl_acciones||'')''; 
                                             
              
       
retorno ='''';       
 FOR g_registros in EXECUTE (v_consulta)
 LOOP
    
       retorno = retorno || g_registros.nombre ||''; '';
    
 END LOOP;           
 
  
return retorno;

END;
'LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;