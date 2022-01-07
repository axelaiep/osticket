<?php
/** Time constant - the number of seconds in a year */
define('YEARSECS', 31536000);

/** Time constant - the number of seconds in a week*/
define('WEEKSECS', 604800);

/** Time constant - the number of seconds in a day*/
define('DAYSECS', 86400);

/** Time constant - the number of seconds in an hour*/
define('HOURSECS', 3600);

/** Time constant - the number of seconds in a minute */
define('MINSECS', 60);

/** Time constant - the number of minutes in a day */
define('DAYMINS', 1440);

/** Time constant - the number of minutes in an hour*/
define('HOURMINS', 60);

$estado_acc[0] = array('cod' => 0,'img' => 'rojo.png','msg' => 'Nunca Conectado');
$estado_acc[1] = array('cod' => 1,'img' => 'naranja.png','msg' => 'Más de 2 semanas sin conexión');
$estado_acc[2] = array('cod' => 2,'img' => 'amarillo.png','msg' => 'Más de 1 semana sin conexión');
$estado_acc[3] = array('cod' => 3,'img' => 'verde.png','msg' => 'Conectado durante la última semana');
$estado_acc[4] = array('cod' => 4,'img' => 'gris.png','msg' => 'NO Volver a Contactar');
$estado_acc[5] = array('cod' => 5,'img' => '','msg' => 'Bienvenida');

$sigla_ext[0] = 'SER';
$sigla_sede[0] = 'LAS';
$sigla_ext[1] = 'BAR';
$sigla_sede[1] = 'BUS';
$sigla_ext[2] = 'VIÑ';
$sigla_sede[2] = 'VIN';


function fecha_gregoriana($fecha,$formato = 2) {
if($fecha == 0) {
    return "NUNCA";
    exit;
  } else {
      if ($formato == 1){
        return date("d-m", $fecha);
      }
      if ($formato == 2){
        return date("d-m-Y", $fecha);
      }
      if ($formato == 3){
        return date("d-m-Y H:i:s", $fecha);
      }
    }
}

function trae_seguimiento_estudiante($estudiante){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT * FROM seguimiento_estudiantes WHERE estudiante = '$estudiante' ORDER BY id DESC LIMIT 0 ,1");
  while($Datos ->setWhile()){
  $separador = "x;x";
  $salida = utf8_encode($Datos ->getDataSQL("id").$separador.$Datos ->getDataSQL("estudiante").$separador.$Datos ->getDataSQL("fecha").$separador.$Datos ->getDataSQL("via").$separador.$Datos ->getDataSQL("mensaje").$separador.$Datos ->getDataSQL("estado").$separador.$Datos ->getDataSQL("usuario"));
  return $salida;
  } 
}

function noContactar($id,$fecha){
  global $estado_acc;
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT COUNT(id) c FROM seguimiento_est WHERE id_estudiante = ".$id." AND respuesta='NO CONTACTAR'");
  while($Datos ->setWhile()){
    $cont = $Datos ->getDataSQL("c");
    if ($cont > 0){
      return $estado_acc[4];
    }else return semaforo($fecha);
  }
}

function semaforo($fecha){
  global $estado_acc;
  $hoy = time();
  $semana1 = $hoy - WEEKSECS;
  $semana2 = $hoy - (WEEKSECS*2); 

  if($fecha == 0) {
      return $estado_acc[0];
      //return "<img src='imagenes/rojo.png' alt='Nunca Conectado' title='N&uacute;nca Conectado'>";
      exit;
    } 
  if($fecha > 0 && $fecha < $semana2) {
      return $estado_acc[1];
      //return "<img src='imagenes/naranja.png' alt='M&aacute;s de 1 semana sin conexi&oacute;n' title='M&aacute;s de 1 semana sin conexi&oacute;n'>";
      exit; 
  } 
  if($fecha > 0 && $fecha < $semana1) {
      return $estado_acc[2];
      //return "<img src='imagenes/amarillo.png' alt='M&aacute;s de 2 semana sin conexi&oacute;n' title='M&aacute;s de 2 semana sin conexi&oacute;n'>";
      exit; 
  }
  
  if($fecha > $semana1) { 
      return $estado_acc[3];
      //return "<img src='imagenes/verde.png' alt='Conectado la &uacute;ltima semana' title='Conectado la &uacute;ltima semana'>";
      exit;    
  }
}

