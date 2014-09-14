<?php
	session_start();
	include('common.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Encuesta</title>
<link rel="stylesheet" type"text/css" href="miestilo.css">
</head>
<body>
<header><center><img src="img/Header.png"/></center>
</header><center>
<nav>
<ul>
    <li><a title="Opcion 1" href="Index.php">Inicio</a></li>
    <li><a title="Opcion 2" href="resultado.php">Resultado</a></li>
    <li><a title="Opcion 3" href="index.php">Salir</a></li>  
</ul>
</nav>
<div >
<br><br>
<?php
if(isset($_POST['encuesta'])){
	$consulta = "SELECT id_pre FROM pregunta WHERE id_enc = '".$_POST['encuesta']."';";
	include "charts.php";
	$mysqli = conectar();
	$resultado = $mysqli->query($consulta);
	if($resultado){
		while($fila=$resultado->fetch_assoc()){
			echo InsertChart ( "charts.swf", "charts_library", "generadorReportes.php?id=".$fila['id_pre'], 500, 300 );
		}
	}
	$mysqli->close();
}else{
	$consulta = "SELECT * FROM encuesta";
	$mysqli = conectar();
	$resultado = $mysqli->query($consulta);
	if($resultado){
		while($fila=$resultado->fetch_assoc()){
			echo '<form method="POST" action="resultado.php">';			
			echo '<input type="hidden" name=encuesta value="'.$fila['id_enc'].'">'.
				  '<input type="submit" value="'.$fila['titulo'].'"></form>';
		}
	}
	$mysqli->close();
}
?><br>
</div>
<footer>
<center><img src="img/footer.jpg" /></center>
</footer>
</body>
</html>