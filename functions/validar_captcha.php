<?php 
include('captcha_keys.php');

$validar_captcha = true;
if ($_POST['google_response_token']) {
	$captcha_site = $_POST['google_response_token'];

	$response_captcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . SECRET_KEY . '&response=' . $captcha_site);
		$response_captcha = json_decode($response_captcha);

		$captcha_state = $response_captcha->success;
		$captcha_score = $response_captcha->score;

		$minCaptchaScore = 0.5;
}
if($captcha_state == false && ($captcha_score < $minCaptchaScore) && $validar_captcha == true ) {

	if ($_SERVER['PHP_SELF'] == '/form_colaborador.php') {
		header("Location: login_colaborador.php?error=2");
	} else {
    	header("Location: login.php?error=2");
	}
}