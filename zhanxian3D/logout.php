<?php
session_start();
unset($_SESSION['logined']);
unset($_SESSION['xwusername']);
unset($_SESSION['ischangepass']);
unset($_SESSION['priv']);
header('Location: index.php');

