<?php
require($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
// include("medoo.php");
use Medoo\Medoo;

function MedooMysql() {

  $database = new Medoo([
         'type' => 'mysql',
         'host' => '172.30.10.35',
         'database' => 'support',
         'username' => 'osticket',
         'password' => 'pKJ56q8P3LdW!VT',
         // [optional]
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        // 'port' => 3306,
        // 'prefix' => 'PREFIX_',
     ]);

  return $database;
}


function getListItemsByListId($list_id, $extra = false) {
  
  $database = MedooMysql();

  $select = [
      'id',
      'list_id',
      'value',
      'extra'
  ];
  
  $where = [
      'list_id' => (int) $list_id,
      'status' => 1,
  ];

  if ($extra) {
    $where['extra[~]'] = $extra . '%';
  }

  $niveles = $database->select('ost_list_items', $select, $where);

  // echo "<pre>";
  // print_r($select);
  // print_r($where);
  // print_r($niveles);
  // exit;

    if(count($niveles) > 0) {

      foreach ($niveles as $key => $nivel) {
        
            $lista[$nivel['id']] = [
              'value' => $nivel['value'],
              'list_id' => $nivel['list_id'],
              'extra' => $nivel['extra'],
            ];
      }
      // print_r($lista);
        return $lista;
    } else {
        return "N/A";
    }
}

function getTopicAcademicos() {
    $database = MedooMysql();

  $select = [
      'topic_id',
      'topic',
  ];
  
  $where = [
      'dept_id' => [4,5,8,9],
      'ispublic' => 1,
      "ORDER" => "topic",
  ];

  if ($extra) {
    $where['extra[~]'] = $extra . '%';
  }

  $niveles = $database->select('ost_help_topic', $select, $where);

  echo "<pre>";
  print_r($select);
  print_r($where);
  print_r($niveles);
  exit;

    // $string_SQL = "SELECT topic_id, topic FROM `ost_help_topic` WHERE dept_id IN(4,5,8,9) AND ispublic = 1 ORDER BY topic;";
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