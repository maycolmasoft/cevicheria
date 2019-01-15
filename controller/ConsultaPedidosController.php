<?php

class ConsultaPedidosController extends ControladorBase{

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
                  clientes.id_clientes = pedidos.id_clientes AND pedidos.entregado_pedido='TRUE'";
	    
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
	        
	        $resultSet=$pedidos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
	            $html.='<th style="text-align: left;  font-size: 12px;">Estado Pedido</th>';
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
	                if($res->entregado_pedido=='f'){
	                    $html.='<td style="font-size: 11px;">Pendiente</td>';
	                }else{
	                    $html.='<td style="font-size: 11px;">Entregado</td>';
	                }
	                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
	                $html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->creado)).'</td>';
	                
	                $html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ConsultaPedidos&action=generar_reporte&id_pedidos='.$res->id_pedidos.'" class="btn btn-info" style="font-size:65%;"><i class="glyphicon glyphicon-print"></i></a></span></td>';
	               
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
	            $html.='<h4>Aviso!!!</h4> <b>Actualmente no hay pedidos entregados registrados...</b>';
	            $html.='</div>';
	            $html.='</div>';
	        }
	        
	        echo $html;
	        die();
	        
	    }
	    
	}
	
	
	
	public function search_x_entregar(){
	
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
                  clientes.id_clientes = pedidos.id_clientes AND pedidos.entregado_pedido='FALSE'";
	
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
	
			$resultSet=$pedidos->getCondicionesPag($columnas, $tablas, $where_to, $id, $limit);
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
				$html.='<th style="text-align: left;  font-size: 12px;">Estado Pedido</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Usuario Reg.</th>';
				$html.='<th style="text-align: left;  font-size: 12px;">Fecha Reg.</th>';
				$html.='<th style="text-align: left;  font-size: 12px;"></th>';
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
					
					if($res->entregado_pedido=='f'){
					$html.='<td style="font-size: 11px;">Pendiente</td>';
					}else{
					 $html.='<td style="font-size: 11px;">Entregado</td>';
					}
					$html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
					$html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->creado)).'</td>';
					$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ConsultaPedidos&action=generar_reporte&id_pedidos='.$res->id_pedidos.'" class="btn btn-info" style="font-size:65%;"><i class="glyphicon glyphicon-print"></i></a></span></td>';
					$html.='<td style="font-size: 18px;"><span class="pull-right"><a href="index.php?controller=ConsultaPedidos&action=index&id_pedidos='.$res->id_pedidos.'" class="btn btn-success" style="font-size:65%;"><i class="glyphicon glyphicon-floppy-saved"></i></a></span></td>';
					
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
				$html.='<h4>Aviso!!!</h4> <b>Actualmente no hay pedidos por entregar registrados...</b>';
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
			
			$nombre_controladores = "ConsultaPedidos";
			$id_rol= $_SESSION['id_rol'];
			$resultPer = $pedidos->getPermisosVer("controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
	
			if (!empty($resultPer))
			{
				
					
			    if(isset($_GET["id_pedidos"])){
			        
			        $id_pedidos =$_GET["id_pedidos"];
			        
			        $colval = "entregado_pedido='TRUE'";
			        $tabla = "pedidos";
			        $where = "id_pedidos = '$id_pedidos'";
			        $resultado=$pedidos->UpdateBy($colval, $tabla, $where);
			        
			        
			        $colval1 = "entregado_pedido='TRUE'";
			        $tabla1 = "pedidos_detalle";
			        $where1 = "id_pedidos = '$id_pedidos'";
			        $resultado=$pedidos->UpdateBy($colval1, $tabla1, $where1);
			        
			        
			        
			    }
			    
			    
				$this->view("ConsultaPedidos",array(
						""=>""));
	
			}
			else
			{
				$this->view("Error",array(
						"resultado"=>"No tiene Permisos de Acceso a consultar Pedidos."
		
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
	
	
	
	
	
	public function generar_reporte(){
		

		session_start();
		$ordinario_detalle = new Ordinario_DetalleModel();
		
		$html="";
		$cedula_usuarios = $_SESSION["cedula_usuarios"];
		$fechaactual = getdate();
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$fechaactual=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
		 
		$directorio = $_SERVER ['DOCUMENT_ROOT'] . '/webcapremci';
		$dom=$directorio.'/view/dompdf/dompdf_config.inc.php';
		$domLogo=$directorio.'/view/images/lcaprem.png';
		$logo = '<img src="'.$domLogo.'" alt="Responsive image" width="200" height="50">';
		 
		
		
		if(!empty($cedula_usuarios)){
				
			if(isset($_POST["desde"]) && isset($_POST["hasta"])){
				
				
				$desde = $_POST["desde"];
				$hasta = $_POST["hasta"];
				$search = $_POST["search"];				
				
				
				$evaluacion = new EvaluacionFuncionariosModel();
				$where_to="";
				$where1="";
				$where2="";
				$columnas = "e.id_evaluacion_funcionarios,
				  e.calificacion_funcionario,
				  e.id_usuarios_funcionario,
				  e.creado,
				  e.id_usuarios_participe,
				  u.cedula_usuarios as cedula_funcionario,
				  u.nombre_usuarios as nombre_funcionario,
				  e.id_usuarios_funcionario,
				  (select us.cedula_usuarios from usuarios us where us.id_usuarios=e.id_usuarios_participe) as cedula_participe,
				  (select us.nombre_usuarios from usuarios us where us.id_usuarios=e.id_usuarios_participe) as nombre_participe";
				
				$tablas   = " public.evaluacion_funcionarios e,
  					  public.usuarios u";
				$where    = "u.id_usuarios = e.id_usuarios_funcionario";
				$id       = "e.id_evaluacion_funcionarios";
				
			
			
				if(!empty($search)){
				
				
					if($desde!="" && $hasta!=""){
							
						$where2=" AND DATE(e.creado)  BETWEEN '$desde' AND '$hasta'";
							
							
					}
				
					$where1=" AND (e.calificacion_funcionario LIKE '%".$search."%' OR u.cedula_usuarios LIKE '%".$search."%' OR u.nombre_usuarios LIKE '%".$search."%' )";
				
					$where_to=$where.$where1.$where2;
				}else{
					if($desde!="" && $hasta!=""){
				
						$where2=" AND DATE(e.creado)  BETWEEN '$desde' AND '$hasta'";
				
					}
				
					$where_to=$where.$where2;
				
				}
			
				$resultSet=$evaluacion->getCondicionesDesc($columnas, $tablas, $where_to, $id);
				
				
				$html.='<p style="text-align: right;">'.$logo.'<hr style="height: 2px; background-color: black;"></p>';
				$html.='<p style="text-align: right; font-size: 13px;"><b>Impreso:</b> '.$fechaactual.'</p>';
				$html.='<p style="text-align: center; font-size: 16px;"><b>CALIFICACIONES FUNCIONARIOS</b></p>';
				
				
				if(!empty($resultSet)){
					$cantidadResult=count($resultSet);
					
					
					$html.='<span style="text-align: left;  font-size: 15px;"><strong>Total Registros: </strong>'.$cantidadResult.'</span>';
					$html.= "<table style='width: 100%;' border=1 cellspacing=0>";
					$html.= "<thead>";
					$html.= "<tr>";
					$html.='<th style="text-align: left;  font-size: 13px;"></th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Cedula Funcionario</th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Nombre Funcionario</th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Calificación</th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Cedula Participe</th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Nombre Participe</th>';
					$html.='<th style="text-align: left;  font-size: 13px;">Fecha Registro</th>';
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
						
						if($res->calificacion_funcionario=='Excelente'){
							$Excelente++;
						}
						
						if($res->calificacion_funcionario=='Bueno'){
							$Bueno++;
						}
						
						if($res->calificacion_funcionario=='Regular'){
							$Reguar++;
						}
						if($res->calificacion_funcionario=='Malo'){
							$Malo++;
						}
						
						
						$i++;
						$html.='<tr>';
						$html.='<td style="font-size: 11px;">'.$i.'</td>';
						$html.='<td style="font-size: 11px;">'.$res->cedula_funcionario.'</td>';
						$html.='<td style="font-size: 11px;">'.$res->nombre_funcionario.'</td>';
						$html.='<td style="font-size: 11px;">'.$res->calificacion_funcionario.'</td>';
						$html.='<td style="font-size: 11px;">'.$res->cedula_participe.'</td>';
						$html.='<td style="font-size: 11px;">'.$res->nombre_participe.'</td>';
						$html.='<td style="font-size: 11px;">'.date("d/m/Y", strtotime($res->creado)).'</td>';
						$html.='</tr>';
					}
					
					
					$html.='</tbody>';
					$html.='</table>';
				
					
					
					
					
					$html.= '<div style="text-align:center;">';
					$html.= "<table style='width: 100%; margin-top:40px;' border=1 cellspacing=0>";
					$html.='<tr>';
					$html.='<th  style="text-align:center; font-size: 13px;">Excelente</th>';
					$html.='<th  style="text-align:center; font-size: 13px;">Bueno</th>';
					$html.='<th  style="text-align:center; font-size: 13px;">Regular</th>';
					$html.='<th  style="text-align:center; font-size: 13px;">Malo</th>';
					$html.='</tr>';
						
					$html.='<tr>';
					$html.='<td  style="text-align:center; font-size: 13px;">'.$Excelente.'</td>';
					$html.='<td  style="text-align:center; font-size: 13px;">'.$Bueno.'</td>';
					$html.='<td  style="text-align:center; font-size: 13px;">'.$Reguar.'</td>';
					$html.='<td  style="text-align:center; font-size: 13px;">'.$Malo.'</td>';
					$html.='</tr>';
					$html.='</table>';
					$html.='</div>';
					
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