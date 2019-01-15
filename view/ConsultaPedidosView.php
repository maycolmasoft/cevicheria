<!DOCTYPE HTML>
<html lang="es">
      <head>
        <meta charset="utf-8"/>
        <title>Consulta Pedidos</title>

	
		
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
        
       
       
       
        <script type="text/javascript">
     
        	   $(document).ready( function (){
        		 
        		 			  $("#reporte").click(function() 
        					  {
        				    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        				    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

        				    	var desde = $("#desde").val();
        				    	var hasta = $("#hasta").val();
        				    	
        				    	
        						if(desde > hasta){

        							$("#mensaje_desde").text("Fecha desde no puede ser mayor a hasta");
        				    		$("#mensaje_desde").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_desde").fadeOut("slow"); //Muestra mensaje de error
        				    		
        						} 


        						if(hasta < desde){

        							$("#mensaje_hasta").text("Fecha hasta no puede ser menor a desde");
        				    		$("#mensaje_hasta").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_hasta").fadeOut("slow"); //Muestra mensaje de error
        				    		
        						} 
        						
        				    					    

        					}); 


        				        $( "#desde" ).focus(function() {
        						  $("#mensaje_desde").fadeOut("slow");
        					    });
        						
        				        $( "#hasta" ).focus(function() {
          						  $("#mensaje_hasta").fadeOut("slow");
          					    });
        		
        		   
	   			});

	   	</script>
       
       
        
        
        <script type="text/javascript">
     
        	   $(document).ready( function (){
        		   pone_espera();
        		   load_pedidos_x_entregar(1);
        		   load_pedidos_entregados(1);


        		 			  $("#buscar_x_entregar").click(function() 
        					{
        				    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        				    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

        				    	var desde = $("#desde_x_entregar").val();
        				    	var hasta = $("#hasta_x_entregar").val();
        				    	
        				    	
        						if(desde > hasta){

        							$("#mensaje_desde_x_entregar").text("Fecha desde no puede ser mayor a hasta");
        				    		$("#mensaje_desde_x_entregar").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_desde_x_entregar").fadeOut("slow"); //Muestra mensaje de error
        				    		load_pedidos_x_entregar(1);
        			        		   
        						} 


        						if(hasta < desde){

        							$("#mensaje_hasta_x_entregar").text("Fecha hasta no puede ser menor a desde");
        				    		$("#mensaje_hasta_x_entregar").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_hasta_x_entregar").fadeOut("slow"); //Muestra mensaje de error
        				    		load_pedidos_x_entregar(1);
        			        		   
        						} 
        						
        				    					    

        					}); 



        		 			  $("#buscar_entregados").click(function() 
        					{
        				    	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        				    	var validaFecha = /([0-9]{4})\-([0-9]{2})\-([0-9]{2})/;

        				    	var desde = $("#desde_entregados").val();
        				    	var hasta = $("#hasta_entregados").val();
        				    	
        				    	
        						if(desde > hasta){

        							$("#mensaje_desde_entregados").text("Fecha desde no puede ser mayor a hasta");
        				    		$("#mensaje_desde_entregados").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_desde_entregados").fadeOut("slow"); //Muestra mensaje de error
        				    	
        			        		   load_pedidos_entregados(1);
        						} 


        						if(hasta < desde){

        							$("#mensaje_hasta_entregados").text("Fecha hasta no puede ser menor a desde");
        				    		$("#mensaje_hasta_entregados").fadeIn("slow"); //Muestra mensaje de error
        				            return false;
        				            
            					}else 
        				    	{
        				    		$("#mensaje_hasta_entregados").fadeOut("slow"); //Muestra mensaje de error
        				    	
        			        		   load_pedidos_entregados(1);
        						} 
        						
        				    					    

        					}); 


        				        $( "#desde_x_entregar" ).focus(function() {
        						  $("#mensaje_desde_x_entregar").fadeOut("slow");
        					    });
        						
        				        $( "#hasta_x_entregar" ).focus(function() {
          						  $("#mensaje_hasta_x_entregar").fadeOut("slow");
          					    });
        						
        				        $( "#desde_entregados" ).focus(function() {
          						  $("#mensaje_desde_entregados").fadeOut("slow");
          					    });
          						
          				        $( "#hasta_entregados" ).focus(function() {
            						  $("#mensaje_hasta_entregados").fadeOut("slow");
            					 });

        		   
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
            	
		        setTimeout($.unblockUI, 300); 
		        
        	   }

        	   
        	   function load_pedidos_x_entregar(pagina){


        		   var search=$("#search_pedidos_x_entregar").val();
        		   var desde=$("#desde_x_entregar").val();
        		   var hasta=$("#hasta_x_entregar").val();
                   var con_datos={
           					  action:'ajax',
           					  page:pagina,
           					  desde:desde,
           					  hasta:hasta
           					  };
                 $("#load_pedidos_x_entregar_registrados").fadeIn('slow');
           	     $.ajax({
           	               beforeSend: function(objeto){
           	                 $("#load_pedidos_x_entregar_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
           	               },
           	               url: 'index.php?controller=ConsultaPedidos&action=search_x_entregar&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#pedidos_x_entregar_registrados").html(x);
           	               	 $("#tabla_pedidos_x_entregar").tablesorter(); 
           	                 $("#load_pedidos_x_entregar_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#pedidos_x_entregar_registrados").html("Ocurrio un error al cargar la informacion de pedidos x entregar..."+estado+"    "+error);
           	              }
           	            });


           		   }

        	   function load_pedidos_entregados(pagina){


        		   var search=$("#search_pedidos_entregados").val();
        		   var desde=$("#desde_entregados").val();
        		   var hasta=$("#hasta_entregados").val();
                   var con_datos={
           					  action:'ajax',
           					  page:pagina,
           					  desde:desde,
           					  hasta:hasta
           					  };
                 $("#load_pedidos_entregados_registrados").fadeIn('slow');
           	     $.ajax({
           	               beforeSend: function(objeto){
           	                 $("#load_pedidos_entregados_registrados").html('<center><img src="view/images/ajax-loader.gif"> Cargando...</center>')
           	               },
           	               url: 'index.php?controller=ConsultaPedidos&action=search_entregados&search='+search,
           	               type: 'POST',
           	               data: con_datos,
           	               success: function(x){
           	                 $("#pedidos_entregados_registrados").html(x);
           	               	 $("#tabla_pedidos_entregados").tablesorter(); 
           	                 $("#load_pedidos_entregados_registrados").html("");
           	               },
           	              error: function(jqXHR,estado,error){
           	                $("#pedidos_entregados_registrados").html("Ocurrio un error al cargar la informacion de pedidos entregados..."+estado+"    "+error);
           	              }
           	            });


           		   }
       		   
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
            <?php
       $sel_menu = "";
       
    
       if($_SERVER['REQUEST_METHOD']=='POST' )
       {
       	 
       	 
       	$sel_menu=$_POST['criterio'];
       	
       	 
       }
      
	 	?>
    <div class="container">
        <section class="content-header">
         <small><?php echo $fecha; ?></small>
         <ol class=" pull-right breadcrumb">
         <li><a href="<?php echo $helper->url("Usuarios","Bienvenida"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Consulta Pedidos</li>
         </ol>
         </section>
       
  
  	<div class="col-md-12 col-lg-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Panel<small>Pedidos</small></h2>
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
                 
                    <li id="nav-entregar" class="active"><a href="#entregar" data-toggle="tab">Pedidos por Entregar</a></li>
                     <li id="nav-entregados"><a href="#entregados" data-toggle="tab" >Pedidos Entregados</a></li>
                   </ul>
				
				
				
				<div class="tab-content">
 		        <br>
                <div class="tab-pane active" id="entregar">
                
					
				<form  action="<?php echo $helper->url("ConsultaPedidos","generar_reporte_x_entregar"); ?>" method="post" enctype="multipart/form-data" target="_blank"  class="col-lg-12 col-md-12 col-xs-12">
                  <div class="row" style="margin-left:1px;">
									
									<div class="col-lg-2 col-xs-12 col-md-2">
                        		    <div class="form-group">
                                                          <label for="desde_x_entregar" class="control-label">Desde:</label>
                                                          <input type="date" class="form-control" id="desde_x_entregar" name="desde_x_entregar" value="" >
                                                          <div id="mensaje_desde_x_entregar" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    
                        		    
                        		    <div class="col-lg-2 col-xs-12 col-md-2">
                        		    <div class="form-group">
                                                          <label for="hasta_x_entregar" class="control-label">Hasta:</label>
                                                          <input type="date" class="form-control" id="hasta_x_entregar" name="hasta_x_entregar" value="">
                                                          <div id="mensaje_hasta_x_entregar" class="errores"></div>
                                               
                                    </div>
                                    </div>
                        		    
                        		    
                        		    <div class="col-xs-12 col-md-2 col-lg-2" style="text-align: center; margin-top:22px">
                    		        <div class="form-group">
                        		    <button type="button" id="buscar_x_entregar" name="buscar_x_entregar" class="btn btn-info"><i class="glyphicon glyphicon-search"></i></button>
                                	<button type="submit" id="reporte_x_entregar" name="reporte_x_entregar" class="btn btn-success"><i class="glyphicon glyphicon-print"></i></button>
                                	
                                	</div>
                                    </div>
                        		    
				  </div>
				  
					
					<div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_pedidos_x_entregar" name="search_pedidos_x_entregar" onkeyup="load_pedidos_x_entregar(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_pedidos_x_entregar_registrados" ></div>	
					<div id="pedidos_x_entregar_registrados"></div>	
				
			 </form>
					
					
				 
				</div>
				
				
				<div class="tab-pane" id="entregados">
               
               
               	<form  action="<?php echo $helper->url("ConsultaPedidos","generar_reporte_entregado"); ?>" method="post" enctype="multipart/form-data" target="_blank"  class="col-lg-12 col-md-12 col-xs-12">
                  <div class="row" style="margin-left:1px;">
									<div class="col-lg-2 col-xs-12 col-md-2">
                        		    <div class="form-group">
                                                          <label for="desde_entregados" class="control-label">Desde:</label>
                                                          <input type="date" class="form-control" id="desde_entregados" name="desde_entregados" value="" >
                                                          <div id="mensaje_desde_entregados" class="errores"></div>
                                    </div>
                        		    </div>
                        		    
                        		    <div class="col-lg-2 col-xs-12 col-md-2">
                        		    <div class="form-group">
                                                          <label for="hasta_entregados" class="control-label">Hasta:</label>
                                                          <input type="date" class="form-control" id="hasta_entregados" name="hasta_entregados" value="">
                                                          <div id="mensaje_hasta_entregados" class="errores"></div>
                                               
                                    </div>
                                    </div>
                        		    
                        		    <div class="col-xs-12 col-md-2 col-lg-2" style="text-align: center; margin-top:22px">
                    		        <div class="form-group">
                        		    <button type="button" id="buscar_entregados" name="buscar_entregados" class="btn btn-info"><i class="glyphicon glyphicon-search"></i></button>
                                	<button type="submit" id="reporte_entregados" name="reporte_entregados" class="btn btn-success"><i class="glyphicon glyphicon-print"></i></button>
                                	
                                	</div>
                                    </div>
                        		    
				  </div>
				  
					
					<div class="pull-right" style="margin-right:11px;">
					<input type="text" value="" class="form-control" id="search_pedidos_entregados" name="search_pedidos_entregados" onkeyup="load_pedidos_entregados(1)" placeholder="search.."/>
					</div>
					
					
					<div id="load_pedidos_entregados_registrados" ></div>	
					<div id="pedidos_entregados_registrados"></div>	
				
			 </form>
						
				
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
	


	
  </body>
</html>   