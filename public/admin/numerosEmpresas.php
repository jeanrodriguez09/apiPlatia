<?php 
    include 'cabecera.php'; 
    !empty($_GET['idb']) ? $idEmpresa=$_GET['idb'] : header('location: index.php?u='.$cadena);
    $sEmpresa="SELECT * FROM empresas WHERE id=".$idEmpresa;
    $qsEmpresa=$link->query($sEmpresa);
    $csEmpresa=mysqli_num_rows($qsEmpresa);
    if($csEmpresa>0){
        while($datosEmpresa=mysqli_fetch_array($qsEmpresa)){
            $nombreEmpresa=$datosEmpresa['nombre'];
        }
    }else{
        header('location:index.php?u='.$cadena);
    }
?>
      <title> N&uacute;meros asociados || platIA || Administrador </title>
      
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
                <h3>N&uacute;meros de la empresa: <?php echo $nombreEmpresa; ?></h3>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ModalRegistroNumero">
                    Registrar n&uacute;mero <i class="bi bi-file-plus m-0"></i>
                </button>
                <div class="table-responsive">
                    <table id="tablaNumerosEmpresa" class="table align-middle table-hover m-0">
                        <thead>
                          <tr>
                            <th scope="col">Orden</th>
                            <th scope="col">N&uacute;mero</th>
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
        <div class="modal fade" id="ModalRegistroNumero" tabindex="-1" aria-labelledby="ModalRegistroNumeroLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content shadow-lg border-0">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-uppercase" id="ModalRegistroNumeroLabel" style="color: #ffffff;">Informaci&oacute;n de la empresa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                  
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="text" class="form-control" id="nombreEmpresa">
                      <label for="nombreEmpresa">Nombre o Raz&oacute;n Social</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="email" class="form-control" id="correoEmpresa">
                      <label for="correoEmpresa">Correo Electr&oacute;nico</label>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success">Guardar</button>
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
                const data = {
                    idb: <?php echo $cod_usuario; ?>,
                    idEmpresa: <?php echo $idEmpresa; ?>
                };
                const tabla = $('#tablaNumerosEmpresa').DataTable({
                    ajax: {
                        url: 'subprocesos/listaNumerosEmpresa.php', // Ruta a tu archivo PHP que retorna los datos
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        dataSrc: 'data',   // Nombre del array dentro del JSON devuelto por PHP
                    },
                    columns: [
                        { data: 'contador' },                 // Columna del contador
                        { data: 'numero' },                   // Columna del código
                        { data: 'estado' },                   // Columna de estado
                        { 
                            data: null,
                            className: 'text-center', 
                            render: function(data){
                                let desactivar  = '',
                                    activar     = '',
                                    verDetalle  = '';
            
                                if(data.idEstado==0){
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistroNumero" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button> `;
                                    activar     = `<button type="button" class="btn btn-sm btn-success shadow-sm" onclick="activarEmpresa(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Activar
                                                    </button> `;
                                    desactivar  = ` `;
                                } else {
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistroNumero" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button> `;
                                    activar     = ` `;
                                    desactivar  = `<button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="desactivarEmpresa(${data.codigo});">
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

    
            function detalles(idNumeroEmpresa) {
                // Limpiar la tabla
                
                $.ajax({
                    url: 'subprocesos/detalleNumeroEmpresa.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { idNumeroEmpresa: idNumeroEmpresa },
                    success: function (response) {
                        if (response.status === 'success') {
                            
                            const datos = response.data;
                            //console.log(datos);
                            // Asignar valores al modal
                            //$('#idEmpresa').val(datos[0].id);
                            $('#nombreEmpresa').val(datos.nombre);
                            $('#correoEmpresa').val(datos.email_contacto);
                            

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
            
            $('#ModalRegistroNumero').on('hidden.bs.modal', function () {
            // Limpiar todos los campos del modal
            $('#ModalRegistroNumero input, #ModalRegistroNumero textarea').val('');
        });
        </script> 
    
    </body>
</html>