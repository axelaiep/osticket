<?php
//include("config.php");
// initialize session
session_start();

include("functions/authenticate.php");
include("functions/captcha_keys.php");

$error = $_GET['error'];

// echo "<pre>";
// print_r($error);
?>

<!DOCTYPE html>
<html lang="en">
<?php include('includes/header.php'); ?>
<body>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>"></script>
    
    <?php include('includes/menu.php'); ?>
    <?php if(isset($_GET['error'])) : ?>
        <?php if ($_GET['error'] == 2): ?>
            <div class="alert alert-danger">
              <strong>ATENCIÓN: </strong> Tiempo de solicitud de captcha expirado. Inténtelo de nuevo.
            </div>
           <?php else: ?>
            <div class="alert alert-danger">
              <strong>ATENCIÓN: </strong> El nombre de usuario o contraseña que ha introducido no son correctos. Inténtelo de nuevo.
            </div>
        <?php endif ?>
    <?php endif; ?>
    

    <div class="container text-center">
	   <center>
	       <div class="card-container" style="width: 25rem;">

                <form class="form-signin" action="osticketform.php" method="post">
                    <h2 class="form-signin-heading">Acceso</h2>
                    <!-- <label for="inputEmail" class="sr-only">Nombre de usuario</label> -->
                    <input type="text" id="userLogin" name="userLogin" class="form-control" placeholder="Ej: nombre.apellido" required autofocus>
                    <div class="checkbox">
         
                    </div>
                    <!-- <label for="inputPassword" class="sr-only">Password</label> -->
                    <input type="password" id="userPassword" name="userPassword" autocomplete="off" class="form-control" placeholder="Contraseña" required>
                    <div class="checkbox">
          
                    </div>
                    <div class="checkbox">
          
                    </div>
                    <div>
                        <button class="btn btn-lg btn-primary btn-block full-width" type="submit">Iniciar sesión</button>
                    </div>
                    <input id="google_response_token" type="hidden" name="google_response_token">
                    <div>
                        <a target="_blank" href="https://intranet.aiep.cl/recuperarclave">Para acceder al sistema de ayuda, debe hacerlo con las mismas credenciales de intranet y aula virtual.
Si no recuerda su clave visite el siguiente link.</a>
                    </div>

                    <!-- <a href="osticketform.php"><button class="btn btn-lg btn-primary btn-block" type="button">Iniciar sesión</button></a> -->


                </form>
            </div>
        </center>
    </div> <!-- /container -->

<?php include('includes/footer.php'); ?>

<script>
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo SITE_KEY; ?>', {action: 'submit'})
            .then(function(token) {
            $('#google_response_token').val(token);
            });
    });
</script>


</body>
</html>