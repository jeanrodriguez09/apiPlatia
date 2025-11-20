<?php
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PLATIA — IA para Atención y Ventas</title>
  <meta name="description" content="PLATIA: automatización con IA para WhatsApp, redes y CRM. Lanzamiento 18–19 de noviembre en Expo Ver (Paseo La Galería).">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!--favicon-img -->
  <link rel="icon" type="image/png" href="assets/images/ico_platia.png">
  <style>
    :root{--brand:#6a5cff;--brand-2:#00d2c6}
    .btn-brand{background:var(--brand);border-color:var(--brand)}
    .btn-brand:hover{filter:brightness(.92)}
    .text-brand{color:var(--brand)}
    .badge-soft{background:rgba(106,92,255,.12);color:var(--brand);border:1px solid rgba(106,92,255,.2)}
    .hero{
      background: radial-gradient(1200px 600px at 10% -10%, rgba(106,92,255,.18), transparent 40%),
                  radial-gradient(1200px 600px at 90% 10%, rgba(0,210,198,.14), transparent 40%),
                  linear-gradient(180deg,#0b0f19, #0f1322 60%, #0b0f19);
      color:#e9ecf1;
    }
    .glass{backdrop-filter: blur(8px); background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12)}
    .card.feature{border:1px solid rgba(0,0,0,.06);border-radius:18px}
    .shadow-soft{box-shadow:0 10px 30px rgba(0,0,0,.08)}
    .icon-hero{width:48px;height:48px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.1)}
    .step{border-left:3px solid var(--brand);padding-left:1rem}
    .qr-cta{border:1px dashed rgba(255,255,255,.45);border-radius:16px}
    .section-muted{background:#f6f7fb}
    .portfolio img{height:220px;object-fit:cover;border-top-left-radius:14px;border-top-right-radius:14px}
  </style>
</head>
<body>
  <!-- NAV -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary-subtle sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#inicio"><img src="assets/images/logoPlatia.png" width="220px"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
      <div id="nav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#que-es">Qué es</a></li>
          <li class="nav-item"><a class="nav-link" href="#funciones">Funciones</a></li>
          <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
          <li class="nav-item"><a class="nav-link" href="#expo">Expo Ver</a></li>
          <li class="nav-item"><a class="nav-link" href="#demo">Demo</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <header id="inicio" class="hero py-5 position-relative overflow-hidden">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <span class="badge badge-soft rounded-pill px-3 py-2 mb-3"><i class="bi bi-calendar-event me-1"></i> Lanzamiento 18–19 de noviembre · Expo Ver · Paseo La Galería</span>
          <h1 class="display-5 fw-bold mb-3">IA que responde, vende y organiza <span class="text-brand">desde tu WhatsApp</span></h1>
          <p class="lead">PLATIA integra atención, marketing y CRM en un solo flujo. Conversaciones inteligentes, campañas segmentadas y métricas en tiempo real.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a href="#demo" class="btn btn-brand btn-lg"><i class="bi bi-lightning-charge-fill me-1"></i> Solicitar demo</a>
            <a href="#que-es" class="btn btn-outline-light btn-lg"><i class="bi bi-play-circle me-1"></i> Ver cómo funciona</a>
          </div>
          <div class="mt-4 small text-white-50 d-flex flex-wrap gap-3">
            <div><i class="bi bi-check2-circle me-1"></i> Integración Meta / WA Business</div>
            <div><i class="bi bi-check2-circle me-1"></i> Embudos con IA</div>
            <div><i class="bi bi-check2-circle me-1"></i> Panel de métricas</div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="glass rounded-4 p-3 shadow-soft">
            <div class="ratio ratio-16x9 rounded-3" style="background:#121629;display:flex;align-items:center;justify-content:center">
              <div class="text-center p-4">
                <div class="icon-hero mb-3"><i class="bi bi-chat-dots fs-3 text-white-50"></i></div>
                <h5 class="mb-1">Vista previa del flujo de PLATIA</h5>
                <p class="text-white-50 small mb-0">Video/captura del chatbot + CRM integrado</p>
              </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 small text-white-50 qr-cta p-3">
              <span><i class="bi bi-qr-code me-1"></i> Escaneá en el stand y probá el bot en vivo</span>
              <span class="text-uppercase">Expo Ver</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- QUÉ ES -->
  <section id="que-es" class="py-5 section-muted">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <h2 class="fw-bold mb-3">¿Qué es PLATIA?</h2>
          <p class="text-secondary mb-3">Una plataforma de <strong>automatización con IA</strong> que conecta <strong>WhatsApp, redes y CRM</strong> para que tu negocio responda 24/7, clasifique leads y mida resultados sin fricción.</p>
          <ul class="list-unstyled text-secondary">
            <li class="mb-2 step"><strong>Atención inteligente:</strong> el bot entiende intención y deriva.</li>
            <li class="mb-2 step"><strong>Embudo de ventas:</strong> mensajes, recordatorios y seguimiento.</li>
            <li class="mb-2 step"><strong>Datos útiles:</strong> dashboard con métricas claras.</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-6">
              <div class="card feature p-3 h-100">
                <i class="bi bi-whatsapp fs-2 text-brand"></i>
                <h6 class="mt-2 mb-1">WhatsApp + IA</h6>
                <p class="small text-secondary mb-0">Respuestas precisas y personalizadas.</p>
              </div>
            </div>
            <div class="col-6">
              <div class="card feature p-3 h-100">
                <i class="bi bi-kanban fs-2 text-brand"></i>
                <h6 class="mt-2 mb-1">CRM visual</h6>
                <p class="small text-secondary mb-0">Estado, etiquetas y asignación.</p>
              </div>
            </div>
            <div class="col-6">
              <div class="card feature p-3 h-100">
                <i class="bi bi-broadcast-pin fs-2 text-brand"></i>
                <h6 class="mt-2 mb-1">Campañas</h6>
                <p class="small text-secondary mb-0">Segmentación y envíos masivos.</p>
              </div>
            </div>
            <div class="col-6">
              <div class="card feature p-3 h-100">
                <i class="bi bi-graph-up-arrow fs-2 text-brand"></i>
                <h6 class="mt-2 mb-1">Métricas</h6>
                <p class="small text-secondary mb-0">Conversiones y ROI en tiempo real.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section id="embudo" class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Embudo de Ventas de PLATIA</h2>
            <p class="text-secondary">Así funciona nuestro proceso, explicado de forma simple.</p>
        </div>

        <div class="row g-4">
            <!-- Paso 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-chat-dots fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">1. Punto de Entrada</h5>
                        </div>
                        <p class="text-secondary small mb-2">
                            El cliente llega desde:
                        </p>
                        <ul class="text-secondary small">
                            <li>WhatsApp</li>
                            <li>Facebook</li>
                            <li>Instagram</li>
                        </ul>
                        <p class="text-secondary small">
                            El bot lo redirige automáticamente a la web oficial de PLATIA para completar un formulario.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paso 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-ui-checks-grid fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">2. Registro & Configuración</h5>
                        </div>
                        <p class="text-secondary small">
                            Al completar el formulario, el cliente puede:
                        </p>
                        <ul class="text-secondary small">
                            <li>Cargar datos de su empresa</li>
                            <li>Registrar sus servicios</li>
                            <li>O agendar una reunión por Meet para asistencia guiada paso a paso</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Paso 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-whatsapp fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">3. Conexión del Bot</h5>
                        </div>
                        <p class="text-secondary small">
                            Para vincular el bot al WhatsApp del cliente es necesario coordinar una reunión
                            (presencial o por Meet) donde se realizan las configuraciones técnicas.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paso 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-gift fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">4. Prueba Gratuita</h5>
                        </div>
                        <p class="text-secondary small">
                            El cliente recibe:
                        </p>
                        <ul class="text-secondary small">
                            <li><strong>15 días</strong> de acceso gratuito a la plataforma</li>
                            <li><strong>5 USD de crédito</strong> para utilizar el bot</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Paso 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-cash-coin fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">5. Sistema de Créditos</h5>
                        </div>
                        <p class="text-secondary small">
                            El cliente puede cargar crédito en su perfil.  
                            El bot descuenta automáticamente según el uso.
                        </p>
                        <ul class="text-secondary small">
                            <li>Cada 24h se envía un reporte de consumo</li>
                            <li>El cliente siempre sabe cuánto crédito queda</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Paso 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-graph-up-arrow fs-2 text-brand me-2"></i>
                            <h5 class="mb-0">6. Activación Comercial</h5>
                        </div>
                        <p class="text-secondary small">
                            Una vez finalizada la prueba, el cliente puede elegir un plan y continuar utilizando PLATIA sin interrupciones.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


  <!-- FUNCIONES -->
  <!--<section id="funciones" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Funciones principales</h2>
        <p class="text-secondary">Todo lo que necesitás para escalar atención y ventas.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 feature">
            <div class="card-body">
              <i class="bi bi-robot fs-2 text-brand"></i>
              <h5 class="mt-2">Chatbot con IA</h5>
              <p class="text-secondary">Entiende preguntas, consulta base de conocimiento y responde en segundos.</p>
              <ul class="small text-secondary">
                <li>Rutas por intención</li>
                <li>FAQ dinámico</li>
                <li>Derivación a humano</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 feature">
            <div class="card-body">
              <i class="bi bi-diagram-3 fs-2 text-brand"></i>
              <h5 class="mt-2">Flujos & Embudos</h5>
              <p class="text-secondary">Recordatorios, secuencias y follow-up automático para cerrar más.</p>
              <ul class="small text-secondary">
                <li>Carritos abandonados</li>
                <li>Citas y recordatorios</li>
                <li>Encuestas NPS</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 feature">
            <div class="card-body">
              <i class="bi bi-speedometer2 fs-2 text-brand"></i>
              <h5 class="mt-2">Métricas & CRM</h5>
              <p class="text-secondary">Panel unificado, etiquetas, etapas y reportería exportable.</p>
              <ul class="small text-secondary">
                <li>Embudo visual</li>
                <li>Segmentos por rubro</li>
                <li>Exportar a Excel</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>-->

  <!-- BENEFICIOS POR PÚBLICO -->
  <section id="beneficios" class="py-5 section-muted">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold">Beneficios según tu perfil</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 feature">
            <div class="card-body">
              <span class="badge bg-light text-dark mb-2">Estudiantes</span>
              <h6>Aprendé IA aplicada</h6>
              <p class="small text-secondary">Certificados y prácticas con casos reales.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 feature">
            <div class="card-body">
              <span class="badge bg-light text-dark mb-2">Veterinarios</span>
              <h6>Recordatorios & fichas</h6>
              <p class="small text-secondary">Turnos, vacunación y seguimiento por WhatsApp.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 feature">
            <div class="card-body">
              <span class="badge bg-light text-dark mb-2">Empresas</span>
              <h6>Ventas 24/7</h6>
              <p class="small text-secondary">Leads calificados, embudos y reportes de ROI.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 feature">
            <div class="card-body">
              <span class="badge bg-light text-dark mb-2">Proveedores</span>
              <h6>Pedidos centralizados</h6>
              <p class="small text-secondary">Catálogo con IA y estado de órdenes en un panel.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- EXPO VER -->
  <section id="expo" class="py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <h2 class="fw-bold mb-3">Nos vemos en Expo Ver</h2>
          <p class="text-secondary">18 y 19 de noviembre · Paseo La Galería · Estimadas 3.000 personas entre estudiantes, veterinarios, proveedores y empresarios. Probá PLATIA en vivo en nuestro stand y llevate el brochure con planes especiales de lanzamiento.</p>
          <ul class="text-secondary">
            <li>Demo guiada en 3 minutos</li>
            <li>Beneficios exclusivos para expositores y asistentes</li>
            <li>Código QR para activar tu prueba</li>
          </ul>
          <a href="#demo" class="btn btn-brand btn-lg">Quiero mi demo</a>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow-soft">
            <div class="card-body">
              <h6 class="text-uppercase text-secondary mb-3">Agenda</h6>
              <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between"><span>18/11 — 10:00</span><strong>Presentación</strong></div>
                <div class="d-flex justify-content-between"><span>18/11 — 16:00</span><strong>Demo IA + WhatsApp</strong></div>
                <div class="d-flex justify-content-between"><span>19/11 — 11:30</span><strong>Casos del rubro vet</strong></div>
                <div class="d-flex justify-content-between"><span>19/11 — 17:00</span><strong>Q&A + sorteos</strong></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- DEMO / CONTACTO -->
  <section id="demo" class="py-5 section-muted">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h2 class="fw-bold mb-2 text-center">Solicitá tu demo gratuita</h2>
          <p class="text-secondary text-center mb-4">Te respondemos en menos de 24h hábiles.</p>
          <form class="row g-3" action="" method="post">
            <div class="col-md-6">
                <label class="form-label">Nombre y Apellido</label>
                <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Empresa / Emprendimiento</label>
                <input type="text" class="form-control" name="empresa" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">WhatsApp</label>
                <input type="tel" class="form-control" name="whatsapp" placeholder="+595..." required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-12">
                <label class="form-label">Rubro</label>
                <select class="form-select" name="rubro" required>
                  <option disabled selected>Elegí una opción</option>
                  <option>Veterinaria</option>
                  <option>Proveedor del rubro</option>
                  <option>Empresa / Comercio</option>
                  <option>Estudiante</option>
                  <option>Otro</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Mensaje</label>
                <textarea class="form-control" name="mensaje" rows="4"></textarea>
            </div>
            <div class="col-12 d-flex align-items-center gap-3">
              <button class="btn btn-brand btn-lg" type="submit" name="enviar_demo">Enviar</button>
              <a class="btn btn-outline-dark btn-lg" href="https://wa.me/595973935191‬"><i class="bi bi-whatsapp me-2"></i>Hablar por WhatsApp</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <?php
    
    if (isset($_POST['enviar_demo'])) {
    
        echo "<script>
            Swal.fire({
                title: 'Procesando...',
                text: 'Enviando tu solicitud.',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        </script>";
    
        $nombre   = $_POST['nombre'];
        $empresa  = $_POST['empresa'];
        $whatsapp = $_POST['whatsapp'];
        $email    = $_POST['email'];
        $rubro    = $_POST['rubro'];
        $mensaje  = $_POST['mensaje'];
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Ingresá un correo electrónico válido.'
                });
            </script>";
            exit;
        }
    
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.plat.com.py';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@platia.plat.com.py';
            $mail->Password   = 'ht0940VG!';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;
    
            $mail->setFrom('info@platia.plat.com.py', 'PlatIA');
            $mail->addAddress('comercial@platia.plat.com.py');
            $mail->addReplyTo($email, $nombre);
    
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Subject = "Nueva solicitud de demo — $nombre";
    
            $mail->Body = "
                <h3>Datos del Formulario de platia DEMO</h3>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Empresa:</strong> $empresa</p>
                <p><strong>WhatsApp:</strong> $whatsapp</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Rubro:</strong> $rubro</p>
                <p><strong>Mensaje:</strong><br>$mensaje</p>
            ";
    
            $mail->send();
    
            echo "<script>
                Swal.fire({
                    title: 'Enviado correctamente',
                    text: 'Te contactaremos pronto.',
                    icon: 'success'
                });
            </script>";
    
        } catch (Exception $e) {
            echo "<script>
                Swal.fire({
                    title: 'Error al enviar',
                    text: 'Detalles: {$mail->ErrorInfo}',
                    icon: 'error'
                });
            </script>";
        }
    }
    ?>

  <!-- FOOTER -->
  <footer class="py-4 bg-dark text-white-50">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
      <div>©️ 2025 PLATIA — Todos los derechos reservados</div>
      <div class="d-flex gap-3">
        <span>By PLATCOM</span>
        <a class="link-light link-underline-opacity-0" href="#">Privacidad</a>
        <a class="link-light link-underline-opacity-0" href="#">Términos</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>