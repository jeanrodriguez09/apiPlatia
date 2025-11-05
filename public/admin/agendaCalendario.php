<?php include 'cabecera.php'; ?>
  <title> Productos || platIA || Administrador </title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="../assets/images/favicon.ico" />
  <?php include 'inc/css.php'; ?>
  <!-- BOTÓN FLOTANTE -->
    <style>
        .btn-flotante {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 18px;
            font-size: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,.3);
            cursor: pointer;
            z-index: 9999;
            transition: all .3s ease;
        }
        .btn-flotante:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-flotante i {
            margin-right: 6px;
        }
    </style>
  <!-- ✅ FullCalendar compatible con FullCalendar.Calendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="color-light">
  <?php include 'inc/preloader.php'; ?>

  <div class="wrapper">
    <?php 
      include 'inc/navbar.php'; 
      require_once 'inc/helper.php'; 
    ?>
    <button type="button" class="btn btn-flotante btn-success mb-3" id="btnAgregarReserva" data-bs-toggle="modal" data-bs-target="#modalAgregarReserva">
        <i class="fa fa-plus"></i> Agregar reserva
    </button>
    <div class="content-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xxl-12">
            <div class="card shadow mb-4">
              <div class="card-body">
                <div id="calendario" style="max-width: 100%; margin: 0 auto;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Agregar Reserva -->
    <div class="modal fade" id="modalAgregarReserva" tabindex="-1" aria-labelledby="ModalAgregarReservaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalAgregarReservaLabel">Agregar Reserva</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="formAgregarReserva">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label>Servicio</label>
                  <select class="form-select" id="selectServicio" required>
                    <option value="">Selecciona un servicio</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Cliente</label>
                  <select class="form-select" id="selectCliente" required>
                    <option value="">Selecciona un cliente</option>
                  </select>
                </div>
              </div>
    
              <div class="row mb-3">
                <div class="col-md-6">
                  <label>Fecha</label>
                  <input type="date" class="form-control" id="inputFecha" required>
                </div>
                <div class="col-md-6">
                  <label>Horario disponible</label>
                  <select class="form-select" id="selectHorario" required>
                    <option value="">Selecciona un horario</option>
                  </select>
                </div>
              </div>
    
              <div class="mb-3">
                <label>Observación</label>
                <textarea class="form-control" id="inputObservacion" rows="2"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnGuardarReserva">Guardar Reserva</button>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php include 'inc/footer.php'; ?>
  <?php include 'inc/scripts.php'; ?>

  <!-- ✅ Script de inicialización -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendario');
    
      window.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 600,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
          fetch('subprocesos/agendaTurnos.php')
            .then(response => {
              if (!response.ok) throw new Error("Error HTTP: " + response.status);
              return response.json();
            })
            .then(data => {
              console.log("✅ Eventos recibidos:", data);
              successCallback(data);
            })
            .catch(error => {
              console.error("❌ Error al obtener eventos:", error);
              failureCallback(error);
            });
        },
        eventClick: function(info) {
          const evento = info.event;
    
          Swal.fire({
            title: evento.title,
            html:
              `<p><strong>Fecha:</strong> ${evento.start.toLocaleString()}</p>` +
              `<p><strong>Servicio:</strong> ${evento.extendedProps.servicio}</p>` +
              `<p><strong>Detalles:</strong> ${evento.extendedProps.observacion}</p>`,
            icon: 'info',
            confirmButtonText: 'Cerrar'
          });
        }
      });
    
      calendar.render();
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const cod_usuario = <?= $cod_usuario ?>;
          const idEmpresa = <?= $idEmpresa ?>;
        
          // Cargar lista de servicios de la empresa del usuario
          function cargarServicios() {
              console.log("🔵 Iniciando petición AJAX para cargar servicios... idEmpresa =", idEmpresa);
              $.ajax({
                url: 'subprocesos/obtenerServiciosEmpresa.php',
                method: 'POST',
                dataType: 'json',
                data: { idEmpresa },
                success: function(res) {
                  console.log("🟢 Respuesta de obtenerServiciosEmpresa.php:", res);
                  if (res.status === 'success') {
                    const select = $('#selectServicio');
                    select.empty().append('<option value="">Selecciona un servicio</option>');
                    res.data.forEach(s => select.append(`<option value="${s.idServicio}">${s.nombreServicio}</option>`));
                  } else {
                    console.warn("⚠️ La respuesta no fue success:", res.message);
                  }
                },
                error: function(xhr, status, error) {
                  console.error("❌ Error AJAX en cargarServicios():", status, error);
                  console.log("📄 Respuesta del servidor:", xhr.responseText);
                }
              });
            }

        
          // Cargar lista de clientes
          function cargarClientes() {
            $.ajax({
              url: 'subprocesos/obtenerClientes.php',
              method: 'POST',
              dataType: 'json',
              data: {idEmpresa},
              success: function(res) {
                if(res.status === 'success'){
                  const select = $('#selectCliente');
                  select.empty().append('<option value="">Selecciona un cliente</option>');
                  res.data.forEach(c => select.append(`<option value="${c.id}">${c.nombreCompleto}</option>`));
                }
              }
            });
          }
        
          // Cargar horarios disponibles según servicio y fecha
          $('#inputFecha, #selectServicio').on('change', function() {
            const idServicio = $('#selectServicio').val();
            const fecha = $('#inputFecha').val();
        
            // Limpiar select de horarios
            const selectHorario = $('#selectHorario');
            selectHorario.empty().append('<option value="">Selecciona un horario</option>');
        
            if(idServicio && fecha){
                console.log("🔵 Consultando horarios disponibles para idServicio =", idServicio, "fecha =", fecha);
        
                $.ajax({
                    url: 'subprocesos/obtenerHorariosDisponibles.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { idServicio, fecha },
                    success: function(res){
                        console.log("🟢 Respuesta de obtenerHorariosDisponibles.php:", res);
        
                        if(res.status === 'success' && res.data.length > 0){
                            res.data.forEach(h => {
                                selectHorario.append(`<option value="${h.idAgenda}">${h.horaInicio} - ${h.horaFin}</option>`);
                            });
                        } else {
                            selectHorario.append('<option value="">No hay horarios disponibles</option>');
                        }
                    },
                    error: function(xhr, status, error){
                        console.error("❌ Error AJAX al obtener horarios:", status, error);
                        console.log("📄 Respuesta del servidor:", xhr.responseText);
                    }
                });
            }
          });
        
          // Guardar reserva
          $('#btnGuardarReserva').on('click', function() {
            const idServicio = $('#selectServicio').val();
            const idCliente = $('#selectCliente').val();
            const idAgenda = $('#selectHorario').val();
            const observacion = $('#inputObservacion').val();
        
            if(!idServicio || !idCliente || !idAgenda){
              Swal.fire('Atención', 'Completa todos los campos obligatorios', 'warning');
              return;
            }
        
            $.ajax({
              url: 'subprocesos/guardarReserva.php',
              method: 'POST',
              dataType: 'json',
              data: { idServicio, idCliente, idAgenda, observacion, cod_usuario },
              success: function(res){
                if(res.status === 'success'){
                  Swal.fire({icon:'success', title:'Reserva guardada', text: res.message, timer:1500, showConfirmButton:false});
                  $('#modalAgregarReserva').modal('hide');
                  calendar.refetchEvents(); // recarga calendario
                } else {
                  Swal.fire('Error', res.message, 'error');
                }
              },
              error: function(){
                Swal.fire('Error', 'Error en la petición', 'error');
              }
            });
          });
          
          document.getElementById('btnAgregarReserva').addEventListener('click', function () {
            console.log("🟡 Botón Agregar Reserva presionado");
              $('#modalAgregarReserva').modal('show');
              cargarServicios();
              cargarClientes();
          });
        
          // Inicializar selects
          cargarServicios();
          cargarClientes();

        });

    </script>
</body>
</html>
