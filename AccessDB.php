<?php
include("ManejoPass.php");
class AccessDB {
	protected $mysqli;

	const LOCALHOST = 'localhost';
	const USER = 'usuario';
	const PASSWORD = 'password';
	const DATABASE = 'database';

    /**
     * Constructor de clase
     */
    public function __construct() {           
    	try{
            //conexión a base de datos
    		$this->mysqli = new mysqli(self::LOCALHOST, self::USER, self::PASSWORD, self::DATABASE);
    	}catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión
            // echo "Error de Conexion con DB"; // For testing purpose
    		http_response_code(500);
    		exit;
    	}     
    } 
    
    /**
     * obtiene un solo registro dado su ID
     * @param int $id identificador unico de registro
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getUsuariosW($id=0){      
    	$stmt = $this->mysqli->prepare("SELECT * FROM usuarios WHERE idusuarios=? ; ");
    	$stmt->bind_param('s', $id);
    	$stmt->execute();
    	$result = $stmt->get_result();        
    	$peoples = $result->fetch_all(MYSQLI_ASSOC); 
    	$stmt->close();
    	return $peoples;              
    }
    
    /**
     * obtiene todos los registros de la tabla "people"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getUsuarios(){        
    	$result = $this->mysqli->query('SELECT * FROM usuarios');          
    	$peoples = $result->fetch_all(MYSQLI_ASSOC);          
    	$result->close();
    	return $peoples; 
    }

    public function getUsuariosMail($email){      
    	$consulta = "SELECT * FROM usuarios WHERE email='".$email."'";
    	$result = $this->mysqli->query($consulta);          
    	$peoples = $result->fetch_all(MYSQLI_ASSOC);          
    	$result->close();
    	return $peoples;              
    }
    
    /**
     * añade un nuevo registro en la tabla persona
     * @param String $name nombre completo de persona
     * @return bool TRUE|FALSE 
     */
    public function insert($name='',$lastname='',$sex='',$country='',$city='',$address='',$idnumber='',$phone='',$email='',$status='',$password='',$profilephoto='',$terms=''){
    	$passwordmd5 = md5($password);
    	$stringconsulta ="INSERT INTO usuarios(name,lastname,sex,country,city,address,N_indetification,phone,email,status,password,profile_photo,terms) VALUES ('".$name."','".$lastname."','".$sex."','".$country."','".$city."','".$address."','".$idnumber."','".$phone."','".$email."','".$status."','".$passwordmd5."','".$profilephoto."','".$terms."');";
    	$stmt = $this->mysqli->prepare($stringconsulta);
    	$stmt->bind_param('s', $stringconsulta);
    	$r = $stmt->execute() ?: false;
    	return $r;        
    }

    /**
     * elimina un registro dado el ID
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function delete($id=0) {
    	$stmt = $this->mysqli->prepare("DELETE FROM usuarios WHERE id = ? ; ");
    	$stmt->bind_param('s', $id);
    	$r = $stmt->execute(); 
    	$stmt->close();
    	return $r;
    }
    
    /**
     * Actualiza registro dado su ID
     * @param int $id Description
     */
    public function update($id, $newName) {
    	if($this->checkID($id)){
    		$stmt = $this->mysqli->prepare("UPDATE usuarios SET name=? WHERE id = ? ; ");
    		$stmt->bind_param('ss', $newName,$id);
    		$r = $stmt->execute(); 
    		$stmt->close();
    		return $r;    
    	}
    	return false;
    }
    
    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkID($id){
    	$consulta = "SELECT idusuarios FROM usuarios WHERE idusuarios='".$id."';" ;
        $result = $this->mysqli->query($consulta);
        if ($result->num_rows > 0){                
            return true;
        }
        return false;
     }

     /**
     * verifica si un email existe
     * @param int $email Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
     public function checkEmail($email){
     	$consulta = "SELECT * FROM usuarios WHERE email='".$email."';";
     	$result = $this->mysqli->query($consulta);
     	if ($result->num_rows > 0){                
     		return true;
     	}
     	return false;
     }

     public function checkEmailConse($email){
        $consulta = "SELECT * FROM concesionarias WHERE email='".$email."';";
        $result = $this->mysqli->query($consulta);
        if ($result->num_rows > 0){                
            return true;
        }
        return false;
     }

     public function checkIDConse($id){
        $consulta = "SELECT * FROM concesionarias WHERE idConcesionarias='".$id."';";
        $result = $this->mysqli->query($consulta);
        if ($result->num_rows > 0){                
            return true;
        }
        return false;
     }

     public function checkLogin($email,$pass){
     	$passmd5 = md5($pass);
     	$query = "SELECT idusuarios FROM usuarios WHERE email='".$email."' AND password='".$passmd5."';";
     	$result = $this->mysqli->query($query);
     	if($result->num_rows == 1){
     		$row = $result->fetch_assoc();
     		return $row['idusuarios'];
     	}else{
     		return false;
     	}
     } 

     public function insertAdmin($name='',$email='',$password=''){
     	$passwordmd5 = md5($password);
     	$stringconsulta ="INSERT INTO administrador(name,email,password) VALUES ('".$name."','".$email."','".$passwordmd5."');";
     	echo $stringconsulta;
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r;        
     }


     public function checkLoginAdmin($email,$pass){
     	$passmd5 = md5($pass);
     	$stmt = $this->mysqli->prepare("SELECT * FROM administrador WHERE email='".$email."' AND password='".$passmd5."';");
     	$stmt->bind_param("s", $id);
     	if($stmt->execute()){
     		$stmt->store_result();    
     		if ($stmt->num_rows == 1){                
     			return true;
     		}
     	}        
     	return false;
     }

     public function checkEmailAdmin($email){
     	$consulta = "SELECT * FROM administrador WHERE email='".$email."';";
     	$result = $this->mysqli->query($consulta);
     	if ($result->num_rows == 1){                
     		return true;
     	}
     	return false;
     }

     public function checkSaved($pub,$user){
     	$consulta = "SELECT * FROM publicaciones_guardadas WHERE id_publicacion='".$pub."' AND id_user='".$user."';";
     	$result = $this->mysqli->query($consulta);
     	if ($result->num_rows > 0){                
     		return true;
     	}
     	return false;
     }

     public function updatePass($email){
     	$clave = generaPass();
        // echo $clave;
     	$clave_encryptada = md5($clave);
     	$consulta = "UPDATE usuarios SET password='".$clave_encryptada."' WHERE email='".$email."';";
     	if($this->mysqli->query($consulta) == TRUE){
     		return json_encode($clave,JSON_PRETTY_PRINT);
     	}else{
     		return false;
     	}
     }

     public function getPubCar(){       
     	$consulta = "SELECT * FROM publicaciones_carros LEFT JOIN photo_car ON idpost_cars=id_publicacion UNION SELECT * FROM publicaciones_carros RIGHT JOIN photo_car ON idpost_cars=id_publicacion;";
     	$result = $this->mysqli->query($consulta);          
     	$peoples = $result->fetch_all(MYSQLI_ASSOC); 
     	$result->close();
     	return $peoples; 
     }

     public function getPubCarW($id){   
     	$consulta = "SELECT * from publicaciones_carros  WHERE 	idpost_cars='".$id."'";     
     	$result = $this->mysqli->query($consulta);          
     	$peoples = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $peoples; 
     }

     public function insertCar($usuario='',$brand='',$model='',$year='',$mileage='',$transmition='',$gas_c='',$color='',$door_number='',$descriptions='',$location='',$price=''){
     	$stringconsulta ="INSERT INTO publicaciones_carros(usuario_c,date_publication_c,brand_c,model_c,year_c,mileage_c,transmition_c,gas_c,color_c,door_number_c,descriptions_c,location_c,price_c) VALUES ('".$usuario."',NOW(),'".$brand."','".$model."','".$year."','".$mileage."','".$transmition."','".$gas_c."','".$color."','".$door_number."','".$descriptions."','".$location."','".$price."');";
        //echo $stringconsulta;
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r;        
     }

     public function insertPhotoCar($idpub='',$photo_uno='',$photo_dos='',$photo_tres='',$photo_cuatro='',$photo_cinco='',$photo_seis='',$photo_siete='',$photo_ocho='',$photo_nueve='',$photo_diez='')
     {
     	$stringconsulta ="INSERT INTO photo_car VALUES ('".$idpub."','".$photo_uno."','".$photo_dos."','".$photo_tres."','".$photo_cuatro."','".$photo_cinco."','".$photo_seis."','".$photo_siete."','".$photo_ocho."','".$photo_nueve."','".$photo_diez."');";
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r; ;
     }

     public function updateCar($idpub='',$brand='',$model='',$year='',$mileage='',$transmition='',$gas_c='',$color='',$door_number='',$descriptions='',$location='',$price='',$status=''){
     	$stringconsulta ="UPDATE publicaciones_carros SET brand_c='".$brand."',model_c='".$model."',year_c='".$year."',mileage_c='".$mileage."',transmition_c='".$transmition."',gas_c='".$gas_c."',color_c='".$color."',door_number_c='".$door_number."',descriptions_c='".$descriptions."',location_c='".$location."',price_c='".$price."',estatus_c='".$status."' WHERE idpost_cars='".$idpub."'";
        // echo $stringconsulta;
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r;        
     }

     public function updateMot($idpub='',$brand='',$model='',$year='',$mileage='',$descriptions='',$location='',$price='',$status=''){
        $stringconsulta ="UPDATE publicaciones_motos SET brand='".$brand."',model='".$model."',year='".$year."',mileage='".$mileage."',descriptions_m='".$descriptions."',location='".$location."',price='".$price."',estatus='".$status."' WHERE idpost_mots='".$idpub."'";
        // echo $stringconsulta;
        $stmt = $this->mysqli->prepare($stringconsulta);
        $stmt->bind_param('s', $stringconsulta);
        $r = $stmt->execute() ?: false;
        return $r;        
     }

     public function updatePhotoCar($idpub='',$photo_uno='',$photo_dos='',$photo_tres='',$photo_cuatro='',$photo_cinco='',$photo_seis='',$photo_siete='',$photo_ocho='',$photo_nueve='',$photo_diez='')
     {
     	$stringconsulta ="UPDATE photo_car SET photo1='".$photo_uno."',photo2='".$photo_dos."',photo3='".$photo_tres."',photo4='".$photo_cuatro."',photo5='".$photo_cinco."',photo6='".$photo_seis."',photo7='".$photo_siete."',photo8='".$photo_ocho."',photo9='".$photo_nueve."',photo10='".$photo_diez."' WHERE id_publicacion='".$idpub."';";
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r; ;
     }

     public function insertMot($usuario='',$brand='',$cilindradas='',$year='',$mileage='',$color='',$price='',$descriptions='',$location=''){
     	$stringconsulta ="INSERT INTO publicaciones_motos(usuario,date_publication,brand,cilindradas,year,mileage,color,price,descriptions_m,location) VALUES ('".$usuario."',NOW(),'".$brand."','".$cilindradas."','".$year."','".$mileage."','".$color."','".$price."','".$descriptions."','".$location."');";
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r;        
     }

     public function insertPhotoMot($idpub='',$photo_uno='',$photo_dos='',$photo_tres='',$photo_cuatro='',$photo_cinco='',$photo_seis='',$photo_siete='',$photo_ocho='',$photo_nueve='',$photo_diez='')
     {
     	$stringconsulta ="INSERT INTO photo_mot VALUES ('".$idpub."','".$photo_uno."','".$photo_dos."','".$photo_tres."','".$photo_cuatro."','".$photo_cinco."','".$photo_seis."','".$photo_siete."','".$photo_ocho."','".$photo_nueve."','".$photo_diez."');";
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r; ;
     }

     public function getAllPub(){
     	$consulta = "SELECT publicaciones_carros.idpost_cars AS idpublicacion,publicaciones_carros.type_post_c,publicaciones_carros.date_publication_c,publicaciones_carros.usuario_c,publicaciones_carros.year_c,publicaciones_carros.brand_c,publicaciones_carros.model_c,publicaciones_carros.color_c,publicaciones_carros.location_c,publicaciones_carros.descriptions_c,publicaciones_carros.price_c,publicaciones_carros.estatus_c FROM publicaciones_carros WHERE 1 UNION SELECT publicaciones_motos.idpost_mots,publicaciones_motos.type_post_m,publicaciones_motos.date_publication,publicaciones_motos.usuario,publicaciones_motos.year,publicaciones_motos.brand,publicaciones_motos.model,publicaciones_motos.color,publicaciones_motos.location,publicaciones_motos.descriptions_m,publicaciones_motos.price,publicaciones_motos.estatus FROM publicaciones_motos WHERE 1;";
     	$result = $this->mysqli->query($consulta);
     	$peoples = $result->fetch_all(MYSQLI_ASSOC);
     	$result->close();
     	return $peoples;
     }

     public function getAllPubActive($id='1'){
     	$consulta = "SELECT publicaciones_carros.idpost_cars AS idpublicacion,publicaciones_carros.type_post_c,publicaciones_carros.date_publication_c,publicaciones_carros.estatus_c FROM publicaciones_carros WHERE publicaciones_carros.estatus_c='".$id."' UNION SELECT publicaciones_motos.idpost_mots,publicaciones_motos.type_post_m,publicaciones_motos.date_publication,publicaciones_motos.estatus FROM publicaciones_motos WHERE publicaciones_motos.estatus='".$id."';";
     	$result = $this->mysqli->query($consulta);
     	$peoples = $result->fetch_all(MYSQLI_ASSOC);
     	$result->close();
     	return $peoples;
     }


  // public function getPubMot(){       
  //     $consulta = "SELECT * from publicaciones_motos";
  //     $result = $this->mysqli->query($consulta);          
  //     $peoples = $result->fetch_all(MYSQLI_ASSOC);          
  //     $result->close();
  //     return $peoples; 
  // }

     public function getPubMot(){       
     	$consulta = "SELECT * FROM publicaciones_motos LEFT JOIN photo_mot ON idpost_mots=id_publicacion UNION SELECT * FROM publicaciones_motos RIGHT JOIN photo_mot ON idpost_mots=id_publicacion;";
     	$result = $this->mysqli->query($consulta);          
     	$peoples = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $peoples; 
     }

     public function getPubMotW($id){       
     	$consulta = "SELECT * from publicaciones_motos WHERE idpost_mots='".$id."'";
     	$result = $this->mysqli->query($consulta);          
     	$peoples = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $peoples; 
     }

     public function lastID()
     {
     	return $this->mysqli->insert_id;
     }

     public function insertPubSaved($usuario='',$publicacion='',$type=''){
     	$stringconsulta ="INSERT INTO publicaciones_guardadas(id_publicacion,id_user,type) VALUES ('".$usuario."','".$publicacion."','".$type."');";
     	$stmt = $this->mysqli->prepare($stringconsulta);
     	$stmt->bind_param('s', $stringconsulta);
     	$r = $stmt->execute() ?: false;
     	return $r;        
     }

     public function getSavedPubb($userid=''){       
     	$consulta = "SELECT * FROM ( SELECT * FROM publicaciones_carros LEFT JOIN photo_car ON idpost_cars=id_publicacion UNION SELECT * FROM publicaciones_carros RIGHT JOIN photo_car ON idpost_cars=id_publicacion ) A INNER JOIN publicaciones_guardadas B WHERE B.id_user = '".$userid."' AND B.id_publicacion=A.idpost_cars";
     	$result = $this->mysqli->query($consulta);          
     	$pub = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $pub; 
     }

     public function DBautocompleteBrandCar($value=''){
     	$query = $value;
     	$consulta = "SELECT DISTINCT marca FROM marcas WHERE marca LIKE '%{$query}%'";
     	$result = $this->mysqli->query($consulta);          
     	$retorno = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $retorno;
     }

     public function DBautocompleteEdosVzla($value=''){
     	$query = $value;
     	$consulta = "SELECT DISTINCT estado FROM edosvzla WHERE estado LIKE '%{$query}%'";
     	$result = $this->mysqli->query($consulta);          
     	$retorno = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $retorno;
     }

     public function DBautocompleteEdosCol($value=''){
     	$query = $value;
     	$consulta = "SELECT DISTINCT estado FROM edoscol WHERE estado LIKE '%{$query}%'";
     	$result = $this->mysqli->query($consulta);          
     	$retorno = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $retorno;
     }

     public function DBautocompleteEdosPan($value=''){
     	$query = $value;
     	$consulta = "SELECT DISTINCT estado FROM edoscol WHERE estado LIKE '%{$query}%' AND id > '29' ";
     	$result = $this->mysqli->query($consulta);          
     	$retorno = $result->fetch_all(MYSQLI_ASSOC);          
     	$result->close();
     	return $retorno;
     }

     public function updstatecar($idpub='',$newstatus=''){       
     	$consulta = "UPDATE publicaciones_carros SET estatus_c='".$newstatus."' WHERE idpost_cars='".$idpub."'";
    	$stmt = $this->mysqli->prepare($consulta);
     	$stmt->bind_param('s', $consulta);
     	$r = $stmt->execute() ?: false;
     	return $r; 
     }

     public function updstatemot($idpub='',$newstatus=''){       
     	$consulta = "UPDATE publicaciones_motos SET estatus='".$newstatus."' WHERE idpost_mots='".$idpub."'";
    	$stmt = $this->mysqli->prepare($consulta);
     	$stmt->bind_param('s', $consulta);
     	$r = $stmt->execute() ?: false;
     	return $r;  
     }

     public function getPhotoCar($id){   
        $consulta = "SELECT * from photo_car  WHERE  id_publicacion='".$id."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function getPhotoMot($id){   
        $consulta = "SELECT * from photo_mot  WHERE  id_publicacion='".$id."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function checkMailReturn($email){       
        $consulta = "SELECT idusuarios FROM usuarios WHERE email='".$email."';";
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

    public function creataeNegociacion($pub='',$user='',$conce='',$type=''){
        $stringconsulta ="INSERT INTO negociacion(publicacion,idusuario,consecionaria,type_publication,status_negociacion) VALUES ('".$pub."','".$user."','".$conce."','".$type."','1');";
        $stmt = $this->mysqli->prepare($stringconsulta);
        $stmt->bind_param('s', $stringconsulta);
        $r = $stmt->execute() ?: false;
        return $r;        
     }

     public function updateNegociacion($negociacion='',$status='')
     {
        $consulta = "UPDATE negociacion SET status_negociacion='".$status."' WHERE negociacion='".$negociacion."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

      public function addpay($negociacion='',$paycode='')
     {
        $consulta = "UPDATE negociacion SET status_negociacion='4',transferencia='".$paycode."' WHERE negociacion='".$negociacion."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function getNeg($negociacion){       
        $consulta = "SELECT * FROM negociacion WHERE negociacion='".$negociacion."';";
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function crearCita($pub='',$fecha=''){
        $stringconsulta ="INSERT INTO cita(negociacion,fecha) VALUES ('".$pub."','".$fecha."');";
        $stmt = $this->mysqli->prepare($stringconsulta);
        $stmt->bind_param('s', $stringconsulta);
        $r = $stmt->execute() ?: false;
        return $r;        
     }

      public function updateCita($cita='',$status='')
     {
        $consulta = "UPDATE cita SET status='".$status."' WHERE idcita='".$idcita."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function getCita($idcita){       
        $consulta = "SELECT * FROM cita WHERE idcita='".$idcita."';";
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function upgradeuser($user='',$status='')
     {
        $consulta = "UPDATE usuarios SET isconsecionario='".$status."' WHERE idusuarios='".$user."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function deleteUser($user='')
     {
        $consulta = "DELETE FROM usuarios WHERE idusuarios='".$user."';" ;
     	$this->mysqli->query($consulta);
     	if ($this->mysqli->affected_rows > 0){                
     		return true;
     	}
     	return false;
     }

     public function insertConse($name='',$n_identification='',$fiscal_address='',$alternative_address='',$postal_zone='',$phone_principal='',$phone_movil='',$phone_alternative='',$email='',$email_alternative='',$country='',$city='',$parish='',$street_address='',$building_number='',$photo_concesionaria='',$status='')
     {
        $stringconsulta ="INSERT INTO concesionarias(name,n_identification,fiscal_address,alternative_address,postal_zone,phone_principal,phone_movil,phone_alternative,email,email_alternative,country,city,parish,street_address,building_number,photo_concesionaria,status) VALUES ('".$name."','".$n_identification."','".$fiscal_address."','".$alternative_address."','".$postal_zone."','".$phone_principal."','".$phone_movil."','".$phone_alternative."','".$email."','".$email_alternative."','".$country."','".$city."','".$parish."','".$street_address."','".$building_number."','".$photo_concesionaria."','1');";
        $stmt = $this->mysqli->prepare($stringconsulta);
        $stmt->bind_param('s', $stringconsulta);
        $r = $stmt->execute() ?: false;
        return $r;  
     }

     public function updateStatusConse($idconse='',$newstatus='')
     {
        $consulta = "UPDATE concesionarias SET status='".$newstatus."' WHERE idConcesionarias='".$idconse."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function getConse(){       
        $consulta = "SELECT * FROM concesionarias;";
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $result->close();
        return $peoples; 
     }

     public function getConseW($id){   
        $consulta = "SELECT * from concesionarias  WHERE  idConcesionarias='".$id."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function getConseCity($city){   
        $consulta = "SELECT * from concesionarias  WHERE  city='".$city."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function updateRatingComprador($idnegociacion='',$rating='')
     {
        $consulta = "UPDATE negociacion SET rating_comprador='".$rating."' WHERE negociacion='".$idnegociacion."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function updateRatingVendedor($idnegociacion='',$rating='')
     {
        $consulta = "UPDATE negociacion SET rating_vendedor='".$rating."' WHERE negociacion='".$idnegociacion."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function updateRatingConsecionario($idnegociacion='',$rating='')
     {
        $consulta = "UPDATE negociacion SET rating_consecionario='".$rating."' WHERE negociacion='".$idnegociacion."'";
        $stmt = $this->mysqli->prepare($consulta);
        $stmt->bind_param('s', $consulta);
        $r = $stmt->execute() ?: false;
        return $r; 
     }

     public function getRatingCom($id='')
     {
        $consulta = "SELECT rating_comprador from negociacion  WHERE  idusuario='".$id."'";    
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function getRatingComAVG($id='')
     {
        $consulta = "SELECT AVG(rating_comprador) FROM negociacion WHERE idusuario='".$id."'";    
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function getRatingCon($id='')
     {
        $consulta = "SELECT rating_comprador from negociacion  WHERE  consecionaria='".$id."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }

     public function getRatingConAVG($id='')
     {
        $consulta = "SELECT AVG(rating_comprador) from negociacion  WHERE  consecionaria='".$id."'";     
        $result = $this->mysqli->query($consulta);          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
     }
 }
