<?php 
function generaPass(){
	header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: *");
	header('Content-Type: application/JSON');
    //Se define una cadena de caractares. Te recomiendo que uses esta.
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //Obtenemos la longitud de la cadena de caracteres
	$longitudCadena=strlen($cadena);
	
    //Se define la variable que va a contener la contraseña
	$pass = "";
    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
	$longitudPass=10;
	
    //Creamos la contraseña
	for($i=1 ; $i<=$longitudPass ; $i++){
        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		$pos=rand(0,$longitudCadena-1);
		
        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		$pass .= substr($cadena,$pos,1);
	}
	return $pass;
}

function generarToken(){

    //Se define una cadena de caractares. Te recomiendo que uses esta.
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    //Obtenemos la longitud de la cadena de caracteres
	$longitudCadena=strlen($cadena);
	
    //Se define la variable que va a contener la contraseña
	$token = "";
    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
	$longitudtoken=20;
	
    //Creamos la contraseña
	for($i=1 ; $i<=$longitudtoken ; $i++){
        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		$pos=rand(0,$longitudCadena-1);
		
        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $token la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		$token .= substr($cadena,$pos,1);
	}
	return $token;
}

?>