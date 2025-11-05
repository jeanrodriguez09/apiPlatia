<?php include 'cabecera.php'; ?>
      <title> Pedidos Procesados || platIA || Administrador </title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="../assets/images/favicon.ico" />
      
    <?php include 'inc/css.php'; ?>
    
  </head>
  <body class=" color-light ">
    <!-- loader Start -->
    <?php echo 'inc/preloader.php'; ?>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
      <?php 
        include 'inc/navbar.php'; 
        require_once 'inc/helper.php'; 
      ?>
      <div class="content-page">
      <div class="container-fluid">
          
        <!-- Row start -->
        <div class="row">
          <div class="col-xxl-12">
            <div class="card shadow mb-4">
              <div class="card-body">
                <div class="table-responsive">
                    <table id="tablaPedidosProcesados" class="table align-middle table-hover m-0">
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
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- Row end -->
        
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
      </div>
    </div>
    <!-- Wrapper End-->
    <?php include 'inc/footer.php'; ?>
    <?php include 'inc/scripts.php'; ?>
    
    <script>
            $(document).ready(function () {
                const tabla = $('#tablaPedidosProcesados').DataTable({
                    ajax: {
                        url: 'subprocesos/listaPedidosProcesados.php', // Ruta a tu archivo PHP que retorna los datos
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
    
    </body>
</html>