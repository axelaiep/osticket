<?php
session_start();

include("config.php");

//$_SESSION['user'] ="ALISON.BURGOS";
$_SESSION['user'] ="ALEJANDRA.MATTHEI";


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso oficinas de atención</title>
	<link rel="icon" href="http://www.aiep.cl/favicon.ico" type="image/x-icon"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<style>
	.footer {
	  position: absolute;
	  bottom: 0;
	  width: 100%;
	  /* Set the fixed height of the footer here */
	  height: 60px;
	  background-color: #f5f5f5;
}
</style>

</head>

<body>
        
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
		<a href="#" class="navbar-left"><img src="https://dte.aiep.cl/img/logo-aiep.png" width="130px"></a>
		<a class="navbar-brand" href="#">&nbsp;</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
		</ul>
	</div>
  </div>
</nav>


<?php 
	//Se incia el container de login
	if(!isset($_SESSION['user'])) {
	$salir = "NO";
?>
<div class="container">

	<center>
		<div class="card card-container" style="width: 35rem;">
			<form class="form-signin" action="index.php" method="post">
				<h3 class="form-signin-heading">Acceso Oficina de Atención</h3>
				
				<br>
				<label for="inputEmail" class="sr-only">Nombre de usuario de Intranet</label>
				<input type="text" id="userLogin" name="userLogin" class="form-control" placeholder="Nombre de usuario de intranet" required autofocus>
				<div class="checkbox"></div>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="userPassword" name="userPassword" class="form-control" placeholder="Contraseña" required>
				<div class="checkbox"></div>
				<div class="checkbox"></div>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
		  </form>
	<?php 
	if(isset($_GET['error'])) 
	   {
		?>
	<div class="alert alert-danger">
	<strong>ATENCIÓN: </strong> El nombre de usuario o contraseña que ha introducido no son correctos. Inténtelo de nuevo.
	</div>


</div> <!-- /container -->

<?php   } 
?>

		</div>
	</center>
</div>	
<?php
	//Finaliza el container de login
} else {
		$salir = "SI";
	//Incia pagina autentificada
?>
<div class="container-fluid">

	<div class="row">
	
		<div class="col-md-6">
		<h3 class="form-signin-heading">Datos del Usuarios</h3>
<?php

$Datos  = new OperacionMysql();
$Datos ->doQuery("SELECT * FROM usuario WHERE EXTERNAL_PERSON_KEY = '".$_SESSION['user']."'");
$existentes = $Datos ->getAffectedRows();


if($existentes == 0) { 
  echo "sin datos del usuario";
} else {
	
	while($Datos ->setWhile()){ 
		//echo $Datos ->getDataSQL("EXTERNAL_PERSON_KEY");
		//echo "<br>";
		//echo $Datos ->getDataSQL("STUDENT_ID");
		echo "<br>USUARIO: ";
		echo utf8_encode($Datos ->getDataSQL("FIRSTNAME"));
		echo " ";
		echo utf8_encode($Datos ->getDataSQL("LASTNAME"));
		//echo "<br>";
		//echo $Datos ->getDataSQL("EMAIL");
		//echo "<br>";
		//echo $Datos ->getDataSQL("INSTITUTION_ROLE");
		//echo "<br>";
		//echo $Datos ->getDataSQL("B_FAX");
		echo "<br>SEDE: ";
		echo utf8_encode($Datos ->getDataSQL("B_PHONE_1"));
		echo "<br>ESCUELA: ";
		echo utf8_encode($Datos ->getDataSQL("STREET_1"));
		echo "<br>CARRERA: ";
		//echo utf8_decode($Datos ->getDataSQL("STREET_2"));	
		//echo "<br>";
		echo utf8_encode($Datos ->getDataSQL("STREET_2"));	

		if($Datos ->getDataSQL("B_PHONE_1") == 'PROVIDENCIA') {
			$sede = 'BELLAVISTA';
		} else {
			$sede = $Datos ->getDataSQL("B_PHONE_1");
		}
		
$Datos1  = new OperacionMysql();
$Datos1 ->doQuery("SELECT * FROM oficina WHERE sede like '%".$sede."%' AND estado = 'ACTIVA'");
$existentes1 = $Datos1 ->getAffectedRows();


if($existentes1 == 0) { 
  echo "<br><br><b>Sin salas para esta sede</b>";
} else {
	
	while($Datos1 ->setWhile()){ 
	echo "<br><br>OFICINAS DE ATENCION DISPONIBLE: Si";
	//echo "<br><br>ID OFICINA: ";
	//echo $Datos1 ->getDataSQL("id_oficina");
	//echo "<br><br>SEDE: ";
	//echo utf8_encode($Datos1 ->getDataSQL("sede"));
	echo "<br><br>ESCUELA: ";
	echo utf8_encode($Datos1 ->getDataSQL("escuela"));
	?>
		</div>
		<div class="col-md-6">
		<h3 class="form-signin-heading">Horarios de atención</h3>		
<br>
<table class="table table-bordered table-sm">
  <tr>
    <td colspan="2" class="table-success">HORARIO AM</td>
    <td colspan="2" class="table-success">HORARIO PM</td>
  </tr>
  <tr>
    <td class="table-success">INICIO</td>
    <td class="table-success">FIN</td>
    <td class="table-success">INICIO</td>
    <td class="table-success">FIN</td>
  </tr>
  <tr>

<td><?php echo substr($Datos1 ->getDataSQL("h_inicio_am"), 0, 5); ?></td>
<td><?php echo substr($Datos1 ->getDataSQL("h_fin_am"), 0, 5); ?></td>
<td><?php echo substr($Datos1 ->getDataSQL("h_inicio_pm"), 0, 5); ?></td>
<td><?php echo substr($Datos1 ->getDataSQL("h_fin_pm"), 0, 5); ?></td>
  </tr>
</table>	
	
<?php	
	echo "<br>DIAS DE ATENCION: ";
	echo utf8_encode($Datos1 ->getDataSQL("dias"));	
	echo "<br>";
	$link_salto = "salto.php?id_oficina=";
	$link_salto .= $Datos1 ->getDataSQL("id_oficina");	
	$link_salto .= "&usuario=";
	$link_salto .= $Datos ->getDataSQL("EXTERNAL_PERSON_KEY");	
	$link_salto .= "&url=";
	$link_salto .= $Datos1 ->getDataSQL("link");

	
	switch(6) {
		case 0:
		$dia = "Domingo";
		break;
		case 1:
		$dia = "Lunes";
		break;
		case 2:
		$dia = "Martes";
		break;
		case 3:
		$dia = "Miércoles";
		break;
		case 4:
		$dia = "Jueves";
		break;
		case 5:
		$dia = "Viernes";
		break;
		case 6:
		$dia = "Sábado";
		break;		
	}


	$pos = strpos(utf8_encode($Datos1 ->getDataSQL("dias")), $dia);
?>

<?php
	
	if($pos === false) {
		echo "<br>LINK: No disponible para este dia de la semana";
	} else {
	
	//AM
	if(strtotime(date("H:i:s")) > strtotime($Datos1 ->getDataSQL("h_inicio_am")) && strtotime(date("H:i:s")) < strtotime($Datos1 ->getDataSQL("h_fin_am")) ) {
		?>
		<br>
		<a class="btn active btn-primary" href="<?php
		echo $link_salto;
		?>">Click aquí para entrar a la oficina</a></B>
<?php
	} else { 
	//PM
	if($dia != "Sábado") {
	if(strtotime(date("H:i:s")) > strtotime($Datos1 ->getDataSQL("h_inicio_pm")) && strtotime(date("H:i:s")) < strtotime($Datos1 ->getDataSQL("h_fin_pm")) ) {
		?>
		<br>
		<a class="btn active btn-primary" href="<?php
		echo $link_salto;
		?>">Click aquí para entrar a la oficina</a></B>
<?php } else { ?>
<br>
<a class="btn active btn-primary" href="#">Oficina no activa en este horario</a>
	<?php } } else {
?>
<a class="btn active btn-primary" href="#">Oficina no activa en este horario</a>
<?php		
		
	}

}
	}
echo "<BR>";
/* 
echo $dia;
echo "<BR>";
echo $Datos1 ->getDataSQL("dias");

echo "<BR>";
	
echo "<br>HORA LOCAL: ";
echo date("H:i:s");

echo "<br>DIA LOCAL: ";
echo date("w");
 */

?>


		</div>


<?php
}
}
	
	 }  
 } 
?>
	</div>
	
</div>
<?php 
} ?>



<br><br>

  <div class="container">
<center>  
      <p> <center>Subdirección de Tecnología Educativa</center> 
	  <?php if($salir == 'SI') { ?><p> <center> Sesión activa para <b><?php echo $_SESSION['user'];?></b> - <a href="<?php echo 'index.php?out';?>"> SALIR</a></center> </p><?php } ?>
	  </p></p>
  <center>	  
  </div>




</body>
</html>