function trae_todas_sedes(){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT DISTINCT(city) FROM mdl_user WHERE city != '' AND address = 'ESTUDIANTE'");
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida[$i]=utf8_encode($Datos ->getDataSQL("city"));
    $i++;
  } 
  return $salida;
}

function trae_formatos(){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT id_mail, nombre FROM seguimiento_mail");
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida[$i]['id']=$Datos ->getDataSQL("id_mail");
    $salida[$i]['nombre']=utf8_encode($Datos ->getDataSQL("nombre"));
    $i++;
  } 
  return $salida;
}

function trae_unidades($id){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT id,section,name FROM mdl_course_sections  WHERE course = ".$id." AND name IS NOT NULL AND visible = 1 ORDER BY section");
  $sec='';
  $i=0;
  while($Datos ->setWhile()){
    $sec[$i]['id']=$Datos ->getDataSQL("id");
    $sec[$i]['sect']=$Datos ->getDataSQL("section");
    $sec[$i]['name']=utf8_encode($Datos ->getDataSQL("name"));
    $i++;
  } 
  return $sec;
}

function trae_modXtipo($idcur,$idsec,$mod){
  //echo "SELECT id,instance FROM mdl_course_modules cm WHERE cm.course = ".$idcur." AND cm.section = ".$idsec." AND cm.module = ".$mod." AND cm.visible = 1";
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT id,instance FROM mdl_course_modules cm WHERE cm.course = ".$idcur." AND cm.section = ".$idsec." AND cm.module = ".$mod." AND cm.visible = 1");
  $modulos='';
  $i=0;
  while($Datos ->setWhile()){
    $modulos[$i]['id']=$Datos ->getDataSQL("id");
    $modulos[$i]['inst']=$Datos ->getDataSQL("instance");
    $i++;
  } 
  return $modulos;
}

function trae_PruebaSum($inst,$idcur,$idusr){
  $Datos  = new OperacionMysql();
  $Intento  = new OperacionMysql();
  $quiz = '';
  $sumativ = '';
  for ($j=0;$j < count($inst);$j++){
    $Datos ->doQuery("SELECT q.id, q.timeopen, q.timeclose FROM mdl_quiz q WHERE q.id = ".$inst[$j]['inst']." AND q.course = ".$idcur." AND UPPER(q.name) LIKE '%SUMATIVA%'");
    $existe = $Datos->getAffectedRows();
    if($existe > 0){
      while($Datos ->setWhile()){
        $i=0;
        $quiz[$i]['id']=$Datos->getDataSQL("id");
        $quiz[$i]['ini']=$Datos->getDataSQL("timeopen");
        $quiz[$i]['fin']=$Datos->getDataSQL("timeclose");
      }
      
      $Intento->doQuery("SELECT qa.timefinish FROM mdl_quiz_attempts qa WHERE qa.state = 'finished' AND qa.userid=".$idusr." AND qa.quiz=".$quiz[0]['id']);
      $cant = $Intento->getAffectedRows();
      if($cant > 0){
        $i=0;
        while($Intento->setWhile()){
          $inte[$i]['fecha'] = $Intento->getDataSQL("timefinish");
        }

        $sumativ['hizo'] = 'S';

        if (($inte[0]['fecha'] >= $quiz[0]['ini']) && ($inte[0]['fecha'] <= $quiz[0]['fin'])){
          $sumativ['tiempo'] = 'S';  
        }else $sumativ['tiempo'] = 'N';

      }else{
        $sumativ['hizo'] = 'N';
        $sumativ['tiempo'] = 'N';
      }
    }
  }    
  return $sumativ;
}

