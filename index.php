<?php 
session_start();
session_unset();
session_destroy(); 
?>
<!DOCTYPE html>
<html lang="en">
<?php include('includes/header.php'); ?>
<body>
        
    <?php include('includes/menu.php'); ?>


    <div class="container">
        <div class="row description">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <p align="justify">Desde aquí ponemos a tu disposición nuestros servicios de ayuda a estudiantes, docentes y colaboradores de Aiep Virtual, Carreras de Telepresencia y temas relacionados con el Aula Virtual.</p>

                 <p align="justify">En el caso de presentar inconvenientes con otras plataformas, como Intranet, correo, carga académica o titulación debe ser realizado vía la opción “Sugerencias y Reclamos” de su intranet o en SAE Digital saedigital.aiep.cl</p>

                 <p align="justify">Si usted tiene algún inconveniente para rendir la una evaluación de un Módulos Semipresencial u Online, debe tomar contacto con su docente, él es el único autorizado y tiene las herramientas y atribuciones para dar un nuevo intento o extender plazo para alguna actividad o evaluación.</p>
            </div>
        </div>
    </div>


    <div class="container text-center">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <a href="login.php">
                    <img class="button-img" src="img/colaboradores.png">
                    <p class="button-title">Estudiantes y Docente</p>
                </a>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                
                <a href="login_colaborador.php">
                <!-- <a href="https://support.dtedevelop.cl/open.php"> -->
                    <img class="button-img" src="img/estudiantes_profesores.png">
                    <p class="button-title">Colaborador AIEP</p>
                </a>

            </div>

            <div class="col-xs-12 col-sm-6 col-md-6 offset-md-3 offset-xs-3">
                
                <a href="/support/view.php">
                <!-- <a href="https://support.dtedevelop.cl/open.php"> -->
                    <img class="button-img" src="img/ticket.png">
                    <p class="button-title">Ver estado de ticket</p>
                </a>

            </div>
        </div>
          
    </div> <!-- /container -->




<?php include('includes/footer.php'); ?>


</body>
</html>