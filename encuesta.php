<?php
	session_start();
	include('common.php');
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Encuesta</title>
<?PHP header("Content-Type: text/html;charset=utf-8");?>
<link rel="stylesheet" type"text/css" href="miestilo.css">
<?php if(isset($_POST['encuesta'])){
	echo "<script>";
	$mysqli = conectar();
	$consulta = "SELECT id_pre FROM pregunta WHERE id_enc = ".$_POST['encuesta'];
	$resultado = $mysqli->query($consulta);
	$preguntas = NULL;
	if($resultado){
		while($fila = $resultado->fetch_assoc()){
			$preguntas[] = $fila['id_pre'];
		}
	}
	$mysqli->close();
	echo "function validar(){";	
	echo "var seleccionado = false;
		  var i = 0;";
		  
	for($i=0;$i<count($preguntas);$i++){
		echo "seleccionado = false;
			  var _p".$preguntas[$i]." = document.getElementsByName('p".$preguntas[$i]."');
			  for(i=0; i<_p".$preguntas[$i].".length; i++){if(_p".$preguntas[$i]."[i].checked) {seleccionado = true;break;}}			 
			  if(!seleccionado) {alert('No ha completado toda la encuesta');return false;}";
	}
	echo "return true;}";

	echo "</script>";
	}?>
</head>
<body>
<?php
	include("includes/header.php");
?>

	<div>
	<br><br><br>
		<?php
			if(isset($_SESSION['nombre'])){
				if(!isset($_POST['encuesta'])){
					echo '<div align="center">Visitante: '.$_SESSION['nombre'].'</div><br>';
					$consulta = "SELECT encuesta.`id_enc`,encuesta.`titulo`
								FROM encuesta LEFT JOIN participa
								ON encuesta.id_enc = participa.id_enc AND participa.entry = '".$_SESSION['entry']."'
								WHERE participa.`id_enc` IS NULL AND estado = '1';";
					$mysqli = conectar();
					$resultado = $mysqli->query($consulta);
					$noRespuestas = true;
					if($resultado){
						while($fila = $resultado->fetch_assoc()){
							echo '<div align="center"><form method="post" 
								  action="encuesta.php">'."\n";
							$_SESSION['encuesta']=$fila['id_enc'];
							echo '<input type="hidden" value="'.$fila['id_enc'].'" name="encuesta">';
							echo '<input id="button" type="submit" value="'.$fila['titulo'].'"></form>'."\n</div>";
							$noRespuestas =false;
						}
					}
					if($noRespuestas){
						?>
						<div id="divError">
							<label id="labelError">Error: Usted no tiene ninguna encuesta disponible para responder</label>
						</div>	
						<?php	
						session_destroy();
					}
					$mysqli->close();
				}else{
					$preguntas = new pregunta();
					$preguntas->listar($_POST['encuesta']);
				}
			}else{
				?>
					<div id="divError">
						<label id="labelError">Error: Debe identificarse para poder ver las Encuestas Disponibles</label>
					</div>	
				<?php
			}
		?>
        
     <br><br><br>
	 
	</div>
<?php
	include("includes/footer.php");
?>