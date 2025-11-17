<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>paltIA - Registro Inicial</title>

    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    <?php include 'inc/css.php'; ?>
</head>

<body>
    <div class="wrapper">
        <section class="login-content">

            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-md-6">
                        <div class="card p-3">
                            <div class="card-body">

                                <!-- Logo -->
                                <div class="auth-logo text-center mb-3">
                                    <img src="assets/images/logo.png" class="img-fluid rounded-normal darkmode-logo" alt="logo">
                                </div>

                                <h3 class="text-center font-weight-bold">Registro Inicial</h3>
                                <p class="text-center text-secondary mb-4">
                                    Completa los datos para crear tu usuario y tu empresa
                                </p>

                                <!-- FORMULARIO -->
                                <form id="formRegistro" action="registrar.php" method="POST">

                                    <!-- ================== PASO 1 – Usuario ================== -->
                                    <div id="step1">

                                        <h5 class="mb-3 font-weight-bold">Datos del Usuario</h5>

                                        <div class="form-group">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Apellido</label>
                                            <input type="text" name="apellido" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Correo del Usuario</label>
                                            <input type="email" name="correo" class="form-control" required>
                                        </div>

                                        <button type="button" id="btnNext" class="btn btn-primary btn-block mt-3">
                                            Siguiente
                                        </button>
                                    </div>

                                    <!-- ================== PASO 2 – Empresa ================== -->
                                    <div id="step2" style="display:none;">

                                        <h5 class="mb-3 font-weight-bold">Datos de la Empresa</h5>

                                        <div class="form-group">
                                            <label>Nombre o Razón Social</label>
                                            <input type="text" name="empresa_nombre" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email empresarial</label>
                                            <input type="email" name="empresa_email" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Dirección</label>
                                            <input type="text" name="empresa_direccion" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>RUC</label>
                                            <input type="text" name="empresa_ruc" class="form-control">
                                        </div>

                                        <!-- UBICACIÓN -->
                                        <div class="form-group">
                                            <label>Ubicación del negocio</label>
                                            <button type="button" id="btnUbicacion" class="btn btn-outline-primary btn-block mb-2">
                                                Obtener ubicación exacta
                                            </button>
                                        </div>

                                        <div class="form-group">
                                            <label>Latitud</label>
                                            <input type="text" name="empresa_latitud" id="latitud" class="form-control" readonly required>
                                        </div>

                                        <div class="form-group">
                                            <label>Longitud</label>
                                            <input type="text" name="empresa_longitud" id="longitud" class="form-control" readonly required>
                                        </div>

                                        <button type="button" id="btnBack" class="btn btn-secondary btn-block">
                                            Volver
                                        </button>

                                        <button type="submit" id="btnFinish" class="btn btn-success btn-block mt-2">
                                            Finalizar Registro
                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <!-- JS Frameworks -->
    <script src="assets/js/backend-bundle.min.js"></script>

    <!-- SCRIPT MULTISTEP + GEOLOCALIZACIÓN -->
    <script>

        // ====== Botón Siguiente ======
        document.getElementById('btnNext').addEventListener('click', function () {

            const nombre = document.querySelector('input[name="nombre"]').value.trim();
            const apellido = document.querySelector('input[name="apellido"]').value.trim();
            const correo = document.querySelector('input[name="correo"]').value.trim();

            if (nombre === "" || apellido === "" || correo === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Completa todos los datos del usuario antes de continuar.'
                });
                return;
            }

            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        });

        // ====== Botón Volver ======
        document.getElementById('btnBack').addEventListener('click', function () {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
        });

        // ====== Obtener Ubicación ======
        document.getElementById('btnUbicacion').addEventListener('click', function () {

            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'No compatible',
                    text: 'Tu navegador no soporta geolocalización.'
                });
                return;
            }

            Swal.fire({
                title: 'Obteniendo ubicación…',
                text: 'Espera unos segundos mientras capturamos tu ubicación.',
                didOpen: () => Swal.showLoading(),
                allowOutsideClick: false
            });

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    Swal.close();
                    document.getElementById('latitud').value = position.coords.latitude;
                    document.getElementById('longitud').value = position.coords.longitude;

                    Swal.fire({
                        icon: 'success',
                        title: 'Ubicación obtenida',
                        text: 'Latitud y longitud registradas.',
                        timer: 2000
                    });
                },
                function () {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo obtener la ubicación',
                        text: 'Activa GPS o concede permisos de ubicación.'
                    });
                }
            );
        });

        // ====== Validación Final ======
        document.getElementById('formRegistro').addEventListener('submit', function (e) {

            if (document.getElementById('latitud').value.trim() === "" ||
                document.getElementById('longitud').value.trim() === "") {

                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Falta la ubicación',
                    text: 'Debes obtener la ubicación de tu negocio antes de finalizar.'
                });
            }
        });

    </script>

</body>
</html>
