<?php

function porcentaje($total, $parcial){ 
$dd = 0;
  if($parcial == 0) {
     $dd = redondeado ($dd, 2); 
    return $dd;
  } else {
     $dd = redondeado (($parcial / $total) * 100, 2); 
    return $dd;  
  }

}


function redondeado ($numero, $decimales) { 
   $factor = pow(10, $decimales); 
   return (round($numero*$factor)/$factor); 
} 


function color($numero) { 
   $color = "#000000";
   if($numero < 50) { $color = "#FF0000";}
   if($numero >= 50 || $numero < 100) { $color = "#FFFF00"; }
   if($numero == 100) { $color = "#00CC00"; }    
   return $color; 
} 



function estado($estado) { 
   $icono = "";
   if($estado == 1) { 
      return "activo.png";
    } else {
      return "inactivo.png";    
    } 
} 

function invierte_fecha($fecha) {
$diviso = "-";
$ff = explode("-", $fecha);
$fecha = $ff[2].$diviso.$ff[1].$diviso.$ff[0];
return $fecha; 
}




/**
 * Veridica si el usuario está logeado
 * @return bool
 */
function estoy_logeado () {
    @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)
    
    if (!isset($_SESSION['USUARIO'])) {
     register_log("00000", "Salida Sesion no existe");
     return false;
     } //no existe la variable $_SESSION['USUARIO']. No logeado.

    if (!is_array($_SESSION['USUARIO'])) {
         register_log("00000", "Salida Sesion terminada");
          return false;
     } //la variable no es un array $_SESSION['USUARIO']. No logeado.
    
    if (empty($_SESSION['USUARIO']['usuario_usuario'])) {
          register_log("00000", "Salida Sesion terminada");
          return false;
    } //no tiene almacenado el usuario en $_SESSION['USUARIO']. No logeado.
    
    
    
    $timeactual = time();
    $diferencia = $timeactual - $_SESSION['time'];
    if ($diferencia > 3600) { 
          register_log("00000", "Salida Sesion terminada");    
    return false;
    }
    //cumple las condiciones anteriores, entonces es un usuario validado
    return true;

}

/**
 * Vacia la sesion con los datos del usuario validado
 */
function logout() {
    @session_start(); //inicia sesion (la @ evita los mensajes de error si la session ya está iniciada)
    register_log($_SESSION['USUARIO']['usuario_usuario'], "Salida OK");
    unset($_SESSION['USUARIO']); //eliminamos la variable con los datos de usuario;
    session_write_close(); //nos asegurmos que se guarda y cierra la sesion
    return true;
}


function color_alerta($fecha) { 
   $color = "celdaColumna";
  
   if($fecha == "00-00-0000") { $color = "celdaAlerta";}
   return $color; 
} 

function alerta($fecha) { 
   $color = "celdaColumna";
   
   $fecha_informada = strtotime($fecha);
   $fecha_hoy = time(); 
   
   if($fecha_informada < $fecha_hoy) { $color = "celdaAlerta";}
      
   return $color; 
} 

function conversorSegundosHoras($tiempo_en_segundos) {
	$horas = floor($tiempo_en_segundos / 3600);
	$minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
	$segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);
 
	$hora_texto = "";
	if ($horas > 0 ) {
		$hora_texto .= $horas . " hora ";
	}
 
	if ($minutos > 0 ) {
		$hora_texto .= $minutos . " minutos y ";
	}
 
	if ($segundos > 0 ) {
		$hora_texto .= $segundos . " segundos";
	}
 
	return $hora_texto;
}

?>