
<?php 


$controladores=$_SESSION['controladores'];
 function getcontrolador($controlador,$controladores){
 	$display="display:none";
 	
 	if (!empty($controladores))
 	{
 	foreach ($controladores as $res)
 	{
 		if($res->nombre_controladores==$controlador)
 		{
 			$display= "display:block";
 			break;
 			
 		}
 	}
 	}
 	
 	return $display;
 }
 

?>








<!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li  style="<?php echo getcontrolador("MenuAdministracion",$controladores) ?>"  ><a    ><i class="fa fa-users"></i> Administración <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li style="<?php echo getcontrolador("Usuarios",$controladores) ?>"><a href="index.php?controller=Usuarios&action=index">Usuarios</a></li>
                      <li style="<?php echo getcontrolador("Controladores",$controladores) ?>"><a href="index.php?controller=Controladores&action=index">Controladores</a></li>
                      <li style="<?php echo getcontrolador("Roles",$controladores) ?>"><a href="index.php?controller=Roles&action=index">Roles de Usuario</a></li>
                      <li style="<?php echo getcontrolador("PermisosRoles",$controladores) ?>"><a href="index.php?controller=PermisosRoles&action=index">Permisos Roles</a></li>
                      <li style="<?php echo getcontrolador("Sesiones",$controladores) ?>"><a href="index.php?controller=Sesiones&action=index">Sesiones</a></li>
                      <li style="<?php echo getcontrolador("Clientes",$controladores) ?>"><a href="index.php?controller=Clientes&action=index">Clientes</a></li>
                      <li style="<?php echo getcontrolador("Ingredientes",$controladores) ?>"><a href="index.php?controller=Ingredientes&action=index">Ingredientes</a></li>
                      <li style="<?php echo getcontrolador("Productos",$controladores) ?>"><a href="index.php?controller=Productos&action=index">Productos</a></li>
                   
                    </ul>
                  </li>
                  
                  <li  style="<?php echo getcontrolador("MenuServiciosLinea",$controladores) ?>"  ><a    ><i class="fa fa-bars"></i> Servicios en Linea <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                    
                      </ul>
                  </li>
                  
                  
                  
                </ul>
              </div>


            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              
              
              <a data-toggle="tooltip" data-placement="top" title="Salir" href="index.php?controller=Usuarios&action=Loguear">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
