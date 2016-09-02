<?php 


$user = $_SESSION['CodeUser'];
 $pwd = $_SESSION['MotPasse'];

 
 

// test de l'OS !
echo PHP_OS;
$isPase = (PHP_OS == "AIX" || PHP_OS == 'OS400'  );

if (!$isPase)  {
	// pas pase
	$dsn = "DRIVER=iseries Access ODBC Driver;SYSTEM=XSMPRD;DBQ=EURO4SCD";
	$db = odbc_pconnect($dsn , $user ,$pwd);
}
else 
{
	// CONVENTION APPELATION SYSTEME : DB2_I5_NAMING_ON  (appelation avec / au lieu  de .) et 
	// utilisation des jobd 
$option = array('i5_lib'=>EURO4SCD,'i5-naming'=>DB2_I5_NAMING_ON,'i5_commit'=>DB2_I5_TXN_NO_COMMIT);
$DB = db2_connect("XSMPRD", $user , $pwd , $option);
}
?>