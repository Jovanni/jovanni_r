<?php
function clear_data ($data){
	$result = htmlspecialchars (trim (strip_tags($data)));
	return $result;
}

//Перевірка чи не зайнятий логін
function reg_test ($login, $email){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare("SELECT id
			FROM users
			WHERE login = :login
			OR email = :email");
		$sql -> bindParam(':login',$login,PDO::PARAM_STR);
		$sql -> bindParam(':email',$email,PDO::PARAM_STR);
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		$rows = $sql -> fetch();
	}
	catch(PDOException $e){
		echo $e -> getMessage();
	}
	return $rows;
}

//Реєстрація користувачів	
function reg ($name ,$login, $password, $email){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$time = time ();
		$sql = $db -> prepare("INSERT INTO users (name, login, password, email, regtime)
			VALUES (:name, :login, :password, :email, :time)");
		$sql -> bindParam(':name',$name,PDO::PARAM_STR);
		$sql -> bindParam(':login',$login,PDO::PARAM_STR);
		$sql -> bindParam(':password',$password,PDO::PARAM_STR);
		$sql -> bindParam(':email',$email,PDO::PARAM_STR);
		$sql -> bindParam(':time',$time,PDO::PARAM_INT);
		$sql -> execute();
	}
	catch(PDOException $e){
		echo $e -> getMessage();  
	}
}

//Авторизація користувачів
function login ($login, $password){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare("SELECT login, password, id
			FROM users
			WHERE login = :login
			OR email = :login
			AND password = :password");
		$sql -> bindParam(':login',$login,PDO::PARAM_STR);
		$sql -> bindParam(':password',$password,PDO::PARAM_STR);
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		$rows = $sql -> fetch();
		}	
	catch(PDOException $e){
		echo $e -> getMessage();  
	}
	return $rows;
}

//Кількість зареєстрованих користувачів
function reg_users (){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '');
	$sql = $db -> query("SELECT login
		FROM users");
	$sql -> setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $sql -> fetch()){
		$num_r [] = $row;
		}
	$rows = count($num_r);
	}
	catch(PDOException $e) {  
		echo $e -> getMessage();  
	}
	return $rows;
}

//Збереження повідомлень до БД
function save_msg ($msg, $title){
	$time = time ();
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare ("INSERT INTO msgs (title, msg,time,user, user_id)
			VALUES (:title, :msg, :time, :login, :id)");
		$sql -> bindParam(':title',$title,PDO::PARAM_STR);
		$sql -> bindParam(':msg',$msg,PDO::PARAM_STR);
		$sql -> bindParam(':time',$time,PDO::PARAM_INT);
		$sql -> bindParam(':login',$_SESSION['login'],PDO::PARAM_STR);
		$sql -> bindParam(':id',$_SESSION['id'],PDO::PARAM_INT);
		$sql -> execute();
	}
	catch(PDOException $e) {  
		echo $e -> getMessage();  
	}
}

//Вивід теми повідомлень
function print_title (){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '', 
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		$sql = $db -> query("SELECT title, user, time, user_id, id, msg
			FROM msgs 
			ORDER BY id DESC");
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $sql -> fetch()){
			$msgs [] = $row;
		}
	}
	catch(PDOException $e){
		echo $e -> getMessage();  
		}
	return $msgs;
}

//Вивід повідомлень
function print_msg ($id){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '', 
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		$sql = $db -> prepare("SELECT title, msg, user, time, user_id, id
			FROM msgs 
			WHERE (id=:id)");
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		$row = $sql -> fetch();
		$row ['msg'] = nl2br ($row ['msg']);
		$row ['msg'] = wordwrap($row ['msg'], 40, "\n", true);
	}
	catch(PDOException $e) {
		echo $e -> getMessage();  
	}
	return $row;
}

//Видалення повідомлень
function del_msg ($id){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', ''); 
		$sql = $db -> exec ("DELETE FROM msgs
			WHERE (id=$id)");
	}
	catch(PDOException $e) {  
		echo $e -> getMessage();  
	}
}

//Інформація про профіль користувача
function profile ($id){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '', 
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		$sql = $db -> prepare ("SELECT name, login, email, regtime, id, last_visit, last_name
			FROM users
			WHERE (id=:id)");
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		$arr = $sql -> fetch();
	}
		catch(PDOException $e) {  
			echo $e -> getMessage();  
	}
	return $arr;
}

//Редагування профілю користувача
function edit_profile ($name, $last_name, $id){
	try {	
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '', 
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare ("UPDATE users
			SET name=:name, last_name=:last_name
			WHERE id=:id");
		$sql -> bindParam(':name',$name,PDO::PARAM_STR);
		$sql -> bindParam(':last_name',$last_name,PDO::PARAM_STR);
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
	}
			catch(PDOException $e) {  
			echo $e -> getMessage();  
	}
}

//Редагування повідомлень
function edit_msg ($id, $msg, $title){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$time = time ();
		$sql = $db -> prepare ("UPDATE msgs
			SET msg=:msg, title=:title, time=:time
			WHERE (id=:id)");
		$sql -> bindParam(':msg',$msg,PDO::PARAM_STR);
		$sql -> bindParam(':title',$title,PDO::PARAM_STR);
		$sql -> bindParam(':time',$time,PDO::PARAM_INT);
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
	}
	catch(PDOException $e) {
		echo $e -> getMessage();  
	}
}

//Зменшення аватарки
function avatar (){
	$source = imagecreatefromjpeg($data_img);
	$target = imagecreatetruecolor(150, 150);
	imagecopyresampled($target, $source, 0, 0, 0, 0, 150, 150, $info[0], $info[1]);
	$small_img = imagejpeg($tmp,$filename,100);
		
	preg_match('{image/(.*)}is', $info['mime'], $p);
	$name_img = "img/" . $_SESSION ['login'] .time().".".$p[1];
	move_uploaded_file($tmp, $name_img);
	
	$old_av = avatar_s ($_SESSION['id'], $name_img);
}

//Збереження шляху до аватару
function avatar_s ($id, $av){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare("UPDATE users
				SET old_avatar=avatar, avatar=:av
				WHERE (id=:id)");
		$sql -> bindParam(':av',$av,PDO::PARAM_STR);
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
		
		$sql = $db -> query("SELECT old_avatar
			FROM users
			WHERE (id=$id)");
		$av = $sql -> fetch();
	}
	catch(PDOException $e) {
		echo $e -> getMessage();  
	}
	return $av['old_avatar'];
}

//Виведення шляху до аватару
function avatar_p ($id){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $db -> prepare("SELECT avatar, old_avatar
			FROM users
			WHERE (id=:id)");
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> execute();
		$sql -> setFetchMode(PDO::FETCH_ASSOC);
		$av = $sql -> fetch();
	}
		catch(PDOException $e) {
		echo $e -> getMessage();  
	}
	return $av['avatar'];
}

//Дата останнього візиту
function visit_time ($id, $time){
	try{
		$db = new PDO('mysql:host=localhost;dbname=Jovanni', 'root', '');
		$sql = $db -> prepare("UPDATE users
			SET last_visit=visit_time, visit_time=:time
			WHERE (id=:id)");
		$sql -> bindParam(':id',$id,PDO::PARAM_INT);
		$sql -> bindParam(':time',$time,PDO::PARAM_INT);
		$sql -> execute();
	}
	catch(PDOException $e) {
		echo $e -> getMessage(); 
	}
}
?>