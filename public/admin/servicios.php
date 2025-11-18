<?php include 'cabecera.php'; ?>
<title>Servicios || platIA || Administrador</title>
<?php include 'inc/css.php'; ?>
<script>
    var cod_usuario = <?php echo $cod_usuario; ?>;
</script>
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
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#ModalAgregarServicio">
                                Agregar nuevo servicio <i class="bi bi-file-plus m-0"></i>
                            </button>
                            <div class="table-responsive">
                                <table id="tablaServicios" class="table align-middle table-hover m-0">
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

            <!-- Modal Agregar Servicio -->
            <div class="modal fade" id="ModalAgregarServicio" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Agregar Servicio</h5>
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
                                <label>Duraci&oacute;n (min) <span class="text-danger">*</span></label>
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
                            <button class="btn btn-success" id="btnGuardarServicio">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Editar Servicio -->
            <div class="modal fade" id="ModalEditarServicio" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Editar Servicio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editarIdServicio">
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
                            <button class="btn btn-warning" id="btnActualizarServicio">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Editar Horarios (REEMPLAZAR) -->
            <div class="modal fade" id="ModalEditarHorarios" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-info text-dark">
                            <h5 class="modal-title">Configurar Horarios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="horariosIdServicio">
            
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="horariosTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-recurrentes" data-bs-toggle="tab" data-bs-target="#pane-recurrentes" type="button" role="tab">Recurrentes</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-excepciones" data-bs-toggle="tab" data-bs-target="#pane-excepciones" type="button" role="tab">Excepciones</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-especiales" data-bs-toggle="tab" data-bs-target="#pane-especiales" type="button" role="tab">Horarios Especiales</button>
                                </li>
                            </ul>
            
                            <!-- Tab panes -->
                            <div class="tab-content pt-3">
                                <!-- Recurrentes -->
                                <div class="tab-pane fade show active" id="pane-recurrentes" role="tabpanel">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <button id="btnAgregarFilaRecurrente" class="btn btn-sm btn-success"><i class="bi bi-plus"></i> Agregar fila</button>
                                        </div>
                                        <div class="text-muted small">Formato 24h. Validación en tiempo real evita solapamientos por día.</div>
                                    </div>
            
                                    <div class="table-responsive">
                                        <table id="tablaRecurrentes" class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Día</th>
                                                    <th>Hora inicio</th>
                                                    <th>Hora fin</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
            
                                <!-- Excepciones -->
                                <div class="tab-pane fade" id="pane-excepciones" role="tabpanel">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <button id="btnAgregarExcepcion" class="btn btn-sm btn-success"><i class="bi bi-plus"></i> Agregar excepción</button>
                                        <div class="text-muted small">Las excepciones bloquean la fecha completa.</div>
                                    </div>
            
                                    <div class="table-responsive">
                                        <table id="tablaExcepciones" class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Motivo</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
            
                                <!-- Horarios especiales -->
                                <div class="tab-pane fade" id="pane-especiales" role="tabpanel">
                                    <div class="mb-2 d-flex justify-content-between align-items-center">
                                        <button id="btnAgregarEspecial" class="btn btn-sm btn-success"><i class="bi bi-plus"></i> Agregar horario especial</button>
                                        <div class="text-muted small">Disponibilidad adicional en fechas específicas.</div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="tablaEspeciales" class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Hora inicio</th>
                                                    <th>Hora fin</th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
            
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button class="btn btn-warning" id="btnActualizarHorario">Actualizar</button>
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
            const tabla = $('#tablaServicios').DataTable({
                ajax: {
                    url: 'subprocesos/listaServicios.php',
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
                            let btnEditar = `<button class="btn btn-sm btn-warning" onclick="editarServicio(${data.codigo})">Editar</button>`;
                            let btnEditarHorario = `<button class="btn btn-sm btn-info" onclick="editarHorariosAgenda(${data.codigo})">Configurar Horarios</button>`;
                            let btnEliminar = `<button class="btn btn-sm btn-danger" onclick="eliminarServicio(${data.codigo})">Eliminar</button>`;
                            return `${btnEditar} ${btnEditarHorario} ${btnEliminar}`;
                        }
                    }
                ]
            });
        
            // Función Insertar Producto
            $('#btnGuardarServicio').click(function() {
                const nombre = $('#agregarNombre').val();
                const descripcion = $('#agregarDescripcion').val();
                const precio = $('#agregarPrecio').val();
                const duracion = $('#agregarDuracion').val();
                const estado = $('#agregarEstado').val();
        
                if(!nombre || !precio || !duracion){
                    Swal.fire('Error','Complete los campos obligatorios','warning');
                    return;
                }
        
                $.ajax({
                    url: 'subprocesos/insertarServicio.php',
                    type: 'POST',
                    data: { nombre, descripcion, precio, duracion, estado, idEmpresa, idUsuarioCreador:cod_usuario},
                    dataType: 'json',
                    success: function(response){
                        if(response.status==='success'){
                            Swal.fire('Éxito',response.message,'success');
                            $('#ModalAgregarServicio').modal('hide');
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
            $('#btnActualizarServicio').click(function() {
                const id = $('#editarIdServicio').val();
                const nombre = $('#editarNombre').val();
                const descripcion = $('#editarDescripcion').val();
                const precio = $('#editarPrecio').val();
                const duracion = $('#editarDuracion').val();
                const estado = $('#editarEstado').val();
        
                if(!nombre || !precio || !duracion){
                    Swal.fire('Error','Complete los campos obligatorios','warning');
                    return;
                }
        
                $.ajax({
                    url: 'subprocesos/editarServicio.php',
                    type: 'POST',
                    data: { id, nombre, descripcion, precio, duracion, estado },
                    dataType: 'json',
                    success: function(response){
                        if(response.status==='success'){
                            Swal.fire('Éxito',response.message,'success');
                            $('#ModalEditarServicio').modal('hide');
                            tabla.ajax.reload();
                        } else {
                            Swal.fire('Error',response.message,'error');
                        }
                    }
                });
            });
        });
        
        // Función para abrir modal editar y cargar datos
        function editarServicio(id){
            $.ajax({
                url: 'subprocesos/detalleServicio.php',
                type: 'POST',
                dataType: 'json',
                data: { id },
                success: function(response){
                    if(response.status==='success'){
                        const d = response.data;
                        $('#editarIdServicio').val(d.id);
                        $('#editarNombre').val(d.nombre);
                        $('#editarDescripcion').val(d.descripcion);
                        $('#editarPrecio').val(d.precio);
                        $('#editarDuracion').val(d.duracion);
                        $('#editarEstado').val(d.estado);
                        $('#ModalEditarServicio').modal('show');
                    } else {
                        Swal.fire('Error',response.message,'error');
                    }
                }
            });
        }
        
        // Función eliminar lógico
        function eliminarServicio(id){
            Swal.fire({
                title: '¿Está seguro?',
                text: "El servicio se desactivará",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: 'subprocesos/eliminarServicio.php',
                        type: 'POST',
                        dataType: 'json',
                        data: { id },
                        success: function(response){
                            if(response.status==='success'){
                                Swal.fire('Éxito',response.message,'success');
                                $('#tablaServicios').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error',response.message,'error');
                            }
                        }
                    });
                }
            });
        }
        
        // ---------- UTIL: Convertir "HH:MM" -> minutos desde medianoche ----------
        function toMinutes(hm) {
            if(!hm || hm.indexOf(':')===-1) return null;
            const parts = hm.split(':').map(x => parseInt(x,10));
            return parts[0]*60 + parts[1];
        }
        
        // ---------- UTIL: Check overlap entre intervalos [start,end) en minutos ----------
        function intervalsOverlap(aStart, aEnd, bStart, bEnd) {
            return (aStart < bEnd) && (bStart < aEnd);
        }
        
        // ---------- ESTADO EN MEMORIA ----------
        let horariosRecurrentesCache = []; // filas cargadas desde servidor [{id, dia, horaInicio, horaFin}]
        let excepcionesCache = []; // [{id, fecha, motivo}]
        let especialesCache = []; // [{id, fecha, horaInicio, horaFin}]
        let idServicioActual = null;
        
        // ---------- CARGA INICIAL ----------
        function loadHorariosRecurrentes(idItem){
            return $.ajax({
                url: 'subprocesos/detalleHorariosRecurrentes.php',
                type: 'POST',
                dataType: 'json',
                data: { idItem }
            }).done(function(resp){
                if(resp.status==='success'){
                    horariosRecurrentesCache = resp.data || [];
                    renderTablaRecurrentes();
                } else {
                    horariosRecurrentesCache = [];
                    renderTablaRecurrentes();
                }
            }).fail(function(){ horariosRecurrentesCache = []; renderTablaRecurrentes(); });
        }
        function loadExcepciones(idItem){
            return $.ajax({
                url: 'subprocesos/detalleHorariosExcepciones.php',
                type: 'POST',
                dataType: 'json',
                data: { idItem }
            }).done(function(resp){
                if(resp.status==='success'){
                    excepcionesCache = resp.data || [];
                    renderTablaExcepciones();
                } else {
                    excepcionesCache = [];
                    renderTablaExcepciones();
                }
            }).fail(function(){ excepcionesCache = []; renderTablaExcepciones(); });
        }
        function loadEspeciales(idItem){
            return $.ajax({
                url: 'subprocesos/detalleHorariosEspeciales.php',
                type: 'POST',
                dataType: 'json',
                data: { idItem }
            }).done(function(resp){
                if(resp.status==='success'){
                    especialesCache = resp.data || [];
                    renderTablaEspeciales();
                } else {
                    especialesCache = [];
                    renderTablaEspeciales();
                }
            }).fail(function(){ especialesCache = []; renderTablaEspeciales(); });
        }
        
        // ---------- RENDER TABLAS ----------
        function renderTablaRecurrentes(){
            const $tbody = $('#tablaRecurrentes tbody').empty();
            // primero las ya guardadas
            horariosRecurrentesCache.forEach(r=>{
                const $tr = $('<tr>').attr('data-id', r.id);
                $tr.append(`<td>
                    <select class="form-select form-select-sm dia-select" disabled>
                        ${optionsDiasDropdown(r.dia)}
                    </select>
                </td>`);
                $tr.append(`<td><input type="time" class="form-control form-control-sm hora-inicio" value="${r.horaInicio}" disabled></td>`);
                $tr.append(`<td><input type="time" class="form-control form-control-sm hora-fin" value="${r.horaFin}" disabled></td>`);
                $tr.append(`<td class="text-center">
                    <button class="btn btn-sm btn-danger btnEliminarRecurrente" data-id="${r.id}"><i class="bi bi-trash"></i></button>
                </td>`);
                $tbody.append($tr);
            });
        
            // filas NO guardadas (si existieran en UI temporal) ya se manejan mediante "Agregar fila"
        }
        
        function renderTablaExcepciones(){
            const $tbody = $('#tablaExcepciones tbody').empty();
            excepcionesCache.forEach(e=>{
                const $tr = $('<tr>').attr('data-id', e.id);
                $tr.append(`<td><input type="date" class="form-control form-control-sm fecha-excepcion" value="${e.fecha}" disabled></td>`);
                $tr.append(`<td><input type="text" class="form-control form-control-sm motivo-excepcion" value="${escapeHtml(e.motivo||'')}" disabled></td>`);
                $tr.append(`<td class="text-center"><button class="btn btn-sm btn-danger btnEliminarExcepcion" data-id="${e.id}"><i class="bi bi-trash"></i></button></td>`);
                $tbody.append($tr);
            });
        }
        
        function renderTablaEspeciales(){
            const $tbody = $('#tablaEspeciales tbody').empty();
            especialesCache.forEach(s=>{
                const $tr = $('<tr>').attr('data-id', s.id);
                $tr.append(`<td><input type="date" class="form-control form-control-sm fecha-especial" value="${s.fecha}" disabled></td>`);
                $tr.append(`<td><input type="time" class="form-control form-control-sm hora-inicio-especial" value="${s.horaInicio}" disabled></td>`);
                $tr.append(`<td><input type="time" class="form-control form-control-sm hora-fin-especial" value="${s.horaFin}" disabled></td>`);
                $tr.append(`<td class="text-center"><button class="btn btn-sm btn-danger btnEliminarEspecial" data-id="${s.id}"><i class="bi bi-trash"></i></button></td>`);
                $tbody.append($tr);
            });
        }
        
        // ---------- Helpers ----------
        function optionsDiasDropdown(selected){
            const dias = [
                {v:1,t:'Lunes'},
                {v:2,t:'Martes'},
                {v:3,t:'Miércoles'},
                {v:4,t:'Jueves'},
                {v:5,t:'Viernes'},
                {v:6,t:'Sábado'},
                {v:7,t:'Domingo'}
            ];
            return dias.map(d => `<option value="${d.v}" ${d.v==selected?'selected':''}>${d.t}</option>`).join('');
        }
        function escapeHtml(s){
            if(!s) return '';
            return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }
        
        // ---------- AGREGAR FILA RECURR. (UI) ----------
        $('#btnAgregarFilaRecurrente').on('click', function(){
            const $tbody = $('#tablaRecurrentes tbody');
            const $tr = $('<tr>');
            $tr.append(`<td>
                <select class="form-select form-select-sm dia-select">
                    ${optionsDiasDropdown('')}
                </select>
            </td>`);
            $tr.append(`<td><input type="time" class="form-control form-control-sm hora-inicio" value=""></td>`);
            $tr.append(`<td><input type="time" class="form-control form-control-sm hora-fin" value=""></td>`);
            $tr.append(`<td class="text-center">
                <button class="btn btn-sm btn-success btnGuardarFilaRecurrente"><i class="bi bi-save"></i></button>
                <button class="btn btn-sm btn-secondary btnCancelarFilaRecurrente"><i class="bi bi-x"></i></button>
            </td>`);
            $tbody.append($tr);
        });
        
        // Cancelar fila nueva
        $(document).on('click', '.btnCancelarFilaRecurrente', function(){
            $(this).closest('tr').remove();
        });
        
        // Guardar fila nueva (ajax insert)
        $(document).on('click', '.btnGuardarFilaRecurrente', function(){
            const $tr = $(this).closest('tr');
            const dia = $tr.find('.dia-select').val();
            const horaInicio = $tr.find('.hora-inicio').val();
            const horaFin = $tr.find('.hora-fin').val();
        
            // validaciones básicas
            if(!dia || !horaInicio || !horaFin){
                Swal.fire('Error','Complete día, hora inicio y hora fin','warning');
                return;
            }
            const s = toMinutes(horaInicio);
            const e = toMinutes(horaFin);
            if(s === null || e === null || s >= e){
                Swal.fire('Error','Rango de horas inválido','warning');
                return;
            }
        
            // validación solapamiento: comparar contra cache y contra filas NO guardadas
            // Construir array de intervalos existentes para ese día (en minutos)
            const existing = [];
            horariosRecurrentesCache.forEach(r=>{
                if(String(r.dia) === String(dia)){
                    existing.push({s: toMinutes(r.horaInicio), e: toMinutes(r.horaFin)});
                }
            });
            // también chequear otras filas en la tabla que no tengan data-id (filas temporales)
            $('#tablaRecurrentes tbody tr').each(function(){
                const id = $(this).data('id');
                if(!id){ // fila no guardada
                    const d = $(this).find('.dia-select').val();
                    const hi = $(this).find('.hora-inicio').val();
                    const hf = $(this).find('.hora-fin').val();
                    if(d && hi && hf && String(d) === String(dia) && this !== $tr[0]){
                        existing.push({s: toMinutes(hi), e: toMinutes(hf)});
                    }
                }
            });
        
            // chequear overlaps
            for(const iv of existing){
                if(intervalsOverlap(s,e, iv.s, iv.e)){
                    Swal.fire('Error','El intervalo se solapa con otro horario existente para el mismo día','error');
                    return;
                }
            }
            
            let data = {
                cod_usuario: cod_usuario,
                idItem: idServicioActual,
                dia: dia,
                horaInicio: horaInicio,
                horaFin: horaFin
            };
        
            // todo ok: enviar AJAX para insertar
            $.ajax({
                url: 'subprocesos/insertarHorarioRecurrente.php',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(resp){
                    if(resp.status === 'success'){
                        Swal.fire({icon:'success', title:'Guardado', text: resp.message, timer:1200, showConfirmButton:false});
                        // recargar la cache desde servidor para evitar inconsistencias
                        loadHorariosRecurrentes(idServicioActual);
                    } else {
                        Swal.fire('Error', resp.message || 'No se pudo guardar', 'error');
                    }
                },
                error: function(xhr) {
                    console.error("DEBUG:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en servidor',
                        text: xhr.responseText || 'No se pudo procesar la solicitud'
                    });
                }

            });
        });
        
        // Eliminar recurrente
        $(document).on('click', '.btnEliminarRecurrente', function(){
            const id = $(this).data('id');
            Swal.fire({
                title:'¿Eliminar horario?',
                text:'Se inactivará este horario recurrente',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Sí, eliminar'
            }).then((res)=>{
                if(res.isConfirmed){
                    $.ajax({
                        url: 'subprocesos/eliminarHorarioRecurrente.php',
                        type: 'POST',
                        data: {
                            idHorario: id,
                            cod_usuario: cod_usuario // el que tengas definido
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.status === 'success'){
                                Swal.fire('Listo', resp.message, 'success');
                                loadHorariosRecurrentes(idServicioActual); // refresca la tabla
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function(xhr){
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'No se pudo eliminar el horario: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
        
        // ---------- EXCEPCIONES ----------
        // Agregar excepción (fila en UI)
        $('#btnAgregarExcepcion').on('click', function(){
            const $tbody = $('#tablaExcepciones tbody');
            const $tr = $('<tr>');
            $tr.append(`<td><input type="date" class="form-control form-control-sm fecha-excepcion"></td>`);
            $tr.append(`<td><input type="text" class="form-control form-control-sm motivo-excepcion" placeholder="Motivo"></td>`);
            $tr.append(`<td class="text-center">
                <button class="btn btn-sm btn-success btnGuardarExcepcion"><i class="bi bi-save"></i></button>
                <button class="btn btn-sm btn-secondary btnCancelarExcepcion"><i class="bi bi-x"></i></button>
            </td>`);
            $tbody.prepend($tr);
        });
        $(document).on('click','.btnCancelarExcepcion', function(){ $(this).closest('tr').remove(); });
        
        $(document).on('click', '.btnGuardarExcepcion', function(){
            const $tr = $(this).closest('tr');
            const fecha = $tr.find('.fecha-excepcion').val();
            const motivo = $tr.find('.motivo-excepcion').val();
        
            if(!fecha) { Swal.fire('Error','Seleccione una fecha','warning'); return; }
        
            // validar que no exista excepción para esa fecha
            for(const ex of excepcionesCache){
                if(ex.fecha === fecha){ Swal.fire('Error','Ya existe una excepción para esa fecha','error'); return; }
            }
        
            $.ajax({
                url: 'subprocesos/insertarHorarioExcepcion.php',
                type: 'POST',
                dataType: 'json',
                data: { idItem: idServicioActual, fecha, motivo, cod_usuario: cod_usuario },
                success:function(resp){
                    if(resp.status==='success'){
                        Swal.fire({icon:'success', title:'Guardado', text: resp.message, timer:1000, showConfirmButton:false});
                        loadExcepciones(idServicioActual);
                        loadHorariosRecurrentes(idServicioActual); // recargar por si queremos evitar crear recurrentes en esa fecha
                    } else Swal.fire('Error', resp.message, 'error');
                }
            });
        });
        
        // Eliminar excepción
        $(document).on('click', '.btnEliminarExcepcion', function(){
            const id = $(this).data('id');
            Swal.fire({
                title:'¿Eliminar excepción?',
                text:'Se inactivará esta excepción',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Sí, eliminar'
            }).then((res)=>{
                if(res.isConfirmed){
                    $.ajax({
                        url: 'subprocesos/eliminarHorarioExcepcion.php',
                        type: 'POST',
                        data: {
                            idHorario: id,
                            cod_usuario: cod_usuario // el que tengas definido
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.status === 'success'){
                                Swal.fire('Listo', resp.message, 'success');
                                loadExcepciones(idServicioActual); // refresca la tabla
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function(xhr){
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'No se pudo eliminar el horario: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
        
        // ---------- ESPECIALES ----------
        $('#btnAgregarEspecial').on('click', function(){
            const $tbody = $('#tablaEspeciales tbody');
            const $tr = $('<tr>');
            $tr.append(`<td><input type="date" class="form-control form-control-sm fecha-especial"></td>`);
            $tr.append(`<td><input type="time" class="form-control form-control-sm hora-inicio-especial"></td>`);
            $tr.append(`<td><input type="time" class="form-control form-control-sm hora-fin-especial"></td>`);
            $tr.append(`<td class="text-center">
                <button class="btn btn-sm btn-success btnGuardarEspecial"><i class="bi bi-save"></i></button>
                <button class="btn btn-sm btn-secondary btnCancelarEspecial"><i class="bi bi-x"></i></button>
            </td>`);
            $tbody.prepend($tr);
        });
        $(document).on('click', '.btnCancelarEspecial', function(){ $(this).closest('tr').remove(); });
        
        $(document).on('click', '.btnGuardarEspecial', function(){
            const $tr = $(this).closest('tr');
            const fecha = $tr.find('.fecha-especial').val();
            const horaInicio = $tr.find('.hora-inicio-especial').val();
            const horaFin = $tr.find('.hora-fin-especial').val();
        
            if(!fecha || !horaInicio || !horaFin){ Swal.fire('Error','Complete los campos','warning'); return; }
            const s = toMinutes(horaInicio), e = toMinutes(horaFin);
            if(s === null || e === null || s >= e){ Swal.fire('Error','Rango de horas inválido','warning'); return; }
        
            // Validar solapamiento con otros especiales en misma fecha
            for(const sp of especialesCache){
                if(sp.fecha === fecha && intervalsOverlap(s,e,toMinutes(sp.horaInicio), toMinutes(sp.horaFin))){
                    Swal.fire('Error','Se solapa con otro horario especial en la misma fecha','error');
                    return;
                }
            }
        
            // También validar con excepciones (si hay excepción para la misma fecha => no permitir)
            for(const ex of excepcionesCache){
                if(ex.fecha === fecha){
                    Swal.fire('Error','Esa fecha está marcada como excepción (bloqueada)','error');
                    return;
                }
            }
        
            $.ajax({
                url: 'subprocesos/insertarHorarioEspecial.php',
                type: 'POST',
                dataType: 'json',
                data: { idItem: idServicioActual, fecha, horaInicio, horaFin, cod_usuario: cod_usuario },
                success:function(resp){
                    if(resp.status==='success'){
                        Swal.fire({icon:'success', title:'Guardado', text: resp.message, timer:1000, showConfirmButton:false});
                        loadEspeciales(idServicioActual);
                    } else Swal.fire('Error', resp.message, 'error');
                }
            });
        });
        
        // Eliminar especial
        $(document).on('click', '.btnEliminarEspecial', function(){
            const id = $(this).data('id');
            Swal.fire({
                title:'¿Eliminar horario especial?',
                text:'Se inactivará este registro',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Sí, eliminar'
            }).then((res)=>{
                if(res.isConfirmed){
                    $.ajax({
                        url: 'subprocesos/eliminarHorarioEspecial.php',
                        type: 'POST',
                        data: {
                            idHorario: id,
                            cod_usuario: cod_usuario // el que tengas definido
                        },
                        dataType: 'json',
                        success: function(resp){
                            if(resp.status === 'success'){
                                Swal.fire('Listo', resp.message, 'success');
                                loadEspeciales(idServicioActual); // refresca la tabla
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        },
                        error: function(xhr){
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'No se pudo eliminar el horario: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
        
        // ---------- BOTON ACTUALIZAR ----------
        $('#btnActualizarHorario').on('click', function () {
            $('#ModalEditarHorarios').modal('hide');
        
            // Lanzar proceso en background
            $.ajax({
                url: 'subprocesos/generarAgendaDisponible.php',
                type: 'POST',
                data: {
                    idServicio: idServicioActual,
                    cod_usuario: cod_usuario
                },
                timeout: 2000, // para no esperar respuesta larga
                success: function(){
                    console.log('Proceso de agenda iniciado en background');
                },
                error: function(){
                    console.log('No importa si da error aquí, el proceso sigue igual.');
                }
            });
        
            // Feedback al usuario sin bloquear
            Swal.fire({
                icon: 'info',
                title: 'Generando agenda...',
                text: 'Puedes seguir trabajando. La agenda se actualizará en unos segundos.',
                timer: 2500,
                showConfirmButton: false
            });
        
            // Refrescar tabla como siempre
            $('#tablaServicios').DataTable().ajax.reload();
        });
        
        // ---------- AL ABRIR MODAL: cargar datos ----------
        function editarHorariosAgenda(id){
            idServicioActual = id;
            // set hidden
            $('#horariosIdServicio').val(id);
            // limpiar tablas
            horariosRecurrentesCache = []; excepcionesCache = []; especialesCache = [];
            renderTablaRecurrentes(); renderTablaExcepciones(); renderTablaEspeciales();
            // pedir datos
            $.when(
                loadHorariosRecurrentes(id),
                loadExcepciones(id),
                loadEspeciales(id)
            ).then(function(){
                // una vez cargado, abrir modal
                $('#ModalEditarHorarios').modal('show');
            });
        }
        
        // ---------- LISTENERS GLOBALES para prevenir ediciones en filas ya guardadas (opcional) ----------
        $(document).on('change', '#tablaRecurrentes tbody tr input, #tablaRecurrentes tbody tr select', function(){
            // Validación en tiempo real al cambiar horas en filas nuevas: comprobamos solapamientos con filas ya guardadas y con otras filas nuevas.
            // Para filas nuevas sólo (las guardadas están disabled).
            const $row = $(this).closest('tr');
            const dia = $row.find('.dia-select').val();
            const hi = $row.find('.hora-inicio').val();
            const hf = $row.find('.hora-fin').val();
            if(!dia || !hi || !hf) return; // esperar hasta tener todos campos
        
            const s = toMinutes(hi), e = toMinutes(hf);
            if(s === null || e === null || s >= e){
                $row.addClass('table-danger');
                return;
            }
            // check against cache
            for(const r of horariosRecurrentesCache){
                if(String(r.dia) === String(dia) && intervalsOverlap(s,e, toMinutes(r.horaInicio), toMinutes(r.horaFin))){
                    $row.addClass('table-danger');
                    return;
                }
            }
            // check against other new rows
            let ok = true;
            $('#tablaRecurrentes tbody tr').each(function(){
                if(this === $row[0]) return;
                const id = $(this).data('id');
                if(id) return; // skip saved rows
                const d2 = $(this).find('.dia-select').val();
                const hi2 = $(this).find('.hora-inicio').val();
                const hf2 = $(this).find('.hora-fin').val();
                if(d2 && hi2 && hf2 && String(d2) === String(dia)){
                    if(intervalsOverlap(s,e, toMinutes(hi2), toMinutes(hf2))) ok = false;
                }
            });
            if(!ok) $row.addClass('table-danger'); else $row.removeClass('table-danger');
        });
    </script>
    
    </body>
</html>