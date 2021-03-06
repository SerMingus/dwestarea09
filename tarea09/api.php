<?php require_once "gestionLibros.php" ?>

<?php

	/**
	 * Función que retorna todos los datos del autor y todos los datos de todos sus libros a partir de parte de su nombre.
	 * @author Sergio Mingorance Martín.
	 * @return array $info_autor Array asociativo con todos los datos del autor y todos los datos de sus libros.
	 */
	function get_datos_autor($nombre){
		
		//Decodifica el parametro pasado en la url.
		$nombre = rawurldecode($nombre);
	
		//Variable que será retornada.
		$info_autores = array();
		
		//Crea un objeto de la clase gestionLibros() para manejar las consultas a la base de datos.
		$gestorDeLibros = new gestionLibros();
		
		// Estable conexión con la base de datos.
		$conn = $gestorDeLibros->conexion("localhost", "usuario", "1234", "Libros");
		
		//Consulta los datos de los autores de los que se ha pasado parte de su nombre como parámetro.
		$consultaAutores = $gestorDeLibros->consultarAutores($conn,$nombre);
		
		//Recorre los resultados.
		foreach($consultaAutores as $autor){
	
			//Consulta los libros de cada autor.
			$consultaLibros = $gestorDeLibros->consultarLibros($conn,$autor["id"]);
			
			//Guarda el resultado del autor y sus libros.
			$info_autores[] = ["autor"=>$autor,
							   "libros"=>$consultaLibros];
		}	
			
		// Cierre la conexión con la bse de datos.
		$conn->close();
		
		// Retorna el array $info_autor con los datos del autor y sus libros.
		return $info_autores;
	}
	
	// Lista de acciones posibles.
	$posibles_URL = array("get_datos_autor");
	
	$valor = "Ha ocurrido un error";
	
	if(isset($_GET["action"]) && in_array($_GET["action"], $posibles_URL)){
		
		switch ($_GET["action"]){
			
			case "get_datos_autor":
				$valor = get_datos_autor(rawurldecode($_GET["nombre"]));
				break;
		}
	}
	
	//devolvemos los datos serializados en JSON
	exit(json_encode($valor));
?>