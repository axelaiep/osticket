<?php


 /*
  *   Pregunta si ya existe la ip en nuestra base local
  *   
  */
function ip_exist($ip){
  $salida = array();
  $Datos  = new OperacionMysql();
  //$Datos ->doQuery("SELECT  `ciudad`, `region`, `pais`, `localizacion`, `proveedor`, `timezone`, `postal`, `asn`, `type`, `fullNameISP` FROM `IpUnica` WHERE `ip` = '$ip'");
   $Datos ->doQuery("SELECT  `ciudad`, `region`, `pais`, `localizacion`, `proveedor`, `timezone`, `postal`, `asn`, `type`, `fullNameISP` FROM `db_ip_unicas` WHERE `ip` = '$ip'");
  while($Datos ->setWhile()){
       $salida = array($Datos ->getDataSQL("ciudad"), $Datos ->getDataSQL("region"), $Datos ->getDataSQL("pais"), $Datos ->getDataSQL("localizacion"), $Datos ->getDataSQL("proveedor"), $Datos ->getDataSQL("timezone"), $Datos ->getDataSQL("postal"),$Datos ->getDataSQL("asn"),$Datos ->getDataSQL("type"), $Datos ->getDataSQL("fullNameISP"));   
  }
  return $salida;

  //INSERT INTO `db_ip_unicas`(`ip`, `ciudad`, `region`, `pais`, `localizacion`, `proveedor`, `timezone`, `postal`, `asn`, `type`, `full_name_isp`) 
  //SELECT `ip`, `ciudad`, `region`, `pais`, `localizacion`, `proveedor`, `timezone`, `postal`, `asn`, `type`, `fullNameISP` FROM `IpUnica`

}


function trae_sede($sede){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT SEDE_NOMBRE FROM sedes WHERE SEDE_COD = '$sede'");
  while($Datos ->setWhile()){
  $salida = utf8_encode($Datos ->getDataSQL("SEDE_NOMBRE"));
  return $salida;
  } 
}



function trae_modalidad($carrera){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT CARR_JORNADA FROM carreras WHERE MAT_ID_CARRERA = '$carrera'");
  while($Datos ->setWhile()){
  $salida = utf8_encode($Datos ->getDataSQL("CARR_JORNADA"));
  return $salida;
  } 
}


function trae_numero_notas($COURSE_ID) {
	  $Datos  = new OperacionMysql();
	  $Datos->doQuery("SELECT DISTINCT PK_COLUMNA FROM notas WHERE COURSE_ID = '$COURSE_ID'");
	  $salida= $Datos ->getNumRows();
  return $salida;

}


function trae_numero_notas_enlazadas($COURSE_ID) {
	  $Datos  = new OperacionMysql();
	  $Datos->doQuery("SELECT * FROM notas_enlace WHERE POSICION IS NOT NULL AND COURSE_ID = '$COURSE_ID'");
	  $salida= $Datos ->getNumRows();
  return $salida;

}

function set_enlace($COURSE_ID, $PK_COLUMNA, $POSICION, $UNIDAD, $TIPO_NOTA) {
	  $Datos  = new OperacionMysql();
	  $Datos->doQuery("UPDATE notas_enlace SET POSICION = '$POSICION',  UNIDAD = '$UNIDAD', TIPO_NOTA = '$TIPO_NOTA' WHERE COURSE_ID = '$COURSE_ID' AND PK_COLUMNA = '$PK_COLUMNA'");
	  $salida= $Datos ->getAffectedRows();
	  if($salida > 0) {
		 return $salida; 
		  
	  } else {
		 return 0; 
	  }
}


function trae_seccion($USUARIO, $MODULO, $ANO, $SEMESTRE) {
	  $Datos  = new OperacionMysql();
	  $Datos->doQuery("SELECT SEPARADOR(SECCION_OBTIENE('$USUARIO', '$MODULO', concat('$ANO','-', '$SEMESTRE')), '-', 2) AS salida");
	  $salida= $Datos ->getAffectedRows();
	  if($salida == 0) {
		  return NULL;
	  }
	  if($salida == 2) {
		  return NULL;
	  }
	  if($salida == 3) {
		  return NULL;
	  }
	  while($Datos ->setWhile()){
		return $Datos ->getDataSQL("salida");
	  } 	  
}


function trae_modalidad_bb($EXTERNAL_COURSE_KEY){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT MODALIDAD_BB FROM modulos WHERE EXTERNAL_COURSE_KEY like '$EXTERNAL_COURSE_KEY%'");
  while($Datos ->setWhile()){
  $salida = utf8_encode($Datos ->getDataSQL("MODALIDAD_BB"));
  return $salida;
  } 
}

function trae_modalidad_forzada($MODULO, $SECCION, $ANO, $SEMESTRE){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT MODALIDAD_FORZADA FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE'");
  $salida= $Datos ->getAffectedRows();
	if($salida > 0) {
  
		  while($Datos ->setWhile()){
				return $Datos ->getDataSQL("MODALIDAD_FORZADA");
		  } 
    } else {
		
		return 'N/A';
	}
}

