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

function getListItemsByListId($list_id, $extra = false){
    $Datos  = new OperacionMysql();

    // print_r($extra);
    // print_r(json_decode($extra));
    // exit;

    $string_SQL = "SELECT id, list_id, value, extra FROM ost_list_items WHERE list_id = $list_id AND status = 1";

    if ($extra) {
      $string_SQL .= " AND extra like '$extra%'";
    }

    // print_r($string_SQL);

    $Datos ->doQuery($string_SQL);
    $cant_datos = $Datos ->getAffectedRows();
    // print_r($cant_datos);
    // exit;
    if($cant_datos > 0) {
        while($Datos ->setWhile()){

            $lista[$Datos ->getDataSQL("id")] = [
              'value' => utf8_encode($Datos ->getDataSQL("value")),
              'list_id' => utf8_encode($Datos ->getDataSQL("list_id")),
              'extra' => utf8_encode($Datos ->getDataSQL("extra")),
            ];
        }

        return $lista;
    } else {
        return "N/A";
    }
}

function getTopicAcademicos() {
    $Datos  = new OperacionMysql();

    $string_SQL = "SELECT topic_id, topic FROM `ost_help_topic` WHERE dept_id IN(4,5,8,9) AND ispublic = 1 ORDER BY topic;";
    // print_r($string_SQL);

    $Datos ->doQuery($string_SQL);
    $cant_datos = $Datos ->getAffectedRows();

    if($cant_datos > 0) {
        while($Datos ->setWhile()){

            $lista[$Datos ->getDataSQL("topic_id")] = [
              'topic_id' => utf8_encode($Datos ->getDataSQL("topic_id")),
              'topic' => utf8_encode($Datos ->getDataSQL("topic")),
            ];
        }

        return $lista;
    } else {
        return "N/A";
    }
}

function getTopicColaboradores() {
    $Datos  = new OperacionMysql();

    $string_SQL = "SELECT topic_id, topic FROM `ost_help_topic` WHERE ispublic = 1 AND flags = 2 ORDER BY topic;";

    $Datos ->doQuery($string_SQL);
    $cant_datos = $Datos ->getAffectedRows();

    if($cant_datos > 0) {
        while($Datos ->setWhile()){

            $lista[$Datos ->getDataSQL("topic_id")] = [
              'topic_id' => utf8_encode($Datos ->getDataSQL("topic_id")),
              'topic' => utf8_encode($Datos ->getDataSQL("topic")),
            ];
        }
        
        return $lista;
    } else {
        return "N/A";
    }
}




?>