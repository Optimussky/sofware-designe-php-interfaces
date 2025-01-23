<?php
$url = "https://jsonplaceholder.typicode.com/posts";
$op = 1;

$response = file_get_contents($url);

if ($response === FALSE){
	die("Error al consumir el Servicio Web");
}


$data = json_decode($response, true);

// Recorre la $data con un ciclo
//

if($op == 1){
	foreach ($data as $post){
		echo "ID: " . $post['id'] . "\n";
		echo "Título: " . $post['title'] . "\n";
		echo "Contenido: " . $post['body'] . "\n";
		echo "--------------------------\n";
	}

}elseif($op == 2){
	$filename = "output.txt";
	$fileContent = "";

	foreach ($data as $post){
		$fileContent .= "ID: " . $post['id'] . "\n";
		$fileContent .= "Título: " . $post['title'] . "\n";
		$fileContent .= "Contenido: " . $post['body'] . "\n";
		$fileContent .= "-------------------------\n";
	}

	if (file_put_contents($filename, $fileContent) === FALSE) {
		die("Error al guardar la información en el archivo.");
	}
	echo "Información guardada en el archivo: $filename\n";

}else if($op == 3){
	$filename = "output.html";
	$htmlContent = "<html><head><title>Datos del Servicio Web</title></head><body>";
	$htmlContent .= "<h1>Datos del Servicio Web</h1>";

	foreach ($data as $post){
		$htmlContent .= "<div style='margin-bottom: 20px;'>";
		$htmlContent .= "<h2>ID: ". $post['id'] . "</h2>";
		$htmlContent .= "<h3>Título: " . htmlspecialchars($post['title']) . "</h3>";
		$htmlContent .= "<p>Contenido: " . nl2br(htmlspecialchars($post['body'])) . "</p>";
		$htmlContent .= "<hr>";
		$htmlContent .= "</div>";
	}

	$htmlContent .= "</body></html>";

	if (file_put_contents($filename, $htmlContent) === FALSE) {
		die("Error al guargar la información en el archivo HTML\n");
	}

	echo "Información guardada en el archivo HTML: $filename\n";


}
