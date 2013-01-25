CREATE OR REPLACE FUNCTION corres.f_arma_arbol_inicia (
  fl_id_correspondencia integer,
  fl_columna varchar
)
RETURNS varchar AS'
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE FLUJO ()
***************************************************************************
 SCRIPT: 		corres.f_arma_arbol_inicia
 DESCRIPCIÓN: 	rama un array con todos los datod indicados 
                en la consulta para evitar derivaciones recursivas
 AUTOR: 		KPLIAN RAC
 FECHA:			13/01/2011
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
  v_respuesta varchar;
  v_sw boolean;
  v_resp boolean;
  v_consulta varchar;
  g_registros record;
BEGIN

 
 
  --1)  encuentro nodo  raiz con nivel = 0
    v_raiz:= corres.f_encuentra_raiz(fl_id_correspondencia);
 
 --  2) creo tabla temporal
    CREATE TEMPORARY TABLE correspondencia_her (                          
                          "id_correspondencia" INTEGER,                           
                          "id_funcionario" INTEGER,                          
                          "id_uo" INTEGER,                          
                          "id_institucion" INTEGER,                          
                          "id_persona" INTEGER,
                          "id_correspondencia_fk" INTEGER,
                          "nivel" integer)  ON COMMIT DROP;  
 
  --3) busco recursivamente todos los nodos dentro de la rama
  
    FOR g_registros in (SELECT co.id_correspondencia,                           
             co.id_funcionario,                          
             co.id_uo,                          
             co.id_institucion,                          
             co.id_persona,
             co.id_correspondencia_fk,
             co.nivel
      FROM   corres.tcorrespondencia co
      WHERE co.id_correspondencia=v_raiz
       and co.estado != ''anulado'' ) LOOP
 
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
     END LOOP;
  
    v_resp = corres.f_arma_arbol (v_raiz);
    
  if v_resp THEN
  -- 4) obtengo los resultados registrados en la tabla temporal
      
      v_consulta = ''SELECT ''||fl_columna|| '' as  respuesta FROM  
                    correspondencia_her'';
      v_respuesta='''';          
      v_sw=false;
      
     -- raise exception ''revision ...  '';
      FOR g_registros in EXECUTE(v_consulta) LOOP      
        -- 4.1 ) armo array con los resultados solicitados

           IF g_registros.respuesta is not null THEN
               IF v_sw = false THEN
                 v_respuesta=g_registros.respuesta;
                 v_sw=true;
               ELSE
                 v_respuesta=v_respuesta||'',''||g_registros.respuesta;
               END IF;
           END IF;    
      END LOOP; 
     
  ELSE    
      raise exception ''error en la busqueda resursiva'';
  END IF; 
  
  RETURN  v_respuesta;
  
END;
'LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;