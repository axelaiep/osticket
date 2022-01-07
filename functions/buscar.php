<?php

ini_set('display_errors',2);
require($_SERVER['DOCUMENT_ROOT'] . "/functions/medoo_querys.php");

if ($_POST) {
	$tipo_nivel = filter_input(INPUT_POST, 'tipo_nivel');
	$nivel_id = filter_input(INPUT_POST, 'nivel_id');

	if ($tipo_nivel == "nivel_1") {

		$nivel_padre = 8;
		$lista_tipo_ticket = getListItemsByListId($nivel_padre, $nivel_id);
		echo json_encode($lista_tipo_ticket);
		exit;
	}

	if ($tipo_nivel == "nivel_2") {
		$nivel_padre = 9;
		$lista_tipo_ticket = getListItemsByListId($nivel_padre, $nivel_id);
		echo json_encode($lista_tipo_ticket);
		exit;
	}
}

