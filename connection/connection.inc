<?
/* Connection.inc */
/* on va lire le fichier connection.ini se trouvant dans le répertoire */

function Init_Config()
{
	$conn="";
	$Trace_ini="";

	$fichier = "../connection/connection.ini";

	$tableauIni = parse_ini_file($fichier);
	if($tableauIni)
	{
		while (list($key, $val) = each($tableauIni)) {

			$GLOBALS[$key]=$val;
		}
		return 0;
	}
	else return 1;

}
function Init_Connection_SCD()
{
	$res=Init_Config();
$option = array('i5_lib'=>'EURO4SCD','i5_naming'=>DB2_I5_NAMING_ON,'i5_commit'=>DB2_I5_TXN_NO_COMMIT);
$DB = db2_connect("IFS", "ACCFICHIER" , "ACCFICHIER" , $option);
if (!$DB) {
$GLOBALS['ConnectError']=db2_conn_errormsg();
return 1;
}
		$GLOBALS['$conn']=$DB;
return 0;
}
function Init_Connection()
{

	/* On charge le fichier de config */
	$res=Init_Config();
	if (!$res)
	{
		$options_connect =array(I5_OPTIONS_INITLIBL => $GLOBALS['Library_ini']);
		$conn= i5_connect($GLOBALS['System_ini'], $GLOBALS['Login_ini'], $GLOBALS['Psswd_ini']);
  $GLOBALS['ConnectError']=i5_errormsg();
		$GLOBALS['$conn']=$conn;
		if (!$conn) return 1;
		else return 0;
	}
	else return 2;
}

function Init_Connection_ADM()
{

	/* On charge le fichier de config */
	$res=Init_Config();
	if (!$res)
	{
		$options_connect =array(I5_OPTIONS_INITLIBL => $GLOBALS['Library_ini_ADM']);
		$conn= i5_connect($GLOBALS['System_ini_ADM'], $GLOBALS['Login_ini_ADM'], $GLOBALS['Psswd_ini_ADM'],$options_connect);
		$GLOBALS['$conn']=$conn;
		if (!$conn) return 1;
		else return 0;
	}
	else return 2;
}
function Debut_Trace($Nom_Fichier)
{
	$Nom_Fichier=strtoupper($Nom_Fichier);
	$Nom_Fichier=$Nom_Fichier;
	$chem=$Nom_Fichier.".log";
	$Var='T_'.$Nom_Fichier;
	if($GLOBALS[$Var]=='1' or $GLOBALS['T_GLOBAL']=='1')
		$fp =  fopen($chem,"a+");
	return $fp; 
}

function Enreg_Trace($Nom_Fichier,$fp,$Trace)
{
	$Nom_Fichier=strtoupper($Nom_Fichier);
	$chem=$Nom_Fichier.".log";
	$Var='T_'.$Nom_Fichier;
	
	
	if($GLOBALS[$Var]=='1' or $GLOBALS['T_GLOBAL']=='1')
		$fp =  fputs($fp,$Trace);
	return; 
}
function Fin_Trace($Nom_Fichier,$fp)
{
	$Nom_Fichier=strtoupper($Nom_Fichier);
	$chem=$Nom_Fichier.".log";
	$Var='T_'.$Nom_Fichier;
	if($GLOBALS[$Var]=='1' or $GLOBALS['T_GLOBAL']=='1')
	fclose($fp);
	return;
}

function SaveFichier($Fich_xml)
{
	$path="/xsmscd/westag/";

$FileName="";
$xml = simplexml_load_string($Fich_xml,"SimpleXmlElement",LIBXML_NOWARNING);
if(!$xml)
{
	$FileName="";
}
else {
	foreach($xml->purchasedata[0]->attributes() as $a => $b) {
		//echo "a=".$a." b=".$b;
		if($a=="purchaseno")
			$FileName=$b;
	}
	
}

if($FileName==""){
	$FileName="Error";
	
}
$Pathfinal=$path.$FileName."_".date("Ymd")."_".date("His").".xml";
$handle = fopen($Pathfinal, "a");
if($handle)
{
fwrite($handle, $Fich_xml);
fclose($handle); 
return "0";
}
else
{
   return "1";
}
}
?>
