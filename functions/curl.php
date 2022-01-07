<?php 

function postCurl($url, $data_array) {

	$ch = curl_init();
	//API KEY
	// print_r($data_array);
	if (count((array)$post_data['attachments']) > 0 ) {
		$data_array['attachments'] = json_decode($post_data['attachments']);
	}

	

	$header = [
		'X-API-Key: 28156029B2FD617A411665AC02C41A4E' //DTE
	];

	$data = json_encode($data_array);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

	//REMOVER EN PRODUCCIÓN | SE SALTA VALIDACIÓN SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$resp = curl_exec($ch);

	// echo "<pre>";
	// print_r($data_array);
	// print_r($resp);
	// exit;
	
	if ($error = curl_error($ch)) {
		$resp_status['status'] = 'error';
		$resp_status['message'] = $resp;

	} else {
		$decoded = json_decode($resp);
		$resp_status['status'] = 'success';
		// print_r($resp);
		if (((int) $resp) == 0) {
			$resp_status['message'] = 'Lo sentimos ha exedido la cantidad máxima de tickets que puede crear.';
		} else {
			$resp_status['message'] = 'El ticket fue creado éxitosamente, el ID generado es ' . $resp;
		}

		// $resp_status['message'] = $resp;
		// $resp_status['message'] = 'El ticket fue creado éxitosamente, el ID generado es ' . $resp;
	}
	// print_r($resp);
	// print_r($ch);
	// print_r($decoded);
	// print_r($error);
	// exit;
	// var_dump((int) $resp);
	// exit;
	$_SESSION['resp_status'] = $resp_status;
	// echo 'end curl';
	curl_close($ch);
	echo json_encode($resp_status);
}


function getAccessToken() {

	$ch = curl_init();
	$url = 'https://apirestbb.aiep.cl/auth/login';

	//DE MOMENTO EL ACCESO ES ESTÁTICO
	curl_setopt_array($ch, array(
	  CURLOPT_URL => 'https://apirestbb.aiep.cl/auth/login',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => array('email' => 'alberto.ortiz@aiep.cl','password' => 'hF8gQqlx4hF8gQqlx4'),
	));

	// curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$resp = curl_exec($ch);

	if ($error = curl_error($ch)) {
		echo $error;
	} else {
		$decoded = json_decode($resp);

	}
	curl_close();
	return $decoded->access_token;

}

function getUserData($userExternalPersonKey = null) {

	$ch = curl_init();

	$token = getAccessToken();

	curl_setopt_array($ch, array(
	  // CURLOPT_URL => 'https://apirestbb.aiep.cl/v2/users/AARON.ALTAMIRANO',
	  CURLOPT_URL => 'https://apirestbb.aiep.cl/v3/users/' . $userExternalPersonKey,
	  // CURLOPT_URL => 'https://apirestbb.aiep.cl/v2/users/' . $userExternalPersonKey,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    "Authorization: Bearer $token"
	  ),
	));

	// curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$resp = curl_exec($ch);



	if ($error = curl_error($ch)) {
		echo $error;
	} else {
		$decoded = json_decode($resp);
	}


		// echo "<pre>";
	// // print_r($resp);
	// print_r($decoded);
	// exit;
	curl_close();

	// return $decoded->user;
	return $decoded->users;
}

function getSedes($userExternalPersonKey = null) {

	$ch = curl_init();

	$token = getAccessToken();
	curl_setopt_array($ch, array(
	  // CURLOPT_URL => 'https://apirestbb.aiep.cl/v2/users/AARON.ALTAMIRANO',
	  CURLOPT_URL => 'https://apirestbb.aiep.cl/v1/sedes/',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
	    "Authorization: Bearer $token"
	  ),
	));

	// curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$resp = curl_exec($ch);
	if ($error = curl_error($ch)) {
		echo $error;
	} else {
		$decoded = json_decode($resp);
	}

	curl_close();

	return $decoded;
}



function putCurl() {

	$ch = curl_init();

	//$url = 'https://support.dtedevelop.cl/api/tickets.json';

	$url = 'https://dte.aiep.cl/osticketform/support/api/tickets.json';

	$data_array = [

	    "alert" => true,
	    "autorespond" => true,
	    "source" => "API",
	    "name" => "Angry User",
	    "email" => "api@osticket.com",
	    "phone" => "3185558634X123",
	    "subject" => "Testing API",
	    "ip" => "123.211.233.122",
	    "message" => "data:text/html,MESSAGE <b>HERE</b>",
	    "attachments" => [
	        ["file.txt" => "data:text/plain;charset=utf-8,content"],
	        ["image.png" => "data:image/png;base64,R0lGODdhMAA..."],
	    ]
	];

	$data = http_build_query($data_array);

	$header = [

		'Authorization: test'
	];

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


	$resp = curl_exec($ch);

	if ($error = curl_error($ch)) {
		echo $error;
	} else {
		$decoded = json_decode($resp);
		foreach ($decoded as $key => $decode) {
			# code...
		}
	}

	curl_close($ch);

}
