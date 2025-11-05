<?php include 'cabecera.php'; ?>
      <title> Clientes || platIA || Administrador </title>
      
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
                  <table id="tablaClientes" class="table align-middle table-hover m-0">
                    <thead>
                      <tr>
                        <th scope="col">Nro.</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">N&uacute;mero</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Estado</th>
                        <!--<th scope="col">Jefe</th>-->
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
        
        <!-- Modal -->
        <div class="modal fade" id="ModalCrearExpediente" tabindex="-1" aria-labelledby="ModalCrearExpedienteLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-center text-uppercase" id="ModalCrearExpedienteLabel">Creaci&oacute;n de expediente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Row start -->
                <div class="row">
                  <div class="col-xxl-12">
                    <div class="bg-light bg-opacity-50 p-3 mb-3 fw-bold">
                      Agrear par&aacute;metros para el nuevo expediente
                    </div>
                  </div>
                  <div class="col-lg-12 col-sm-12 col-12">
                    <div class="mb-3">
                      <div class="white-space space-big"></div>
                    </div>
                  </div>
                </div>
                <!-- Row end -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="crearExpediente()">Guardar</button>
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
        $('#tablaClientes').DataTable({
              ajax: {
                  url: 'subprocesos/listaClientes.php', // Ruta a tu archivo PHP que retorna los datos
                  dataSrc: 'data'   // Nombre del array dentro del JSON devuelto por PHP
              },
              columns: [
                  { data: 'codigo' },                   // Columna del código
                  { data: 'nombreCliente' },            // Columna del nombre de cliente
                  { data: 'numero' },                   // Columna de numero
                  { data: 'correo' },                   // Columna de correo
                  { data: 'estado' },                   // Columna de estado
                  { 
                    data: null,
                    className: 'text-center', 
                    render: function(data){
                        let verDetalle  = '',
                            cancelar = '';
                    
                        if(data.idEstado == 0){
                            verDetalle = `<a href="mensajes.php?u=<?php echo $cadena; ?>&id=${data.codigo}" class="btn btn-sm btn-success shadow-sm">
                                            <i class="bi bi-eye"></i> Mensaje WA
                                        </a>`;
                            cancelar = `<button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="bloquearCliente(${data.codigo});">
                                            <i class="bi bi-trash"></i> Bloquear
                                        </button>`;
                        }else{
                            verDetalle = `<a href="mensajes.php?u=<?php echo $cadena; ?>&id=${data.codigo}" class="btn btn-sm btn-success shadow-sm">
                                            <i class="bi bi-eye"></i> Mensajes WA
                                        </a>`;
                        }

                        return `${verDetalle} ${cancelar}`;
                    }
                  },                                    // Columna de acciones
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
      });

      function crearExpediente() {
            const data = {
                almacenes: $("#almacenes").val(), // Reemplaza con los almacenes seleccionados
                idb: <?php echo $cod_usuario; ?>
            };
            console.log('Datos enviados:', data); // Muestra la respuesta en consola
            $.ajax({
                url: 'subprocesos/crearExpediente.php', // Ruta al archivo PHP
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta recibida:', response); // Depuración: Muestra la respuesta en consola
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message
                        }).then(() => {
                            // Recargar el DataTable aquí
                            $('#tablaExpedientes').DataTable().ajax.reload(); // Reemplaza 'tuDataTable' con el ID correcto de tu DataTable
                            $("#ModalCrearExpediente").modal("hide");
                        });
                    } else {
                        $("#ModalCrearExpediente").modal("hide");
                        Swal.fire({
                            icon: 'error',
                            title: 'Ocurrió un error',
                            text: response.message
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#ModalCrearExpediente").modal("hide");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la solicitud',
                        text: 'No se pudo completar la operación. Intente nuevamente.'
                    });
                    console.error('Respuesta del servidor:', jqXHR.responseText); // Depuración: Muestra la respuesta del servidor
                }
            });
        };

        function cancelarExpediente(idExpediente) {
            const data = {
                idb: idExpediente,
                cod_usuario: <?php echo $cod_usuario; ?>
            };
            console.log('Datos enviados:', data); // Muestra la respuesta en consola
            $.ajax({
                url: 'subprocesos/cancelarExpediente.php', // Ruta al archivo PHP
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta recibida:', response); // Depuración: Muestra la respuesta en consola
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.message
                        }).then(() => {
                            // Recargar el DataTable aquí
                            $('#tablaExpedientes').DataTable().ajax.reload(); // Reemplaza 'tuDataTable' con el ID correcto de tu DataTable
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ocurrió un error',
                            text: response.message
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la solicitud',
                        text: 'No se pudo completar la operación. Intente nuevamente.'
                    });
                    console.error('Respuesta del servidor:', jqXHR.responseText); // Depuración: Muestra la respuesta del servidor
                }
            });
        };
    </script>
    </body>
</html>