<?php
function authenticate($usuario, $pass) {
	if(empty($usuario) || empty($pass)) return false;
        /*
		$ldap_server = "ldap://172.30.11.90";
		$auth_user = "Moodle";
		$auth_pass = "ServiciosM00dl3";
		*/

		$ldap_server = "ldap://172.30.11.90";
		$auth_user = "Moodle";
		$auth_pass = "ServiciosM00dl3";

		// connect to server
		if (!($connect=@ldap_connect($ldap_server))) {
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
		$ldap_contexts = "ou=alumnos,dc=correoaiep,dc=cl;ou=docentes,dc=correoaiep,dc=cl";

		// Show only user persons
		$filter = "(&(objectClass=top)(objectClass=person)(objectClass=organizationalPerson)(objectCategory=user)(sAMAccountName=".$usuario."))";
		
		$base_dn= explode(';', $ldap_contexts); // separa contextos en array

		// print_r($base_dn);
		foreach ($base_dn as $v_base_dn) { // por cada array de contextos busca los usuarios del AD

			 // search active directory
			if (!($search=@ldap_search($connect, $v_base_dn, $filter))) {
	     		die("No se puede buscar en el servidor LDAP");
			}

			$cant=0;
			$cant = ldap_count_entries($connect,$search);
			$info = ldap_get_entries($connect, $search);

			?>

			<?php
			if ($cant > 0) { //si encuentra al usuario indicado continua con login
				$cn = array_column($info, 'cn'); // recoge campo de cn
				$rut = array_column($info, 'postalcode'); // recoge campo de postalCode
				$nombrecompleto_cn = array_column($info, 'displayname'); // recoge campo de postalCode
				$email_cn = array_column($info, 'mail'); // recoge campo de postalCode
				$name = array_column($info, 'name'); // recoge campo de postalCode
				$st = array_column($info, 'st'); // recoge rol alumno / docente
				$user_cn = $cn[0][0]; // guarda el cn en variable de conexion
				$user_rut = $rut[0][0];
				$postalCode_cn = $cn[0][5]; // guarda el cn en variable de conexion
				$nombrecompleto = $nombrecompleto_cn[0][0]; // guarda el cn en variable de conexion
				$email = $email_cn[0][0]; // guarda el cn en variable de conexion
				$name = $name[0][0];
				$role_usuario = $st[0][0];
				// print_r($email);
				// return true;
				// die();

				if (@ldap_bind($connect,$user_cn."@correoaiep.cl",$pass)) {		

					// establish session variables
					 $_SESSION['user'] = $usuario;
					 $_SESSION['access'] = $cant;
					 $_SESSION['nombre'] = $user_cn;
					 $_SESSION['rut'] = $user_rut;
					 $_SESSION['nombrecompleto'] = $nombrecompleto;
					 $_SESSION['name'] = $name;
					 $_SESSION['email'] = $email;
					 $_SESSION['colaborador'] = 0;
					 $_SESSION['role_usuario'] = $role_usuario;
					 
					 // $_SESSION['api_user_info'] = getUserData('AARON.ALTAMIRANO');
					 $_SESSION['api_user_info'] = getUserData($name);

					 // echo "<pre>";
					 // echo $name;
					 // // print_r($info);
					 // print_r(var_dump($_SESSION['api_user_info']));
					 // echo "<pre>";
					 // print_r(getUserData($name));
					 // exit;

					//return true;
					//die();
				} else {
					
				// user has no rights
				//return false;
				header("Location: login.php?error=1");
					
			}
		} else {

				// user has no rights
				//return false;
			//header("Location: error.php?error=2");


		}
		
	}
            

}


?>