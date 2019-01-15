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
			$estado = new EstadoModel();
			$resultEst = $estado->getAll("nombre_estado");
			
			$nombre_controladores = "Ingredientes";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $ingredientes->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
			
			if (!empty($resultPer))
			{
				
			    $columnas1 = " ingredientes.id_ingredientes,
                                      ingredientes.nombre_ingredientes,
                                      estado.id_estado,
                                      estado.nombre_estado,
                                      ingredientes.creado,
                                      ingredientes.modificado";
			    $tablas1   = "public.ingredientes,
                                     public.estado";
			    $where1    = "estado.id_estado = ingredientes.id_estado";
			    $id1       = "ingredientes.nombre_ingredientes";
			    
			    $resultSet = $ingredientes->getCondiciones($columnas1 ,$tablas1 ,$where1, $id1);
			    
			    
				
				
				
				
				if (isset ($_GET["id_ingredientes"]))
				{
					
					
						$_id_ingredientes = $_GET["id_ingredientes"];
						$columnas = " ingredientes.id_ingredientes, 
                                      ingredientes.nombre_ingredientes, 
                                      estado.id_estado, 
                                      estado.nombre_estado, 
                                      ingredientes.creado, 
                                      ingredientes.modificado";
						$tablas   = "public.ingredientes, 
                                     public.estado";
						$where    = "estado.id_estado = ingredientes.id_estado AND ingredientes.id_ingredientes = '$_id_ingredientes' "; 
						$id       = "ingredientes.nombre_ingredientes";
							
						$resultEdit = $ingredientes->getCondiciones($columnas ,$tablas ,$where, $id);
					
					
					
					
				}
				
				
				$this->view("Ingredientes",array(
				    "resultSet"=>$resultSet, "resultEdit" =>$resultEdit, "resultEst"=>$resultEst
			
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
				$_id_estado = $_POST["id_estado"];
				
				if($_id_ingredientes>0) 
				{
					
					$colval = " nombre_ingredientes = '$_nombre_ingredientes', id_estado='$_id_estado'   ";
					$tabla = "ingredientes";
					$where = "id_ingredientes = '$_id_ingredientes'    ";
					
					$resultado=$ingredientes->UpdateBy($colval, $tabla, $where);
					
					
					
				}else {
					
			
				
				$funcion = "ins_ingredientes";
				$parametros = " '$_nombre_ingredientes', '$_id_estado'  ";
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
				
				$ingredientes->UpdateBy("id_estado=2","ingredientes","id_ingredientes='$id_ingredientes'");
				
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