<!DOCTYPE HTML>
<html lang="es">
      <head>
        <meta charset="utf-8"/>
        <title>Productos</title>


        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="view/css/estilos.css">
		<link rel="stylesheet" href="view/vendors/table-sorter/themes/blue/style.css">
	
		    <!-- Bootstrap -->
    		<link href="view/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    		<!-- Font Awesome -->
		    <link href="view/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		    <!-- NProgress -->
		    <link href="view/vendors/nprogress/nprogress.css" rel="stylesheet">
		    
		   
		    <!-- Custom Theme Style -->
		    <link href="view/build/css/custom.min.css" rel="stylesheet">
				
			
			<!-- Datatables -->
		    <link href="view/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		    
		   		

			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
			<script type="text/javascript" src="view/vendors/table-sorter/jquery.tablesorter.js"></script> 
       		 <script src="view/js/jquery.blockUI.js"></script>
            <script src="view/js/jquery.inputmask.bundle.js"></script>
            
            <script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		
			<script>
			    //webshims.activeLang("en");
			    webshims.setOptions('forms-ext', { datepicker: { dateFormat: 'yy/mm/dd' } });
				webshims.polyfill('forms forms-ext');
			</script>
           
           
       		<script src="view/input-mask/jquery.inputmask.js"></script>
			<script src="view/input-mask/jquery.inputmask.date.extensions.js"></script>
			<script src="view/input-mask/jquery.inputmask.extensions.js"></script>
			
			
			
			     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>  
                 <script src="view/js/jquery.js"></script>
		         <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        
        <script type="text/javascript">
     
        	   $(document).ready( function (){
        		   pone_espera();
        		   load_productos(1);
	   			});

        	   function pone_espera(){
        		   $.blockUI({ 
        				message: '<h4><img src="view/images/load.gif" /> Espere por favor, estamos procesando su requerimiento...</h4>',
        				css: { 
        		            border: 'none', 
        		            padding: '15px', 
        		            backgroundColor: '#000', 
        		            '-webkit-border-radius': '10px', 
        		            '-moz-border-radius': '10px', 
        		            opacity: .5, 
        		            color: '#fff',
        		           
        	        		}
        	    });
            	
		        setTimeout($.unblockUI, 500); 
		        
        	   }


        	   
        	   function load_productos(pagina){

        		   var search=$("#search_productos").val();
                   var con_datos={
           					  action:'ajax',
           					  page:pagina
           					  };
                 $("#load_productos_registrados").fadeIn('slow');
           	     $.ajax({
           	               beforeSend: function(objeto){
           	                 $("#load_productos_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
           	               },
           	               url: 'index.php?controller=Productos&action=index10&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#productos_registrados").html(x);
           	               	 $("#tabla_productos").tablesorter(); 
           	                 $("#load_productos_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#productos_registrados").html("Ocurrio un error al cargar la informacion de Productos..."+estado+"    "+error);
           	              }
           	            });

           		   }


       		   
        </script>
        
        
        
        
      		 
		        
        
        
         <script >
		    // cada vez que se cambia el valor del combo
		    $(document).ready(function(){
		    $("#Cancelar").click(function() 
			{
		    	$('#id_tipo_productos').val("0");
				$('#nombre_productos').val("");
				$('#valor_productos').val("");
				$('#imagen_productos').val("");
						     
		    }); 
		    }); 
			</script>
        
        
                  
         

        
			        
    </head>
    
    
    <body class="nav-md">
    
      <?php
        
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
      ?>
    
    
       
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col  menu_fixed">
          <div class="left_col scroll-view">
            <?php include("view/modulos/logo.php"); ?>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <?php include("view/modulos/menu_profile.php"); ?>
            <!-- /menu profile quick info -->

            <br />
			<?php include("view/modulos/menu.php"); ?>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
		<?php include("view/modulos/head.php"); ?>	
        <!-- /top navigation -->

        <!-- page content -->
		<div class="right_col" role="main">        
          
    <div class="container">
        <section class="content-header">
         <small><?php echo $fecha; ?></small>
         <ol class=" pull-right breadcrumb">
         <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Productos</li>
         </ol>
         </section>
       
  	<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>INSERTAR<small>Productos</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">






            <form  action="<?php echo $helper->url("Productos","InsertaProductos"); ?>" method="post" enctype="multipart/form-data"  class="col-lg-12 col-md-12 col-xs-12">
                               
                           <?php if ($resultEdit !="" ) { foreach($resultEdit as $resEdit) {?>
                 
                 
                 
                 
                   		   
                    		   
            <div class="row">
             					   <div class="col-lg-2 col-xs-12 col-md-2">
                        		   <div class="form-group">
                                                          <label for="id_tipo_productos" class="control-label">Tipo Producto:</label>
                                                          <select name="id_tipo_productos" id="id_tipo_productos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProd as $res) {?>
                        										<option value="<?php echo $res->id_tipo_productos; ?>" <?php if ($res->id_tipo_productos == $resEdit->id_tipo_productos )  echo  ' selected="selected" '  ;  ?>><?php echo $res->nombre_tipo_productos; ?> </option>
                        							    
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_tipo_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                    
                                   <div class="col-lg-4 col-xs-12 col-md-4">
                        		   <div class="form-group">
                                                      <label for="nombre_productos" class="control-label">Nombre Producto:</label>
                                                      <input type="hidden" class="form-control" id="id_productos" name="id_productos" value="<?php echo $resEdit->id_productos; ?>">
                                                      <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" value="<?php echo $resEdit->nombre_productos; ?>"  placeholder="nombre..">
                                                      <div id="mensaje_nombre_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                   <div class="col-lg-2 col-xs-12 col-md-2">
                        		   <div class="form-group">
                                                      <label for="valor_productos" class="control-label">Valor Producto:</label>
                                                      <input type="number" class="form-control" id="valor_productos" name="valor_productos" value="<?php echo $resEdit->valor_productos; ?>"  placeholder="valor..">
                                                      <div id="mensaje_valor_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                                          <label for="imagen_productos" class="control-label">Imagen Productos:</label>
                                                          <input type="file" class="form-control" id="imagen_productos" name="imagen_productos" value="">
                                                          <div id="mensaje_imagen_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		
                                    
            </div>        		   
                    	      
    
                 
                 
                 
                         	           	
                    	        <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; margin-top:20px">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"> Actualizar</i></button>
                                					  <a href="index.php?controller=Productos&action=index" class="btn btn-primary" ><i class="glyphicon glyphicon-floppy-remove"> Cancelar</i></a>
				  		
                                </div>
                    		    </div>
                    		    </div>
                    	           	
                    
                 
                               
                    		  
                                
                    	   <?php } } else {?>
                    		    
                    		   
                    		   
            <div class="row">
             					   <div class="col-lg-2 col-xs-12 col-md-2">
                        		   <div class="form-group">
                                                          <label for="id_tipo_productos" class="control-label">Tipo Producto:</label>
                                                          <select name="id_tipo_productos" id="id_tipo_productos"  class="form-control" >
                                                          <option value="0" selected="selected">--Seleccione--</option>
                        									<?php foreach($resultProd as $res) {?>
                        										<option value="<?php echo $res->id_tipo_productos; ?>" ><?php echo $res->nombre_tipo_productos; ?> </option>
                        							    
                        							        <?php } ?>
                        								   </select> 
                                                          <div id="mensaje_id_tipo_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                    
                                   <div class="col-lg-4 col-xs-12 col-md-4">
                        		   <div class="form-group">
                                                      <label for="nombre_productos" class="control-label">Nombre Producto:</label>
                                                      <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" value=""  placeholder="nombre..">
                                                      <div id="mensaje_nombre_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                   <div class="col-lg-2 col-xs-12 col-md-2">
                        		   <div class="form-group">
                                                      <label for="valor_productos" class="control-label">Valor Producto:</label>
                                                      <input type="number" class="form-control" id="valor_productos" name="valor_productos" value=""  placeholder="valor..">
                                                      <div id="mensaje_valor_productos" class="errores"></div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-xs-12 col-md-3">
                        		    <div class="form-group">
                                                          <label for="imagen_productos" class="control-label">Imagen Productos:</label>
                                                          <input type="file" class="form-control" id="imagen_productos" name="imagen_productos" value="">
                                                          <div id="mensaje_imagen_productos" class="errores"></div>
                                    </div>
                        		    </div>
                        		
                                    
            </div>        		   
                    	      
                           	           	
                    	           	
                    	        <div class="row">
                    		    <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: center; margin-top:20px">
                    		    <div class="form-group">
                                                      <button type="submit" id="Guardar" name="Guardar" class="btn btn-success"><i class="glyphicon glyphicon-floppy-saved"> Guardar</i></button>
                                					  <button type="button" id="Cancelar" name="Cancelar" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-remove"> Cancelar</i></button>
                                
                                </div>
                    		    </div>
                    		    </div>
                    	           	
                    	           	
                    	           	
                    		     <?php } ?>
                    		      
                    		   
  
              </form>
  
                  </div>
                </div>
              </div>
		
  
      
        <!-- /page content -->
		
		<div class="col-md-12 col-lg-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>LISTADO<small>Productos</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
					
				
				   <section class="content">
                   <div class='nav-tabs-custom'>
          	       <ul id="myTabs" class="nav nav-tabs">
                 
                    <li id="nav-activos" class="active"><a href="#activos" data-toggle="tab">Productos</a></li>
                   </ul>
				
				
				
				<div class="tab-content">
 		        <br>
                <div class="tab-pane active" id="activos">
                
					<div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_productos" name="search_productos" onkeyup="load_productos(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_productos_registrados" ></div>	
					<div id="productos_registrados"></div>	
				 
				</div>
				
				
				
				
				
				
				</div>
				</div>
				</section>
				
				
				
				
				
				
					
                  
                  </div>
                </div>
              </div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
      
      </div>
    </div>

</div>
    
    
    
  
 
     <!-- Bootstrap -->
    <script src="view/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    
    
    
    <!-- NProgress -->
    <script src="view/vendors/nprogress/nprogress.js"></script>
   
   
    <!-- Datatables -->
    <script src="view/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    
    
    <script src="view/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="view/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    
    
    
    <!-- Custom Theme Scripts -->
    <script src="view/build/js/custom.min.js"></script>
	<script src="view/js/jquery.inputmask.bundle.js"></script>
	<!-- codigo de las funciones -->

	
  </body>
</html>   