function trae_PruebaFor($inst,$idcur,$idusr,$tip){
  $Datos  = new OperacionMysql();
  $Intento  = new OperacionMysql();
  $quiz = '';
  $forma = '';
  $formativ = '';

  if ($tip == 'ini'){
    $forma = 'FORMATIVA INICIAL';
  }else if ($tip == 'fin'){
    $forma = 'FORMATIVA FINAL';
  }

  for ($j=0;$j < count($inst);$j++){
    $Datos ->doQuery("SELECT q.id, q.timeopen, q.timeclose FROM mdl_quiz q WHERE q.id = ".$inst[$j]['inst']." AND q.course = ".$idcur." AND UPPER(q.name) LIKE '%".$forma."%'");
    $existe = $Datos->getAffectedRows();
    if($existe > 0){
      while($Datos ->setWhile()){
        $i=0;
        $quiz[$i]['id']=$Datos->getDataSQL("id");
        $quiz[$i]['ini']=$Datos->getDataSQL("timeopen");
        $quiz[$i]['fin']=$Datos->getDataSQL("timeclose");
      }
      
      $Intento->doQuery("SELECT qa.timefinish FROM mdl_quiz_attempts qa WHERE qa.state = 'finished' AND qa.userid=".$idusr." AND qa.quiz=".$quiz[0]['id']);
      $cant = $Intento->getAffectedRows();
      if($cant > 0){
        $i=0;
        while($Intento->setWhile()){
          $inte[$i]['fecha'] = $Intento->getDataSQL("timefinish");
        }

        $formativ['hizo'] = 'S';

        if (($inte[0]['fecha'] >= $quiz[0]['ini']) && ($inte[0]['fecha'] <= $quiz[0]['fin'])){
          $formativ['tiempo'] = 'S';  
        }else $formativ['tiempo'] = 'N';

      }else{
        $formativ['hizo'] = 'N';
        $formativ['tiempo'] = 'N';
      }
    }
  }    
  return $formativ;
}

function trae_TareaSum($inst, $idcur, $idusr){
  $Datos  = new OperacionMysql();
  $Intento  = new OperacionMysql();
  $assign = '';
  $sumativ = '';
  $i=0;
  for ($j=0;$j < count($inst);$j++){
    //echo "SELECT a.id, a.duedate close, a.allowsubmissionsfromdate open FROM mdl_assign a WHERE a.id =".$inst[$j]['inst']." AND a.course=".$idcur." AND UPPER(a.name) LIKE '%SUMATIVA%'";
    $Datos ->doQuery("SELECT a.id, a.duedate close, a.allowsubmissionsfromdate open FROM mdl_assign a WHERE a.id =".$inst[$j]['inst']." AND a.course=".$idcur." AND UPPER(a.name) LIKE '%SUMATIVA%'");
    $existe = $Datos->getAffectedRows();
    if($existe > 0){
      while($Datos ->setWhile()){
        $assign[$i]['id']=$Datos->getDataSQL("id");
        $assign[$i]['ini']=$Datos->getDataSQL("open");
        $assign[$i]['fin']=$Datos->getDataSQL("close");
        $i++;
      }
    }
  }

  if ($i == 1){
    $Intento->doQuery("SELECT ab.timecreated FROM mdl_assign_submission ab WHERE ab.status = 'submitted' AND ab.userid=".$idusr." AND ab.assignment=".$assign[0]['id']);
    $cant = $Intento->getAffectedRows();
    if($cant > 0){
      $j=0;
      while($Intento->setWhile()){
        $inte[$j]['fecha'] = $Intento->getDataSQL("timecreated");
      }

      $sumativ['hizo'] = 'S';

      if (($inte[0]['fecha'] >= $assign[0]['ini']) && ($inte[0]['fecha'] <= $assign[0]['fin'])){
        $sumativ['tiempo'] = 'S';  
      }else $sumativ['tiempo'] = 'N';

    }else{
      $sumativ['hizo'] = 'N';
      $sumativ['tiempo'] = 'N';
    }
  }else{
    $sumativ['hizo'] = '-';
    $sumativ['tiempo'] = '-';
  }
  return $sumativ; 
}

