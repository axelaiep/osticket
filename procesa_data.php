<?php

require_once('functions/BrowserDetection.php');
require_once('functions/funciones.php');
include ('functions/config.php');
include("functions/authenticate.php");
include("functions/curl.php");
// initialize session
session_start();
// getRealIP();
$geolocalizacion = getLocalizacion();

$apiInterna = [
	'sede' => $_SESSION['api_user_info']->sede,
	'carrera' => $_SESSION['api_user_info']->carrera,
	'modalidad' => $_SESSION['api_user_info']->modalidad,
	'escuela' => $_SESSION['api_user_info']->escuela,
	'cod_sede' => $_SESSION['api_user_info']->cod_sede,
	'cod_carrera' => $_SESSION['api_user_info']->cod_carrera,
];

if ($_SESSION['colaborador'] == 1) {
	
	$data_sede = explode('-', $_POST['sedeId']);
	$sede = $data_sede[1];
	$cod_sede = $data_sede[0];

	$apiInterna = [
		'sede' => $sede,
		'carrera' => $_POST['carrera'],
		'modalidad' => $_POST['modalidadId'],
		'escuela' => $_POST['escuelaId'],
		'cod_sede' => $cod_sede,
		'cod_carrera' => 'NO APLICA',
	];
}

$email = $_SESSION['email'];
$name = $_SESSION['nombrecompleto'];
$asunto = $_POST['asunto'];
$mensaje = $_POST['detalle'];
$real_ip = getRealIP();
$prioridad = 'Alta';
$topic = $_POST['topicId'];

if(!empty($_POST['files'])) {
	$retorna_archivos = json_decode($_POST['files']);
}

$rut = $_SESSION['rut'];
$username = $_SESSION['user'];
$sede = $apiInterna['sede'];
$cod_sede = (int) $apiInterna['cod_sede'];
$carrera = $apiInterna['carrera'];
$cod_carrera = (int) $apiInterna['cod_carrera'];
$modalidad = $apiInterna['modalidad'];
$escuela = $apiInterna['escuela'];
$modulo = $_POST['modulo'];
$seccion = $_POST['seccion'];
$telefono_alt = $_POST['telefono_alt'];
$correo_alt = $_POST['correo_alt'];
$navegador = $browserName;
$sistema_operativo = $platformName;
$dispositivo = 'Desktop';

if ($isMobile === true) {
	$dispositivo = 'Mobile';
}
$ciudad = $geolocalizacion['city'];
$proveedor = $geolocalizacion['org'];

$velocidad_descarga = $_POST['velocidad_descarga'];

$tipoId = $_POST['tipoId']; // category form_osticket

$role_usuario = $_SESSION['role_usuario'];

$ticket_data = [
	'email' => $email,
	'name' => $name,
	"phone" => $telefono_alt,
	'username' => $username,
	'rut' => $rut,
	"alert" => true,
	"autorespond" => true,
	'source' => 'API',
	'subject' => $asunto,
	'ip' => $real_ip,
	'message' => $mensaje,
	'sede' => $sede,
	'carrera' => $carrera,
	'modalidad' => $modalidad,
	'escuela' => $escuela,
	'modulo' => $modulo,
	'seccion' => $seccion,
	'browser' => $navegador,
	'SO' => $sistema_operativo,
	'device' => $dispositivo,
	'city' => $ciudad,
	'priority' => $prioridad,
	'topicId' => (int) $topic,
	'sede_cod' => $cod_sede,
	'cod_carrera' => $cod_carrera,
	'isp' => $proveedor,
	'category' => $tipoId,
	'mbps' => $velocidad_descarga,
	'nivel1' => $_POST['Nivel1'],
	'nivel2' => $_POST['Nivel2'],
	'nivel3' => $_POST['Nivel3'],
	'role-usuario' => $role_usuario,
	'correo-alt' => $correo_alt,
	// 'telefono-alt' => $telefono_alt,

];

// echo "<pre>";
// // print_r($apiInterna);
// print_r($_POST);
// print_r($ticket_data);
// exit;

if ($topic == 22) {
	$ticket_data['biblio-category'] = $_POST['bibliotecaId'];
}

if ($retorna_archivos) {
	$ticket_data['attachments'] = $retorna_archivos;
}

// $ticket_data['attachments'] = [
//         ["file.txt" => "data:text/plain;charset=utf-8,content"],
//         ["image.png" => "data:image/png;base64,R0lGODdhMAA..."],
//     ];

//$url = 'https://support.dtedevelop.cl/api/tickets.json';
// $url = 'https://dte.aiep.cl/osticketform/support/api/tickets.json';
// $url = 'https://dte.aiep.cl/osticketform/support/api/http.php/tickets.json';
$url = 'https://saeonline.aiep.cl/support/api/http.php/tickets.json';


postCurl($url, $ticket_data);
// exit;
 
// check to see if user is logging out
if(isset($_GET['out'])) {
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['usuario'],$_SESSION['access']);
	session_destroy();
}


$user_login = filter_input(INPUT_POST, 'userLogin');
$user_login = preg_replace('([^A-Za-z0-9. ])', '', $user_login);

$user_password = filter_input(INPUT_POST, 'userPassword');
$user_password = preg_replace('([^A-Za-z0-9. ])', '', $user_password);

// check to see if login form has been submitted
if(isset($user_login)){
	// run information through authenticator
	if(authenticate($user_login, $user_password)) {

		//REGISTRO LOGIN
		// $log = reg_login($user_login);
		// echo $log ;
		// authentication passed		
		
		header("Location: web/osticketform.php");
		// header("Location: web/index.php");
		die();
    
	} else {
		// authentication failed
		$error = 1;
	}
}

// output error to user
//if(isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";
