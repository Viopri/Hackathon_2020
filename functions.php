<?php
function Luhn($TDC)
{
    $s = 0;
    $f = 0;
    for ($i = strlen($TDC) - 1; $i >= 0; $i--) {
        $k = $f++ & 1 ? $TDC[$i] * 2 : $TDC[$i];
        $s += $k > 9 ? $k - 9 : $k;
    }
    return $s % 10 === 0;
}

function getMail($nombre_text, $apellido_text){
	return substr($nombre_text, 0, -1).'@'.str_replace('!', '.com',$apellido_text);
}

function TDC($SUBCANAL_ENTRANTE, $CODIGO_ENTIDAD_ORI, $producto_1_number, $bin_2_number, $NU_CTE){
	$idCard = ltrim($SUBCANAL_ENTRANTE, '0').ltrim($CODIGO_ENTIDAD_ORI, '0');
	$TDC = $idCard.bindec($producto_1_number.$bin_2_number).substr($NU_CTE,1);
	$testcard = ' - Not a creditcard!';
	if(Luhn($TDC)==0)
		$testcard = ' - It\'s a creditcard!';
	return $TDC.$testcard;
}

function nombreApe($desc_id,$desc_text){
	global $ApeIn, $nombre, $Apellido;
	$ApeIn = substr($desc_id, 2);
	$nombre = explode( strtolower($ApeIn), $desc_text);
	$Apellido = $ApeIn.$nombre[1];
	return ucfirst($nombre[0]).' '.$Apellido;
}
function RFCCURP($desc_id,$FECHA_ULTIMO_USO,$producto_id,$agrupacion_id, $desc_text, $CURP,$cond){
	global $ApeIn, $nombre, $Apellido;
	$nameInc = substr($desc_id, 0, 2);
	$piecesDate = explode('-',$FECHA_ULTIMO_USO);
	$curpnum = substr($CURP, -2);
	$vow = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
	$Con1 = substr(str_replace($vow, "", substr($nombre[0],1)),0,1);
	$Con2 = substr(str_replace($vow, "", substr($Apellido,1)),0,1);
	$RFC1 = $ApeIn.'X'.substr($nameInc, 0, -1).substr($piecesDate[0], -2).$piecesDate[1].$piecesDate[2];

	if($cond)
		return strtoupper($RFC1.$agrupacion_id.$Con2.'X'.$Con1.$curpnum);
	else
		return strtoupper($RFC1.$producto_id);
}
function tel($clave_secundaria_text){
	$letters = array('Z','E','A','S','B');
	$nums = array('2','3','4','5','8');

	return str_replace($letters,$nums,$clave_secundaria_text);
}
?>