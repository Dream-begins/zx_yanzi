<?php
session_start();
if(!isset($_SESSION['logined']) or $_SESSION['logined'] != 1)
{

        echo "<script>window.parent.location.href='index.php'</script>";
	exit;
	header('Location: index.php');
	exit;
}
if($_SESSION['ischangepass'] != 1)
{
	header('Location: changepass2.php');
	exit;
}	