function trae_ForoOb($inst, $idcur, $idusr){
  $Datos  = new OperacionMysql();
  $Discu  = new OperacionMysql();
  $forum = '';
  $foros = '';
  $i=0;
  for ($j=0;$j < count($inst);$j++){
    $Datos ->doQuery("SELECT fr.id, fr.completiondiscussions, fr.completionreplies, fr.completionposts FROM mdl_forum fr WHERE fr.id =".$inst[$j]['inst']." AND fr.course=".$idcur." AND UPPER(fr.name) LIKE '%OBLIGATORIO%'");
    $existe = $Datos->getAffectedRows();
    if($existe > 0){
      while($Datos ->setWhile()){
        $forum[$i]['id']=$Datos->getDataSQL("id");
        $forum[$i]['dis']=$Datos->getDataSQL("completiondiscussions");
        $forum[$i]['rep']=$Datos->getDataSQL("completionreplies");
        $forum[$i]['pos']=$Datos->getDataSQL("completionposts");
      }

      $Discu->doQuery("SELECT COUNT(fd.id) canti FROM mdl_forum_discussions fd WHERE fd.forum = ".$forum[0]['id']." AND fd.userid =".$idusr);
      while($Discu->setWhile()){
          $cant['disc'] = $Discu->getDataSQL("canti");
      }
  
      $Discu->doQuery("SELECT COUNT(fp.id) canti FROM mdl_forum_posts fp WHERE fp.userid=".$idusr." AND fp.discussion IN (SELECT fd.id FROM mdl_forum_discussions fd WHERE fd.forum = ".$forum[0]['id'].")");
      while($Discu->setWhile()){
          $cant['post'] = $Discu->getDataSQL("canti");
      }

      if($forum[0]['dis'] <= $cant['disc']){
        $foros['cant_d'] = 'S';
      }else $foros['cant_d'] = 'N';

      if($forum[0]['pos'] <= $cant['post']){
        $foros['cant_p'] = 'S';
      }else $foros['cant_p'] = 'N';
    }else {
      $foros['cant_d'] ='-';
      $foros['cant_p'] ='-';
    }
    return $foros;
  }
}

function trae_gestion($id_est, $estado, $tipo_ges){
  if($estado != 3){
    $id_seg='';
    $Datos  = new OperacionMysql();
    $Datos->doQuery("SELECT MAX(id) id FROM seguimiento_est WHERE id_estudiante =".$id_est." AND estado=".$estado." AND gestion='".$tipo_ges."'");
    //echo "SELECT MAX(id) FROM seguimiento_est WHERE id_estudiante =".$id_est." AND estado=".$estado." AND gestion='".$tipo_ges."'";
    while($Datos->setWhile()){
      $id_seg=$Datos->getDataSQL("id");
    }
    if($id_seg != ''){
      $ges = '<img src="imagenes/chk.png">';
    }else $ges = '<img src="imagenes/sin.png">';

  }else{
    $ges = '<img src="imagenes/no.png">';
  }
  return $ges;
}

function trae_img($letra){
  if($letra == 'S'){
    $img = '<img src="imagenes/chk.png">';
  }

  if($letra == 'N'){
    $img = '<img src="imagenes/sin.png">';
  }

  if(($letra == '') || ($letra == '-')) {
    $img = '<img src="imagenes/no.png" alt="No Aplica" title="No Aplica">';
  }

  return $img;
} 

function trae_cohorte($estudiante){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT data FROM mdl_user_info_data WHERE userid = $estudiante AND fieldid = 5");
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida =  $Datos ->getDataSQL("data");
    $i++;
  } 
  return $salida;
}


