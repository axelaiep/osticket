<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">
<?php include('includes/header.php'); ?>
<body>
        
    <?php include('includes/menu.php'); ?>

    <div class="container text-center">
	   <center>
	       <div class="card-container" style="width: 35rem;">
            <hr>
                <p id="response">
                <?php echo $_SESSION['resp_status']['message']; ?>
                </p>
            </div>            
        </center>
    </div> <!-- /container -->

    <div class="container text-left">
       <center>
           <div class="card-container" style="width: 35rem;">
            <hr>
                <p>Te recomendamos guardar el número de ticket para luego poder hacerle seguimiento y revisar el estado.</p>
                <p>Se ha enviado un correo con la confirmación de creacón de su caso.</p>
                <p>Para revisar el estado de su caso puede hacerlo a través del icono "Ver estado de ticket" en la página de inicio.</p>
            </div>
        </center>
    </div> <!-- /container -->

    <div class="container text-center">
        <center>
            <a href="/salir.php"><button type="button" class="btn btn-success">Cerrar Sesión</button></a>
        </center>
    </div> <!-- /container -->

<?php include('includes/footer.php'); ?>

</body>
</html>