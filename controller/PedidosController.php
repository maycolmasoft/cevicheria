<?php
class PedidosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        session_start();
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            //Creamos el objeto usuario
            $rol=new RolesModel();
            $resultRol = $rol->getAll("nombre_rol");
            $resultSet="";
            $estado = new EstadoModel();
            $resultEst = $estado->getAll("nombre_estado");
            
            $usuarios = new UsuariosModel();
            
            $nombre_controladores = "Usuarios";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $usuarios->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                
                
                $resultEdit = "";
                
                if (isset ($_GET["id_usuarios"])   )
                {
                    
                    
                    $columnas = " usuarios.id_usuarios,
								  usuarios.cedula_usuarios,
								  usuarios.nombre_usuarios,
								  usuarios.clave_usuarios,
								  usuarios.pass_sistemas_usuarios,
								  usuarios.telefono_usuarios,
								  usuarios.celular_usuarios,
								  usuarios.correo_usuarios,
								  rol.id_rol,
								  rol.nombre_rol,
								  estado.id_estado,
								  estado.nombre_estado,
								  usuarios.fotografia_usuarios,
								  usuarios.creado";
                    
                    $tablas   = "public.usuarios,
								  public.rol,
								  public.estado";
                    
                    $id       = "usuarios.id_usuarios";
                    
                    $_id_usuarios = $_GET["id_usuarios"];
                    $where    = "rol.id_rol = usuarios.id_rol AND estado.id_estado = usuarios.id_estado AND usuarios.id_usuarios = '$_id_usuarios' ";
                    $resultEdit = $usuarios->getCondiciones($columnas ,$tablas ,$where, $id);
                }
                
                
                $this->view("Pedidos",array(
                    "resultSet"=>$resultSet, "resultRol"=>$resultRol, "resultEdit" =>$resultEdit, "resultEst"=>$resultEst
                    
                ));
                
            }
            else
            {
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a Usuarios"
                    
                ));
                
            }
            
            
        }
        else{
            
            $this->redirect("Pedidos","sesion_caducada");
            
        }
        
    }
    
    
    public function index10(){
    	 
    	session_start();
    	$id_rol=$_SESSION["id_rol"];
    	$usuarios = new UsuariosModel();
    	$where_to="";
    	$columnas = " usuarios.id_usuarios,
								  usuarios.cedula_usuarios,
								  usuarios.nombre_usuarios,
								  usuarios.clave_usuarios,
								  usuarios.pass_sistemas_usuarios,
								  usuarios.telefono_usuarios,
								  usuarios.celular_usuarios,
								  usuarios.correo_usuarios,
								  rol.id_rol,
								  rol.nombre_rol,
								  estado.id_estado,
								  estado.nombre_estado,
								  usuarios.fotografia_usuarios,
								  usuarios.creado";
    		
    	$tablas   = "public.usuarios,
								  public.rol,
								  public.estado";
    		
    	$where    = " rol.id_rol = usuarios.id_rol AND
								  estado.id_estado = usuarios.id_estado AND usuarios.id_estado=1";
    		
    	$id       = "usuarios.id_usuarios";
    		
    	
    	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    	$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
    	
    	
    	
    	
    	if($action == 'ajax')
    	{
    		
    		if(!empty($search)){
    			 
    			 
    			$where1=" AND (usuarios.cedula_usuarios LIKE '".$search."%' OR usuarios.nombre_usuarios LIKE '".$search."%' OR usuarios.correo_usuarios LIKE '".$search."%' OR rol.nombre_rol LIKE '".$search."%' OR estado.nombre_estado LIKE '".$search."%')";
    			 
    			$where_to=$where.$where1;
    		}else{
    		
    			$where_to=$where;
    			 
    		}
    		
    		$html="";
    		$resultSet=$usuarios->getCantidad("*", $tablas, $where_to);
    		$cantidadResult=(int)$resultSet[0]->total;
    		
    		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
    		
    		$per_page = 50; //la cantidad de registros que desea mostrar
    		$adjacents  = 9; //brecha entre páginas después de varios adyacentes
    		$offset = ($page - 1) * $per_page;
    		
    		$limit = " LIMIT   '$per_page' OFFSET '$offset'";
    		
    		$resultSet=$usuarios->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
    		$count_query   = $cantidadResult;
    		$total_pages = ceil($cantidadResult/$per_page);
    		
    			
    		
    		
    		
    	if($cantidadResult>0)
    	{
    
    		$html.='<div class="pull-left" style="margin-left:11px;">';
    		$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidadResult.'</span>';
    		$html.='<input type="hidden" value="'.$cantidadResult.'" id="total_query" name="total_query"/>' ;
    		$html.='</div>';
    		$html.='<div class="col-lg-12 col-md-12 col-xs-12">';
			$html.='<section style="height:425px; overflow-y:scroll;">';
    		$html.= "<table id='tabla_usuarios' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
    		$html.= "<thead>";
    		$html.= "<tr>";
    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Cedula</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Teléfono</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Celular</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Correo</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Rol</th>';
    		$html.='<th style="text-align: left;  font-size: 12px;">Estado</th>';
    		
    		if($id_rol==1){
	    		
    			$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		$html.='<th style="text-align: left;  font-size: 12px;"></th>';
	    		
    		}else{
    			
    			
    			$html.='<th style="text-align: left;  font-size: 12px;"></th>';
    		}
    		
    		$html.='</tr>';
    		$html.='</thead>';
    		$html.='<tbody>';
    		 
    		$i=0;
    		
    		
    		
    		foreach ($resultSet as $res)
    		{
    			$i++;
    			$html.='<tr>';
    			$html.='<td style="font-size: 11px;"><img src="view/DevuelveImagenView.php?id_valor='.$res->id_usuarios.'&id_nombre=id_usuarios&tabla=usuarios&campo=fotografia_usuarios" width="80" height="60"></td>';
    			$html.='<td style="font-size: 11px;">'.$i.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->cedula_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->telefono_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->celular_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->correo_usuarios.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_rol.'</td>';
    			$html.='<td style="font-size: 11px;">'.$res->nombre_estado.'</td>';
    			
    			if($id_rol==1){
    			
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_usuarios='.$res->id_usuarios.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
    				$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=borrarId&id_usuarios='.$res->id_usuarios.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
    				//$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
    			
    			
    			}else{
    			
    				//$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
    			
    			}
    			
    				$html.='</tr>';
    		}
    		
    		
    		$html.='</tbody>';
    		$html.='</table>';
    		$html.='</section></div>';
    		$html.='<div class="table-pagination pull-right">';
    		$html.=''. $this->paginate_usuarios_activos("index.php", $page, $total_pages, $adjacents).'';
    		$html.='</div>';
    		
    		
    		 
    	}else{
    		$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
    		$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
    		$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    		$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay usuarios activos registrados...</b>';
    		$html.='</div>';
    		$html.='</div>';
    	}
    	
    	
    	echo $html;
    	die();
    	 
    	} 
    	 
    	 
    }
    
    public function ajax_busca_cliente(){
        
        $cedula_cliente = (isset($_REQUEST['cedula_cliente'])&& $_REQUEST['cedula_cliente'] !=NULL)?$_REQUEST['cedula_cliente']:'';
        
        if($cedula_cliente!=''){
            
            $clientes = null; $clientes = new ClientesModel();
            
            $where = " identificacion_clientes = '$cedula_cliente'";
            
            $resultado = $clientes->getBy($where);
            
            if(is_array($resultado))
            
            print_r($resultado);
            
        }
    }
    
    
}
    
       
    
    