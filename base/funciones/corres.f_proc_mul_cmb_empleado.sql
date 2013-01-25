CREATE OR REPLACE FUNCTION corres.f_proc_mul_cmb_empleado (
  fl_cadena varchar,
  fl_id_correspondencia integer,
  fl_mensaje varchar,
  fl_id_usuario_reg integer,
  fl_id_documento integer,
  fl_numero varchar,
  fl_tipo varchar,
  fl_referencia varchar,
  fl_id_acciones varchar,
  fl_id_periodo integer,
  fl_id_gestion integer,
  fl_nivel integer,
  fl_id_nivel_seguridad integer,
  fl_cite varchar,
  fl_nivel_prioridad varchar,
  fl_origen varchar,
  fl_fecha_documento date
)
RETURNS boolean AS'
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE FLUJO ()
***************************************************************************
 SCRIPT: 		corres.f_proc_mul_cob_empleado
 DESCRIPCIÓN: 	Para analizar el combo multiple que recive varios empleados
                y registrarlos en la tabla hijo  
 AUTOR: 		RAC  KPLIAN
 FECHA:			24/01/2012
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA  

******************/

DECLARE
  v_partes varchar[];
  v_i  integer;
  v_num integer;
  v_sub_part1 varchar;
  v_sub_part2 varchar;
  v_sub_part3 varchar;
  v_id_funcionario integer;
  v_correo varchar;
  v_id_uo integer[];
  v_id_depto  integer;
 
  v_array integer[];
  v_array_var varchar;
  
  v_j integer;
  v_num_emp integer;
  v_nombre_funcionario varchar;
  
BEGIN
  /* 
  0) listamos todas las derivaciones de los 
     diferentes niveles armando un vector
  
  1)  partir la cadena dividiendo por la comas
  2) FOR recorre las partes trozadas de la cadena en un for
  
  	2.1)IF   analizar si el pedazo tiene unsa subcadea 
          entre los caracteres "<" ">"
    
    2.2 ELSE si no existe ninguna cadena entre "<"  ">"  
        2.2.1 )analizamos la pedazo y leauitamos los catateres
               tab, y retorno de carro
  
    2.3)IF  buscamos el empleado al que le corresponde el correo
    //verificamos si no tiene otra derivacion de las misma co
    //correspondencia y advertimoy advertimos
        2.3.0
    
        2.3.1)  insertamos el empleado que recibe la correspondecia
                     con fl_id_correspomndecia, mensaje, accion por defecto
    2.4 ELSE lanzamos el error de que no existe el correo indicado
        
  
  
  */ 
 -- 0) listamos todas las derivaciones de los 
 --    diferentes niveles armando un vector
  
  
  
   v_array_var= corres.f_arma_arbol_inicia(fl_id_correspondencia,''id_funcionario'');
   v_array = string_to_array(v_array_var,'','');
  
--  1)  partir la cadena dividiendo por la comas

   v_partes = string_to_array(fl_cadena,'','');
   
   v_num=array_upper(v_partes,1);

-- 2) FOR recorre las partes trozadas de la cadena en un for
	
    
     FOR v_i IN 1..v_num
      loop
           
        v_id_funcionario= v_partes[v_i]; 
        
        SELECT f.desc_funcionario1
        into v_nombre_funcionario
        FROM orga.vfuncionario f
        WHERE f.id_funcionario =v_id_funcionario;
       
            
           
     	 IF v_id_funcionario is not null THEN
      
                 v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_id_funcionario,array[''activo'',''suplente'']); 
          
                  if(array_upper(v_id_uo,1)=1) then
                     raise exception ''El funcionario: %, no pertenece a ninguna Unidad Organizacional'',v_nombre_funcionario;
                  end if;
                 -- obtiene el departemo de correspondeic a de la uo
                  
                  SELECT  
                      dep.id_depto
                  INTO
                      v_id_depto
                  FROM orga.tdepto_uo duo
                  INNER JOIN segu.tsubsistema sis 
                   ON sis.codigo = ''CORRES''
                  INNER JOIN orga.tdepto dep 
                   ON dep.id_depto = duo.id_depto
                  WHERE duo.id_uo = ANY (v_id_uo); 
    
                  -- 2.3.1)  insertamos el empleado que recibe la correspondecia
                  --          con fl_id_correspomndecia, mensaje, 
                  --          accion por defecto y estado borrador
                    if(v_id_depto is null)THEN
                      raise exception ''La UO % no tiene un departamento relacionado'',v_id_uo[2];
                    end if;
      
      
                  -- RAC verifica que el empleado no tenga otra derivación
                        
                     IF (v_array_var is not NULL  and v_array_var != '''') THEN 
                        v_num_emp =array_upper(v_array,1);    
                        FOR v_j IN 1..v_num_emp loop       
                            IF v_id_funcionario =v_array[v_j] THEN
                            raise exception ''El empleado % ya tiene derivaciones de este mismo mensaje'',v_nombre_funcionario;
                           end if;
                        end loop;
    				  END IF;
      
      
                   insert into corres.tcorrespondencia 
                     (id_depto,
                      id_funcionario,
                      id_correspondencia_fk,
                      id_uo,
                      id_acciones,
                      estado,
                      mensaje,
                      id_usuario_reg,
                      fecha_reg,
                      tipo,
                      id_documento,
                      numero,
                      referencia,
                      cite,
                      id_periodo,
                      id_gestion,
                      nivel,
                      id_clasificador,
                      nivel_prioridad,
                      origen,
                      fecha_documento
                      )
                      values
                      (
                      v_id_depto,
                      v_id_funcionario,
                      fl_id_correspondencia,
                      v_id_uo[2],
                      string_to_array(fl_id_acciones,'','')::integer[],
                      ''borrador_detalle_recibido'',
                      fl_mensaje,
                      fl_id_usuario_reg,
                      now(),
                      fl_tipo,
                      fl_id_documento,
                      fl_numero,
                      fl_referencia,
                      fl_cite,
                      fl_id_periodo,
                      fl_id_gestion,
                      fl_nivel,
                      fl_id_nivel_seguridad,
                      fl_nivel_prioridad,
                      fl_origen,
                      fl_fecha_documento
                      );
        
   		ELSE
          raise exception ''ERROR NO ESPERADO: FUNCIONARIO NULO'';
        END IF;
     
    
     
--END FOR
 end loop;
 

  
return true;

END;
'LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;