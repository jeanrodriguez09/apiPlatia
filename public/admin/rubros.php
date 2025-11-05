<?php include 'cabecera.php'; ?>
      <title> Rubros Empresas || platIA || Administrador || Configuraciones </title>
      
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
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ModalRubros">
                    Agregar rubro <i class="bi bi-file-plus m-0"></i>
                </button>
                <div class="table-responsive">
                    <table id="tablaRubros" class="table align-middle table-hover m-0">
                        <thead>
                          <tr>
                            <th scope="col">Orden</th>
                            <th scope="col">Descripci&oacute;n</th>
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
        <div class="modal fade" id="ModalRubros" tabindex="-1" aria-labelledby="ModalRubrosLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content shadow-lg border-0">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-uppercase" id="ModalRubrosLabel" style="color: #ffffff;">Informaci&oacute;n del rubro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                  
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="hidden" id="idRubro" name="idRubro">
                      <input type="text" class="form-control" id="descripcion">
                      <label for="descripcion">Descripci&oacute;n</label>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
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
                    tabla = $('#tablaRubros').DataTable({
                    ajax: {
                        url: 'subprocesos/listaRubros.php', // Ruta a tu archivo PHP que retorna los datos
                        type: 'POST',
                        dataType: 'json',
                        dataSrc: 'data',   // Nombre del array dentro del JSON devuelto por PHP
                    },
                    columns: [
                        { data: 'contador' },                 // Columna del contador
                        { data: 'descripcion' },                   // Columna del código
                        { data: 'estado' },                   // Columna de estado
                        { 
                            data: null,
                            className: 'text-center', 
                            render: function(data){
                                let desactivar  = '',
                                    activar     = '',
                                    verDetalle  = '';
            
                                if(data.idEstado==0){
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRubros" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button> `;
                                    activar     = `<button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRubros" onclick="activarRubro(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Activar
                                                    </button> `;
                                    desactivar  = ` `;
                                } else {
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRubros" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button>`;
                                    activar     = ` `;
                                    desactivar  = `<button type="button" class="btn btn-sm btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRubros" onclick="desactivarRubro(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Desactivar
                                                    </button> `;
                                }
            
                                return `${verDetalle} ${activar} ${desactivar}`;
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

    
            function detalles(idRubro) {
                // Limpiar la tabla
                
                $.ajax({
                    url: 'subprocesos/detalleRubro.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { idRubro: idRubro },
                    success: function (response) {
                        if (response.status === 'success') {
                            
                            const datos = response.data;
                            //console.log(datos);
                            // Asignar valores al modal
                            //$('#idEmpresa').val(datos[0].id);
                            $('#idRubro').val(datos.id);
                            $('#descripcion').val(datos.descripcion);
                            

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
            
            function guardar() {
                const idRubro = $('#idRubro').val().trim();
                const descripcion = $('#descripcion').val();
                let idb = <?php echo $cod_usuario; ?>;
            
                // Validación simple
                if (descripcion === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completá todos los campos.'
                    });
                    return;
                }
            
                const datos = {
                    idb: idb,
                    idRubro: idRubro,
                    descripcion: descripcion
                };
            
                $.ajax({
                    url: 'subprocesos/guardarRubro.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: datos,
                    success: function (response) {
                        if (response.status === 'success') {
                            tabla.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message
                            }).then(() => {
                                // Cerrar modal o limpiar formulario si querés
                                $('#ModalRubros input, #ModalRubros textarea').val('');
                                $('#ModalRubros').modal('hide');
                                // cargarEmpresas();
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
                            text: 'No se pudo guardar la información.'
                        });
                        console.error('Error:', jqXHR.responseText);
                    }
                });
            }
            
            $('#ModalRubros').on('hidden.bs.modal', function () {
            // Limpiar todos los campos del modal
            $('#ModalRubros input, #ModalRubros textarea').val('');
        });
        </script> 
    
    </body>
</html>