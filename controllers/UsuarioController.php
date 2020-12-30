<?php
require_once 'models/usuario.php';

class usuarioController
{

	public function index()
	{
		echo "Controlador Usuarios, Acción index";
	}

	public function registro()
	{
		require_once 'views/usuario/registro.php';
	}

	public function save()
	{
		if (isset($_POST)) {

			// Iniciar sesión
			if (!isset($_SESSION)) {
				session_start();
			}


			$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
			$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : false;
			$email = isset($_POST['email']) ? $_POST['email'] : false;
			$password = isset($_POST['password']) ? $_POST['password'] : false;

			// Array de errores
			$errores = array();
			// Validar los datos antes de guardarlos en la base de datos


			// Validar campo nombre
			if (!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)) {
				$nombre_validado = true;
			} else {
				$nombre_validado = false;
				$errores['nombre'] = "<strong class='alert_red'>El nombre no es válido</strong>";
			}



			// Validar apellidos
			if (!empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $apellidos)) {
				$apellidos_validado = true;
			} else {
				$apellidos_validado = false;
				$errores['apellidos'] = "<strong class='alert_red'>Los apellidos no son válido</strong>";
			}



			// Validar el email
			if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$email_validado = true;
			} else {
				$email_validado = false;
				$errores['email'] = "<strong class='alert_red'>El email no es válido</strong>";
			}

			// Validar la contraseña
			if (!empty($password)) {
				$password_validado = true;
			} else {
				$password_validado = false;
				$errores['password'] = "<strong class='alert_red'>La contraseña está vacía</strong>";
			}


			if (count($errores) == 0) {


				$usuario = new Usuario();
				$usuario->setNombre($nombre);
				$usuario->setApellidos($apellidos);
				$usuario->setEmail($email);
				$usuario->setPassword($password);

				$save = $usuario->save();
				if ($save) {
					$_SESSION['register'] = "complete";
				} else {
					$_SESSION['register'] = "failed";
				}
			} else {
				$_SESSION['errores'] = $errores;
				header("Location:" . base_url);
			}
		} else {
			$_SESSION['register'] = "failed";
		}
		header("Location:" . base_url . 'usuario/registro');
	}



	public function login()
	{
		if (isset($_POST)) {
			// Identificar al usuario
			// Consulta a la base de datos
			$usuario = new Usuario();
			$usuario->setEmail($_POST['email']);
			$usuario->setPassword($_POST['password']);

			$identity = $usuario->login();

			if ($identity && is_object($identity)) {
				$_SESSION['identity'] = $identity;

				if ($identity->rol == 'admin') {
					$_SESSION['admin'] = true;
				}
			} else {
				$_SESSION['error_login'] = 'Identificación fallida !!';
			}
		}
		header("Location:" . base_url);
	}

	public function logout()
	{
		if (isset($_SESSION['identity'])) {
			unset($_SESSION['identity']);
		}

		if (isset($_SESSION['admin'])) {
			unset($_SESSION['admin']);
		}

		header("Location:" . base_url);
	}
} // fin clase