function trae_email2($estudiante){
  $Datos  = new OperacionMysql();
  $Datos ->doQuery("SELECT ui.data, LOWER(u.email) email FROM mdl_user_info_data ui, mdl_user u WHERE ui.userid = $estudiante AND ui.fieldid = 6 AND ui.userid = u.id");
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida =  $Datos ->getDataSQL("data");
    if($salida == ''){
      $salida =  $Datos ->getDataSQL("email");
    }
    $i++;
  } 
  return $salida;
}

function trae_escuela($estudiante){
  $Datos  = new OperacionMysql();
  $Datos->doQuery("SELECT ui.data escuela FROM mdl_user_info_data ui WHERE ui.fieldid = 4 AND ui.userid = ".$estudiante);
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida =  $Datos->getDataSQL("escuela");
    $i++;
  } 
  return $salida;
}

function trae_fono($estudiante){
  $Datos  = new OperacionMysql();
  $Datos->doQuery("SELECT ui.data fono FROM mdl_user_info_data ui WHERE ui.fieldid = 8 AND ui.userid = ".$estudiante);
  $salida='';
  $i=0;
  while($Datos ->setWhile()){
    $salida =  $Datos->getDataSQL("fono");
    $i++;
  } 
  return $salida;
}

function DatosXrut($rut){
  $Datos  = new OperacionMysql();
  $existe= 0;
  $Datos ->doQuery("SELECT id,username,firstname,lastname,lastaccess FROM mdl_user WHERE idnumber = '".$rut."'");
  $existe = $Datos->getAffectedRows();
  if($existe > 0){
    while($Datos ->setWhile()){
      $usuario['id'] = $Datos->getDataSQL("id");
      $usuario['username'] = $Datos->getDataSQL("username");
      $usuario['firstname'] = $Datos->getDataSQL("firstname");
      $usuario['lastname'] = $Datos->getDataSQL("lastname");
      $usuario['lastaccess'] = $Datos->getDataSQL("lastaccess");
    }
  }
  return $usuario;
}

function DiasEntreStamp($fecha1){
  $fecha2 = time();
  $dif = $fecha2-$fecha1;
  //$dif = $dif;
  return intval($dif/60/60/24);
}


function trae_docente($curso) {
  $Datos  = new OperacionMysql();
  $existe= 0;
  $Datos ->doQuery("SELECT mdl_user.idnumber as rut, concat(mdl_user.firstname, ' ',mdl_user.lastname ) as nombres FROM mdl_course INNER JOIN mdl_context ON mdl_context.instanceid = mdl_course.id INNER JOIN mdl_role_assignments ON mdl_context.id = mdl_role_assignments.contextid INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid INNER JOIN mdl_user ON mdl_user.id = mdl_role_assignments.userid WHERE mdl_course.id = '".$curso."' AND mdl_role.id = 3 LIMIT 0, 1");
  $existe = $Datos->getAffectedRows();
  $divi = "||";
  if($existe > 0){
    while($Datos ->setWhile()){
      return $Datos->getDataSQL("rut").$divi.$Datos->getDataSQL("nombres");
    }
  }
}

function trae_rutaContex($curso) {
  $Datos  = new OperacionMysql();
  $existe= 0;
  $Datos ->doQuery("SELECT SUBSTRING(REPLACE(path, '/', ','),2) context FROM mdl_context WHERE instanceid =".$curso." AND contextlevel = 50");
  $existe = $Datos->getAffectedRows();
  if($existe > 0){
    while($Datos ->setWhile()){
      return $Datos->getDataSQL("context");
    }
  }
}

