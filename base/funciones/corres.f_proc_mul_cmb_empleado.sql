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
  fl_fecha_documento date,
  f1_id_origen integer,
  f1_id_depto integer,
  fl_persona_remitente varchar,
  fl_fisico varchar,
  fl_grupo varchar
)
RETURNS boolean AS
$body$
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
HISTORIAL DE MODIFICACIONES:

 #ISSUE         FECHA        AUTOR        			DESCRIPCION
 #4  	      	31/07/2019   MCGH        		Adición del parametro persona_remitente, fecha recepción
 #5      		21/08/2019   MCGH         		Eliminación de Código Basura
 #7      		06/09/2019   MCGH         		Adición del campo Tiene el Fisico
 #7      		06/09/2019   Manuel Guerra      Agregacion de Grupos
******************/


DECLARE
    v_partes 			varchar[];
    v_i  				integer;
    v_num 				integer;
    v_sub_part1 		varchar;
    v_sub_part2 		varchar;
    v_sub_part3 		varchar;
    v_id_funcionario 	integer;
    v_correo 			varchar;
    v_id_uo 			integer[];
    v_id_depto  		integer;
    v_array 			integer[];
    v_array_var 		varchar;
    v_j 				integer;
    v_num_emp 			integer;
    v_nombre_funcionario varchar;
    v_id_correspondencia INTEGER;
    v_id_documento_fisico INTEGER;
    v_resp				varchar;
    v_nombre_funcion	varchar;
    v_count 			integer; --#7
	v_tam 				integer;
    v_tam_b 			integer;
    v_aux 				integer;
    v_auxiliar			integer;
    v_func				integer[];
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

   	v_nombre_funcion = 'f_proc_mul_cmb_empleado';
  	v_array_var= corres.f_arma_arbol_inicia(fl_id_correspondencia,'id_funcionario');
   	v_array = string_to_array(v_array_var,',');

    -- 1)  partir la cadena dividiendo por la comas
    -- if (select position(',' in fl_cadena)=0)THEN
    -- v_partes = string_to_array(fl_cadena,null);
    -- else
    v_partes = string_to_array(fl_cadena,',');
   	--end if;

	--raise exception '%',''||v_partes;
   	v_num=array_upper(v_partes,1);

	-- 2) FOR recorre las partes trozadas de la cadena en un for
	FOR v_i IN 1..coalesce(v_num,0) LOOP

        v_id_funcionario= v_partes[v_i];
        SELECT f.desc_funcionario1
        into v_nombre_funcionario
        FROM orga.vfuncionario f
        WHERE f.id_funcionario =v_id_funcionario;

		IF v_id_funcionario is not null THEN
			v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_id_funcionario,array['activo','suplente'], fl_fecha_documento);

            IF(array_upper(v_id_uo,1)=1) then
               raise exception 'El funcionario: %, no pertenece a ninguna Unidad Organizacional o la fecha de Asignación del funcionario es menor a la fecha del Documento',v_nombre_funcionario;
            end if;
            -- obtiene el departemo de correspondeic a de la uo
            SELECT dep.id_depto
            INTO v_id_depto
            FROM param.tdepto_uo duo
            INNER JOIN segu.tsubsistema sis ON sis.codigo = 'CORRES'
            INNER JOIN param.tdepto dep ON dep.id_depto = duo.id_depto
            WHERE duo.id_uo = ANY (v_id_uo);

            -- 2.3.1)  insertamos el empleado que recibe la correspondecia
            --          con fl_id_correspomndecia, mensaje,
            --          accion por defecto y estado borrador
            if(v_id_depto is null)THEN
            	raise exception 'La UO % no tiene un departamento relacionado',v_id_uo[2];
            end if;
            --******* #7 VERIFICAR QUE LA CORRESPONDENCIA FISICA SOLO ESTE CON UN FUNCIONARIO
            -----*****PRIMERO VERIFICAMOS QUE EL PADRE TENGA EL FISICO PARA QUE PUEDA DERIVAR CON EL FISICO
            SELECT COUNT(*) INTO v_count
            FROM corres.tcorrespondencia c
            WHERE c.id_correspondencia = fl_id_correspondencia
            and c.sw_fisico = 'si'
            and c.estado <> 'anulado';

            IF (fl_fisico = 'si' AND v_count <= 0 )THEN
                raise exception 'No puede derivar el documento físico, porque usted no lo tiene.';
            END IF;
        	------*****
         	SELECT COUNT(*) INTO v_count
            FROM corres.tcorrespondencia c
            WHERE c.id_correspondencia_fk = fl_id_correspondencia
            and c.sw_fisico = 'si'
            and c.estado <> 'anulado';

            IF (fl_fisico = 'si' AND v_count > 0) THEN
                raise exception 'Otro funcionario ya tiene registrado que recibirá el fisico';
            ELSE
            	INSERT INTO corres.tcorrespondencia(
                    id_depto,
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
                    fecha_documento,
                    id_origen,
                    fecha_creacion_documento,
                    persona_remitente,  --#4
                    sw_fisico		--#7
                    )
                VALUES
                    (
                    v_id_depto,
                    v_id_funcionario,
                    fl_id_correspondencia,
                    v_id_uo[2],
                    string_to_array(fl_id_acciones,',')::integer[],
                    'borrador_detalle_recibido',
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
                    fl_fecha_documento,
                    f1_id_origen,
                    now(),
                    fl_persona_remitente,   --#4
                    fl_fisico			--#7
                ) RETURNING id_correspondencia into v_id_correspondencia;
            --*************
            IF f1_id_depto != v_id_depto THEN
                  INSERT INTO corres.tdocumento_fisico(
                    id_correspondencia,
                    id_correspondencia_padre,
                    estado,
                    estado_reg,
                    fecha_reg,
                    id_usuario_reg,
                    fecha_mod,
                    id_usuario_mod
                  )
                  VALUES(
                    v_id_correspondencia,
                    fl_id_correspondencia,
                    'pendiente',
                    'activo',
                    now(),
                    fl_id_usuario_reg,
                    null,
                    null
                  )RETURNING id_documento_fisico INTO v_id_documento_fisico;
            END IF;
    	END IF;

        ELSE
            raise exception 'ERROR NO ESPERADO: FUNCIONARIO NULO';
        END IF;

	--END FOR
	END LOOP;

    v_j=1;
    v_aux=1;
    v_tam = coalesce(array_length(string_to_array(fl_grupo,','), 1),0);
    v_array = string_to_array(fl_grupo,',');
    --#7
	IF v_tam > 0 THEN
        WHILE v_aux <= v_tam LOOP
            v_auxiliar=1;
            SELECT pxp.aggarray(g.id_funcionario)
            INTO v_func
            FROM corres.tgrupo_funcionario g
            WHERE g.id_grupo= v_array[v_aux];
            v_tam_b=coalesce(array_length(v_func,1),0);
            WHILE v_auxiliar <= v_tam_b LOOP

				v_id_uo = corres.f_get_uo_correspondencia_funcionario(v_func[v_auxiliar],array['activo','suplente'], fl_fecha_documento);

                SELECT dep.id_depto
                INTO v_id_depto
                FROM param.tdepto_uo duo
                INNER JOIN segu.tsubsistema sis ON sis.codigo = 'CORRES'
                INNER JOIN param.tdepto dep ON dep.id_depto = duo.id_depto
                WHERE duo.id_uo = ANY (v_id_uo);

				SELECT f.desc_funcionario1
                into v_nombre_funcionario
                FROM orga.vfuncionario f
                WHERE f.id_funcionario =v_func[v_auxiliar];
                IF(v_id_depto is null)THEN
                    RAISE EXCEPTION 'La UO % no tiene un departamento relacionado',v_nombre_funcionario;
                END IF;
            	INSERT INTO corres.tcorrespondencia(
                  id_depto,
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
                  fecha_documento,
                  id_origen,
                  fecha_creacion_documento,
                  persona_remitente,  --#4
                  sw_fisico		--#7
                  )
            	VALUES
                  (
                  v_id_depto,
                  v_func[v_auxiliar],
                  fl_id_correspondencia,
                  v_id_uo[2],
                  string_to_array(fl_id_acciones,',')::integer[],
                  'borrador_detalle_recibido',
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
                  fl_fecha_documento,
                  f1_id_origen,
                  now(),
                  fl_persona_remitente,   --#4
                  'no'			--#7
                  ) RETURNING id_correspondencia into v_id_correspondencia;
            	v_auxiliar=v_auxiliar+1;
            END LOOP;
            v_aux=v_aux+1;
		END LOOP;
	END IF;



return true;

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