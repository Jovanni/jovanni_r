<?php
session_start ();
require_once "lib.php";
require_once "menu.php";
require_once "conf.php";
if (empty ($_SESSION ['lang'])){
	$_SESSION ['lang'] = $lang ['ua'];
}
if (isset ($_POST ['lang'])){
	if ($_POST ['lang'] == 'Ru'){
		$_SESSION ['lang'] = $lang ['ru'];
		$_SESSION ['sel_ru'] = selected;
		unset ($_SESSION ['sel_eng'], $_SESSION ['sel_ua']);
	}
	
	elseif ($_POST ['lang'] == 'Eng'){
		$_SESSION ['lang'] = $lang ['eng'];
		$_SESSION ['sel_eng'] = selected;
		unset ($_SESSION ['sel_ru'], $_SESSION ['sel_ua']);
	}
		
	elseif ($_POST ['lang'] == 'Ua'){
		$_SESSION ['lang'] = $lang ['ua'];
		$_SESSION ['sel_ua'] = selected;
		unset ($_SESSION ['sel_eng'], $_SESSION ['sel_ru']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title>Jovanni - site</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<body>

<table width="100%" border="1">

<tr>
	<td colspan="2" align="center">
		<!-- Верхня частина сторінки -->
		<?php
			include ("html_files/top.html");
		?>
	</td>
</tr>

<tr>
	<td width="20%" valign="top">
		<!-- Меню -->
		<?php
		if (empty ($_SESSION['id'])){
			include ("html_files/log.html");
		}else include ("html_files/logined.html");
		?>
	</td>
	<td>
		<!-- Основний контент -->
		<?php	
			if (isset ($_GET ['registration'])){
				include ("html_files/reg.html");
			}
			elseif (isset ($_GET ['profile'])){
				$profile = clear_data ($_GET ['profile']);
				$arr = profile($profile);
				include ("html_files/profile.html");
			}
			elseif (isset ($_GET ['add_news'])){
				include ("html_files/new_msg.html");
			}
			elseif (isset ($_GET ['readmsg'])){
				$readmsg = clear_data ($_GET ['readmsg']);
				$msg_arr = print_msg ($readmsg);
				include ("html_files/msg_info.html");
			}
			elseif (isset ($_GET ['edit'])){
				$editmsg = clear_data ($_GET ['edit']);
				$msg_arr = print_msg ($editmsg);
				include ("html_files/edit_msg.html");
			}
			elseif (isset ($_GET ['edit_profile'])){
				$edit_profile = clear_data ($_GET ['edit_profile']);
				$prof_arr = profile ($edit_profile);
				include ("html_files/edit_profile.html");	
			}else{
				$msgs = print_title ();
				if (empty ($msgs))
				exit;
				foreach ($msgs as $row){
				include ("html_files/msg.html");
				}
			}
		?>
	</td>
</tr>
</table>
</body>
</html>