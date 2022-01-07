<?php
function authenticate($usuario, $pass) {

	if(empty($usuario) || empty($pass)) return false;

/*
	//echo $usuario;
	if ($usuario == 'root' ) {
			
		$_SESSION['nombre'] = 'ROOT';	
		$_SESSION['access'] = 1;		
		$_SESSION['user'] = $usuario;		
		// return 1;

	} elseif($usuario == 'jcarcamo' ) {
			
		$_SESSION['nombre'] = 'JOSE CARCAMO';	
		$_SESSION['access'] = 1;		
		$_SESSION['user'] = $usuario;		
		// return 1;

	} elseif($usuario == 'usuarioaiep' ) {
			
		$_SESSION['nombre'] = 'USUARIO aiep';	
		$_SESSION['access'] = 1;		
		$_SESSION['user'] = $usuario;		
		// return 1;

	} elseif($usuario == 'pablo valdes s' ) {
				
		$_SESSION['nombre'] = 'PABLO VALDES SANCHEZ';	
		$_SESSION['access'] = 1;		
		$_SESSION['user'] = $usuario;		
		// return 1;
	}
*/
	//$ldap_server = "ldap://10.30.10.21";
	$ldap_server = "ldap://172.30.10.223";
	$auth_user = "CN=Usuario Moodle AD,OU=CTAS. DE SERVICIO,OU=PLATAFORMA Y REDES,OU=INFORMATICA,OU=NIVEL CENTRAL,DC=aiep,DC=corp";
	$auth_pass = "MoodLe@Aiep2013";
	$LDAP_PORT=636;

	// connect to server
	if (!($connect=@ldap_connect($ldap_server,$LDAP_PORT))) {
	     die("No es posible encontrar el servidor");
	}

	// Especifico la versión del protocolo LDAP
	ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3) 
		or die ("Imposible asignar el Protocolo LDAP");

	// bind to server
	if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {			
	     die("No se puede conectar al servidor");
	}

	// Set the base dn to search the entire directory.
	$ldap_contexts = "ou=ad sync,dc=aiep,dc=corp;ou=directiva,dc=aiep,dc=corp;ou=nivel central,dc=aiep,dc=corp;ou=sedes,dc=aiep,dc=corp;ou=incremental,dc=aiep,dc=corp;ou=pruebacursos,dc=aiep,dc=corp";

	// Show only user persons
	//$filter = "(&(objectClass=top)(objectClass=person)(objectClass=organizationalPerson)(objectCategory=user)(sAMAccountName=".$usuario."))";
	$filter = "(&(objectClass=user)(objectClass=person)(sAMAccountName=".$usuario."))";
	
	$base_dn= explode(';', $ldap_contexts); // separa contextos en array

	foreach ($base_dn as $v_base_dn) { // por cada array de contextos busca los usuarios del AD
		 // search active directory
		if (!($search=@ldap_search($connect, $v_base_dn, $filter))) {
			//header("Location: index.php?error");
     		//die("No se puede buscar en el servidor LDAP");
		}

		$cant=0;
		$cant = ldap_count_entries($connect,$search);
		$info = ldap_get_entries($connect, $search);

/*
		echo '<pre>';
			print_r($info);	
		echo '</pre>';
		*/
//sleep(1);


		if ($cant > 0) { //si encuentra al usuario indicado continua con login
			$cn = array_column($info, 'cn'); // recoge campo de cn
			$dn  = array_column($info, 'distinguishedname'); // recoge campo de cn
			$rut = array_column($info, 'extensionattribute1'); // recoge campo de postalCode
			$nombrecompleto_cn = array_column($info, 'displayname'); // recoge campo de postalCode
			$email_cn = array_column($info, 'mail'); // recoge campo de postalCode
			$st = array_column($info, 'st'); // recoge rol alumno / docente
			$user_cn = $cn[0][0]; // guarda el cn en variable de conexion
			$user_rut = $rut[0][0];
			$postalCode_cn = $cn[0][5]; // guarda el cn en variable de conexion
			$nombrecompleto = $nombrecompleto_cn[0][0]; // guarda el cn en variable de conexion
			$email = $email_cn[0][0]; // guarda el cn en variable de conexion
			//$role_usuario = $st[0][0];
			$role_usuario = 'Colaborador';
			//return true;
			if (@ldap_bind($connect,$dn[0][0],$pass))				
			{		
				// establish session variables
				 $_SESSION['user'] = $usuario;
				 $_SESSION['access'] = $cant;
				 $_SESSION['nombre'] = $user_cn;

				 $_SESSION['rut'] = $user_rut;
				 $_SESSION['nombrecompleto'] = $nombrecompleto;
				 $_SESSION['email'] = $email;
				 $_SESSION['colaborador'] = 1;
				 $_SESSION['role_usuario'] = $role_usuario;
				 // $_SESSION['api_user_info'] = getUserData('AARON.ALTAMIRANO');
				return true;
				//die();
			}
			else
			{
				// echo "else";
				// user has no rights
				return false;
			}

		} else {
			// user has no rights
			//return false;

			header("Location: login_colaborador.php");
		}
	}
}


?>