function trae_ForoNov($inst, $idcur, $idusr){
  $Discu  = new OperacionMysql();
  $cant = '';
  $Discu->doQuery("SELECT COUNT(fd.id) canti, MAX(fd.timemodified) fecha FROM mdl_forum_discussions fd WHERE fd.forum = ".$inst[0]['inst']." AND fd.userid =".$idusr);
  while($Discu->setWhile()){
    $cant['disc'] = $Discu->getDataSQL("canti");
    if ($cant['disc'] > 0) {
      $cant['fecha_d'] = DiasEntreStamp($Discu->getDataSQL("fecha"));
    } else $cant['fecha_d'] = 'Nunca';
  }
  
  $Discu->doQuery("SELECT COUNT(fp.id) canti, MAX(fp.created) fecha FROM mdl_forum_posts fp WHERE fp.userid=".$idusr." AND fp.discussion IN (SELECT fd.id FROM mdl_forum_discussions fd WHERE fd.forum = ".$inst[0]['inst'].")");
  while($Discu->setWhile()){
    $cant['post'] = $Discu->getDataSQL("canti");
    if ($cant['post'] > 0) {
      $cant['fecha_p'] = DiasEntreStamp($Discu->getDataSQL("fecha"));
    }else $cant['fecha_p'] = 0;
  }
    return $cant;
  }

