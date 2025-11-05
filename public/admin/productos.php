<?php include 'cabecera.php'; ?>
<title>Productos || platIA || Administrador</title>
<?php include 'inc/css.php'; ?>
</head>
<body class="color-light">
<?php include 'inc/preloader.php'; ?>
<div class="wrapper">
    <?php include 'inc/navbar.php'; ?>
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ModalAgregarProducto">
                                Agregar nuevo producto <i class="bi bi-file-plus m-0"></i>
                            </button>
                            <div class="table-responsive">
                                <table id="tablaProductos" class="table align-middle table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Duraci&oacute;n (min)</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Agregar Producto -->
            <div class="modal fade" id="ModalAgregarProducto" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Agregar Producto</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="agregarNombre">
                            </div>
                            <div class="mb-3">
                                <label>Descripci&oacute;n</label>
                                <textarea class="form-control" id="agregarDescripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Precio <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="agregarPrecio" min="0">
                            </div>
                            <div class="mb-3">
                                <label>Duraci&oacute;n (min)</label>
                                <input type="number" class="form-control" id="agregarDuracion" min="0" max="999">
                            </div>
                            <div class="mb-3">
                                <label>Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="agregarEstado">
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-success" id="btnGuardarProducto">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar Producto -->
            <div class="modal fade" id="ModalEditarProducto" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Editar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editarIdProducto">
                            <div class="mb-3">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editarNombre">
                            </div>
                            <div class="mb-3">
                                <label>Descripci&oacute;n</label>
                                <textarea class="form-control" id="editarDescripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Precio <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editarPrecio" min="0">
                            </div>
                            <div class="mb-3">
                                <label>Duraci&oacute;n (min) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editarDuracion" min="0" max="999">
                            </div>
                            <div class="mb-3">
                                <label>Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="editarEstado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-warning" id="btnActualizarProducto">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
<?php include 'inc/scripts.php'; ?>

    <script>
        $(document).ready(function () {
        
            const idEmpresa = <?php echo $idEmpresa; ?>;
        
            // Inicializar DataTable
            const tabla = $('#tablaProductos').DataTable({
                ajax: {
                    url: 'subprocesos/listaProductos.php',
                    type: 'POST',
                    data: { idEmpresa: idEmpresa },
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'contador' },
                    { data: 'nombre' },
                    { data: 'precio' },
                    { data: 'duracion' },
                    { data: 'estado' },
                    { 
                        data: null,
                        className: 'text-center',
                        render: function (data) {
                            let btnEditar = `<button class="btn btn-sm btn-warning" onclick="editarProducto(${data.codigo})">Editar</button>`;
                            let btnEliminar = `<button class="btn btn-sm btn-danger" onclick="eliminarProducto(${data.codigo})">Eliminar</button>`;
                            return `${btnEditar} ${btnEliminar}`;
                        }
                    }
                ]
            });
        
            // Función Insertar Producto
            $('#btnGuardarProducto').click(function() {
                const nombre = $('#agregarNombre').val();
                const descripcion = $('#agregarDescripcion').val();
                const precio = $('#agregarPrecio').val();
                const duracion = $('#agregarDuracion').val();
                const estado = $('#agregarEstado').val();
        
                if(!nombre || !precio){
                    Swal.fire('Error','Complete los campos obligatorios','warning');
                    return;
                }
        
                $.ajax({
                    url: 'subprocesos/insertarProducto.php',
                    type: 'POST',
                    data: { nombre, descripcion, precio, duracion, estado, idEmpresa },
                    dataType: 'json',
                    success: function(response){
                        if(response.status==='success'){
                            Swal.fire('Éxito',response.message,'success');
                            $('#ModalAgregarProducto').modal('hide');
                            tabla.ajax.reload();
                            // Limpiar campos
                            $('#agregarNombre').val('');
                            $('#agregarDescripcion').val('');
                            $('#agregarPrecio').val('');
                            $('#agregarDuracion').val('');
                            $('#agregarEstado').val('1');
                        } else {
                            Swal.fire('Error',response.message,'error');
                        }
                    }
                });
            });
        
            // Función Actualizar Producto
            $('#btnActualizarProducto').click(function() {
                const id = $('#editarIdProducto').val();
                const nombre = $('#editarNombre').val();
                const descripcion = $('#editarDescripcion').val();
                const precio = $('#editarPrecio').val();
                const duracion = $('#editarDuracion').val();
                const estado = $('#editarEstado').val();
        
                if(!nombre || !precio){
                    Swal.fire('Error','Complete los campos obligatorios','warning');
                    return;
                }
        
                $.ajax({
                    url: 'subprocesos/editarProducto.php',
                    type: 'POST',
                    data: { id, nombre, descripcion, precio, duracion, estado },
                    dataType: 'json',
                    success: function(response){
                        if(response.status==='success'){
                            Swal.fire('Éxito',response.message,'success');
                            $('#ModalEditarProducto').modal('hide');
                            tabla.ajax.reload();
                        } else {
                            Swal.fire('Error',response.message,'error');
                        }
                    }
                });
            });
        });
        
        // Función para abrir modal editar y cargar datos
        function editarProducto(id){
            $.ajax({
                url: 'subprocesos/detalleProducto.php',
                type: 'POST',
                dataType: 'json',
                data: { id },
                success: function(response){
                    if(response.status==='success'){
                        const d = response.data;
                        $('#editarIdProducto').val(d.id);
                        $('#editarNombre').val(d.nombre);
                        $('#editarDescripcion').val(d.descripcion);
                        $('#editarPrecio').val(d.precio);
                        $('#editarDuracion').val(d.duracion);
                        $('#editarEstado').val(d.estado);
                        $('#ModalEditarProducto').modal('show');
                    } else {
                        Swal.fire('Error',response.message,'error');
                    }
                }
            });
        }
        
        // Función eliminar lógico
        function eliminarProducto(id){
            Swal.fire({
                title: '¿Está seguro?',
                text: "El producto se desactivará",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: 'subprocesos/eliminarProducto.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { id },
                        success: function(response){
                            if(response.status==='success'){
                                Swal.fire('Éxito',response.message,'success');
                                $('#tablaProductos').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error',response.message,'error');
                            }
                        }
                    });
                }
            });
        }
    </script>

    
    </body>
</html>