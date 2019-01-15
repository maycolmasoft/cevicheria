<?php

class FacturaController extends ControladorBase{

	public function __construct() {
		parent::__construct();
		
	}

	
	public function search_entregados(){
	    
	    session_start();
	    
	    $pedidos = new PedidosModel();
	    $where_to="";
	    $columnas = "pedidos.id_pedidos,
                  clientes.id_clientes,
                  clientes.apellidos_clientes,
                  clientes.nombres_clientes,
                  clientes.identificacion_clientes,
                  usuarios.id_usuarios,
                  usuarios.cedula_usuarios,
                  usuarios.nombre_usuarios,
                  mesas.id_mesas,
                  mesas.nombre_mesas,
                  mesas.mesa_ocupada,
                  pedidos.numero_pedidos,
                  pedidos.valor_total_pedidos,
                  pedidos.cancelado_pedido,
                  pedidos.entregado_pedido,
                  pedidos.creado,
                  pedidos.modificado";
	    
	    $tablas   = "  public.pedidos,
                      public.clientes,
                      public.usuarios,
                      public.mesas";
	    
	    $where    = "pedidos.id_usuarios_registra = usuarios.id_usuarios AND
                  pedidos.id_mesas = mesas.id_mesas AND
                  clientes.id_clientes = pedidos.id_clientes AND pedidos.cancelado_pedido='TRUE' AND pedidos.entregado_pedido='TRUE'";
	    
	    $id       = "pedidos.id_pedidos";
	    
	    
	    
	    
	    $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	    $search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
	    $desde=  (isset($_REQUEST['desde'])&& $_REQUEST['desde'] !=NULL)?$_REQUEST['desde']:'';
	    $hasta=  (isset($_REQUEST['hasta'])&& $_REQUEST['hasta'] !=NULL)?$_REQUEST['hasta']:'';
	    
	    $where2="";
	    
	    
	    if($action == 'ajax')
	    {
	        
	        if(!empty($search)){
	            
	            
	            if($desde!="" && $hasta!=""){
	                
	                $where2=" AND DATE(pedidos.creado)  BETWEEN '$desde' AND '$hasta'";
	                
	            }
	            
	            $where1=" AND (pedidos.numero_pedidos LIKE '%".$search."%' OR usuarios.cedula_usuarios LIKE '%".$search."%' OR usuarios.nombre_usuarios LIKE '%".$search."%' OR clientes.identificacion_clientes LIKE '%".$search."%' OR clientes.nombres_clientes LIKE '%".$search."%'  OR clientes.apellidos_clientes LIKE '%".$search."%' )";
	            
	            $where_to=$where.$where1.$where2;
	        }else{
	            if($desde!="" && $hasta!=""){
	                
	                $where2=" AND DATE(pedidos.creado)  BETWEEN '$desde' AND '$hasta'";
	                
	            }
	            
	            $where_to=$where.$where2;
	            
	        }
	        
	        $html="";
	        $resultSet=$pedidos->getCantidad("*", $tablas, $where_to);
	        $cantidadResult=(int)$resultSet[0]->total;
	        
	        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	        
	        $per_page = 50; //la cantidad de registros que desea mostrar
	        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
	        $offset = ($page - 1) * $per_page;
	        
	        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
	        
	        $resultSet=$pedidos->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.= "<table id='tabla_pedidos_entregados' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
	            $html.= "<thead>";
	            $html.= "<tr>";
	            $html.='<th style="text-align: left;  font-size: 12px;"># Pedido</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Cedula Cliente</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Datos Cliente</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"># Mesa</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Usuario Reg.</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;">Fecha Reg.</th>';
	            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
	       
	            $html.='</tr>';
	            $html.='</thead>';
	            $html.='<tbody>';
	            
	            $i=0;
	            $Excelente=0;
	            $Bueno=0;
	            $Reguar=0;
	            $Malo=0;
	            foreach ($resultSet as $res)
	            {
	                
	                $i++;
	                $html.='<tr>';
	                $html.='<td style="font-size: 11px;">'.$res->numero_pedidos.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->identificacion_clientes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombres_clientes.' '.$res->apellidos_clientes.'</td>';
	                $html.='<td style="font-size: 11px;">'.$res->nombre_mesas.'</td>';
	               
	                $html.='<td style="font-size: 11px;">'.$res->valor_total_pedidos.'</td>';
	               
	                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->creado)).'</td>';
	                
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Factura&action=generar_reporte_entregado&id_pedidos='.$res->id_pedidos.'" target="_blank" class="btn btn-warning" style="font-size:65%;"><i class="glyphicon glyphicon-print"></i></a></span></td>';
	               
	                $html.='</tr>';
	            }
	            
	            
	            $html.='</tbody>';
	            $html.='</table>';
	            $html.='</section></div>';
	            $html.='<div class="table-pagination pull-right">';
	            $html.=''. $this->paginate_load_pedidos_entregados("index.php", $page, $total_pages, $adjacents).'';
	            $html.='</div>';
	            
	            
	            
	        }else{
	            $html.='<div class="col-lg-6 col-md-6 col-xs-12">';
	            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
	            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay pedidos facturados registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	public function search_x_entregar(){
	
		session_start();
		$id_rol = $_SESSION["id_rol"];
	
		$pedidos = new PedidosModel();
		$where_to="";
		$columnas = "pedidos.id_pedidos, 
                  clientes.id_clientes, 
                  clientes.apellidos_clientes, 
                  clientes.nombres_clientes, 
                  clientes.identificacion_clientes, 
                  usuarios.id_usuarios, 
                  usuarios.cedula_usuarios, 
                  usuarios.nombre_usuarios, 
                  mesas.id_mesas, 
                  mesas.nombre_mesas, 
                  mesas.mesa_ocupada, 
                  pedidos.numero_pedidos, 
                  pedidos.valor_total_pedidos, 
                  pedidos.cancelado_pedido, 
                  pedidos.entregado_pedido,
                  pedidos.creado, 
                  pedidos.modificado";
	
		$tablas   = "  public.pedidos, 
                      public.clientes, 
                      public.usuarios, 
                      public.mesas";
	
		$where    = "pedidos.id_usuarios_registra = usuarios.id_usuarios AND
                  pedidos.id_mesas = mesas.id_mesas AND
                  clientes.id_clientes = pedidos.id_clientes AND pedidos.entregado_pedido='TRUE' AND pedidos.cancelado_pedido='FALSE'";
	
		$id       = "pedidos.id_pedidos";
	
		 
		
		 
		$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
		$search =  (isset($_REQUEST['search'])&& $_REQUEST['search'] !=NULL)?$_REQUEST['search']:'';
		$desde=  (isset($_REQUEST['desde'])&& $_REQUEST['desde'] !=NULL)?$_REQUEST['desde']:'';
		$hasta=  (isset($_REQUEST['hasta'])&& $_REQUEST['hasta'] !=NULL)?$_REQUEST['hasta']:'';
		 
		$where2="";
		 
		 
		if($action == 'ajax')
		{
	
			if(!empty($search)){
	
				
				if($desde!="" && $hasta!=""){
					
					$where2=" AND DATE(pedidos.creado)  BETWEEN '$desde' AND '$hasta'";
					
				}
	
				$where1=" AND (pedidos.numero_pedidos LIKE '%".$search."%' OR usuarios.cedula_usuarios LIKE '%".$search."%' OR usuarios.nombre_usuarios LIKE '%".$search."%' OR clientes.identificacion_clientes LIKE '%".$search."%' OR clientes.nombres_clientes LIKE '%".$search."%'  OR clientes.apellidos_clientes LIKE '%".$search."%' )";
	
				$where_to=$where.$where1.$where2;
			}else{
				if($desde!="" && $hasta!=""){
						
					$where2=" AND DATE(pedidos.creado)  BETWEEN '$desde' AND '$hasta'";	
				}
				
				$where_to=$where.$where2;
	
			}
	
			$html="";
			$resultSet=$pedidos->getCantidad("*", $tablas, $where_to);
			$cantidadResult=(int)$resultSet[0]->total;
	
			$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	
			$per_page = 50; //la cantidad de registros que desea mostrar
			$adjacents  = 9; //brecha entre páginas después de varios adyacentes
			$offset = ($page - 1) * $per_page;
	
			$limit = " LIMIT   '$per_page' OFFSET '$offset'";
	
			$resultSet=$pedidos->getCondicionesPagDesc($columnas, $tablas, $where_to, $id, $limit);
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
				$html.= "<table id='tabla_pedidos_x_entregar' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
				$html.= "<thead>";
				$html.= "<tr>";
				$html.='<th style="text-align: left;  font-size: 12px;"># Pedido</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Cedula Cliente</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Datos Cliente</th>';
				$html.='<th style="text-align: left;  font-size: 12px;"># Mesa</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Valor</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Usuario Reg.</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Fecha Reg.</th>';
				$html.='<th style="text-align: left;  font-size: 12px;"></th>';
				
				
				$html.='</tr>';
				$html.='</thead>';
				$html.='<tbody>';
				 
				$i=0;
				$Excelente=0;
				$Bueno=0;
				$Reguar=0;
				$Malo=0;
				foreach ($resultSet as $res)
				{
					
					$i++;
					$html.='<tr>';
					$html.='<td style="font-size: 11px;">'.$res->numero_pedidos.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->identificacion_clientes.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombres_clientes.' '.$res->apellidos_clientes.'</td>';
					$html.='<td style="font-size: 11px;">'.$res->nombre_mesas.'</td>';
					
					$html.='<td style="font-size: 11px;">'.$res->valor_total_pedidos.'</td>';
					
					$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
					$html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->creado)).'</td>';
					
					$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=Factura&action=index2&id_pedidos='.$res->id_pedidos.'" target="_blank" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-floppy-saved"></i></a></span></td>';
					
					
					$html.='</tr>';
				}
	
	
				$html.='</tbody>';
				$html.='</table>';
				$html.='</section></div>';
				$html.='<div class="table-pagination pull-right">';
				$html.=''. $this->paginate_load_pedidos_x_entregar("index.php", $page, $total_pages, $adjacents).'';
				$html.='</div>';
	
	 
				
			}else{
				$html.='<div class="col-lg-6 col-md-6 col-xs-12">';
				$html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
				$html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
				$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay pedidos para facturar...</b>';
				$html.='</div>';
				$html.='</div>';
			}
			
			echo $html;
			die();
	
		}
	
	}
	
	
	
	
	
	
	
	public function index(){
	
		session_start();
		if (isset(  $_SESSION['nombre_usuarios']) )
		{
			
			$pedidos = new PedidosModel();
			
			$nombre_controladores = "Factura";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $pedidos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	
			if (!empty($resultPer))
			{
				
					
			    if(isset($_GET["id_pedidos"])){
			        
			        $id_pedidos =$_GET["id_pedidos"];
			        
			        $colval = "cancelado_pedido='TRUE'";
			        $tabla = "pedidos";
			        $where = "id_pedidos = '$id_pedidos'";
			        $resultado=$pedidos->UpdateBy($colval, $tabla, $where);
			        
			    }
			    
			    
				$this->view("Factura",array(
						""=>""));
	
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a Facturar."
		
				));
					
			}
				
	
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
	
	
	
	
	
	
	public function index2(){
	    
	    session_start();
	    if (isset(  $_SESSION['nombre_usuarios']) )
	    {
	        
	        $pedidos = new PedidosModel();
	        $pedidos_detalle = new PedidosDetalleModel();
	        $nombre_controladores = "Factura";
	        $id_rol= $_SESSION['id_rol'];
	        $resultPer = $pedidos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	        
	        if (!empty($resultPer))
	        {
	            $resultSetCabeza="";
	            $resultSetDetalle="";
	            
	            
	            if(isset($_GET["id_pedidos"])){
	                
	                $id_pedidos =$_GET["id_pedidos"];
	                
	               
	                $columnas = "pedidos.id_pedidos,
                              clientes.id_clientes,
                              clientes.apellidos_clientes,
                              clientes.nombres_clientes,
                              clientes.identificacion_clientes,
                              clientes.correo_clientes,
                              clientes.telefono_clientes,
                              clientes.celular_clientes,
                              usuarios.id_usuarios,
                              usuarios.cedula_usuarios,
                              usuarios.nombre_usuarios,
                              mesas.id_mesas,
                              mesas.nombre_mesas,
                              mesas.mesa_ocupada,
                              pedidos.numero_pedidos,
                              pedidos.valor_total_pedidos,
                              pedidos.cancelado_pedido,
                              pedidos.entregado_pedido,
                              pedidos.creado,
                              pedidos.modificado";
	                
	                $tablas   = "public.pedidos,
                              public.clientes,
                              public.usuarios,
                              public.mesas";
	                $where    = "pedidos.id_usuarios_registra = usuarios.id_usuarios AND
                              pedidos.id_mesas = mesas.id_mesas AND
                              clientes.id_clientes = pedidos.id_clientes AND pedidos.id_pedidos='$id_pedidos'";
	                $id       = "pedidos.id_pedidos";
	                
	                
	                $resultSetCabeza=$pedidos->getCondicionesDesc($columnas, $tablas, $where, $id);
	                
	                
	                if(!empty($resultSetCabeza)){
	                    
	                    
	                    $columnas1 = "pedidos_detalle.id_pedidos_detalle,
                                  pedidos_detalle.id_pedidos,
                                  productos.id_productos,
                                  productos.nombre_productos,
                                  productos.imagen_productos,
                                  pedidos_detalle.cantidad_productos,
                                  pedidos_detalle.valor_unitario,
                                  pedidos_detalle.valor_total,
                                  pedidos_detalle.entregado_pedido";
	                    
	                    $tablas1   = " public.pedidos_detalle,
                                    public.productos";
	                    $where1    = "productos.id_productos = pedidos_detalle.id_productos AND pedidos_detalle.id_pedidos='$id_pedidos'";
	                    $id1      = "pedidos_detalle.id_pedidos_detalle";
	                    $resultSetDetalle=$pedidos_detalle->getCondicionesDesc($columnas1, $tablas1, $where1, $id1);
	                    
	                }
	                
	                
	            }
	            
	            
	            $this->view("FacturaFinal",array(
	                "resultSetCabeza"=>$resultSetCabeza, "resultSetDetalle"=>$resultSetDetalle
	                
	            ));
	            
	        }
	        else
	        {
	            $this->view("Error",array(
	                "resultado"=>"No tiene Permisos de Acceso a Facturar."
	                
	            ));
	            
	        }
	        
	        
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
	
	
	
	
	
	
	public function paginate_load_pedidos_entregados($reload, $page, $tpages, $adjacents) {
	    
	    $prevlabel = "&lsaquo; Prev";
	    $nextlabel = "Next &rsaquo;";
	    $out = '<ul class="pagination pagination-large">';
	    
	    // previous label
	    
	    if($page==1) {
	        $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
	    } else if($page==2) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_entregados(1)'>$prevlabel</a></span></li>";
	    }else {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_entregados(".($page-1).")'>$prevlabel</a></span></li>";
	        
	    }
	    
	    // first label
	    if($page>($adjacents+1)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_entregados(1)'>1</a></li>";
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
	            $out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_entregados(1)'>$i</a></li>";
	        }else {
	            $out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_entregados(".$i.")'>$i</a></li>";
	        }
	    }
	    
	    // interval
	    
	    if($page<($tpages-$adjacents-1)) {
	        $out.= "<li><a>...</a></li>";
	    }
	    
	    // last
	    
	    if($page<($tpages-$adjacents)) {
	        $out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_entregados($tpages)'>$tpages</a></li>";
	    }
	    
	    // next
	    
	    if($page<$tpages) {
	        $out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_entregados(".($page+1).")'>$nextlabel</a></span></li>";
	    }else {
	        $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
	    }
	    
	    $out.= "</ul>";
	    return $out;
	}
	
	
	
	
	
	
	public function paginate_load_pedidos_x_entregar($reload, $page, $tpages, $adjacents) {
	
		$prevlabel = "&lsaquo; Prev";
		$nextlabel = "Next &rsaquo;";
		$out = '<ul class="pagination pagination-large">';
	
		// previous label
	
		if($page==1) {
			$out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
		} else if($page==2) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(1)'>$prevlabel</a></span></li>";
		}else {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(".($page-1).")'>$prevlabel</a></span></li>";
	
		}
	
		// first label
		if($page>($adjacents+1)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(1)'>1</a></li>";
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
				$out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(1)'>$i</a></li>";
			}else {
				$out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(".$i.")'>$i</a></li>";
			}
		}
	
		// interval
	
		if($page<($tpages-$adjacents-1)) {
			$out.= "<li><a>...</a></li>";
		}
	
		// last
	
		if($page<($tpages-$adjacents)) {
			$out.= "<li><a href='javascript:void(0);' onclick='load_pedidos_x_entregar($tpages)'>$tpages</a></li>";
		}
	
		// next
	
		if($page<$tpages) {
			$out.= "<li><span><a href='javascript:void(0);' onclick='load_pedidos_x_entregar(".($page+1).")'>$nextlabel</a></span></li>";
		}else {
			$out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
		}
	
		$out.= "</ul>";
		return $out;
	}
	
	
	
	public function  generar_reporte_entregado(){
	    
	    session_start();
	    $pedidos = new PedidosModel();
	    $pedidos_detalle = new PedidosDetalleModel();
	    
	    $html="";
	    $cedula_usuarios = $_SESSION["cedula_usuarios"];
	    $fechaactual = getdate();
	    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	    $fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	    
	    $directorio = $_SERVER ['DOCUMENT_ROOT'] . '/cevicheria';
	    $dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
	    $domLogo=$directorio.'/view/images/agua.png';
	    $logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
	    
	    
	    
	    if(!empty($cedula_usuarios)){
	        
	        
	        if(isset($_GET["id_pedidos"])){
	            
	            
	            $id_pedidos = $_GET["id_pedidos"];
	            
	            
	            $columnas = "pedidos.id_pedidos,
                              clientes.id_clientes,
                              clientes.apellidos_clientes,
                              clientes.nombres_clientes,
                              clientes.identificacion_clientes,
                              usuarios.id_usuarios,
                              usuarios.cedula_usuarios,
                              usuarios.nombre_usuarios,
                              mesas.id_mesas,
                              mesas.nombre_mesas,
                              mesas.mesa_ocupada,
                              pedidos.numero_pedidos,
                              pedidos.valor_total_pedidos,
                              pedidos.cancelado_pedido,
                              pedidos.entregado_pedido,
                              pedidos.creado,
                              pedidos.modificado";
	            
	            $tablas   = "public.pedidos,
                              public.clientes,
                              public.usuarios,
                              public.mesas";
	            $where    = "pedidos.id_usuarios_registra = usuarios.id_usuarios AND
                              pedidos.id_mesas = mesas.id_mesas AND
                              clientes.id_clientes = pedidos.id_clientes AND pedidos.id_pedidos='$id_pedidos'";
	            $id       = "pedidos.id_pedidos";
	            
	            
	            $resultSetCabeza=$pedidos->getCondicionesDesc($columnas, $tablas, $where, $id);
	            
	            
	            if(!empty($resultSetCabeza)){
	                
	                
	                
	                $_numero_pedido     =$resultSetCabeza[0]->numero_pedidos;
	                $_numero_mesa       =$resultSetCabeza[0]->nombre_mesas;
	                $_nombre_clientes   =$resultSetCabeza[0]->nombres_clientes;
	                $_apellidos_clientes   =$resultSetCabeza[0]->apellidos_clientes;
	                $_identificacion_clientes =$resultSetCabeza[0]->identificacion_clientes;
	                
	                
	                $_cedula_usuarios       =$resultSetCabeza[0]->cedula_usuarios;
	                $_nombre_usuarios       =$resultSetCabeza[0]->nombre_usuarios;
	                $_estado_pedido         =$resultSetCabeza[0]->entregado_pedido;
	                $_valor_total_pedidos   =$resultSetCabeza[0]->valor_total_pedidos;
	                $_fecha_pedido          =date("d/m/Y", strtotime($resultSetCabeza[0]->creado));
	                $_fecha_pedido=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
	                
	                
	                
	                
	                $columnas1 = "pedidos_detalle.id_pedidos_detalle,
                                  pedidos_detalle.id_pedidos,
                                  productos.id_productos,
                                  productos.nombre_productos,
                                  productos.imagen_productos,
                                  pedidos_detalle.cantidad_productos,
                                  pedidos_detalle.valor_unitario,
                                  pedidos_detalle.valor_total,
                                  pedidos_detalle.entregado_pedido";
	                
	                $tablas1   = " public.pedidos_detalle,
                                    public.productos";
	                $where1    = "productos.id_productos = pedidos_detalle.id_productos AND pedidos_detalle.id_pedidos='$id_pedidos' AND pedidos_detalle.entregado_pedido='TRUE'";
	                $id1      = "pedidos_detalle.id_pedidos_detalle";
	                $resultSetDetalle=$pedidos_detalle->getCondicionesDesc($columnas1, $tablas1, $where1, $id1);
	                
	                $html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
	                $html.='<p style="text-align: right; font-size: 13px;"><b>Fecha Pedido:</b> '.$_fecha_pedido.'</p>';
	                $html.='<p style="text-align: center; font-size: 16px;"><b>PEDIDO No. '.$_numero_pedido.'</b></p>';
	                
	                $html.='<table style="width: 100%;">';
	                
	                $html.='<tr>';
	                $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Identificación Cliente</th>';
	                $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Nombres y Apellidos Cliente</th>';
	                $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Número Mesa</th>';
	                $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Estado Pedido</th>';
	                $html.='</tr>';
	                
	                $html.='<tr>';
	                
	                $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_identificacion_clientes.'</td>';
	                $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_clientes.' '.$_apellidos_clientes.'</td>';
	                $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_numero_mesa.'</td>';
	                
	                if($_estado_pedido=='f'){
	                    $html.='<td colspan="2" style="text-align:left; font-size: 13px;">Pendiente</td>';
	                }else{
	                    $html.='<td colspan="2" style="text-align:left; font-size: 13px;">Entregado</td>';
	                }
	                
	                $html.='</tr>';
	                $html.='</table>';
	                
	                
	                if(!empty($resultSetDetalle)){
	                    
	                    
	                    
	                    $html.= "<table style='width: 100%; margin-top:40px;' border=1 cellspacing=0>";
	                    $html.= "<thead>";
	                    $html.= "<tr>";
	                    //  $html.='<th style="text-align: left;  font-size: 13px;"></th>';
	                    $html.='<th style="text-align: left;  font-size: 13px;">Nombre Producto</th>';
	                    $html.='<th style="text-align: left;  font-size: 13px;">Cantidad</th>';
	                    $html.='<th style="text-align: left;  font-size: 13px;">Valor C/U</th>';
	                    $html.='<th style="text-align: left;  font-size: 13px;">Valor Total</th>';
	                    $html.='</tr>';
	                    $html.='</thead>';
	                    $html.='<tbody>';
	                    
	                    $i=0;
	                    
	                    foreach ($resultSetDetalle as $res)
	                    {
	                        
	                        
	                        
	                        
	                        $i++;
	                        $html.='<tr>';
	                        //  $html.='<td><img src="'.$directorio.'/view/DevuelveImagenView.php?id_valor='.$res->id_productos.'&id_nombre=id_productos&tabla=productos&campo=imagen_productos" width="80" height="60"></td>';
	                        $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
	                        $html.='<td style="font-size: 11px;">'.$res->cantidad_productos.'</td>';
	                        $html.='<td style="font-size: 11px;">'.$res->valor_unitario.'</td>';
	                        $html.='<td style="font-size: 11px;">'.$res->valor_total.'</td>';
	                        $html.='</tr>';
	                    }
	                    
	                    
	                    $html.='</tbody>';
	                    $html.='</table>';
	                    
	                    
	                }
	                
	                
	                $html.='<table style="width: 100%; margin-top:40px;">';
	                
	                $html.='<tr>';
	                $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Funcionario que toma el pedido</th>';
	                $html.='<th colspan="4" style="text-align:left; font-size: 13px;"></th>';
	                $html.='<th colspan="2" style="text-align:left; font-size: 13px;"></th>';
	                $html.='<th colspan="2" style="text-align:left; font-size: 13px;"></th>';
	                $html.='</tr>';
	                
	                $html.='<tr>';
	                
	                $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_usuarios.'</td>';
	                $html.='<td colspan="4" style="text-align:left; font-size: 13px;"></td>';
	                $html.='<td colspan="2" style="text-align:left; font-size: 13px;"></td>';
	                $html.='<td colspan="2" style="text-align:left; font-size: 13px;"></td>';
	                
	                $html.='</tr>';
	                $html.='</table>';
	                
	                
	            }
	            
	            
	            $this->report("PedidosDetalle",array( "resultSet"=>$html));
	            die();
	            
	            
	        }
	        
	        
	    }else{
	        
	        $this->redirect("Usuarios","sesion_caducada");
	        
	    }
	    
	    
	    
	}
	
	
	
	public function generar_reporte_x_entregar(){
		

		session_start();
		$pedidos = new PedidosModel();
		$pedidos_detalle = new PedidosDetalleModel();
		
		$html="";
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
		$fechaactual = getdate();
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
		 
		$directorio = $_SERVER ['DOCUMENT_ROOT'] . '/cevicheria';
		$dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
		$domLogo=$directorio.'/view/images/agua.png';
		$logo = '<img src="'.$domLogo.'" alt="Responsive image" width="130" height="70">';
		 
		
		
		if(!empty($cedula_usuarios)){
				
		    
			if(isset($_GET["id_pedidos"])){
				
			    $id_pedidos = $_GET["id_pedidos"];
				
				
				$columnas = "pedidos.id_pedidos, 
                              clientes.id_clientes, 
                              clientes.apellidos_clientes, 
                              clientes.nombres_clientes, 
                              clientes.identificacion_clientes, 
                              usuarios.id_usuarios, 
                              usuarios.cedula_usuarios, 
                              usuarios.nombre_usuarios, 
                              mesas.id_mesas, 
                              mesas.nombre_mesas, 
                              mesas.mesa_ocupada, 
                              pedidos.numero_pedidos, 
                              pedidos.valor_total_pedidos, 
                              pedidos.cancelado_pedido, 
                              pedidos.entregado_pedido, 
                              pedidos.creado, 
                              pedidos.modificado";
				
				$tablas   = "public.pedidos, 
                              public.clientes, 
                              public.usuarios, 
                              public.mesas";
				$where    = "pedidos.id_usuarios_registra = usuarios.id_usuarios AND
                              pedidos.id_mesas = mesas.id_mesas AND
                              clientes.id_clientes = pedidos.id_clientes AND pedidos.id_pedidos='$id_pedidos'";
				$id       = "pedidos.id_pedidos";
				
			
				$resultSetCabeza=$pedidos->getCondicionesDesc($columnas, $tablas, $where, $id);
				
				
				if(!empty($resultSetCabeza)){
				    
				    
				    
				    $_numero_pedido     =$resultSetCabeza[0]->numero_pedidos;
				    $_numero_mesa       =$resultSetCabeza[0]->nombre_mesas;
				    $_nombre_clientes   =$resultSetCabeza[0]->nombres_clientes;
				    $_apellidos_clientes   =$resultSetCabeza[0]->apellidos_clientes;
				    $_identificacion_clientes =$resultSetCabeza[0]->identificacion_clientes;
				    
				    
				    $_cedula_usuarios       =$resultSetCabeza[0]->cedula_usuarios;
				    $_nombre_usuarios       =$resultSetCabeza[0]->nombre_usuarios;
				    $_estado_pedido         =$resultSetCabeza[0]->entregado_pedido;
				    $_valor_total_pedidos   =$resultSetCabeza[0]->valor_total_pedidos;
				    $_fecha_pedido          =date("d/m/Y", strtotime($resultSetCabeza[0]->creado));
				    $_fecha_pedido=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
				    
				  
				    
				    
				    $columnas1 = "pedidos_detalle.id_pedidos_detalle, 
                                  pedidos_detalle.id_pedidos, 
                                  productos.id_productos, 
                                  productos.nombre_productos, 
                                  productos.imagen_productos, 
                                  pedidos_detalle.cantidad_productos, 
                                  pedidos_detalle.valor_unitario, 
                                  pedidos_detalle.valor_total, 
                                  pedidos_detalle.entregado_pedido";
				    
				    $tablas1   = " public.pedidos_detalle, 
                                    public.productos";
				    $where1    = "productos.id_productos = pedidos_detalle.id_productos AND pedidos_detalle.id_pedidos='$id_pedidos' AND pedidos_detalle.entregado_pedido='FALSE'";
				    $id1      = "pedidos_detalle.id_pedidos_detalle";
				    $resultSetDetalle=$pedidos_detalle->getCondicionesDesc($columnas1, $tablas1, $where1, $id1);
				    
				    $html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
				    $html.='<p style="text-align: right; font-size: 13px;"><b>Fecha Pedido:</b> '.$_fecha_pedido.'</p>';
				    $html.='<p style="text-align: center; font-size: 16px;"><b>PEDIDO No. '.$_numero_pedido.'</b></p>';
				    
				    $html.='<table style="width: 100%;">';
				   
				    $html.='<tr>';
				    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Identificación Cliente</th>';
				    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Nombres y Apellidos Cliente</th>';
				    $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Número Mesa</th>';
				    $html.='<th colspan="2" style="text-align:left; font-size: 13px;">Estado Pedido</th>';
				    $html.='</tr>';
				    
				    $html.='<tr>';
				        
				        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_identificacion_clientes.'</td>';
				        $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_clientes.' '.$_apellidos_clientes.'</td>';
				        $html.='<td colspan="2" style="text-align:left; font-size: 13px;">'.$_numero_mesa.'</td>';
				      
				        if($_estado_pedido=='f'){
				            $html.='<td colspan="2" style="text-align:left; font-size: 13px;">Pendiente</td>';
				        }else{
				            $html.='<td colspan="2" style="text-align:left; font-size: 13px;">Entregado</td>';
				        }
				   
				    $html.='</tr>';
				    $html.='</table>';
				    
				    
				    if(!empty($resultSetDetalle)){
				        
				        
				        
				        $html.= "<table style='width: 100%; margin-top:40px;' border=1 cellspacing=0>";
				        $html.= "<thead>";
				        $html.= "<tr>";
				      //  $html.='<th style="text-align: left;  font-size: 13px;"></th>';
				        $html.='<th style="text-align: left;  font-size: 13px;">Nombre Producto</th>';
				        $html.='<th style="text-align: left;  font-size: 13px;">Cantidad</th>';
				        $html.='<th style="text-align: left;  font-size: 13px;">Valor C/U</th>';
				        $html.='<th style="text-align: left;  font-size: 13px;">Valor Total</th>';
				        $html.='</tr>';
				        $html.='</thead>';
				        $html.='<tbody>';
				        
				        $i=0;
				       
				        foreach ($resultSetDetalle as $res)
				        {
				           
				            
				            
				            
				            $i++;
				            $html.='<tr>';
				          //  $html.='<td><img src="'.$directorio.'/view/DevuelveImagenView.php?id_valor='.$res->id_productos.'&id_nombre=id_productos&tabla=productos&campo=imagen_productos" width="80" height="60"></td>';
				            $html.='<td style="font-size: 11px;">'.$res->nombre_productos.'</td>';
				            $html.='<td style="font-size: 11px;">'.$res->cantidad_productos.'</td>';
				            $html.='<td style="font-size: 11px;">'.$res->valor_unitario.'</td>';
				            $html.='<td style="font-size: 11px;">'.$res->valor_total.'</td>';
				            $html.='</tr>';
				        }
				        
				        
				        $html.='</tbody>';
				        $html.='</table>';
				        
				        
				    }
				    
				    
				    $html.='<table style="width: 100%; margin-top:40px;">';
				    
				    $html.='<tr>';
				    $html.='<th colspan="4" style="text-align:left; font-size: 13px;">Funcionario que toma el pedido</th>';
				    $html.='<th colspan="4" style="text-align:left; font-size: 13px;"></th>';
				    $html.='<th colspan="2" style="text-align:left; font-size: 13px;"></th>';
				    $html.='<th colspan="2" style="text-align:left; font-size: 13px;"></th>';
				    $html.='</tr>';
				    
				    $html.='<tr>';
				    
				    $html.='<td colspan="4" style="text-align:left; font-size: 13px;">'.$_nombre_usuarios.'</td>';
				    $html.='<td colspan="4" style="text-align:left; font-size: 13px;"></td>';
				    $html.='<td colspan="2" style="text-align:left; font-size: 13px;"></td>';
				    $html.='<td colspan="2" style="text-align:left; font-size: 13px;"></td>';
				    
				    $html.='</tr>';
				    $html.='</table>';
				    
				    
				    
				}
				
				
				
				
				
				
				
				
				$this->report("PedidosDetalle",array( "resultSet"=>$html));
				die();
				
				
			}
			
			
					
		
		}else{
		
			$this->redirect("Usuarios","sesion_caducada");
		
		}
		
		
		
		
	}
	
	
	
}
?>