function trae_TareaDoc($inst, $idcur, $idusr){
  $Datos  = new OperacionMysql();
  $Entrega  = new OperacionMysql();
  $assign = '';
  $i=0;
  for ($j=0;$j < count($inst);$j++){
    $Datos ->doQuery("SELECT a.id, a.duedate close FROM mdl_assign a WHERE a.id =".$inst[$j]['inst']." AND a.course=".$idcur." AND UPPER(a.name) LIKE '%SUMATIVA%'");
    $existe = $Datos->getAffectedRows();
    if($existe > 0){
      while($Datos ->setWhile()){
        $assign[$i]['id']=$Datos->getDataSQL("id");
        $assign[$i]['fin']=fecha_gregoriana($Datos->getDataSQL("close"),1);
        $context = trae_rutaContex($idcur);   
        $Entrega->doQuery("SELECT COUNT(u.id) canti FROM mdl_user u JOIN (SELECT DISTINCT eu2_u.id FROM mdl_user eu2_u JOIN mdl_role_assignments eu2_ra3 
          ON (eu2_ra3.userid = eu2_u.id AND eu2_ra3.roleid IN (5) AND eu2_ra3.contextid IN (".$context.")) JOIN mdl_user_enrolments eu2_ue 
          ON eu2_ue.userid = eu2_u.id JOIN mdl_enrol eu2_e ON (eu2_e.id = eu2_ue.enrolid AND eu2_e.courseid = '".$idcur."') WHERE eu2_u.deleted = 0 
          AND eu2_u.id <> '1' AND eu2_ue.status = '0' AND eu2_e.status = '0') je ON je.id = u.id");
        $Entrega->setWhile();
        $assign[$i]['parti']=$Entrega->getDataSQL("canti");
        //echo "SELECT COUNT(ab.id) canti FROM mdl_assign_grades ab WHERE ab.grader = ".$idusr." AND ab.assignment=".$inst[$j]['inst']."<br>";
        $Entrega->doQuery("SELECT COUNT(DISTINCT s.userid) canti FROM mdl_assign_submission s JOIN(SELECT DISTINCT eu1_u.id FROM mdl_user eu1_u JOIN mdl_role_assignments eu1_ra3 
          ON (eu1_ra3.userid = eu1_u.id AND eu1_ra3.roleid IN (5) AND eu1_ra3.contextid IN (".$context.")) JOIN mdl_user_enrolments eu1_ue 
          ON eu1_ue.userid = eu1_u.id JOIN mdl_enrol eu1_e ON (eu1_e.id = eu1_ue.enrolid AND eu1_e.courseid = ".$idcur.") WHERE eu1_u.deleted = 0 AND eu1_u.id <> '1' 
          AND eu1_ue.status = '0' AND eu1_e.status = '0') e ON e.id = s.userid WHERE s.assignment = ".$assign[$i]['id']." AND s.timemodified IS NOT NULL");
        $Entrega->setWhile();
        $assign[$i]['entrega']=$Entrega->getDataSQL("canti");
        $Entrega->doQuery("SELECT COUNT('x') canti FROM mdl_assign_submission s LEFT JOIN mdl_assign_grades g ON s.assignment = g.assignment AND s.userid = g.userid 
          WHERE s.assignment = ".$assign[$i]['id']." AND s.timemodified IS NOT NULL AND (s.timemodified > g.timemodified OR g.timemodified IS NULL)");
        $Entrega->setWhile();
        $assign[$i]['xcalif']=$Entrega->getDataSQL("canti");
        $assign[$i]['porc']=porcentaje($assign[$i]['entrega'],$assign[$i]['xcalif']);
        $i++;
      }
    }
  }
  return $assign; 
}

function TiempoDedicado($idcur,$idusr){
  $Datos  = new OperacionMysql();
  $previouslogtime = '';
  $Datos->doQuery("SELECT lg.id,lg.time FROM mdl_log lg WHERE lg.course = ".$idcur." AND lg.userid = ".$idusr." ORDER BY lg.time ASC");
  $existe = $Datos->getAffectedRows();
  $totaldedication = 0;
  $dedication = 0;

  if($existe > 0){
    $i=0;
    while($Datos->setWhile()){
      $logs[$i]['id'] = $Datos->getDataSQL("id");
      $logs[$i]['time'] = $Datos->getDataSQL("time");
      $i++;
    }

    $limitinseconds = 30*60;
    $previouslog = array_shift($logs);
    $previouslogtime = $previouslog['time'];
    $sessionstart = $previouslogtime;
    $totaldedication = 0;

    foreach ($logs as $log){
      if (($log['time'] - $previouslogtime) > $limitinseconds) {
          $dedication = $previouslogtime - $sessionstart;
          $totaldedication += $dedication;
          $sessionstart = $log['time'];
      }
      $previouslogtime = $log['time'];
    }

    $dedication = $previouslogtime - $sessionstart;
  }

  $totaldedication += $dedication;
  if($totaldedication){
      return format_time($totaldedication);
  }else{
      return 'Nunca';
  }
}

function format_time($totalsecs, $str = null) {

    $totalsecs = abs($totalsecs);

    if (!$str) {
        // Create the str structure the slow way.
        $str = new stdClass();
        $str->day   = 'día';
        $str->days  = 'días';
        $str->hour  = 'hora';
        $str->hours = 'horas';
        $str->min   = 'min';
        $str->mins  = 'mins';
        $str->sec   = 'seg';
        $str->secs  = 'segs';
        $str->year  = 'año';
        $str->years = 'años';
    }

    $years     = floor($totalsecs/YEARSECS);
    $remainder = $totalsecs - ($years*YEARSECS);
    $days      = floor($remainder/DAYSECS);
    $remainder = $totalsecs - ($days*DAYSECS);
    $hours     = floor($remainder/HOURSECS);
    $remainder = $remainder - ($hours*HOURSECS);
    $mins      = floor($remainder/MINSECS);
    $secs      = $remainder - ($mins*MINSECS);

    $ss = ($secs == 1)  ? $str->sec  : $str->secs;
    $sm = ($mins == 1)  ? $str->min  : $str->mins;
    $sh = ($hours == 1) ? $str->hour : $str->hours;
    $sd = ($days == 1)  ? $str->day  : $str->days;
    $sy = ($years == 1)  ? $str->year  : $str->years;

    $oyears = '';
    $odays = '';
    $ohours = '';
    $omins = '';
    $osecs = '';

    if ($years) {
        $oyears  = $years .' '. $sy;
    }
    if ($days) {
        $odays  = $days .' '. $sd;
    }
    if ($hours) {
        $ohours = $hours .' '. $sh;
    }
    if ($mins) {
        $omins  = $mins .' '. $sm;
    }
    if ($secs) {
        $osecs  = $secs .' '. $ss;
    }

    if ($years) {
        return trim($oyears .' '. $odays);
    }
    if ($days) {
        return trim($odays .' '. $ohours);
    }
    if ($hours) {
        return trim($ohours .' '. $omins);
    }
    if ($mins) {
        return trim($omins .' '. $osecs);
    }
    if ($secs) {
        return $osecs;
    }
    return 'now';
}

?>