// jlcp 07/02/2019
function trae_modalidad_forzadax($MODULO, $SECCION, $ANO, $SEMESTRE, $CARRERA){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT MODALIDAD_FORZADA FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE' AND CARRERA_COD = '$CARRERA'");
  $salida= $Datos ->getAffectedRows();
	if($salida > 0) {
  
		  while($Datos ->setWhile()){
				return $Datos ->getDataSQL("MODALIDAD_FORZADA");
		  } 
    } else {
		
		return 'N/A';
	}
}



function trae_fecha_forzada($MODULO, $SECCION, $ANO, $SEMESTRE){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT FECHA_FORZADA FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE'");
  $salida= $Datos ->getAffectedRows();
	if($salida > 0) {
		  while($Datos ->setWhile()){
				return $Datos ->getDataSQL("FECHA_FORZADA");
		  } 
    } else {
		return '0000-00-00';
	}
}

// jlcp 07/02/2019
function trae_fecha_forzadax($MODULO, $SECCION, $ANO, $SEMESTRE, $CARRERA){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT FECHA_FORZADA FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE' AND CARRERA_COD = '$CARRERA'");
  $salida= $Datos ->getAffectedRows();
	if($salida > 0) {
		  while($Datos ->setWhile()){
				return $Datos ->getDataSQL("FECHA_FORZADA");
		  } 
    } else {
		return '0000-00-00';
	}
}

function trae_fecha_forzada2($MODULO, $SECCION, $ANO, $SEMESTRE){
    $Datos  = new OperacionMysql();
    $Datos ->doQuery("SELECT FECHA_FORZADA2 FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE'");
    $salida= $Datos ->getAffectedRows();
    if($salida > 0) {
        while($Datos ->setWhile()){
            return $Datos ->getDataSQL("FECHA_FORZADA2");
        }
    } else {
        return '0000-00-00';
    }
}

// jlcp 07/02/2019
function trae_fecha_forzada2x($MODULO, $SECCION, $ANO, $SEMESTRE, $CARRERA){
    $Datos  = new OperacionMysql();
    $Datos ->doQuery("SELECT FECHA_FORZADA2 FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE' AND CARRERA_COD = '$CARRERA'");
    $salida= $Datos ->getAffectedRows();
    if($salida > 0) {
        while($Datos ->setWhile()){
            return $Datos ->getDataSQL("FECHA_FORZADA2");
        }
    } else {
        return '0000-00-00';
    }
}

// jlcp 26/07/2019 otimiza la consulta
function trae_datos_forzados($MODULO, $SECCION, $ANO, $SEMESTRE, $CARRERA){
    $Datos  = new OperacionMysql();
    $Datos ->doQuery("SELECT MODALIDAD_FORZADA, FECHA_FORZADA, FECHA_FORZADA2 FROM modulos_forzados WHERE MODULO = '$MODULO' AND SECCION = '$SECCION' AND ANO = '$ANO' AND SEMESTRE = '$SEMESTRE' AND CARRERA_COD = '$CARRERA'");
    $salida= $Datos ->getAffectedRows();
    if($salida > 0) {
        while($Datos ->setWhile()){
            return $Datos ->getDataSQL("MODALIDAD_FORZADA")."|".$Datos ->getDataSQL("FECHA_FORZADA")."|".$Datos ->getDataSQL("FECHA_FORZADA2");
        }
    } else {
        return "N/A|0000-00-00|0000-00-00";
    }
}

// function test(){
//   $Datos  = new OperacionMysql();
//   $Datos ->doQuery("SELECT * FROM ost_list");
//   while($Datos ->setWhile()){
//     echo "<pre>";
//     print_r($Datos ->setWhile());
//     exit;
//   $salida = utf8_encode($Datos ->getDataSQL('name'));
//   echo "<pre>";
//   print_r($salida);
//   exit;

//   return $salida;
//   } 
// }

function getListItemsByListId($list_id, $extra = false){
    $Datos  = new OperacionMysql();

    $string_SQL = "SELECT id, list_id, value, extra FROM ost_list_items WHERE list_id = $list_id AND status = 1";

    if ($extra) {
      $string_SQL .= " AND extra like '$extra%'";
    }
    // print_r($string_SQL);
    $Datos ->doQuery($string_SQL);
    // $Datos ->doQuery("SELECT id, list_id, value, extra FROM ost_list_items WHERE list_id = $list_id AND status = 1");
    $salida= $Datos ->getAffectedRows();
    if($salida > 0) {
        while($Datos ->setWhile()){
          // echo "<pre>";

            $lista[$Datos ->getDataSQL("id")] = [
              'value' => utf8_encode($Datos ->getDataSQL("value")),
              'list_id' => utf8_encode($Datos ->getDataSQL("list_id")),
              'extra' => utf8_encode($Datos ->getDataSQL("extra")),
            ];
        }

        return $lista;
    } else {
        return "N/A|0000-00-00|0000-00-00";
    }
}

?>