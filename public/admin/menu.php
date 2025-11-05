<?php 
    $modulo="Menu";
    include 'cabecera.php'; 
?>
      <title> Panel de control principal || PlatIA </title>
      
        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico" />
        
        <?php include 'inc/css.php'; ?>  
    
    </head>
    <body class="  ">
        <?php include 'inc/preloader.php'; ?>
        <!-- loader END -->
        <!-- Wrapper Start -->
        <div class="wrapper">
          <?php include 'inc/navbar.php'; ?>
            <div class="content-page">
            	<div class="container-fluid">
            		<div class="row">
            			<div class="col-md-12 mb-4 mt-1">
            				<div class="d-flex flex-wrap justify-content-between align-items-center">
            					<h4 class="font-weight-bold">General</h4>
            					<div class="form-group mb-0 vanila-daterangepicker d-flex flex-row">
            						<div class="date-icon-set">
            							<input type="text" name="start" class="form-control" placeholder="From Date">
            							<span class="search-link">
            								<svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            								</svg>
            							</span>
            						</div>
            						<span class="flex-grow-0">
            						    <span class="btn">A</span>
            						</span>
            						<div class="date-icon-set">
            							<input type="text" name="end" class="form-control" placeholder="To Date">
            							<span class="search-link">
            								<svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            								</svg>
            							</span>
            						</div>
            					</div>
            				</div>
            			</div>
            			
            			<div class="col-md-4">
    						<div class="card">
    							<div class="card-body">
    								<div class="d-flex align-items-center">
    									<div class="">
    										<p class="mb-2 text-secondary">Ganancias totales</p>
    										<div class="d-flex flex-wrap justify-content-start align-items-center">
    											<h5 class="mb-0 font-weight-bold">$95,595</h5>
    											<p class="mb-0 ml-3 text-success font-weight-bold">+3.55%</p>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
    					<!--<div class="col-md-4">
    						<div class="card">
    							<div class="card-body">
    								<div class="d-flex align-items-center">
    									<div class="">
    										<p class="mb-2 text-secondary">Gastos totales</p>
    										<div class="d-flex flex-wrap justify-content-start align-items-center">
    											<h5 class="mb-0 font-weight-bold">$12,789</h5>
    											<p class="mb-0 ml-3 text-success font-weight-bold">+2.67%</p>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>-->
    					<div class="col-md-4">
    						<div class="card">
    							<div class="card-body">
    								<div class="d-flex align-items-center">
    									<div class="">
    										<p class="mb-2 text-secondary">Nuevos clientes</p>
    										<div class="d-flex flex-wrap justify-content-start align-items-center">
    											<h5 class="mb-0 font-weight-bold">13,984</h5>
    											<p class="mb-0 ml-3 text-danger font-weight-bold">-9.98%</p>
    										</div>
    									</div>
    								</div>
    							</div>
    						</div>
    					</div>
            			
            			<div class="col-lg-8 col-md-12">
            				<div class="card">
            					<div class="card-header d-flex justify-content-between">
            						<div class="header-title">
            							<h4 class="card-title">Nuevos pedidos (10 m&aacute;s recientes.)</h4>
            						</div>
            					</div>
            					<div class="card-body">
            						<div class="table-responsive w-100">
            						    <table id="tablaPedidosPendientesMenu" class="table align-middle table-hover m-0">
                                            <thead>
                                              <tr>
                                                <th scope="col">Orden</th>
                                                <th scope="col">Identificador</th>
                                                <th scope="col">Total del pedido</th>
                                                <th scope="col">Nombre Cliente</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Acciones</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
            							<div class="d-flex justify-content-end align-items-center border-top-table p-3">
            								<a href="pedidosPendientes.php?u=<?php echo $cadena; ?>" class="btn btn-secondary btn-sm">Ver todos los pendientes</a>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
            			
            			<div class="col-lg-4 col-md-6">
            				<div class="card">
            					<div class="card-body">
            						<h4 class="font-weight-bold mb-3">Categorias populares</h4>
            						<div id="chart-apex-column-03" class="custom-chart"></div>
            						<div class="d-flex justify-content-around align-items-center">
            							<div>
            								<svg width="24" height="24" viewBox="0 0 24 24" fill="#ffbb33" xmlns="http://www.w3.org/2000/svg">
            									<rect x="3" y="3" width="18" height="18" rx="2" fill="#ffbb33" />
            								</svg>
            								<span>M&oacute;vil</span>
            							</div>
            							<div>
            								<svg width="24" height="24" viewBox="0 0 24 24" fill="#e60000" xmlns="http://www.w3.org/2000/svg">
            									<rect x="3" y="3" width="18" height="18" rx="2" fill="#e60000" />
            								</svg>
            								<span>Laptop</span>
            							</div>
            						</div>
            						<div class="d-flex justify-content-around align-items-center mt-3">
            							<div>
            								<svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="http://www.w3.org/2000/svg">
            									<rect x="3" y="3" width="18" height="18" rx="2" fill="#04237D" />
            								</svg>
            								<span>Electr&oacute;nica</span>
            							</div>
            							<div>
            								<svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="http://www.w3.org/2000/svg">
            									<rect x="3" y="3" width="18" height="18" rx="2" fill="#8080ff" />
            								</svg>
            								<span>Otros</span>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
            			
            			<div class="col-lg-8 col-md-12">
            				<div class="row">
            					<div class="col-md-12">
            						<div class="card">
            							<div class="card-body">
            								<div class="d-flex justify-content-between align-items-center flex-wrap">
            									<h4 class="font-weight-bold">Informe de venta</h4>
            									<div class="d-flex justify-content-between align-items-center">
            										<div>
            											<svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="http://www.w3.org/2000/svg">
            												<rect x="3" y="3" width="18" height="18" rx="2" fill="#3378FF" />
            											</svg>
            											<span>Ingresos</span>
            										</div>
            										<div class="ml-3">
            											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            												<rect x="3" y="3" width="18" height="18" rx="2" fill="#19b3b3" />
            											</svg>
            											<span>Gastos</span>
            										</div>
            									</div>
            								</div>
            								<div id="chart-apex-column-01" class="custom-chart"></div>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>
            			
            			<!-- Modal Detalles -->
                        <div class="modal fade" id="ModalDetalles" tabindex="-1" aria-labelledby="ModalDetallesLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content shadow-lg border-0">
                              <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title text-uppercase" id="ModalDetallesLabel" style="color: #ffffff;">Detalles del Pedido</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row g-3">
                                  <div class="col-md-8">
                                    <div class="form-floating mb-2">
                                      <input type="number" class="form-control" id="idPedido" readonly>
                                      <label for="idPedido">Código Pedido</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                      <input type="text" class="form-control" id="nombreCliente" readonly>
                                      <label for="nombreCliente">Nombre del Cliente</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                      <input type="text" class="form-control" id="numero" readonly>
                                      <label for="numero">Contacto</label>
                                    </div>
                                    <div class="form-floating mb-2">
                                      <input type="text" class="form-control" id="direccion" readonly>
                                      <label for="direccion">Dirección de Entrega</label>
                                    </div>
                                  </div>
                                  <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                                    <div>
                                      <h6 class="text-muted mb-1">Total</h6>
                                      <h3 class="text-success">Gs. <span id="total">0</span></h3>
                                    </div>
                                  </div>
                                </div>
                        
                                <hr>
                        
                                <div class="table-responsive">
                                  <table id="tablaDetalles" class="table table-striped align-middle table-hover">
                                    <thead class="table-light">
                                      <tr>
                                        <th>Orden</th>
                                        <th>Cód. Item</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-danger">Rechazar</button>
                                <button type="button" class="btn btn-success">Aprobar</button>
                              </div>
                            </div>
                          </div>
                        </div>
            		</div>
            		<!-- Page end  -->
            	</div>
            </div>
        </div>
        <!-- Wrapper End-->
        <?php include 'inc/footer.php'; ?>
        <?php include 'inc/scripts.php'; ?>
        
        <script>
            $(document).ready(function () {
                const tabla = $('#tablaPedidosPendientesMenu').DataTable({
                    ajax: {
                        url: 'subprocesos/listaPedidosMenu.php', // Ruta a tu archivo PHP que retorna los datos
                        dataSrc: 'data'   // Nombre del array dentro del JSON devuelto por PHP
                    },
                    columns: [
                        { data: 'contador' },                 // Columna del contador
                        { data: 'codigo' },                   // Columna del código
                        { data: 'total' },                    // Columna del total
                        { data: 'nombreCliente' },            // Columna del responsable
                        { data: 'estado' },                   // Columna de estado
                        { 
                            data: null,
                            className: 'text-center', 
                            render: function(data){
                                let verRechazar  = '',
                                    verAprobar  = '',
                                    verDetalle  = '';
            
                                if(data.idEstado==0){
                                    verDetalle = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalDetalles" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button>`;
                                } else {
                                    verDetalle = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalDetalles" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button>`;
                                }
            
                                return `${verDetalle}`;
                            }
                        },
                    ],
                    paging: true,
                    searching: true,
                    info: true,
                    ordering: true,
                    autoWidth: false,
                    language: {
                        emptyTable: "No se encontraron registros para mostrar",
                        loadingRecords: "Cargando...",
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        zeroRecords: "No se encontraron resultados",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        paginate: {
                            first: "Primero",
                            last: "Último",
                            next: "Siguiente",
                            previous: "Anterior"
                        },
                        aria: {
                            sortAscending: ": activar para ordenar la columna de manera ascendente",
                            sortDescending: ": activar para ordenar la columna de manera descendente"
                        }
                    },
                    stripeClasses: [], // Desactiva las clases de rayas de las filas
                });
            
                // Recarga la tabla cada 30 segundos (30000 ms)
                setInterval(function () {
                    tabla.ajax.reload(null, false); // false para no reiniciar el paginado
                }, 10000);
            });

    
            function detalles(idPedido) {
                // Limpiar la tabla
                $('#tablaDetalles').DataTable().clear().destroy();
            
                $.ajax({
                    url: 'subprocesos/detallePedido.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { idPedido: idPedido },
                    success: function (response) {
                        if (response.status === 'success') {
                            
                            const datos = response.data;
                            // Asignar valores al modal
                            $('#idPedido').val(datos[0].codigo);
                            $('#nombreCliente').val(datos[0].nombreCliente);
                            $('#numero').val(datos[0].numero);
                            $('#direccion').val(datos[0].direccion);
                            $('#total').text(parseInt(datos[0].total).toLocaleString('es-PY'));

                            let tbody = '';
                            datos.forEach((item, index) => {
                                tbody += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.idItem}</td>
                                        <td>${item.denominacionItem}</td>
                                        <td>${item.cantidad}</td>
                                        <td>${parseInt(item.precioUnitario).toLocaleString('es-PY')}</td>
                                        <td>${parseInt(item.subTotal).toLocaleString('es-PY')}</td>
                                    </tr>
                                `;
                            });
                            $('#tablaDetalles tbody').html(tbody);
            
                            // Inicializar DataTable
                            $('#tablaDetalles').DataTable({
                                paging: true,
                                searching: false,
                                info: false,
                                ordering: false,
                                stripeClasses: [],
                                language: { emptyTable: "Sin datos" }
                            });
            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (jqXHR) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la solicitud',
                            text: 'No se pudo obtener la información.'
                        });
                        console.error('Error:', jqXHR.responseText);
                    }
                });
            }
        </script>  
        <script>
          const modal = document.getElementById('ModalDetalles');
        
          modal.addEventListener('hidden.bs.modal', function () {
            // Limpiar inputs
            $('#idPedido').val('');
            $('#nombreCliente').val('');
            $('#numero').val('');
            $('#direccion').val('');
            $('#total').text('0');
        
            // Limpiar tabla
            $('#tablaDetalles').DataTable().clear().destroy(); // Eliminar DataTable
            $('#tablaDetalles tbody').html(''); // Vaciar el tbody
          });
        </script>
    </body>
</html>