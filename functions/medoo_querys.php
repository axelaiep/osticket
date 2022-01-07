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

    //agrego guiones (-) si el nivel es igual o menor a 2. Para que la busqueda de datos sea más preciso.
    $cant_niveles = count(explode('-', $extra));
    if ($cant_niveles <= 2) {
      $extra = $extra . '-';
    }

    $where['extra[~]'] = $extra . '%';
  }

  $niveles = $database->select('ost_list_items', $select, $where);

    //si encuentro datos, preparo para retornar a la vista.
    if(count($niveles) > 0) {

      foreach ($niveles as $key => $nivel) {
        
            $lista[$nivel['id']] = [
              'value' => $nivel['value'],
              'list_id' => $nivel['list_id'],
              'extra' => $nivel['extra'],
            ];
      }
        return $lista;
    } 
}

function getTopicAcademicos() {
  $database = MedooMysql();

  $select = [
      'topic_id',
      'topic',
  ];
  
  $where = [
      // 'dept_id' => [4,5,8,9],
      'topic_id' => [13,14,19,20,22],
      'ispublic' => 1,
      "flags" => 2,
      "ORDER" => "topic",
  ];

  $topics = $database->select('ost_help_topic', $select, $where);
  
  if (count((array) $topics) > 0) {
    foreach ($topics as $key => $topic) {
      $lista[$topic['topic_id']] = [
        'topic_id' => $topic['topic_id'],
        'topic' => $topic['topic'],
      ];
    }
  }

  // echo "<pre>";
  // print_r($lista);
  // print_r($topics);
  // exit;
  return $lista;
}

function getTopicColaboradores() {
  $database = MedooMysql();

  $select = [
      'topic_id',
      'topic',
  ];
  
  $where = [
      'ispublic' => 1,
      "flags" => 2,
      "ORDER" => "topic",
  ];

  $topics = $database->select('ost_help_topic', $select, $where);

  if (count((array) $topics) > 0) {
    foreach ($topics as $key => $topic) {
      $lista[$topic['topic_id']] = [
        'topic_id' => $topic['topic_id'],
        'topic' => $topic['topic'],
      ];
    }
  }

  return $lista;
  // echo "<pre>";
  // print_r($lista);
  // exit;
}