CREATE OR REPLACE FUNCTION corres.ft_documento_fisico_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de documentos
 FUNCION: 		corres.ft_documento_fisico_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'corres.tdocumento_fisico'
 AUTOR: 		 (admin)
 FECHA:	        27-04-2016 16:45:39
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
	v_deptos VARCHAR;
  v_permiso VARCHAR;
			    
BEGIN

	v_nombre_funcion = 'corres.ft_documento_fisico_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CORRES_DOCFIS_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		27-04-2016 16:45:39
	***********************************/

	if(p_transaccion='CORRES_DOCFIS_SEL')then
     				
    	begin




    		--Sentencia de la consulta
			v_consulta:='select
									docfis.id_documento_fisico,
									docfis.id_correspondencia,
									docfis.id_correspondencia_padre,
									docfis.estado,
									docfis.estado_reg,
									docfis.id_usuario_ai,
									docfis.usuario_ai,
									docfis.fecha_reg,
									docfis.id_usuario_reg,
									docfis.fecha_mod,
									docfis.id_usuario_mod,
									usu1.cuenta as usr_reg,
									usu2.cuenta as usr_mod,
									per.nombre_completo2 as desc_person,
									per_padre.nombre_completo2 as desc_person_padre,
									depto.nombre as desc_depto,
									depto_padre.nombre as desc_depto_padre,
									cor.numero
								from corres.tdocumento_fisico docfis
									inner join segu.tusuario usu1 on usu1.id_usuario = docfis.id_usuario_reg
									left join segu.tusuario usu2 on usu2.id_usuario = docfis.id_usuario_mod
									inner join corres.tcorrespondencia cor on cor.id_correspondencia = docfis.id_correspondencia
								INNER JOIN corres.tcorrespondencia cor_padre on cor_padre.id_correspondencia = docfis.id_correspondencia_padre

								inner join orga.tfuncionario fun on fun.id_funcionario = cor.id_funcionario
								INNER JOIN orga.tfuncionario fun_padre on fun_padre.id_funcionario = cor_padre.id_funcionario
								inner join segu.vpersona2 per on per.id_persona = fun.id_persona
								inner join segu.vpersona2 per_padre on per_padre.id_persona = fun_padre.id_persona
								inner join param.tdepto depto on depto.id_depto = cor.id_depto
								inner join param.tdepto depto_padre on depto_padre.id_depto = cor_padre.id_depto
				        where  ';




			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;



        IF p_administrador = 1 THEN

          v_deptos = 'admin';

        ELSE

          select pxp.list(depus.id_depto::VARCHAR)
          into v_deptos
          from param.tdepto_usuario depus
            inner join param.tdepto dep on dep.id_depto = depus.id_depto
            inner join segu.tsubsistema sis on sis.id_subsistema = dep.id_subsistema
          where depus.id_usuario = p_id_usuario and depus.cargo in ('responsable','auxiliar')
                and sis.codigo = 'CORRES';



          IF v_deptos is not null THEN -- verifica que no sea null

            IF v_parametros.vista_documento_fisico = 'despachador' THEN
              v_consulta:=v_consulta||' and depto_padre.id_depto in ('||v_deptos||') ';
            ELSEIF v_parametros.vista_documento_fisico = 'recepcionar' THEN
              v_consulta:=v_consulta||' and depto.id_depto in ('||v_deptos||') ';
            END IF ;


          ELSE --si es null entonces no tiene ningun departamento asignado
            RAISE EXCEPTION '%','no eres responsable o axuliar de ningun departamento';
          end IF;



        END IF;







        --RAISE EXCEPTION '%',v_consulta;

			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'CORRES_DOCFIS_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		27-04-2016 16:45:39
	***********************************/

	elsif(p_transaccion='CORRES_DOCFIS_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_documento_fisico)
					    from corres.tdocumento_fisico docfis
					    inner join segu.tusuario usu1 on usu1.id_usuario = docfis.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = docfis.id_usuario_mod
							inner join corres.tcorrespondencia cor on cor.id_correspondencia = docfis.id_correspondencia
								INNER JOIN corres.tcorrespondencia cor_padre on cor_padre.id_correspondencia = docfis.id_correspondencia_padre

								inner join orga.tfuncionario fun on fun.id_funcionario = cor.id_funcionario
								INNER JOIN orga.tfuncionario fun_padre on fun_padre.id_funcionario = cor_padre.id_funcionario
								inner join segu.vpersona2 per on per.id_persona = fun.id_persona
								inner join segu.vpersona2 per_padre on per_padre.id_persona = fun_padre.id_persona
								inner join param.tdepto depto on depto.id_depto = cor.id_depto
								inner join param.tdepto depto_padre on depto_padre.id_depto = cor_padre.id_depto

					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
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