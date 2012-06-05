<?php
session_start ();
require_once ("lib.php");
require_once ("menu.php");

//Авторизація
if (isset ($_POST ['login'])){
	$login = clear_data ($_POST ['login']);
	$password = md5 (clear_data ($_POST ['password']));
	if ($login == '' or $password == ''){
		exit ($_SESSION ['lang']['empty_fields'] . "<a href = index.php>" . $_SESSION ['lang']['again'] . "</a>.");
	}
	$log = login ($login, $password);
	if ($log != NULL){
		$_SESSION['login'] = $log ['login'];
		$_SESSION['id'] = $log ['id'];
		$t = time();
		visit_time ($log ['id'], $t);
	}else{
	exit ($_SESSION ['lang']['Incorrect_log_pass'] . "<a href = 'index.php'>" . $_SESSION ['lang']['back'] . "</a>");
	}
	header('location: index.php');
	exit;
}

//Реєстрація
if (isset ($_POST ['r_login'])){
	$name = clear_data ($_POST ['name']);
	$r_login = clear_data ($_POST ['r_login']);
	$r_password = md5 (clear_data ($_POST ['r_password']));
	$r_password2 = md5 (clear_data ($_POST ['r_password2']));
	$email = clear_data ($_POST ['email']);
	if ($name == '' or $r_login == '' or $r_password == '' or $r_password2 == '' or $email == ''){
		exit ($_SESSION ['lang']['empty_fields'] . "<a href = index.php?registration>" . $_SESSION ['lang']['again'] . "</a>.");
	}
	$preg = preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/",$email);
	if ($preg != 1){
		exit ($_SESSION ['lang']['ifemail']. "<a href = index.php?registration>" . $_SESSION ['lang']['again'] . "</a>.");
	}
	if ($r_password != $r_password2){
	echo  $_SESSION ['lang']['not_pass'] . '<a href = index.php?registration>' . $_SESSION ['lang']['again'] . '</a>.';
	}else{
		$r = reg_test ($r_login, $email);
	if ($r != NULL){
			exit ( $_SESSION ['lang']['log_exist'] . "<a href='index.php?registration'>" . $_SESSION ['lang']['back'] . "</a>");
		}else{
			reg ($name, $r_login, $r_password, $email);
			$log = login ($r_login, $r_password);
			$_SESSION['login'] = $log ['login'];
			$_SESSION['id'] = $log ['id'];
			$t = time();
			visit_time ($log ['id'], $t);
			echo $_SESSION ['lang']['successfully_reg'] . '<a href = index.php>' . $_SESSION ['lang']['back'] . '</a>';
		}
	exit;
	}
}

//Збереження повідомлень
if (isset ($_POST ['msg'])){
	$title = clear_data ($_POST ['title']);
	$msg = clear_data ($_POST ['msg']);
	if (empty ($title)){
		exit ($_SESSION ['lang']['write'] . "<a href = index.php?add_news>" . $_SESSION ['lang']['back'] . "</a>");
	}else
	save_msg ($msg, $title);
	header('location: index.php');
	exit;
}

//Видалення повідомлень
if (isset ($_GET ['del'])){
	$del = $_GET ['del'];
	del_msg ($del);
}

//Вихід
if (isset ($_GET ['exit'])){
	unset ($_SESSION['id']);
	header ("location: index.php");
	exit;
}

//Редагування повідомлень
if (isset ($_POST ['edit_title'])){
	$edit_title = clear_data ($_POST ['edit_title']);
	$edit_msg = clear_data ($_POST ['edit_msg']);
	$edit_id = $_POST ['edit_id'];
	edit_msg ($edit_id, $edit_msg, $edit_title);
	header('location: index.php');
	exit;
}

//Редагування профілю
if (isset ($_POST['edit_prof'])){
	$edit_name = clear_data ($_POST['name']);
	$edit_soname = clear_data ($_POST['soname']);
	$us_id = clear_data ($_POST ['user_id']);
	edit_profile ($edit_name, $edit_soname, $us_id);
	header('location: index.php');
	exit;
}

//Завантаження аватарки
if (isset ($_POST ['doUpload'])){
	if (isset ($_FILES['img']) and is_uploaded_file ($_FILES['img']['tmp_name'])){
		$data_img = $_FILES['img'];
		$tmp = $data_img['tmp_name'];
			$info = getimagesize($_FILES['img']['tmp_name']);
			if ($info === false){
				exit ($_SESSION ['lang']['wrong_type'] . "<a href = 'index.php'>" . $_SESSION ['lang']['back'] . "</a>");
			}else{

				if ($old_av != NULL and file_exists($old_av)){
					unlink ($old_av);
				}
				header ('Location: index.php?profile=' . $_SESSION['id']);
			}
		}else{
			echo "Помилка <a href = 'index.php'>" . $_SESSION ['lang']['back'] . "</a>";
		}
}
?>