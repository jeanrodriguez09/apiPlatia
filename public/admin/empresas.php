<?php include 'cabecera.php'; ?>
      <title> Empresas || platIA || Administrador </title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="../assets/images/favicon.ico" />
      
    <?php include 'inc/css.php'; ?>
    <script src="https://cdn.tiny.cloud/1/dqvlndx2m3ptvddx5pk3chzsysri5r3bbvfobwrpty88ly5a/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    
  </head>
  <body class=" color-light ">
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: [
          // Core editing features
          'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
          // Your account includes a free trial of TinyMCE premium features
          // Try the most popular premium features until Jul 21, 2025:
          //'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Platcom',
        mergetags_list: [
          { value: 'Webmaster', title: 'Nombre' },
          { value: 'web@plat.com.py', title: 'Correo' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('Observa la documentación para la implementación del asistente IA.')),
      });
    </script>
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
                <!--<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ModalRegistroEmpresa">-->
                <!--    Registrar empresa <i class="bi bi-file-plus m-0"></i>-->
                <!--</button>-->
                <div class="table-responsive">
                    <table id="tablaEmpresas" class="table align-middle table-hover m-0">
                        <thead>
                          <tr>
                            <th scope="col">Orden</th>
                            <th scope="col">Denominaci&oacute;n</th>
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
        <div class="modal fade" id="ModalRegistroEmpresa" tabindex="-1" aria-labelledby="ModalRegistroEmpresaLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content shadow-lg border-0">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-uppercase" id="ModalRegistroEmpresaLabel" style="color: #ffffff;">Informaci&oacute;n de la empresa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                  
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="hidden" id="idEmpresa" name="idEmpresa">
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
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="text" class="form-control" id="id_numero">
                      <label for="id_numero">ID N&uacute;mero META</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                      <input type="text" class="form-control" id="token_acceso">
                      <label for="token_acceso">Token Acceso META</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-floating mb-2">
                        <select class="form-select" id="rubro" name="rubro">
                          <option value="">Cargando rubros</option>
                        </select>
                        <label for="rubro">Rubro</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating mb-2">
                        <textarea id="descripcion" rows="9" id="textarea-input" class="form-control"></textarea>
                        <label for="descripcion">Descripci&oacute;n</label>
                    </div>
                  </div>
                  <hr>
                  <h3>Reglas Bot</h3>
                  <div class="col-md-12">
                    <div class="form-floating mb-2">
                        <textarea rows="9" id="reglasBasicas" class="form-control"></textarea>
                        <label for="descripcion">Reglas B&aacute;sicas</label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-floating mb-2">
                        <textarea rows="9" id="reglasRestrictivas" class="form-control"></textarea>
                        <label for="descripcion">Reglas Restrictivas</label>
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
        $(document).ready(function() {
            // Inicializar TinyMCE solo sobre los textareas
            tinymce.init({
                selector: 'textarea.tinymce'
            });
        });
    </script>
    <script>
            $(document).ready(function () {
                cargarRubros();
                const data = {
                    idb: <?php echo $cod_usuario; ?> // Reemplaza con el ID del usuario correspondiente
                };
                tabla = $('#tablaEmpresas').DataTable({
                    ajax: {
                        url: 'subprocesos/listaEmpresas.php', // Ruta a tu archivo PHP que retorna los datos
                        data: data,
                        type: 'POST',
                        dataType: 'json',
                        dataSrc: 'data',   // Nombre del array dentro del JSON devuelto por PHP
                    },
                    columns: [
                        { data: 'contador' },                 // Columna del contador
                        { data: 'nombre' },                   // Columna del código
                        { data: 'estado' },                   // Columna de estado
                        { 
                            data: null,
                            className: 'text-center', 
                            render: function(data){
                                let desactivar  = '',
                                    activar     = '',
                                    vincularWA  = '',
                                    verDetalle  = '';
            
                                if(data.idEstado==0){
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistroEmpresa" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button> `;
                                    activar     = `<button type="button" class="btn btn-sm btn-success shadow-sm" onclick="activarEmpresa(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Activar
                                                    </button> `;
                                    vincularWA  = ` `;
                                    desactivar  = ` `;
                                } else {
                                    verDetalle  = `<button type="button" class="btn btn-sm btn-info shadow-sm" data-bs-toggle="modal" data-bs-target="#ModalRegistroEmpresa" onclick="detalles(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Ver detalle
                                                    </button> `;
                                    vincularWA  = `<button type="button" id="btnVincularWhatsapp" class="btn btn-sm btn-success shadow-sm" onclick="abrirVinculoWhatsApp(${data.codigo});">
                                                      Vincular WhatsApp
                                                    </button>`;
                                    activar     = ` `;
                                    desactivar  = `<button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="desactivarEmpresa(${data.codigo});">
                                                        <i class="bi bi-eye"></i> Desactivar
                                                    </button> `;
                                }
            
                                return `${verDetalle} ${activar} ${desactivar} ${vincularWA}`;
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
            
            function cargarRubros(selectedRubro = '') {
                $.ajax({
                    url: 'subprocesos/listaRubros.php', // tu archivo actual
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        let $select = $('#rubro');
                        $select.empty();
            
                        if (response.data && response.data.length > 0) {
                            $select.append('<option value="">Seleccione un rubro</option>');
                            response.data.forEach(function (item) {
                                let selected = (item.codigo == selectedRubro) ? 'selected' : '';
                                $select.append(`<option value="${item.codigo}" ${selected}>${item.descripcion}</option>`);
                            });
                        } else {
                            $select.append('<option value="">No hay rubros disponibles</option>');
                        }
                    },
                    error: function (jqXHR) {
                        console.error('Error cargando rubros:', jqXHR.responseText);
                        $('#rubro').html('<option value="">Error cargando rubros</option>');
                    }
                });
            }

    
            function detalles(idEmpresa) {
                // Limpiar la tabla
                
                $.ajax({
                    url: 'subprocesos/detalleEmpresa.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { idEmpresa: idEmpresa },
                    success: function (response) {
                        if (response.status === 'success') {
                            
                            const datos = response.data;
                            // console.log(datos);
                            // Asignar valores al modal
                            //$('#idEmpresa').val(datos[0].id);
                            $('#idEmpresa').val(datos.id);
                            $('#nombreEmpresa').val(datos.nombre);
                            $('#correoEmpresa').val(datos.email_contacto);
                            $('#id_numero').val(datos.phone_number_id);
                            $('#token_acceso').val(datos.access_token);

                            
                            cargarRubros(datos.idRubro);
                            
                            if (tinymce.get('descripcion')) {
                                tinymce.get('descripcion').setContent(datos.descripcionNegocio || '');
                            } else {
                                $('#descripcion').val(datos.descripcionNegocio);
                            }

                            if (tinymce.get('reglasBasicas')) {
                                tinymce.get('reglasBasicas').setContent(datos.reglasBasicas || '');
                            } else {
                                $('#reglasBasicas').val(datos.reglasBasicas);
                            }

                            if (tinymce.get('reglasRestrictivas')) {
                                tinymce.get('reglasRestrictivas').setContent(datos.reglasRestrictivas || '');
                            } else {
                                $('#reglasRestrictivas').val(datos.reglasRestrictivas);
                            }
                            

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
                const idEmpresa = $('#idEmpresa').val().trim();
                const nombre = $('#nombreEmpresa').val().trim();
                const idRubro = $('#rubro').val().trim();
                const correo = $('#correoEmpresa').val().trim();
                const id_phone_number = $('#id_numero').val().trim();
                const token_access = $('#token_acceso').val().trim();
                const descripcion = tinymce.get('descripcion').getContent();
                const reglasBasicas = tinymce.get('reglasBasicas').getContent();
                const reglasRestrictivas = tinymce.get('reglasRestrictivas').getContent();
                let idb = <?php echo $cod_usuario; ?>;
                
                console.log('Datos a enviar:', {idb, idEmpresa, nombre, correo, idRubro, descripcion, reglasBasicas, reglasRestrictivas, id_phone_number, token_access});
            
                // Validación simple
                if (nombre === '' || correo === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completá todos los campos.'
                    });
                    return;
                }
            
                const datos = {
                    idb: idb,
                    idEmpresa: idEmpresa,
                    nombre: nombre,
                    correo: correo,
                    idRubro: idRubro,
                    descripcion: descripcion,
                    reglasBasicas: reglasBasicas,
                    reglasRestrictivas: reglasRestrictivas,
                    id_phone_number: id_phone_number,
                    token_access: token_access
                };
            
                $.ajax({
                    url: 'subprocesos/guardarEmpresa.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: datos,
                    success: function (response) {
                        if (response.status === 'success') {
                            console.log('Respuesta del servidor:', response);
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: response.message
                            }).then(() => {
                                // Cerrar modal o limpiar formulario si querés
                                $('#ModalRegistroEmpresa input, #ModalRegistroEmpresa textarea').val('');
                                $('#ModalRegistroEmpresa select').prop('selectedIndex', 0);
                                tinymce.get('descripcion').setContent(''); // ✅ limpiar editor
                                tinymce.get('reglasBasicas').setContent(''); // ✅ limpiar editor
                                tinymce.get('reglasRestrictivas').setContent(''); // ✅ limpiar editor
                                $('#ModalRegistroEmpresa').modal('hide');
                                // Podés recargar tabla o datos acá
                                tabla.ajax.reload();
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
            
            function abrirVinculoWhatsApp(idb) {
                const idEmpresa = idb;
                if (!idEmpresa || idEmpresa === '0') {
                    Swal.fire('Atención', 'Primero guardá o seleccioná la empresa antes de vincular.', 'warning');
                    return;
                }
            
                // Abrir popup para OAuth
                const url = `subprocesos/loginFacebook.php?idEmpresa=${encodeURIComponent(idEmpresa)}`;
                const popup = window.open(url, 'fb_oauth', 'width=600,height=700,menubar=no,toolbar=no');
            
                // Monitorear el cierre: al cerrarse recargamos la tabla (o detalles)
                const timer = setInterval(function () {
                    if (!popup || popup.closed) {
                        clearInterval(timer);
                        // recargar tabla y detalles
                        if (typeof tabla !== 'undefined') tabla.ajax.reload(null, false);
                        // opcional: recargar detalles si modal abierto
                        const empresaActual = $('#idEmpresa').val();
                        if (empresaActual) {
                            // actualizar la vista de detalles si querés
                            detalles(empresaActual);
                        }
                        Swal.fire('Listo', 'Si completaste la autorización en Facebook, el número quedará asociado a la empresa.', 'success');
                    }
                }, 1000);
            }
            
            $('#ModalRegistroEmpresa').on('hidden.bs.modal', function () {
                // Limpiar inputs y textareas
                $(this).find('input, textarea').val('');
            
                // Reiniciar selects
                $(this).find('select').prop('selectedIndex', 0);
            
                // Limpiar TinyMCE si existe
                $(this).find('textarea').each(function () {
                    if (tinymce.get(this.id)) {
                        tinymce.get(this.id).setContent('');
                    }
                });
            });
        </script> 
    
    </body>
</html>