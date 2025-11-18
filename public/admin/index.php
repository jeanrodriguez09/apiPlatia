<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>paltIA - Administrador || Inicio de Sesi&oacute;n</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="assets/images/favicon.ico" />
      
      <?php include 'inc/css.php'; ?>
  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
        <section class="login-content">
            
          <?php
            
            if(!empty($_GET['m'])){
                if($_GET['m'] == 1){
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Usuario no existe',
                                text: 'No encontramos datos del usuario ingresado'
                            });  
                          </script>";
                }elseif($_GET['m'] == 2){
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Acceso denegado',
                                text: 'Las credenciales ingresadas no corresponden al usuario.'
                            });  
                          </script>";
                }elseif($_GET['m'] == 3){
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Acceso denegado',
                                text: 'El usuario fue bloqueado, contacte con el administrador.'
                            });  
                          </script>";
                }elseif($_GET['m'] == 4){
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Acceso denegado',
                                text: 'El rol del usuario fue bloqueado, contacte con el administrador.'
                            });  
                          </script>";
                }else{
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error desconocido, contacte con el administrador.'
                            });  
                          </script>";
                }
            }
            
         ?>
            
         <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
               <div class="col-md-5">
                  <div class="card p-3">
                     <div class="card-body">
                        <div class="auth-logo">
                           <img src="assets/images/logo.png " class="img-fluid  rounded-normal  darkmode-logo" alt="logo">
                           <img src="assets/images/logo-dark.png" class="img-fluid rounded-normal light-logo" alt="logo">
                        </div>
                        <h3 class="mb-3 font-weight-bold text-center">Iniciar sesi&oacute;n</h3>
                        <p class="text-center text-secondary mb-4">Ingresar sus credenciales de acceso:</p>
                        <!--<div class="social-btn d-flex justify-content-around align-items-center mb-4">
                            <button class="btn btn-outline-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 262" preserveAspectRatio="xMidYMid">
                                    <path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/>
                                    <path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/>
                                    <path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/>
                                    <path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/>
                                </svg>
                        </div>
                        <div class="mb-5">
                            <p class="line-around text-secondary mb-0"><span class="line-around-1">o inicar con su correo electr&oacute;nico</span></p>
                        </div>-->
                        <form action="login.php" method="POST">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label class="text-secondary">Usuario</label>
                                    <input class="form-control" type="text" name="username" placeholder="Ingresar usuario o Email" required>
                                 </div>
                              </div>
                              <div class="col-lg-12 mt-2">
                                 <div class="form-group">
                                     <div class="d-flex justify-content-between align-items-center">
                                         <label class="text-secondary">Contrase&ntilde;a</label>
                                         <!--<label><a href="auth-recover-pwd.html">Recordar contrase&ntilde;a?</a></label>-->
                                     </div>
                                    <input class="form-control" type="password" name="password" placeholder="Ingresar contrase&ntilde;a" required>
                                 </div>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary btn-block mt-2">Iniciar sesi&oacute;n</button>
                           <div class="col-lg-12 mt-3">
                                <p class="mb-0 text-center">No tienes una cuenta? <a href="registro.php">Crear cuenta</a></p>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
        </section>
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/backend-bundle.min.js"></script>
    <!-- Chart Custom JavaScript -->
    <script src="assets/js/customizer.js"></script>
    
    <script src="assets/js/sidebar.js"></script>
    
    <!-- Flextree Javascript-->
    <script src="assets/js/flex-tree.min.js"></script>
    <script src="assets/js/tree.js"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="assets/js/table-treeview.js"></script>
    
    <!-- slider JavaScript -->
    <script src="assets/js/slider.js"></script>
    
    <!-- Emoji picker -->
    <script src="assets/vendor/emoji-picker-element/index.js" type="module"></script>
    
    <!-- app JavaScript -->
    <script src="assets/js/app.js"></script>  
    </body>
</html>