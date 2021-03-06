<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<style>
			* { margin: 10px 10px 10px 10px; }
			table { width: 400px; }
			td { width: 200px; }
			li.tabla,ul.tabla { list-style: none;
								padding: 0px;
								margin: 0px; }
			a.tabla {margin: 0px; }
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
		<script>
			$(document).ready(function(){
				
				//Intercambia los resultados visibles al hacer click en el cuadro de texto del buscador PHP.
				$("#nombrePHP").click(function(){
					$("#resultAJAX").hide();
					$("#resultPHP").show();
				});
				
				//Intercambia los resultados visibles al hacer click en el cuadro de texto del buscador Ajax con jQuery.
				$("#nombreAJAX").click(function(){
					$("#resultAJAX").show();
					$("#resultPHP").hide();
				});
				
				$("#nombreAJAX").keyup(function(){
					
					// Quita los resultados que haya en pantalla.
					$("#resultPHP").hide();
					div = document.getElementById("resultAJAX");
					document.body.removeChild(div);
					
					// Crea la etiqueta donde se colocarán los resultados de la consulta Ajax.
					div = document.createElement("div");
					id = document.createAttribute("id");
					id.value = "resultAJAX";
					div.setAttributeNode(id);
					document.body.appendChild(div);
					
					<!-- Titulo de los resultados de Ajax -->
								
					h2 = document.createElement("h2");
					h2.textContent = "Resultado buscador Ajax con jQuery:";
					resultAJAX.appendChild(h2);
					
					<!-- Raya horizontal -->
								
					hr = document.createElement("hr");
					width = document.createAttribute("width");
					width.value = "390";
					hr.setAttributeNode(width);
					resultAJAX.appendChild(hr);
					
					// Recoge las letras introducidas en el cuadro de busqueda de Ajax.
					var nombre = $("#nombreAJAX").val();
					
					// Si la última tecla pulsada no deja la cadena vacía.
					if(nombre != ""){
						
						//Codifica el parámetro con el nombre a buscar.
						nombre = encodeURIComponent(nombre);
						
						// Hace la consulta a la API mendiante Ajax con jQuery
						$.get("http://localhost/tarea09/api.php?action=get_datos_autor&nombre=" + nombre, function(datos){
							
							// Convierte el resultado obtenido de la consulta en un array de arrays.
							resultadoConsulta = JSON.parse(datos);

							// Si la consulta da algún resultado.
							if(resultadoConsulta != ""){
								
								// Recorre los resultados obtenidos.
								resultadoConsulta.forEach(function(autor){
									
									// Referencia el nodo donde se van a colocar los resultados.
									var resultAJAX = document.getElementById("resultAJAX");
									
									<!-- Tabla  de resultados -->
									
									table = document.createElement("table");
									
										<!-- Primera fila de la tabla -->
									
										tr = document.createElement("tr");
									
											<!-- Primera celda de la primera fila -->
											
											td = document.createElement("td");
											td.textContent = "Nombre:";

										tr.appendChild(td);
										
											<!-- Segunda celda de la primera fila -->
									
											td = document.createElement("td");
											td.textContent = autor.autor.nombre + " " + autor.autor.apellidos;
										
										tr.appendChild(td);
									
									table.appendChild(tr);
									
										<!-- Segunda fila de la tabla -->
									
										tr = document.createElement("tr");
										
											<!-- Primera celda de la segunda fila -->
									
											td = document.createElement("td");
											valign = document.createAttribute("valign");
											valign.value = "top";
											td.setAttributeNode(valign);
											td.textContent = "Libros escritos:";
											
										tr.appendChild(td);
										
											<!-- Segunda celda de la segunda fila -->
									
											td = document.createElement("td");
											
												ul = document.createElement("ul");
												clase = document.createAttribute("class");
												clase.value = "tabla";
												ul.setAttributeNode(clase);
												
												autor.libros.forEach(function(libro){
													
													<!-- Lista de los libros escritos por el autor -->
													
													li = document.createElement("li");
													clase = document.createAttribute("class");
													clase.value = "tabla";
													li.setAttributeNode(clase);
													li.textContent = libro.titulo;
													
													ul.appendChild(li);
													
												});
										
											td.appendChild(ul);
											
										tr.appendChild(td);
								
									table.appendChild(tr);
								
									resultAJAX.appendChild(table);
									
									<!-- Raya horizontal -->
								
									hr = document.createElement("hr");
									width = document.createAttribute("width");
									width.value = "390";
									hr.setAttributeNode(width);
									resultAJAX.appendChild(hr);
											
								}); //resultadoConsulta.forEach(function(autor){
							}else{
								div = document.getElementById("resultAJAX");
								p = document.createElement("p");
								p.textContent = "No se ha encontrado ningún resultado";
								div.appendChild(p);
							} //if(resultadoConsulta != ""){
						}); //$.get("http://localhost/tarea09/api.php?action=get_datos_autor&nombre=" + nombre, function(datos){
					} else { 
						div = document.getElementById("resultAJAX");
						p = document.createElement("p");
						p.textContent = "Introduzca algunas letras";
						div.appendChild(p);
					} //if(nombre != ""){
				}); //$("#nombreAJAX").keyup(function(){
			}); //$(document).ready(function(){
			
		</script>
	</head>
	<body>
		<hr>
		<h1>Base de datos de autores y sus libros</h1>
		<hr>
		<form action="index.php" method="get">
			<legend><b>Buscador PHP:</b> (Introduce algunas letras y pulsa el botón para ver los autores que contienen esas letras en sus nombres)</legend>
			<input type="text" id="nombrePHP" name="nombrePHP" value="<?php if(isset($_GET["nombrePHP"])) echo $_GET["nombrePHP"]; ?>">
			<button type="submit">Buscar autores...</button>
		</form>
		<hr>
		<form>
			<legend><b>Buscador Ajax:</b> (Introduce algunas letras para ver los autores que contienen esas letras en sus nombres de forma inmediata)</legend>
			<input type="text" id="nombreAJAX" autocomplete="off" onkeypress="return event.keyCode != 13;">
		</form>
		<hr>
		<div id="resultPHP">
			<?php
			// Si se ha hecho una peticion que busca informacion de un autor "get_datos_autor" a través de parte de su nombre.
			if(isset($_GET["nombrePHP"])){
			?>
				<h2>Resultado buscador PHP:</h2>
				<hr width="390">
				
				<?php
				if($_GET["nombrePHP"] != ""){
					
					//Codifica el valor del parámetro.
					$nombre = rawurlencode($_GET["nombrePHP"]);
					
					//Se realiza la peticion a la api que nos devuelve el JSON con la información de los autores.
					$datosAutores = file_get_contents('http://localhost/tarea09/api.php?action=get_datos_autor&nombre='.$nombre);
					
					//Se decodifica el fichero JSON y se convierte a array.
					$datosAutores = json_decode($datosAutores, true);
					
					//Si hay algún resultado de la consulta.
					if(!empty($datosAutores)) {
						
						//Recorre los resultados y los añade a una tabla para mostrarlos.
						foreach($datosAutores as $datosAutor) {
				?>
						<table>
							<tr>
								<td>Nombre:</td>
								<td><?php echo $datosAutor["autor"]["nombre"]." ".$datosAutor["autor"]["apellidos"] ?></td>
							</tr>
							<tr>
								<td valign="top">Libros escritos:</td>
								<td>
									<ul class='tabla'>
										<?php
										foreach($datosAutor["libros"] as $libro){
										?>
											<li class='tabla'><?php echo $libro["titulo"]; ?></li>
										<?php
										} 
										?>
									
									</ul>
								</td>
							</tr>
						</table>
						<hr width='390'>
						<?php
						}
					}else{
					?>
						<p>No se ha encontrado ningún resultado<p>
					<?php
					}
				}else{
				?>
					<p>Introduzca algunas letras</p>
				<?php
				}
			}
			?>
		</div>
		<div id="resultAJAX"></div>
	</body>
</html>