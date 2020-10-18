<?php
date_default_timezone_set('America/Monterrey');
$db_server = 'localhost';
$db_name = 'bbva';
$db_user = 'root';
$db_passwd = '';
$pass=false;

if(!empty($_REQUEST['s'])&&isset($_POST['inicio'])){
	$_POST['inicio'] += $_POST['lim'];
	$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);
	if ($conn->connect_error) {
		printf("Falló la conexión: %s\n", $conn->connect_error);
		exit();
	}
	require('functions.php');
}
echo'
<html>
<head>
	<title>BBVA</title>
<style>
body {
    background-size: contain;
    background-position-x: 110%;
    background-repeat: no-repeat;
    background-color: #2a2a2a;
    background-attachment: fixed;
    font-family: \'lucida grande\',tahoma,verdana,arial,sans-serif;
    font-size: 11px;
    color: #fff;
    line-height: 1.28;
    margin: 0;
    padding: 0;
    text-align: left;
    direction: ltr;
    unicode-bidi: embed;
}
div{
	margin: 10px;
}
input, button, select, textarea {
    font: 95%/115% verdana, Helvetica, sans-serif;
    background: #f3f3f3;
    border: 1px solid #b1b1b1;
    color: #333;
    outline: none;
}
a {
    color: #427eb2 !important;
}
a:visited{
    color: #427eb2 !important;
}
._txtImp {
    padding: 7px 7px 7px 15px;
    line-height: 7px;
    border-radius: 18px 18px;
    border-width: 1px 0 1px 1px;
    width: 70%;
}
.RegForm{
    color: #FFF;
	transition: 200ms cubic-bezier(.08,.52,.52,1) background-color, 200ms cubic-bezier(.08,.52,.52,1) box-shadow, 200ms cubic-bezier(.08,.52,.52,1) transform;
	background-color: #427eb2;
    font-weight: bold;
    border-radius: 3px;
    padding: 5px 10px 5px 10px;
    border: 0;
    cursor: pointer;
    outline: none;
}
.RegForm:hover{
    background-color: #5d8ce2;
}
.separator{
	height: 50px;
}
#container{
	width:50%;
	margin: auto !important;
	text-align: center;
}
</style>
</head>
<body>
	<div id="container">
	<div class="separator"></div>
	<img src="https://www.bbva.com/wp-content/themes/coronita-bbvacom/assets/images/logos/logo-amp.png" width="400"/>';
echo'<form action="BBVA.php?s=succes" method="post">
	<div class="separator"></div>
	<div>Inicio:</div>
	<div><input type="text" class="_txtImp" name="inicio" value="',empty($_REQUEST['s'])? 0:$_POST['inicio'],'"></div>
	<div>límite:</div>
	<div><input type="text" class="_txtImp" name="lim" value="',empty($_REQUEST['s'])? 500:$_POST['lim'],'"></div>
	<div>',empty($_REQUEST['s'])? '':'¿Desea continuar?','</div>
	<div><input type="submit" class="RegForm" value="Submit"></div>
</form>
';

if(!empty($_REQUEST['s']))
	if($_REQUEST['s']=='succes'){
		echo'<br><a href="http://localhost/BBVA/BBVA.php">De nuevo</a><br>';
		$pass=true;
		if($pass){
			$_POST['inicio']-=$_POST['lim'];
			$start = microtime(true);
			if ($resultado = $conn->query("SELECT NU_CTE, clave_id, desc_text, producto_id, correo_id, CURP, clave_secundaria_text, clave_primaria_text, desc_id, producto_1_number, bin_2_number, SUBCANAL_ENTRANTE, CODIGO_ENTIDAD_ORI, tel_id, nombre_text, apellido_text, pasaporte, FECHA_ULTIMO_USO, NSS, REFERENCIA_NUMERICA, agrupacion_id FROM BBVA ORDER BY id_cliente LIMIT ".$_POST['inicio'].",".$_POST['lim'])) {
				$fechaGen = date("F j, Y, g.i.s a");
				$ruta = 'Registro - '.$fechaGen.'.txt';
				$salto = "\r\n";
				$guardar = "Fijo, Celular, Mail, TDC, Nombre, Dirección, Pasaporte, NSS, INE, RFC, CURP, Fecha de nacimiento".$salto;
				
				while($row = $resultado->fetch_assoc()){
					$guardar.= tel($row['clave_primaria_text']).','.tel($row['clave_secundaria_text']).','.getMail($row['nombre_text'],$row['apellido_text']).','.TDC($row['SUBCANAL_ENTRANTE'], $row['CODIGO_ENTIDAD_ORI'], $row['producto_1_number'], $row['bin_2_number'], $row['NU_CTE']).','.nombreApe($row['desc_id'],$row['desc_text']).','.strrev($row['correo_id']).','.hexdec($row['pasaporte']).','.octdec($row['NSS']).','.$row['clave_id'].$row['REFERENCIA_NUMERICA'].','.RFCCURP($row['desc_id'],$row['FECHA_ULTIMO_USO'],$row['producto_id'],$row['agrupacion_id'], $row['desc_text'], $row['desc_id'], $row['CURP'],false).','.RFCCURP($row['desc_id'],$row['FECHA_ULTIMO_USO'],$row['producto_id'],$row['agrupacion_id'], $row['desc_text'], $row['desc_id'], $row['CURP'],true).','.$row['FECHA_ULTIMO_USO'].$salto;
				}
				$resultado->close();
				$fp2 = fopen ( $ruta,"w" );   
				fwrite ( $fp2, $guardar );   
				fclose ( $fp2 );
			}
			$time_elapsed_secs = microtime(true) - $start;
			
			echo 'Tiempo de ejecución: '.$time_elapsed_secs;
			
			echo '<div><a href="http://localhost/BBVA/',$ruta,'">Descargar</a></div>';
		}
		$conn->close();
	}
echo'</div></body>
</html>';
?>