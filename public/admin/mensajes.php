<?php include 'cabecera.php'; ?>
<title>Chat || Mensajes || platIA || Administrador</title>
<link rel="shortcut icon" href="../assets/images/favicon.ico" />
<?php include 'inc/css.php'; ?>
</head>
<body class="color-light">
  <?php echo 'inc/preloader.php'; ?>
  <div class="wrapper">
    <?php 
      include 'inc/navbar.php'; 
      require_once 'inc/helper.php'; 

      $idCliente = $_GET['id'] ?? '';
      $numberPhoneCliente = '';

      if (!empty($idCliente)) {
          $numberPhoneCliente = get_numeroCliente($link, $idCliente);   
      }

      if (empty($numberPhoneCliente)) {
          echo "<script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de parámetros.',
                      text: 'No se pudo obtener el número del cliente.'
                  }).then(() => {
                      window.location.href = 'menu.php?u=$cadena';
                  });
                </script>";
          exit;
      }
    ?>
    <div class="content-page">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xxl-12">
            <div class="card shadow mb-4">
              <div class="card-body">
                <div id="chat-container" style="height: 500px; overflow-y: auto; background-color: #f8f9fa; padding: 15px; border-radius: 10px;">
                  <!-- Mensajes se cargarán aquí -->
                </div>
                <div class="mt-3 d-flex">
                  <input type="text" id="mensaje" class="form-control me-2" placeholder="Escribe tu mensaje...">
                  <button class="btn btn-primary" id="btnEnviar">Enviar</button>
                </div>
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
    const numeroRemitente = '<?php echo $numberPhoneCliente; ?>';

    function cargarMensajes() {
        
        console.log(numeroRemitente);

      $.ajax({
        url: 'subprocesos/obtenerMensajes.php',
        method: 'POST',
        data: { numero_remitente: numeroRemitente },
        dataType: 'json',
        success: function(respuesta) {
          if (!respuesta || !Array.isArray(respuesta)) {
            console.warn('Respuesta no válida:', respuesta);
            return;
          }
        
          let html = '';
          respuesta.forEach(mensaje => {
            const clase = mensaje.tipo === 'recibido' ? 'text-start' : 'text-end';
            const estilo = mensaje.tipo === 'recibido' ? 'bg-light' : 'bg-primary text-white';
            html += `<div class="${clase} mb-2">
                      <div class="d-inline-block p-2 rounded ${estilo}">
                        ${mensaje.mensaje}
                        <div class="small text-muted">${mensaje.fecha}</div>
                      </div>
                    </div>`;
          });
          $('#chat-container').html(html);
          $('#chat-container').scrollTop($('#chat-container')[0].scrollHeight);
        },
        error: function(xhr, status, error) {
          console.error('Error AJAX al cargar mensajes:');
          console.log('Estado:', status);
          console.log('Error:', error);
          console.log('Respuesta del servidor:', xhr.responseText);
    
          Swal.fire({
            icon: 'error',
            title: 'Error al cargar mensajes',
            html: `<pre>${xhr.responseText}</pre>`,
            width: 600
          });
        }
      });
    }


    $('#btnEnviar').click(function() {
      const mensaje = $('#mensaje').val().trim();
      if (!mensaje) return;

      $.post('subprocesos/enviarMensaje.php', {
        numero_destino: numeroRemitente,
        mensaje: mensaje
      }, function(res) {
        $('#mensaje').val('');
        cargarMensajes();
      });
    });

    // Cargar mensajes al iniciar y luego cada 5 segundos
    $(document).ready(() => {
      cargarMensajes();
      setInterval(cargarMensajes, 5000);
    });
  </script>
</body>
</html>
