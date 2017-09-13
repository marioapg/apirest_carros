<?php 
include("AccessDB.php");

class LoginAPI {    
	public function API(){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: *");
		header('Content-Type: application/JSON');                
		$method = $_SERVER['REQUEST_METHOD'];
		switch ($method) {
      case 'GET'://consulta
      if($_GET['action']=='clientes'){
      	$this->getPeoples(); 
      }
      if($_GET['action']=='publicacionescarros'){
      	$this->getPublicacionesCar();
      }
      if($_GET['action']=='publicacionescarrosdos'){
      	$this->getPublicacionesCarDOS();
      }
      if($_GET['action']=='publicacionesmotos'){
      	$this->getPublicacionesMot();
      }
      if($_GET['action']=='getall'){
      	$this->getAll();
      }
      if($_GET['action']=='getpubactive'){
      	$this->getPublicacionesByActive();
      }
      if($_GET['action']=='getsavedpub'){
      	$this->getSavedPub();
      }
      if($_GET['action']=='autocompletebrandcar'){
      	$this->autocompleteBrandCar($_SERVER['REQUEST_URI']);
      }
      if($_GET['action']=='autocompleteedosvzla'){
      	$this->autocompleteEdosVzla($_SERVER['REQUEST_URI']);
      }
      if($_GET['action']=='autocompleteedoscolo'){
      	$this->autocompleteEdosCol($_SERVER['REQUEST_URI']);
      }
      if($_GET['action']=='autocompleteedospana'){
      	$this->autocompleteEdosPan($_SERVER['REQUEST_URI']);
      }
      if($_GET['action']=='getphotocars'){  
      	$this->getPhotoCar();
      }
      if($_GET['action']=='getphotomots'){  
      	$this->getPhotoMot();
      }
      if($_GET['action']=='getnegociacion'){
      	$this->getNegociacion();
      }
      if($_GET['action']=='getcita'){ 
      	$this->getCita();
      }
      if($_GET['action']=='deluser'){ 
      	$this->deleteUser();
      }
      if($_GET['action']=='getconse'){  
      	$this->getConse();
      }
      if($_GET['action']=='getratingcomprador'){
      	$this->getRatingComprador();
      }
      if($_GET['action']=='getratingvendedor'){
 		 //$this->getRatingVendedor();
      }
      if($_GET['action']=='getratingconsecionario'){
      	$this->getRatingConsecionario();
      }

      if($_GET['action']=='getratingcompradoravg'){
      	$this->getRatingCompradorAVG();
      }
      if($_GET['action']=='getratingvendedoravg'){
 		 //$this->getRatingVendedorAVG();
      }
      if($_GET['action']=='getratingconsecionarioavg'){
      	$this->getRatingConsecionarioAVG();
      }

      break; 



      case 'POST'://inserta
      if($_GET['action']=='login'){
      	$this->comprobarLogin(); 
      }
      if($_GET['action']=='loginadmin'){
      	$this->LoginAdmin();
      }
      if($_GET['action']=='registrar'){
      	$this->saveUsuarios();
      }
      if($_GET['action']=='registraradmin'){
      	$this->saveAdmin();
      }
      if($_GET['action']=='recovery'){
      	$this->recuperarPass();
      }
      if($_GET['action']=='addcar'){
      	$this->saveCar();
      }
      if($_GET['action']=='addmot'){
      	$this->saveMot();
      }
      if($_GET['action']=='clientebymail'){
      	$this->getPeoplesEmail();
      }
      if($_GET['action']=='savefavpub'){
      	$this->savefavpub();
      }
      if($_GET['action']=='updatestatuscar'){ 
      	$this->updateStatusCar();
      }
      if($_GET['action']=='updatestatusmot'){ 
      	$this->updateStatusMot();
      }
      if($_GET['action']=='checkmail'){ 
      	$this->checkemailuser();
      }
      if($_GET['action']=='createnegociacion'){ 
      	$this->createNegociacion();
      }
      if($_GET['action']=='updatenegociacion'){ 
      	$this->updateStatusNegociacion();
      }
      if($_GET['action']=='sendpay'){ 
      	$this->sendPay();
      }
      if($_GET['action']=='cancelnegociacion'){
      	$this->cancelNegociacion();
      }
      if($_GET['action']=='crearcita'){
      	$this->crearCita();
      }
      if($_GET['action']=='usertoconse'){
      	$this->upgradeuser();
      }
      if($_GET['action']=='updatecar'){
      	$this->updateCar();
      }
      if($_GET['action']=='updatemot'){  
      	$this->updateMot();
      }
      if($_GET['action']=='addconse'){ 
      	$this->saveConse();
      }
      if($_GET['action']=='updatestatusconse'){ 
      	$this->updateStatusConse();
      }
      if($_GET['action']=='getconsecity'){
      	$this->getConseByCity();
      } 
      if($_GET['action']=='updateratingcomprador'){
      	$this->updateRatingComprador();
      }
      if($_GET['action']=='updateratingvendedor'){
      	$this->updateRatingVendedor();
      }
      if($_GET['action']=='updateratingconse'){
      	$this->updateRatingConse();
      }
      break;




     case 'PUT'://actualiza
     echo "PUT";
     break;      
     case 'DELETE'://elimina
     echo 'DELETE';
     break;
     default://metodo NO soportado
     echo 'METODO NO SOPORTADO';
     break;
 }
}

/**
  * función que segun el valor de "action" e "id":
  *  - mostrara una array con todos los registros de personas
  *  - mostrara un solo registro 
  *  - mostrara un array vacio
  */
function getPeoples(){
	if($_GET['action']=='clientes'){         
		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getUsuariosW($_GET['id']);                
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$response = $db->getUsuarios();              
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }    

 function getPeoplesEmail(){
 	if($_GET['action']=='clientebymail'){        
 		$obj = json_decode( file_get_contents('php://input'));   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			return $this->response(442,"false","Error insertanto. Revisar json");
 		}else if(isset($obj->email)){
 			$people = new AccessDB(); 
 			if($people->checkEmail($obj->email)){   
 				$db = new AccessDB();       
 				$response = $db->getUsuariosMail($obj->email);              
 				echo json_encode($response,JSON_PRETTY_PRINT);
 			}else{
 				$this->response(422,"false","El usuario no existe");
 			}       
 		}
 	}else{
 		$this->response(400);
 	}
 }
/**
 * Respuesta al cliente
 * @param int $code Codigo de respuesta HTTP
 * @param String $status indica el estado de la respuesta puede ser "success" o "error"
 * @param String $message Descripcion de lo ocurrido
 */
function response($code=200, $status="", $message="") {
	http_response_code($code);
	if( !empty($status) && !empty($message) ){
		$response = array('status' => $status ,'message'=>$message);  
		echo json_encode($response,JSON_PRETTY_PRINT);    
	}            
}

function responsepass($code=200, $status="", $message="", $pass="") {
	http_response_code($code);
	if( !empty($status) && !empty($message) && !empty($pass)){
		$response = array('status' => $status ,'message'=>$message,'pass'=>$pass);  
		echo json_encode($response,JSON_PRETTY_PRINT);    
	}            
}

function responseToken($code=200, $status="", $message="",$token="") {
	http_response_code($code);
	if( !empty($status) && !empty($message) ){
		$response = array('status' => $status ,'message'=>$message,'token'=>$token);  
		echo json_encode($response,JSON_PRETTY_PRINT);    
	}            
}
function responseTokenAndID($code=200, $status="", $message="",$token="",$iduser="") {
	http_response_code($code);
	if( !empty($status) && !empty($message) ){
		$response = array('status' => $status ,'message'=>$message,'token'=>$token,'iduser'=>$iduser);  
		echo json_encode($response,JSON_PRETTY_PRINT);    
	}            
}

function responsepub($code=200, $status="", $data="") {
	http_response_code($code);
	if( !empty($status) && !empty($data)){
		$response = array('status' => $status ,'data'=>$data);  
		//var_dump($response);
		echo json_encode($response,JSON_PRETTY_PRINT);    
	}            
}

/**
 * metodo para guardar un nuevo registro de persona en la base de datos
 */
function saveUsuarios(){
	if($_GET['action']=='registrar'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else
		if(!empty($obj->name) && !empty($obj->email) && !empty($obj->password)){
			$foto = $obj->photoprofile;
			$str = str_replace("[", "",$foto);
			$str = str_replace("]", "",$str);

			$array = explode(",",$str);
			
			$data = base64_decode($array[0]);
			$filenomb = 'images_user/'.rand() . '.png';
			$fileori = "../".$filenomb;
			$success = file_put_contents($fileori, $data);
			$data = base64_decode($data); 
			$source_img = imagecreatefromstring($data);
			$rotated_img = imagerotate($source_img, 90, 0); 
			$fileaux = 'images_user/'. rand(). '.png';
			$file = "../".$fileaux;
			$imageSave = imagejpeg($rotated_img, $file, 10);
			imagedestroy($source_img);
			$array[0] = "http://garage7.net/".$filenomb;

			$people = new AccessDB();   
			if(($people->checkEmail($obj->email)) == true){
				$this->response(200,'false','Usuario ya Existe');
			} else{
				if(($people->insert( $obj->name,$obj->lastname,$obj->sex,$obj->country,$obj->city,$obj->address,$obj->idnumber,$obj->phone,$obj->email,$obj->status,$obj->password,$array[0],$obj->terms)) == true){
				    $iduser = $people->checkLogin($obj->email,$obj->password);
				    $this->responseTokenAndID(200,"true","Agregado exitosamente",generarToken(), $iduser);                           
				}else{
					$this->response(412,"false","No se pudo registrar, intente mas tarde");
				}
			} 
		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
} 

function comprobarLogin(){
	if($_GET['action']=='login'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input'));   
		$objArr = (array)$obj;
		if (empty($objArr)){
			return $this->response(422,"false","Error insertanto. Revisar json");
		}else if(isset($obj->email) && isset($obj->password)){
			$people = new AccessDB(); 
			if($people->checkEmail($obj->email)){   
				$iduser = $people->checkLogin($obj->email,$obj->password);
				if($iduser != false ){
					$token = generarToken();
					return $this->responseTokenAndID(200,"true","Ha iniciado sesión de manera correcta.",$token,$iduser);                             
				}else{
					return $this->response(422,"false","Contrase#a Incorrecta");
				}
			}else{
				return  $this->response(422,"false","El usuario no existe");
			}
		}else{
			return $this->response(422,"false","Campo(s) Vacios");
		}
	} else{               
		return $this->response(400);
	}  
} 

/**
 * metodo para guardar un nuevo registro de persona en la base de datos
 */
function saveAdmin(){
	if($_GET['action']=='registraradmin'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else
		if(!empty($obj->name) && !empty($obj->email) && !empty($obj->password)){
			$people = new AccessDB();   
			if(($people->checkEmailAdmin($obj->email)) == true){
				$this->response(200,"false","Usuario ya Existe");
			} else{
				if(($people->insertAdmin( $obj->name,$obj->email,$obj->password )) == true){
					$this->response(200,"true","Administrador Agregado exitosamente");                             
				}else{
					$this->response(200,"false","No se pudo registrar, intente mas tarde");
				}
			} 
		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
}

function LoginAdmin(){
	if($_GET['action']=='loginadmin'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			return $this->response(442,"false","Error insertanto. Revisar json");
		}else if(isset($obj->email) && isset($obj->password)){
			$people = new AccessDB(); 
			if($people->checkEmailAdmin($obj->email)){   
				if(($people->checkLoginAdmin($obj->email,$obj->password )) == true){
					return $this->response(200,"true","Ha iniciado sesión de manera correcta.");                             
				}else{
					return $this->response(200,"false","Contrase#a Incorrecta");
				}
			}else{
				return $this->response(422,"false","El Administrador no existe");
			}
		}else{
			return $this->response(402,"false","Campo(s) Vacios");
		}
	} else{               
		return $this->response(400);
	}  
}  

function recuperarPass(){
	if($_GET['action']=='recovery'){
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if(!empty($objArr)){
			if(!empty($obj->email)){
				$people = new AccessDB();
				if($people->checkEmail($obj->email) == true){
					if($resultado = $people->updatePass($obj->email)){
						$clave = json_decode($resultado);
						return $this->responsepass(200,"true","Se ha enviado a su correo su nueva contrase#a",$clave);
					}else{
						return $this->response(422,"false","Error recuperando contraseña");
					}
				}else{
					return $this->response(422,"false","El usuario no existe");
				}
			}
		}else{
			return $this->response(422,"false","Revisar Json");
		}
	}
}

function saveCar(){
	if($_GET['action']=='addcar'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else
		if(!empty($obj->usuario) && !empty($obj->brand) && !empty($obj->model) && !empty($obj->year) && !empty($obj->mileage) && !empty($obj->transmition) && !empty($obj->gas) && !empty($obj->color) && !empty($obj->door_number) && !empty($obj->descriptions) && !empty($obj->location) && !empty($obj->price) && !empty($obj->photos)){
 			// $foto = $obj->photos;
 			// $str = str_replace("[", "",$foto);
 			// $str = str_replace("]", "",$str);
			$foto = $obj->photos;
			$str = str_replace("[", "",$foto);
			$str = str_replace("]", "",$str);

			$array = explode(",",$str);
			$longitud = count($array);
			for($i=0; $i<$longitud; $i++)
			{
				$data = base64_decode($array[$i]);
				$filenomb = 'images/'.rand() . '.png';
				$fileori = "../".$filenomb;
				$success = file_put_contents($fileori, $data);
				$data = base64_decode($data); 
				$source_img = imagecreatefromstring($data);
				$rotated_img = imagerotate($source_img, 90, 0); 
				$fileaux = 'images/'. rand(). '.png';
				$file = "../".$fileaux;
				$imageSave = imagejpeg($rotated_img, $file, 10);
				imagedestroy($source_img);
				$array[$i] = "http://garage7.net/".$filenomb;
			}

			for($i=0; $i<10; $i++)
			{
				if(is_null($array[$i])){
					$array[$i] = null;
				}
			}

			$people = new AccessDB();   
			if(($people->insertCar($obj->usuario,$obj->brand,$obj->model,$obj->year,$obj->mileage,$obj->transmition,$obj->gas,$obj->color,$obj->door_number,$obj->descriptions,$obj->location,$obj->price)) ==  true){
				$ultimo =$people->lastID();
				$people->insertPhotoCar($ultimo,$array[0],$array[1],$array[2],$array[3],$array[4],$array[5],$array[6],$array[7],$array[8],$array[9]);

				$this->response(200,"true","Agregado exitosamente");                             
			}else{
				$this->response(200,"false","No se pudo registrar, intente mas tarde");
			}

		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
}

function updateCar(){
	if($_GET['action']=='updatecar'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else
		if(!empty($obj->idpub) && !empty($obj->brand) && !empty($obj->model) && !empty($obj->year) && !empty($obj->mileage) && !empty($obj->transmition) && !empty($obj->gas) && !empty($obj->color) && !empty($obj->door_number) && !empty($obj->descriptions) && !empty($obj->location) && !empty($obj->price) && !empty($obj->status)){
			$people = new AccessDB();   
			if(($people->updateCar($obj->idpub,$obj->brand,$obj->model,$obj->year,$obj->mileage,$obj->transmition,$obj->gas,$obj->color,$obj->door_number,$obj->descriptions,$obj->location,$obj->price,$obj->status)) ==  true){
				$this->response(200,"true","Actualizado exitosamente");                             
			}else{
				$this->response(200,"false","No se pudo actualzar, intente mas tarde");
			}
		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
}

function updateMot(){
	if($_GET['action']=='updatemot'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else
		if(!empty($obj->idpub) && !empty($obj->brand) && !empty($obj->model) && !empty($obj->year) && !empty($obj->mileage) && !empty($obj->descriptions_m) && !empty($obj->location) && !empty($obj->price) && !empty($obj->status)){
			$people = new AccessDB();   
			if(($people->updateMot($obj->idpub,$obj->brand,$obj->model,$obj->year,$obj->mileage,$obj->descriptions_m,$obj->location,$obj->price,$obj->status)) ==  true){
				$this->response(200,"true","Actualizado exitosamente");                             
			}else{
				$this->response(200,"false","No se pudo actualzar, intente mas tarde");
			}
		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
}

function saveMot(){
	if($_GET['action']=='addmot'){   
          //Decodifica un string de JSON
		$obj = json_decode( file_get_contents('php://input') );   
		$objArr = (array)$obj;
		if (empty($objArr)){
			$this->response(422,"false","Error insertanto. Revisar json");                           
		}else

		if(!empty($obj->usuario) && !empty($obj->brand) && !empty($obj->cilindradas) && !empty($obj->year) && !empty($obj->mileage) && !empty($obj->color) && !empty($obj->price) && !empty($obj->descriptions) && !empty($obj->location) && !empty($obj->photos)){
			
			$foto = $obj->photos;
			$str = str_replace("[", "",$foto);
			$str = str_replace("]", "",$str);

			$array = explode(",",$str);
			$longitud = count($array);
			for($i=0; $i<$longitud; $i++)
			{
				$data = base64_decode($array[$i]);
				$filenomb = 'images/'.rand() . '.png';
				$fileori = "../".$filenomb;
				$success = file_put_contents($fileori, $data);
				$data = base64_decode($data); 
				$source_img = imagecreatefromstring($data);
				$rotated_img = imagerotate($source_img, 90, 0); 
				$fileaux = 'images/'. rand(). '.png';
				$file = "../".$fileaux;
				$imageSave = imagejpeg($rotated_img, $file, 10);
				imagedestroy($source_img);
				$array[$i] = "http://garage7.net/".$filenomb;
			}

			for($i=0; $i<10; $i++)
			{
				if(is_null($array[$i])){
					$array[$i] = null;
				}
			}

			$people = new AccessDB();   
			if(($people->insertMot($obj->usuario,$obj->brand,$obj->cilindradas,$obj->year,$obj->mileage,$obj->color,$obj->price,$obj->descriptions,$obj->location)) == true){
				echo $obj->descriptions."\n";
				$ultimo =$people->lastID();
				$people->insertPhotoMot($ultimo,$array[0],$array[1],$array[2],$array[3],$array[4],$array[5],$array[6],$array[7],$array[8],$array[9]);
				$this->response(200,"true","Agregado exitosamente");                             
			}else{
				$this->response(200,"false","No se pudo registrar, intente mas tarde");
			}

		}else{
			$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
		}
	}else{               
		$this->response(400);
	}  
}

function getAll(){
	if($_GET['action']=='getall'){         
		$db = new AccessDB();
         //muestra todos los registros                   
		$response = $db->getAllPub(); 
		$arrayresponse = array('vehiculos' => $response);
		return $this->responsepub(200,"true",$arrayresponse); 
 		//echo json_encode($response,JSON_PRETTY_PRINT);
	}else{
     	//echo "Bad Quequest";    // For testing purpose
		$this->response(400);
	}
}

function getPublicacionesByActive(){
	if($_GET['action']=='getpubactive'){         
		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
         	$response = $db->getAllPubActive($_GET['id']);  
         	$arrayresponse = array('vehiculos' => $response);
         	return $this->responsepub(200,"true",$arrayresponse);              
         	//echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$response = $db->getAllPubActive();
         	$arrayresponse = array('vehiculos' => $response);
         	return $this->responsepub(200,"true",$arrayresponse);              
         	//echo json_encode($response,JSON_PRETTY_PRINT);
         }
     }else{
      //echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 function getPublicacionesCar(){
 	if($_GET['action']=='publicacionescarros'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
         	//muestra 1 solo registro si es que existiera ID                 
 			$response = $db->getPubCarW($_GET['id']);
 			$arrayresponse = array('vehiculos' => $response);
 			return $this->responsepub(200,"true",$arrayresponse);
 		}else{ 
         	//muestra todos los registros                   
 			$response = $db->getPubCar();
 			$arrayresponse = array('vehiculos' => $response);              
 			return $this->responsepub(200,"true",$arrayresponse);
 		}
 	}else{
      //echo "Bad Quequest";    // For testing purpose
 		$this->response(400);
 	}       
 }

 function getPublicacionesCarDOS(){
 	$db = new AccessDB();
 	$response = $db->getPubCar();
 	$arrayresponse = array('vehiculos' => $response);  
 	return $this->responsepub(200,"true",$arrayresponse);

 }

 function getPublicacionesMot(){
 	if($_GET['action']=='publicacionesmotos'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
         	$response = $db->getPubMotW($_GET['id']);   
         	$arrayresponse = array('vehiculos' => $response);
         	return $this->responsepub(200,"true",$arrayresponse);             
         	//echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$response = $db->getPubMot();    
         	$arrayresponse = array('vehiculos' => $response);
         	return $this->responsepub(200,"true",$arrayresponse);          
         	//echo json_encode($response,JSON_PRETTY_PRINT);
         }
     }else{
      //echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 public function saveFavPub(){
 	if($_GET['action']=='savefavpub'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!is_null($obj->userid) && !is_null($obj->pubid) && !is_null($obj->type)){
 			$people = new AccessDB();   
 			if($people->checkSaved($obj->pubid,$obj->userid) == true){
 				$this->response(422,"false","Ya ha guardado esta publicacion");
 			}else{
 				if(($people->insertPubSaved($obj->pubid,$obj->userid,$obj->type )) == true){
 					$this->response(200,"true","Publicacion MARCADA con exito");                             
 				}else{
 					$this->response(422,"false","Erro guardando publicacion");
 				}
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 function getSavedPub(){
 	if($_GET['action']=='getsavedpub'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getSavedPubb($_GET['id']);                
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$this->response(422,"false","Debe indicar un usuario a consultar");
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 } 

 function autocompleteBrandCar($url){
 	$frase = substr($url,41, strlen($url));
 	if(strlen($frase) > 2){
 		$db = new AccessDB();
 		$response = $db->DBautocompleteBrandCar($frase);
 		echo json_encode($response,JSON_PRETTY_PRINT); 
 	}    
 }

 function autocompleteEdosVzla($url){
 	$frase = substr($url,41, strlen($url));
 	if(strlen($frase) > 2){
 		$db = new AccessDB();
 		$response = $db->DBautocompleteEdosVzla($frase);
 		echo json_encode($response,JSON_PRETTY_PRINT); 
 	}    
 }

 
 function autocompleteEdosCol($url){
 	$frase = substr($url,41, strlen($url));
 	if(strlen($frase) > 2){
 		$db = new AccessDB();
 		$response = $db->DBautocompleteEdosCol($frase);
 		echo json_encode($response,JSON_PRETTY_PRINT); 
 	}    
 }

 function autocompleteEdosPan($url){
 	$frase = substr($url,41, strlen($url));
 	if(strlen($frase) > 2){
 		$db = new AccessDB();
 		$response = $db->DBautocompleteEdosPan($frase);
 		echo json_encode($response,JSON_PRETTY_PRINT); 
 	}    
 }


 public function updateStatusCar(){
 	if($_GET['action']=='updatestatuscar'){   
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!is_null($obj->idpub) && !is_null($obj->newstatus)){
 			$people = new AccessDB();  
 			if($people->updstatecar($obj->idpub,$obj->newstatus) == true){
 				$this->response(200,"true","Status actualizado con éxito");
 			}else{
 				$this->response(422,"false","No se pudo actualizar el estatus");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function updateStatusMot(){
 	if($_GET['action']=='updatestatusmot'){   
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!is_null($obj->idpub) && !is_null($obj->newstatus)){
 			$people = new AccessDB();  
 			if($people->updstatemot($obj->idpub,$obj->newstatus) == true){
 				$this->response(200,"true","Status actualizado con éxito");
 			}else{
 				$this->response(422,"false","No se pudo actualizar el estatus");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function checkemailuser(){
 	if($_GET['action']=='checkmail'){   
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!is_null($obj->email)){
 			$people = new AccessDB();  
 			if($people->checkEmail($obj->email) == true){
 				$iduser = $people->checkMailReturn($obj->email);
 				$token = generarToken();
 				return $this->responseTokenAndID(200,"true","El usuario si existe",$token,$iduser);
 			}else{
 				$this->response(200,"false","El usuario no está registrado");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}
 }


 function getPhotoCar(){
 	if($_GET['action']=='getphotocars'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getPhotoCar($_GET['id']);                
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$this->response(422,"false","Debe indicar la publicacion a consultar");
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 function getPhotoMot(){
 	if($_GET['action']=='getphotomots'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getPhotoMot($_GET['id']);                
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$this->response(422,"false","Debe indicar la publicacion a consultar");
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 public function createNegociacion(){
 	if($_GET['action']=='createnegociacion'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idpub) && !empty($obj->iduser) && !empty($obj->idconse) && !empty($obj->type)){
 			$people = new AccessDB();   
 			if(($people->creataeNegociacion($obj->idpub,$obj->iduser,$obj->idconse,$obj->type)) == true){
 				$this->response(200,"true","Negociacion Creada");                             
 			}else{
 				$this->response(422,"false","Error guardando publicacion");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function updateStatusNegociacion(){
 	if($_GET['action']=='updatenegociacion'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idpub) && !empty($obj->newstatus)){
 			$people = new AccessDB();   
 			if(($people->updateNegociacion($obj->idpub,$obj->newstatus)) == true){
 				$this->response(200,"true","Estatus Actualizado");                             
 			}else{
 				$this->response(422,"false","Error actualizando publicacion");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function sendPay(){
 	if($_GET['action']=='sendpay'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idpub) && !empty($obj->paycode)){
 			$people = new AccessDB();   
 			if(($people->addpay($obj->idpub,$obj->paycode)) == true){
 				$this->response(200,"true","Pago agregado Correctamente");                             
 			}else{
 				$this->response(422,"false","Error actualizando publicacion");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function cancelNegociacion(){
 	if($_GET['action']=='cancelnegociacion'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idpub)){
 			$people = new AccessDB();   
 			if(($people->updateNegociacion($obj->idpub,0)) == true){
 				$this->response(200,"true","Negociacion Cancelada");                             
 			}else{
 				$this->response(422,"false","Error cancelando negociacion");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function getNegociacion(){
 	if($_GET['action']=='getnegociacion'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getNeg($_GET['id']); 
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$this->response(422,"false","Debe indicar la publicacion a consultar");
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 public function crearCita(){
 	if($_GET['action']=='crearcita'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idpub) && !empty($obj->date)){
 			$people = new AccessDB();   
 			if(($people->crearCita($obj->idpub,$obj->date)) == true){
 				if(($people->updateNegociacion($obj->idpub,2)) == true){
 					$this->response(200,"true","Cita Creada");	
 				}else{
 					$this->response(422,"false","Error actualizando estatus negociacion al agregar cita");
 				}
 			}else{
 				$this->response(422,"false","Error creando cita");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function updateCita(){
 	if($_GET['action']=='updatecita'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idcita) && !empty($obj->newstatus)){
 			$people = new AccessDB();   
 			if(($people->updateCita($obj->idcita,$obj->newstatus)) == true){
 				$this->response(200,"true","Estatus Cita Actualizado");                             
 			}else{
 				$this->response(422,"false","Error actualizando status de cita");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function getCita(){
 	if($_GET['action']=='getcita'){         
 		$db = new AccessDB();
         if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID   
         	$response = $db->getCita($_GET['id']); 
         	echo json_encode($response,JSON_PRETTY_PRINT);
         }else{ //muestra todos los registros                   
         	$this->response(422,"false","Debe indicar la cita a consultar");
         }
     }else{
     	//echo "Bad Quequest";    // For testing purpose
     	$this->response(400);
     }       
 }

 public function upgradeuser(){
 	if($_GET['action']=='usertoconse'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!is_null($obj->iduser) && !is_null($obj->newstatus)){
 			$people = new AccessDB();   
 			if(($people->upgradeuser($obj->iduser,$obj->newstatus)) == true){
 				$this->response(200,"true","Se ha convertido en un consecionario");                             
 			}else{
 				$this->response(422,"false","Error al hacer upgrade a su cuenta");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function deleteUser(){
 	if($_GET['action']=='deluser'){         
 		if(isset($_GET['id'])){
 			$db = new AccessDB();
 			if($db->checkID($_GET['id']) == true){
 				if($db->deleteUser($_GET['id']))
 					$this->response(200,"true","Usuario eliminado");
 				else
 					$this->response(400,"false","No pudo ser eliminado, intente más tarde");
 			}else 
 			$this->response(400,"false","El usuario que está indicando no existe");
 		}else{ //muestra todos los registros                   
 			$this->response(422,"false","Debe indicar la cita a consultar");
 		}
 	}else{
     	//echo "Bad Quequest";    // For testing purpose
 		$this->response(400);
 	} 
 }

 function saveConse(){
 	if($_GET['action']=='addconse'){ 
 		$obj = json_decode(file_get_contents('php://input'));  
 		$objArr = (array)$obj;          
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else{
 			if(empty($obj->name) && empty($obj->email)){
 				$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
 			}else{
 				$people = new AccessDB(); 
 				if(($people->checkEmailConse($obj->email)) == true){
 					$this->response(200,'false','Usuario ya Existe');
 				}else{
 					$foto = $obj->photo_concesionaria;
 					$str = str_replace("[", "",$foto);
 					$str = str_replace("]", "",$str);

 					$array = explode(",",$str);

 					$data = base64_decode($array[0]);
 					$filenomb = 'images_conse/'.rand() . '.png';
 					$fileori = "../".$filenomb;
 					$success = file_put_contents($fileori, $data);
 					$data = base64_decode($data); 
 					$source_img = imagecreatefromstring($data);
 					$rotated_img = imagerotate($source_img, 90, 0); 
 					$fileaux = 'images_conse/'. rand(). '.png';
 					$file = "../".$fileaux;
 					$imageSave = imagejpeg($rotated_img, $file, 10);
 					imagedestroy($source_img);
 					$array[0] = "http://garage7.net/".$filenomb;

 					if($people->insertConse( $obj->name,$obj->n_identification,$obj->fiscal_address,$obj->alternative_address,$obj->postal_zone,$obj->phone_principal,$obj->phone_movil,$obj->phone_alternative,$obj->email,$obj->email_alternative,$obj->country,$obj->city,$obj->parish,$obj->street_address,$obj->building_number,$array[0],$obj->status) == true){
 						$this->response(200,"true","Agregado exitosamente");
 					}else{
 						$this->response(412,"false","No se pudo registrar, intente mas tarde");
 					}
 				}
 			}
 		}
 	}else{
 		$this->response(400);
 	}
 }

 function updateStatusConse(){
 	if($_GET['action']=='updatestatusconse'){ 
 		$obj = json_decode(file_get_contents('php://input'));  
 		$objArr = (array)$obj;          
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else{
 			if(empty($obj->idconse) && empty($obj->newstatus)){
 				$this->response(422,"false","Hay Campos Vacios, Debe completarlos todos");
 			}else{
 				$people = new AccessDB(); 
 				if(($people->checkIDConse($obj->idconse)) == true){
 					if($people->updateStatusConse($obj->idconse,$obj->newstatus) == true){
 						$this->response(200,'true','Status Cambiado con Exito');	
 					}else{
 						$this->response(422,'false','No se pudo actualizar el status, intente mas tarde');
 					}
 				}else{
 					$this->response(422,'false','Consecionario no existe');
 				}
 			}
 		}
 	}else{
 		$this->response(400);
 	}
 }

 function getConse(){
 	if($_GET['action']=='getconse'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getConseW($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$response = $db->getConse();              
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getConseByCity(){
 	if($_GET['action']=='getconsecity'){  
 		$obj = json_decode(file_get_contents('php://input'));  
 		$objArr = (array)$obj;          
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else{
 			$db = new AccessDB();
 			$response = $db->getConseCity($obj->city);              
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 public function updateRatingComprador(){
 	if($_GET['action']=='updateratingcomprador'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idnegociacion) && !empty($obj->ratingcomprador)){
 			$people = new AccessDB();   
 			if(($people->updateRatingComprador($obj->idnegociacion,$obj->ratingcomprador)) == true){
 				$this->response(200,"true","Ha calificado satisfactoriamente");                             
 			}else{
 				$this->response(422,"false","Error calificando");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function updateRatingVendedor(){
 	if($_GET['action']=='updateratingvendedor'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idnegociacion) && !empty($obj->ratingvendedor)){
 			$people = new AccessDB();   
 			if(($people->updateRatingVendedor($obj->idnegociacion,$obj->ratingvendedor)) == true){
 				$this->response(200,"true","Ha calificado satisfactoriamente");                             
 			}else{
 				$this->response(422,"false","Error calificando");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 public function updateRatingConse(){
 	if($_GET['action']=='updateratingconse'){   
          //Decodifica un string de JSON
 		$obj = json_decode( file_get_contents('php://input') );   
 		$objArr = (array)$obj;
 		if (empty($objArr)){
 			$this->response(422,"false","Error insertanto. Revisar json");                           
 		}else
 		if(!empty($obj->idnegociacion) && !empty($obj->ratingconse)){
 			$people = new AccessDB();   
 			if(($people->updateRatingConsecionario($obj->idnegociacion,$obj->ratingconse)) == true){
 				$this->response(200,"true","Ha calificado satisfactoriamente");                             
 			}else{
 				$this->response(422,"false","Error calificando");
 			}
 		}else{               
 			$this->response(422,"false","Campos vacios");
 		}
 	}else{               
 		$this->response(400);
 	}  
 }

 function getRatingComprador(){
 	if($_GET['action']=='getratingcomprador'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingCom($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getRatingVendedor(){
 	if($_GET['action']=='getratingvendedor'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingVen($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getRatingConsecionario(){
 	if($_GET['action']=='getratingconsecionario'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingCon($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getRatingCompradorAVG(){
 	if($_GET['action']=='getratingcompradoravg'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingComAVG($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getRatingVendedorAVG(){
 	if($_GET['action']=='getratingvendedoravg'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingVenAVG($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }

 function getRatingConsecionarioAVG(){
 	if($_GET['action']=='getratingconsecionarioavg'){         
 		$db = new AccessDB();
 		if(isset($_GET['id'])){
 			$response = $db->getRatingConAVG($_GET['id']);                
 			echo json_encode($response,JSON_PRETTY_PRINT);
 		}else{
 			$this->response(422,"false","Debe indicar el id del comprador");
 		}
 	}else{
 		$this->response(400);
 	}       
 }




 }//end class
 ?>