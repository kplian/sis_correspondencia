<?php
/**
*@package pXP
*@file gen-FuncionesCorrespondencia.php
*@author  (rac)
*@date 13-12-2011 13:49:30
*@description Clase que centraliza todos los metodos de todas las clases del Sistema de Correspondencia
*/

class FuncionesCorrespondencia{
		
	function __construct(){
		foreach (glob('../../sis_correspondencia/modelo/MOD*.php') as $archivo){
			include_once($archivo);
		}
	}

	/*Clase: MODAccion
	* Fecha: 13-12-2011 13:49:30
	* Autor: rac*/
	function listarAccion(CTParametro $parametro){
		$obj=new MODAccion($parametro);
		$res=$obj->listarAccion();
		return $res;
	}
			
	function insertarAccion(CTParametro $parametro){
		$obj=new MODAccion($parametro);
		$res=$obj->insertarAccion();
		return $res;
	}
			
	function modificarAccion(CTParametro $parametro){
		$obj=new MODAccion($parametro);
		$res=$obj->modificarAccion();
		return $res;
	}
			
	function eliminarAccion(CTParametro $parametro){
		$obj=new MODAccion($parametro);
		$res=$obj->eliminarAccion();
		return $res;
	}
	/*FinClase: MODAccion*/

			
	/*Clase: MODCorrespondencia
	* Fecha: 13-12-2011 16:13:21
	* Autor: rac*/
	function listarCorrespondencia(CTParametro $parametro){
		$obj=new MODCorrespondencia($parametro);
		$res=$obj->listarCorrespondencia();
		return $res;
	}
			
	function insertarCorrespondencia(CTParametro $parametro){
		$obj=new MODCorrespondencia($parametro);
		$res=$obj->insertarCorrespondencia();
		return $res;
	}
			
	function modificarCorrespondencia(CTParametro $parametro){
		$obj=new MODCorrespondencia($parametro);
		$res=$obj->modificarCorrespondencia();
		return $res;
	}
			
	function eliminarCorrespondencia(CTParametro $parametro){
		$obj=new MODCorrespondencia($parametro);
		$res=$obj->eliminarCorrespondencia();
		return $res;
	}
	/*FinClase: MODCorrespondencia*/


	/*Clase: MODGrupo
	* Fecha: 10-01-2012 16:02:20
	* Autor: rac*/
	function listarGrupo(CTParametro $parametro){
		$obj=new MODGrupo($parametro);
		$res=$obj->listarGrupo();
		return $res;
	}
			
	function insertarGrupo(CTParametro $parametro){
		$obj=new MODGrupo($parametro);
		$res=$obj->insertarGrupo();
		return $res;
	}
			
	function modificarGrupo(CTParametro $parametro){
		$obj=new MODGrupo($parametro);
		$res=$obj->modificarGrupo();
		return $res;
	}
			
	function eliminarGrupo(CTParametro $parametro){
		$obj=new MODGrupo($parametro);
		$res=$obj->eliminarGrupo();
		return $res;
	}
	/*FinClase: MODGrupo*/


	/*Clase: MODGrupoFuncionario
	* Fecha: 10-01-2012 16:15:05
	* Autor: rac*/
	function listarGrupoFuncionario(CTParametro $parametro){
		$obj=new MODGrupoFuncionario($parametro);
		$res=$obj->listarGrupoFuncionario();
		return $res;
	}
			
	function insertarGrupoFuncionario(CTParametro $parametro){
		$obj=new MODGrupoFuncionario($parametro);
		$res=$obj->insertarGrupoFuncionario();
		return $res;
	}
			
	function modificarGrupoFuncionario(CTParametro $parametro){
		$obj=new MODGrupoFuncionario($parametro);
		$res=$obj->modificarGrupoFuncionario();
		return $res;
	}
			
	function eliminarGrupoFuncionario(CTParametro $parametro){
		$obj=new MODGrupoFuncionario($parametro);
		$res=$obj->eliminarGrupoFuncionario();
		return $res;
	}
	/*FinClase: MODGrupoFuncionario*/


}//marca_generador
?>