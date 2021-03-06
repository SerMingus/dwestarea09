<?php
class gestionLibros
{
	/**
	 * Método para establecer conexión con la base de datos.
	 * @author Sergio Mingorance Martín.
	 * @param string $servername URL del servidor.
	 * @param string $database Nombre de la base de datos.
	 * @param string $username Nombre de usuario para acceder a la base de datos.
	 * @param string $password Contraseña del usuario para acceder a la base de datos.
	 * @return mysqli|null Si se ha establecido la conexión se retorna o si no se ha establecido conexión se retorna null.
 	 */
	public function conexion($servername,$username,$password,$database){
		
		// Establece conexión con la base de datos omitiendo errores por pantalla.
		$conn = new mysqli($servername,$username,$password,$database);
		
		// Si hay algún problema con la conexión lo muestra por pantalla.
		if($conn->connect_error){
			echo "Error de conexión: ".$conn->connect_error;
			return null;
			
		// Si la conexión se establece correctamente se cargan los caracteres utf8.
		}else{
			
			// Carga el conjunto de caracteres utf8.
			if($conn->set_charset("utf8")){
				
				// Si todo hay ido correcto retorna un objeto con la conexión a la base de datos.
				return $conn;
			}else{
				echo "Error cargando el conjunto de caracteres utf8: ".$conn->error;
				return null;
			}
		}
	}

	/**
	 * Método para consultar los datos de un autor pasando parte de su nombre como parametro.
	 * @author Sergio Mingorance Martín.
	 * @param string $nombre Nombre del autor a consultar.
	 * @param mysqli $conn Objeto mysqli con la conexión a la base de datos.
	 * @return array Retorna array con el resultado de la consulta.
 	 */
	public function consultarAutores($conn,$nombre){
		
		//Variable que será retornada.
		$final = array();
		
		//Prepara la consulta.
		$sql = "SELECT * FROM Autor WHERE nombre LIKE '%$nombre%'";
			
		// Se efectua la consulta.
		if($resultado = $conn->query($sql)){

			// Guarda el resultado de la consulta en un array asociativo.
			while ($fila = $resultado->fetch_assoc()){
				$final[] = ["id"=>$fila["id"],
						    "nombre"=>$fila["nombre"],
						    "apellidos"=>$fila["apellidos"],
						    "nacionalidad"=>$fila["nacionalidad"]];
			}
				
		// Si hay algún problema con la consulta se muestra por pantalla.
		}else{
			echo "Hubo algún problema con la consulta a la base de datos: ".$conn->error;
		}
			
		// Retorna un array asociativo con el resultado de la busqueda.
		return $final;
		
	}
	
	/**
	 * Método para consultar todo los libros de un autor pasando su id como parametro
	 * @author Sergio Mingorance Martín.
	 * @param int $autor Número id del autor a consultar.
	 * @param mysqli $conn Objeto mysqli con la conexión a la base de datos.
	 * @return array|null Retorna array con el resultado de la consulta. Retorna null ante cualquier problema.
 	 */
	public function consultarLibros($conn, $id_autor){
		
		//Variable que será retornada.
		$final = array();
		
		// Prepara la consulta.
		$sql = "SELECT * FROM Libro WHERE id_autor='$id_autor'";
		
		// Efectua la consulta.
		if($resultado = $conn->query($sql)){
			
			// Guarda el resultado de la consulta en un array asociativo.
			while ($fila = $resultado->fetch_assoc()){
				$final[] = ["id"=>$fila["id"],
							"titulo"=>$fila["titulo"],
							"f_publicacion"=>$fila["f_publicacion"],
							"id_autor"=>$fila["id_autor"]];
			}
			
		// Si hay algún problema con la consulta se muestra por pantalla.
		}else{
			echo "Hubo algún problema con la consulta a la base de datos: ".$conn->error;
		}
			
		// Retorna un array asociativo con el resultado de la busqueda.
		return $final;
	}
}

?>
