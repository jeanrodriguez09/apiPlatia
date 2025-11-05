<?php 
    $modulo="Perfil";
    include 'cabecera.php'; 
?>
      <title> Mi perfil || platIA </title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="../assets/images/favicon.ico" />
      
      <?php include 'inc/css.php'; ?>
      </head>
  <body class=" color-light ">
    <!-- loader Start -->
    <?php include 'inc/preloader.php'; ?>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
        <?php 
            include 'inc/navbar.php'; 
            require_once 'inc/helper.php'; 
        ?>
      <div class="content-page">
      <div class="container-fluid">
         <ul class="nav nav-tabs" id="myTab-1" role="tablist">
           <li class="nav-item">
              <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Datos personales</a>
           </li>
           <li class="nav-item">
              <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">Historial de cambios</a>
           </li>
        </ul>
        <div class="tab-content" id="myTabContent-2">
           <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-sm-8 col-12">
                      <div class="card shadow mb-4">
                        <div class="card-header">
                          <h5 class="card-title">Datos personales</h5>
                        </div>
                        <div class="card-body">
                            <!-- Row start -->
                            <div class="row">
                                <div class="col-6">
                                  <!-- Form Field Start -->
                                  <div class="mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombreUp" placeholder="Nombre" value="<?php echo $nombre; ?>" required />
                                  </div>
                                </div>
                                <div class="col-6"
                                  <!-- Form Field Start -->
                                  <div class="mb-3">
                                    <label for="lastName" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellidoUp" placeholder="Apellido" value="<?php echo $apellido; ?>" required />
                                  </div>
                                </div>
                                <div class="col-12">
                                  <!-- Form Field Start -->
                                  <div class="mb-3">
                                    <label for="emailId" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="emailId" placeholder="Email"
                                      value="<?php echo $correo; ?>" disabled readonly />
                                  </div>
                                </div>
                            </div>
                            <!-- Row end -->
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4 col-12">
                      <div class="card shadow mb-4">
                        <div class="card-header">
                          <h5 class="card-title">Restablecer contrase&ntilde;a</h5>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-12">
                              <!-- Form Field Start -->
                              <div class="mb-3">
                                  <label for="contrasenaActual" class="form-label">Contraseña actual</label>
                                  <div class="input-container" style="display: flex; align-items: center;">
                                    <input type="password" class="form-control password-input" id="contrasenaActual"
                                      placeholder="Ingresar contraseña actual" maxlength="65" style="flex: 1;" />
                                    <button type="button" class="toggle-password btn btn-outline-default ms-2"
                                      onclick="togglePassword('contrasenaActual')">
                                      <i class="bi bi-eye"></i>
                                    </button>
                                  </div>
                              </div>
                              <!-- Form Field Start -->
                              <div class="mb-3">
                                <label for="newPassword" class="form-label">Nueva contrase&ntilde;a</label>
                                <div class='input-container' style="display: flex; align-items: center;">
                                  <input type="password" class="form-control" class='swal2-input password-input' id="contrasenaNueva"
                                    placeholder="Ingresar nueva contrase&ntilde;a" maxlength='65' style="flex: 1;" />
                                  <button type='button' class='toggle-password btn btn-outline-default ms-2' onclick="togglePassword('contrasenaNueva')">
                                    <i class='bi bi-eye'></i>
                                  </button>
                                </div>
                              </div>
                              <!-- Form Field Start -->
                              <div class="mb-3">
                                <label for="confirmNewPassword" class="form-label">Confirmar nueva contrase&ntilde;a</label>
                                <div class='input-container' style="display: flex; align-items: center;">
                                  <input type="password" class="form-control" id="confirmarContrasenaNueva" class='swal2-input password-input' 
                                  placeholder="Confirmar nueva contrase&ntilde;a" maxlength='65' style="flex: 1;" />
                                  <button type='button' class='toggle-password btn btn-outline-default ms-2' onclick="togglePassword('confirmarContrasenaNueva')">
                                    <i class='bi bi-eye'></i>
                                  </button>
                                </div>
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <!-- Row end -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="menu.php?u=<?php echo $cadena; ?>" class="btn btn-outline-danger">
                        Salir
                    </a>
                    <button class="btn btn-primary" id="actualizarBtn" type="button">
                        Actualizar
                    </button>
                </div>
           </div>
           
           <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                    <div class="col-12">
                      <div class="table-responsive">
                        <table id="tablaHistorialUsuario"  class="table align-middle table-bordered m-0">
                          <thead>
                            <tr>
                              <th>Orden</th>
                              <th>Fecha Modificaci&oacute;n</th>
                              <th>Modificado por</th>
                              <th>Nombre Completo</th>
                              <th>Estado</th>
                              <th>Nombre Rol</th>
                              <th>Fecha Creaci&oacute;n</th>
                              <th>Creado por</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                 </div>
                 <!-- Row end -->
                 <div class="d-flex gap-2 mt-4 justify-content-end">
                    <a href="menu.php?u=<?php echo $cadena; ?>" class="btn btn-outline-secondary">
                        Salir
                    </a>
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
            // Datos que serán enviados al archivo PHP
            const data = {
                idb: <?php echo $cod_usuario; ?> // Reemplaza con el ID del usuario que modifica
            };
          $('#tablaHistorialUsuario').DataTable({
              ajax: {
                  url: 'subprocesos/listarHistorialUsuario.php', // Ruta a tu archivo PHP que retorna los datos
                  type: 'POST',
                  data: data,
                  dataSrc: 'data'   // Nombre del array dentro del JSON devuelto por PHP
              },
              columns: [
                  { data: 'contador' },                     // Columna del contador
                  { data: 'fechaModificacion' },            // Columna de fecha modificación
                  { data: 'nombreCompletoModificador' },    // Columna de nombre completo del usuario modificador
                  { data: 'nombreCompleto' },               // Columna de nombre completo del usuario
                  { data: 'estado' },                       // Columna de estado
                  { data: 'nombreRol' },                    // Columna de rol
                  { data: 'fechaCreacion' },                // Columna de fecha creación
                  { data: 'nombreCompletoCreador' }        // Columna de nombre completo del usuario creador.
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

          $('#actualizarBtn').on('click', function () {
                
                const dataUpdate = {
                    idb: <?php echo $cod_usuario; ?>,                                                       // ID del usuario que modifica
                    nombre: document.getElementById('nombreUp').value,                                        // Nombre a modificar
                    apellido: document.getElementById('apellidoUp').value,                                    // Apellido a modificar
                    contrasenaActual: document.getElementById('contrasenaActual').value,
                    contrasenaNueva: document.getElementById('contrasenaNueva').value,
                    confirmarContrasenaNueva: document.getElementById('confirmarContrasenaNueva').value
                };
                // Realiza la solicitud AJAX
                $.ajax({
                    url: 'subprocesos/actualizarMiPerfil.php', // Ruta al archivo PHP
                    type: 'POST',
                    data: dataUpdate,
                    success: function (response) {
                        //console.log('Respuesta recibida:', response); // Depuración: Muestra la respuesta en consola
                        if (response.status === 'success') {
                            document.getElementById('contrasenaActual').value = '';
                            document.getElementById('contrasenaNueva').value = '';
                            document.getElementById('confirmarContrasenaNueva').value = '';
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error',
                                text: response.message
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la solicitud',
                            text: 'No se pudo completar la operación. Intente nuevamente.'
                        });
                        console.error('Respuesta del servidor:', jqXHR.responseText); // Depuración: Muestra la respuesta del servidor
                    }
                });
            });
      });

      // Validación durante la escritura
      document.addEventListener('input', function (event) {
        if (event.target.id === 'contrasenaNueva' || event.target.id === 'confirmarContrasenaNueva') {
          const newPassword = document.getElementById('contrasenaNueva').value;
          const confirmPassword = document.getElementById('confirmarContrasenaNueva').value;

          if (newPassword !== confirmPassword) {
            document.getElementById('confirmarContrasenaNueva').setCustomValidity('Las contraseñas no coinciden');
          } else {
            document.getElementById('confirmarContrasenaNueva').setCustomValidity('');
          }
        }
      }, true);

      // Función para alternar la visibilidad de la contraseña
      function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('bi-eye');
          icon.classList.add('bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.remove('bi-eye-slash');
          icon.classList.add('bi-eye');
        }
      }

    </script>  
    </body>
</html>