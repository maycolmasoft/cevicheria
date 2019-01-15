<?php

class IngredientesController extends ControladorBase{

	public function __construct() {
		parent::__construct();
	}

//maycol

	public function index(){
	
	
	  session_start();
	  $resultEdit = "";
	  
	   
		if (isset($_SESSION['nombre_usuarios']))
		{
			$ingredientes = new IngredientesModel();
			
			$nombre_controladores = "Ingredientes";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $ingredientes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				$resultSet=$ingredientes->getAll("id_ingredientes");
				
				if (isset ($_GET["id_ingredientes"]))
				{
					
					
						$_id_ingredientes = $_GET["id_ingredientes"];
						$columnas = " id_ingredientes, nombre_ingredientes";
						$tablas   = "ingredientes";
						$where    = "id_ingredientes = '$_id_ingredientes' "; 
						$id       = "nombre_ingredientes";
							
						$resultEdit = $ingredientes->getCondiciones($columnas ,$tablas ,$where, $id);
					
					
					
					
				}
				
				
				$this->view("Ingredientes",array(
						"resultSet"=>$resultSet, "resultEdit" =>$resultEdit
			
				));
		
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Ingredientes"
				
				));
				
				exit();	
			}
				
		}
	else{
       	
       	$this->redirect("Usuarios","sesion_caducada");
       	
       }
	
	}
	
	public function InsertaIngredientes(){
			
		session_start();

		
		if (isset(  $_SESSION['nombre_usuarios']) )
		{
			
			$ingredientes=new IngredientesModel();
			
			if (isset ($_POST["nombre_ingredientes"]) )
				
			{
				
				$_nombre_ingredientes = $_POST["nombre_ingredientes"];
				$_id_ingredientes = $_POST["id_ingredientes"];
				
				if($_id_ingredientes>0) 
				{
					
					$colval = " nombre_ingredientes = '$_nombre_ingredientes'   ";
					$tabla = "ingredientes";
					$where = "id_ingredientes = '$_id_ingredientes'    ";
					
					$resultado=$ingredientes->UpdateBy($colval, $tabla, $where);
					
					
					
				}else {
					
			
				
				$funcion = "ins_ingredientes";
				$parametros = " '$_nombre_ingredientes'  ";
				$ingredientes->setFuncion($funcion);
				$ingredientes->setParametros($parametros);
				$resultado=$ingredientes->Insert();
				
			 }
		
			}
			$this->redirect("Ingredientes", "index");

		}
		else
		{
				$error = TRUE;
	   	$mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
	   		
	   	$this->view("Login",array(
	   			"resultSet"=>"$mensaje", "error"=>$error
	   	));
	   		
	   		
	   	die();
		
		}
		
	}
	
	public function borrarId()
	{

		session_start();
		
		$ingredientes=new IngredientesModel();
		$nombre_controladores = "Ingredientes";
		$id_rol= $_SESSION['id_rol'];
		$resultPer = $ingredientes->getPermisosEditar("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
		if (!empty($resultPer))
		{
			if(isset($_GET["id_ingredientes"]))
			{
				$id_ingredientes=(int)$_GET["id_ingredientes"];
				
				$ingredientes=new IngredientesModel();
				$ingredientes->deleteBy(" id_ingredientes",$id_ingredientes);
				
			}
			
			$this->redirect("Ingredientes", "index");
			
			
		}
		else
		{
			$this->view("Error",array(
				"resultado"=>"No tiene Permisos de Borrar Controladores"
			
			));
		}
				
	}
	
	
	
	
	
	
	
		
}
?>