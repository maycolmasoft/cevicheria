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
        $mesa_id = (isset($_REQUEST['id_mesas'])&& $_REQUEST['id_mesas'] !=NULL)?$_REQUEST['id_mesas']:'';
        
        if($cedula_cliente!=''){
            
            $clientes = null; $clientes = new ClientesModel();
            
            $where = " identificacion_clientes = '$cedula_cliente'";
            
            /*$resultado = array();
            
            try{
                $resultado = $clientes->getBy($where);
            }catch (Exception $e){
                
            }*/
            
            $resultado = $clientes->getBy($where);            
            
            if(is_array($resultado)){
                if(count($resultado)>0){
                    
                    $actualizado = $clientes->UpdateBy("mesa_ocupada='t'","mesas","id_mesas=$mesa_id");
                    
                    //print_r($actualizado);
                                      
                    echo json_encode($resultado);
                }
            }
            
            
            
        }
    }
    
    public function ajax_trae_productos(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $usuarios = new UsuariosModel();
        $productos = null; $productos = new ProductosModel();
        $where_to="";
        $columnas = "  productos.id_productos, 
                  productos.nombre_productos, 
                  productos.valor_productos, 
                  productos.id_tipo_productos, 
                  productos.imagen_productos, 
                  tipo_productos.nombre_tipo_productos";
        
        $tablas   = "public.productos, 
                    public.tipo_productos";
        
        $where    = " tipo_productos.id_tipo_productos = productos.id_tipo_productos";
        
        $id       = "productos.id_productos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (productos.nombre_productos LIKE '".$search."%' OR tipo_productos.nombre_tipo_productos LIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$productos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 50; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$productos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Tipo Producto</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
                /*if($id_rol==1){
                    
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                    
                }else{
                    
                    
                    $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                }*/
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                $i=0;
                
                
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;"><img src="view/DevuelveImagenView.php?id_valor='.$res->id_productos.'&id_nombre=id_productos&tabla=productos&campo=imagen_productos" width="80" height="60"></td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_tipo_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->valor_productos.'</td>';
                    $html.='<td class="col-xs-1"><div class="pull-right">';
                    $html.='<input type="text" class="form-control input-sm"  id="cantidad_'.$res->id_productos.'" value="1"></div></td>';
                    
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="#" onclick="agregar_pedido('.$res->id_productos.')" class="btn btn-info" style="font-size:65%;"><i class="glyphicon glyphicon-plus"></i></a></span></td>';
                    
                    
                    /*if($id_rol==1){
                        
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_productos='.$res->id_productos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
                        $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=borrarId&id_productos='.$res->id_productos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
                        //$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
                        
                        
                    }else{
                        
                        //$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
                        
                    }*/
                    
                    $html.='</tr>';
                }
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_temp_productos("index.php", $page, $total_pages, $adjacents).'';
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
            
        } 
    }
    
    public function paginate_temp_productos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        for($i=$pmin; $i<=$pmax; $i++) {
            if($i==$page) {
                $out.= "<li class='active'><a>$i</a></li>";
            }else if($i==1) {
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function insertar_temp_pedido(){
        
        session_start();
        
        $_id_usuarios = $_SESSION['id_usuarios'];
        
        $producto_id = (isset($_REQUEST['id_productos'])&& $_REQUEST['id_productos'] !=NULL)?$_REQUEST['id_productos']:0;
        
        $cliente_id = (isset($_REQUEST['id_clientes'])&& $_REQUEST['id_clientes'] !=NULL)?$_REQUEST['id_clientes']:0;
        
        $mesa_id = (isset($_REQUEST['id_mesas'])&& $_REQUEST['id_mesas'] !=NULL)?$_REQUEST['id_mesas']:0;
        
        $cantidad = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:0;
        
        
        if($_id_usuarios!='' && $producto_id>0 && $cliente_id >0 && $mesa_id >0 ){
            
            $_session_id = session_id();
            
            //para insertado de temp
            $temp_pedido = null; $temp_pedido = new TempPedidosModel();
            $funcion = "ins_temp_pedidos";            
            $parametros = "'$cliente_id',
		    				   '$_id_usuarios',
                               '$producto_id',
                                $cantidad,
                               '1',
                               '$mesa_id' ";
            /*nota estado de temp no esta insertado por el momento*/
            $temp_pedido->setFuncion($funcion);
            $temp_pedido->setParametros($parametros);
            $resultado=$temp_pedido->Insert();
            
            //print_r($resultado);
          
        }
    }
    
    public function ajax_mesas_disponibles(){
        
        $mesas = null; $mesas = new MesasModel();
        $where = " mesa_ocupada = 'f'";
        $resultado = $mesas->getBy($where);
        
        if(is_array($resultado)){
            if(count($resultado)>0){
                echo json_encode($resultado);
            }
        }
    }
    
    public function ajax_trae_temp_pedidos(){
        
        session_start();
        $id_rol=$_SESSION["id_rol"];
        $usuarios = new UsuariosModel();
        $productos = null; $productos = new ProductosModel();
        $where_to="";
        $columnas = "  clientes.id_clientes, 
                      clientes.apellidos_clientes, 
                      clientes.nombres_clientes, 
                      clientes.identificacion_clientes, 
                      temp_pedidos.id_temp_pedidos, 
                      productos.id_productos, 
                      productos.nombre_productos, 
                      productos.imagen_productos, 
                      temp_pedidos.cantidad_temp_pedidos, 
                      mesas.id_mesas, 
                      mesas.nombre_mesas, 
                      mesas.mesa_ocupada";
        
        $tablas   = "public.temp_pedidos, 
                      public.clientes, 
                      public.productos, 
                      public.mesas";
        
        $where    = " temp_pedidos.id_productos = productos.id_productos AND
                      clientes.id_clientes = temp_pedidos.id_clientes AND
                      mesas.id_mesas = temp_pedidos.id_mesas";
        
        $id       = "productos.id_productos";
        
        
        $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
        $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
        
        
        
        
        if($action == 'ajax')
        {
            
            if(!empty($search)){
                
                
                $where1=" AND (productos.nombre_productos LIKE '".$search."%' OR tipo_productos.nombre_tipo_productos LIKE '".$search."%')";
                
                $where_to=$where.$where1;
            }else{
                
                $where_to=$where;
                
            }
            
            $html="";
            $resultSet=$productos->getCantidad("*", $tablas, $where_to);
            $cantidadResult=(int)$resultSet[0]->total;
            
            $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
            
            $per_page = 50; //la cantidad de registros que desea mostrar
            $adjacents  = 9; //brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            
            $resultSet=$productos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
                $html.= "<table id='tabla_temp_pedidos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
                $html.= "<thead>";
                $html.= "<tr>";
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Mesa</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Cantidad</th>';
                $html.='<th style="text-align: left;  font-size: 12px;">Nombre</th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
                /*if($id_rol==1){
                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                
                }else{
                
                
                $html.='<th style="text-align: left;  font-size: 12px;"></th>';
                }*/
                
                $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';
                
                $i=0;
                
                
                
                foreach ($resultSet as $res)
                {
                    $i++;
                    $html.='<tr>';
                    $html.='<td style="font-size: 11px;">'.$i.'</td>';
                    $html.='<td style="font-size: 11px;"><img src="view/DevuelveImagenView.php?id_valor='.$res->id_productos.'&id_nombre=id_productos&tabla=productos&campo=imagen_productos" width="80" height="60"></td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_mesas.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->cantidad_temp_pedidos.'</td>';
                    $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
                    
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="#" onclick="eliminar_pedido('.$res->id_temp_pedidos.')" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
                    
                    
                    /*if($id_rol==1){
                    
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=index&id_productos='.$res->id_productos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-edit"></i></a></span></td>';
                    $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=borrarId&id_productos='.$res->id_productos.'" class="btn btn-danger" style="font-size:65%;"><i class="glyphicon glyphicon-trash"></i></a></span></td>';
                    //$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
                    
                    
                    }else{
                    
                    //$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Usuarios&action=search&cedula='.$res->cedula_usuarios.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-eye-open"></i></a></span></td>';
                    
                    }*/
                    
                    $html.='</tr>';
                }
                
                
                $html.='</tbody>';
                $html.='</table>';
                $html.='</section></div>';
                $html.='<div class="table-pagination pull-right">';
                $html.=''. $this->paginate_temp_pedidos("index.php", $page, $total_pages, $adjacents).'';
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
            
        }
    }
    
    public function paginate_temp_pedidos($reload, $page, $tpages, $adjacents) {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        for($i=$pmin; $i<=$pmax; $i++) {
            if($i==$page) {
                $out.= "<li class='active'><a>$i</a></li>";
            }else if($i==1) {
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='load_productos_apedir($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='load_productos_apedir(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
    public function eliminar_temp_pedido(){
        
        session_start();
        
        $_id_usuarios = $_SESSION['id_usuarios'];
        
        $pedido_temp_id = (isset($_REQUEST['id_pedido_temp'])&& $_REQUEST['id_pedido_temp'] !=NULL)?$_REQUEST['id_pedido_temp']:0;
        
        if($_id_usuarios!='' && $pedido_temp_id>0){
            
            $_session_id = session_id();
            
            //para eliminado de temp
            $temp_pedidos = new TempPedidosModel();
            
            $where = "id_temp_pedidos = $pedido_temp_id";
            $resultado=$temp_pedidos->deleteById($where);
            
            /*print_r($resultado);*/
            
        }
    }
    
    public function insertaPedidos(){
        
        session_start();
        $resultado = null;
        $usuarios=new UsuariosModel();
        $pedidos = null; $pedidos = new PedidosModel();
        
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            if (isset ($_POST["id_clientes"]))
            {
               
                $_id_clientes   = $_POST["id_clientes"];
                $_id_mesas      = $_POST['id_mesa_selecionada'];
                $_id_usuario    = $_SESSION['id_usuarios'];
                $_id_rol        = $_SESSION['id_rol'];
                
                
                //agregar numero pedido 
                $numero_pedido='0';
                
                //traer numero pedido
                
                $columna="*";
                $tabla="consecutivos";
                $where="nombre_consecutivos='PEDIDOS'";
                
                $resultado = $pedidos->getCondiciones($columna,$tabla,$where,"id_consecutivos");
                
                $numero_pedido = $resultado[0]->real_consecutivos;
                
                $valor_total_pedidos=0.0;
                
                
                $funcion = "ins_pedidos";
                
                $parametros = "'$_id_clientes',
		    				   '$_id_usuario',
		    				   '$_id_mesas',
		    	               '$numero_pedido',
		    	               '$valor_total_pedidos'";
                
                $pedidos->setFuncion($funcion);
                $pedidos->setParametros($parametros);
                $resultadoinsert=$pedidos->Insert();
                
                $columna="pedidos.id_clientes, 
                          pedidos.id_usuarios_registra, 
                          pedidos.id_mesas, 
                          pedidos.numero_pedidos, 
                          pedidos.id_pedidos";
                
                $tabla="public.pedidos";
                
                $actualizado = $pedidos->UpdateBy("real_consecutivos = real_consecutivos + 1 ","consecutivos","nombre_consecutivos='PEDIDOS'");
                
                $where="numero_pedidos='$numero_pedido' AND  id_usuarios_registra='$_id_usuario' AND id_clientes = '$_id_clientes' AND id_mesas = '$_id_mesas'";
                
                $resultado = $pedidos->getCondiciones($columna,$tabla,$where,"id_pedidos");
                
                $pedido_id = $resultado[0]->id_pedidos;
                
                if($pedido_id>0){
                    
                    $pedidos_detalle = null; $pedidos_detalle = new PedidosDetalleModel();
                    
                    $col_temp = "temp_pedidos.id_temp_pedidos, 
                              temp_pedidos.id_clientes, 
                              temp_pedidos.id_usuarios, 
                              temp_pedidos.id_productos, 
                              temp_pedidos.cantidad_temp_pedidos, 
                              temp_pedidos.estado_temp_pedidos, 
                              temp_pedidos.id_mesas, 
                              productos.valor_productos"; 
                    
                    $tab_temp="public.temp_pedidos, 
                             public.productos"; 
                    
                    $where_temp="productos.id_productos = temp_pedidos.id_productos AND 
                                temp_pedidos.id_clientes='$_id_clientes' AND temp_pedidos.id_mesas='$_id_mesas'";
                    
                    $resultTemp = $pedidos->getCondiciones($col_temp,$tab_temp,$where_temp,"temp_pedidos.id_temp_pedidos");
                    
                    if(!empty($resultTemp)){
                        
                        $funcion = "ins_pedidos_detalle";
                        
                        foreach ($resultTemp as $res){
                            
                            $producto_id = $res->id_productos;
                            $cantidad_producto = $res->cantidad_temp_pedidos;
                            $valor_producto = $res->valor_productos;
                            $valor_total = $cantidad_producto * $valor_producto;
                            
                            $parametros = "'$pedido_id',
		    				   '$producto_id',
		    				   '$cantidad_producto',
		    	               '$valor_producto',
		    	               '$valor_total',
                                'f'";
                            
                            $pedidos_detalle->setFuncion($funcion);
                            $pedidos_detalle->setParametros($parametros);
                            $resultado=$pedidos_detalle->Insert();
                        }
                        
                    }
                    
                    //actualiza mesa
                    $actualizado = $pedidos->UpdateBy("mesa_ocupada='f'","mesas","id_mesas='$_id_mesas'");
                    
                    //eliminacion de temporal
                    $temp_pedidos = new TempPedidosModel();
                    $where = "id_clientes='$_id_clientes' AND id_mesas='$_id_mesas'";
                    $resultado=$temp_pedidos->deleteById($where);
                    
                    
                    
                }
                
                               
                
                $this->redirect("Pedidos", "index");
            }
            
        }else{
            
            $error = TRUE;
            $mensaje = "Te sesión a caducado, vuelve a iniciar sesión.";
            
            $this->view("Login",array(
                "resultSet"=>"$mensaje", "error"=>$error
            ));
            
            
            die();
            
        }
    }
    
    
}
    
       
    
    