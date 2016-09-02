<HTML>
<HEAD>
<?

ini_set("soap.wsdl_cache_enabled", "0");
require('../connection/connection.inc');

$res=Init_Config();
//echo 'Mail Moderateur'.$GLOBALS['Mail_Moderateur'];
//	exit();

if ($GLOBALS['Blocage']=='1')
{
echo '<img src="../connection/construction.gif" width="25%" align=center></img>';
echo ' <b>Maintenance en cours......</b></br></br>';
echo '<b> Cause:'.$GLOBALS['MsgBlocage'].'</b></br>';
exit();
}
if($res!=0)
{
	/* Erreur dans le fichier  ini */
	echo '<font color=red></br><b> Le Fichier Connection.ini comporte des erreurs ou bien il ne le trouve pas</b></br></font>';
	echo '<font color=red><b> [Serveur distant Inconnu] <br>[Connection au Serveur distant impossible] </b></br></font>';
	echo 'Veuillez contacter l\'Administrateur Web Services distant';


	exit();
}
/*--------------------------------------------------------------------------*/
/* Chemin: Chemin=valeur de l'url à lancer si connection effectué                 */
/*--------------------------------------------------------------------------*/

?>

         
<?
/* ici nous regardons si l'utilisateur a été enregistré précédement et est référencé dans le system */
/* on demande de saisir le Login et le Passwd */
$Variable=$_GET['up_VARIABLE'];
$NBLIG=$_GET['up_NBLIG'];
$Chemin=$_GET['Chemin'];
$Login=$_GET['Login'];
$Psswd=$_GET['Psswd'];
$Portail_local=$_GET['PorLocal'];

$Login_cook=$_COOKIE["EUREKA_LOGIN"];
$Psswd_cook=$_COOKIE["EUREKA_PASSWD"];
$Portail_local_cook=$_COOKIE["PORTAIL_LOCAL"];
//echo 'Portail_local'.$Portail_local;
//echo 'chem='.$Chemin;
//exit();
$autorized='0';
/* Utilisé pour se déconnecté du système Proviens du ListPortail.php */
$Deconnect=$_GET['Deconnect'];
//echo 'Log:'.$Login_cook;
//echo 'Pswd:'.$Psswd_cook;
//echo $Variable;
//echo $NBLIG;
//exit();
if(isset($Deconnect) and $Deconnect=='2')
{
	setcookie("EUREKA_LOGIN", 0, time()-3600*24,"/");
	setcookie("EUREKA_PASSWD", 0, time()-3600*24,"/");
	setcookie("PORTAIL_LOCAL", 0, time()-3600*24,"/");

	echo 'Vous êtes deconnecté';
	echo '<a href=./connection.php?Chemin=../Eurekaprim/ListPortail.php&up_VARIABLE=ListePort> Se Connecter </a>';
	exit();
}
if(isset($Login))
{
	$Login_cook=$Login;
	$Psswd_cook=$Psswd;
	$Portail_local_cook=$Portail_local;
}
//echo 'Log:'.$Login_cook;
//echo 'Pswd:'.$Psswd_cook;
if (isset($Login_cook) and isset($Psswd_cook))
{
	$Login_save=$Login_cook;
	$Psswd_save=$Psswd_cook;
	$Portail_local_save=$Portail_local_cook;

	try {
		$client=new SoapClient('http://'.$GLOBALS['SERVEUR_LOCAL'].':'.$GLOBALS['PORT_WEB'].'/Eurekaprim/Eurekaprim.wsdl',array('encoding'=>'ISO-8859-1'));
	}
	catch (SoapFault $exception)
	{   echo '<font color=red>Une Erreur est survenue, Si Vous voulez vous déconnecter Cliquer <a href="./connection.php?&Deconnect=2" target=_self>ici</a></br></font>';
	echo '<br>Texte Erreur'.$exception; }
	
	try {
		
		$Connex=$client->Connection($Login_cook,$Psswd_cook,$Variable,$GLOBALS['Mail_Administrateur'],$GLOBALS['Mail_Moderateur']);
	}
	catch (Exception $exception)
	{
	 echo '<font color=red>Une Erreur est survenue, Si Vous voulez vous déconnecter Cliquer <a href="./connection.php?&Deconnect=2" target=_self>ici</a></br></font>';
	echo '<br>Texte Erreur'.$exception;
	exit();}
	
	/* On test l'erreur de premier niveau -> Connection au serveur Impossible */
	if($Connex->VALD=='}')
	{
		/* Erreur dans le fichier  ini */
		echo '<font color=red></br><b> Le Fichier Connection.ini comporte des erreurs ou bien il ne le trouve pas</b></br></font>';
		echo '<font color=red><b> [Serveur distant Inconnu] <br>[Connection au Serveur distant impossible] </b></br></font>';
		echo 'Veuillez contacter l\'Administrateur Web Services distant='.$Variable;
		exit();


	}

	//echo '<br>Variable='.$Variable;
	//echo '<br>Serveur='.$GLOBALS['SERVEUR_LOCAL'];
	$VALD=$Connex->VALD;
	//echo 'Vald='.$VALD;
	//echo 'User='.$Connex->USER;
	//exit();
	$User=$Connex->USER;
	$FLG1=$Connex->FLG1;
	$MAIL=$Connex->MAIL;
	$APPL=$Connex->APPL;
	$NDOS=$Connex->NDOS;
	//echo 'NDOS1='.$NDOS.'<br>';
	//echo 'APPL1='.$APPL.'<br>';
	//exit();
	/* en retour du prog RPG */
	/* $VALD='1' => Portail OK
	$VALD='/' => Utilisateur non référencé
	$VALD='-' or 'D' or 'E'=> Portail non autorisé
	$VALD='0' => Portail en Batch */
	/* Pour ListePort on passe toujours */
	//if(($VALD=='-' or $VALD=='?') and $Variable=='ListePort')
	if($Variable=='ListePort' and ($VALD<>'/' and $VALD<>'D' and $VALD<>'E'))
	$VALD='1';
	//echo 'Vald='.$VALD;
	//echo 'User='.$Connex->USER;
	//exit();
	if($VALD=='1' or $VALD=='-')
	{
		//echo '</br>Sauvegarde cookies';
		/* on enregistre les variables de session */
		setCookie("EUREKA_LOGIN",$Login_save,time()+60*60*24*365,"/");
		setCookie("EUREKA_PASSWD",$Psswd_save,time()+60*60*24*365,"/");
		$cres=setCookie("PORTAIL_LOCAL",$Portail_local_save,time()+60*60*24*365,"/");
		//if ($cres) echo'reussi '.$Portail_local_save;
		//else echo 'maj échoué';
		//if($Variable!='ListePort') exit();
		/* je determine si la demande est fait sur le serveur local ou su un serveur distant */
		//$GLOBALS['SERVEUR_WEB']=$GLOBALS['SERVEUR_WIDGET'];

	}
	$autorized=$VALD;
}
//echo 'autorized='.$autorized;

if($autorized!='1')
{
?>

<BODY>
<img src='logo.jpg' ></img>
<FORM>
<?
/*echo '</br>E£LOGI='.$params["E£LOGI"].'</br>';
echo '</br>E£PSWD='.$params["E£PSWD"].'</br>';
echo '</br>E£GJOB='.$params["E£GJOB"].'</br>';
$chaine=$params["E£LOGI"];
//echo 'chaine 4='.$chaine[4]; */
		?>
<? if(isset($Login) and ($VALD=='/'))
{
	echo '<font color=red>Login ou Mot de passe incorrect </font>';

}



if((isset($VALD) and ($VALD=='-' or $VALD=='?' or $VALD=='D' or $VALD=='E')))
{
	echo '<font color=red></br> Non Autorisé à utiliser ce portail</br></font>';
	if($VALD=='?') $Subject='***** Utilisateur Interne non autorisé ******';
	if($VALD=='-') $Subject='***** Utilisateur Web non autorisé ******';
	if($VALD=='D') $Subject='***** Date de Validité du profil non valide  ******';
	if($VALD=='E') $Subject='***** Date de Validité du portail non valide  ******';
	
	echo '<font color=red>'.$Subject.'<br></font>';
	echo '<a href=mailto:'.$GLOBALS['Mail_Administrateur'].'>Demande Acces au portail '.$Variable.'</a>';
	
	$Message='Utilisateur: '.$Login. 'essai d\'accéder au portail '.$Variable;
	mail($MAIL,$Subject,$Message);
}

?>
<?
if($VALD=='/' or (!isset($Login) and !(isset($VALD))))
{
?>

<table>
<tr>
<td>
Identifiant:
</td>
<td>
<INPUT TYPE=text NAME=Login>
</td>
</tr>
<tr>
<td>
Mot de Passe:
</td>
<td>
<INPUT TYPE="password" NAME=Psswd>
</td>
</tr>
<tr>

<td>
Lancement depuis Internet
</td>

<td>
<INPUT TYPE="checkbox" NAME=PorLocal >
</td>
</tr>

<tr>

<td>
</td>

<td>
<INPUT TYPE=submit VALUE="Valider">
</td>
</tr>

<tr>

<td>
<a href=mailto:<? echo $GLOBALS['Mail_Administrateur']; ?>>Je ne connais plus mon Mot de passe</a>
</td>
</tr>
<tr>

<td>
</td>

<td>
<INPUT TYPE=hidden name="Chemin" VALUE=<? echo $Chemin; ?>>
</td>
</tr>
<tr>

<td>
</td>

<td>
<INPUT TYPE=hidden name="up_VARIABLE" VALUE=<? echo $Variable; ?>>
</td>
</tr>

</table>

<?
}
?>
</FORM>
</BODY>
</HTML>
<? } 
else
{
	/*
	try {
		$client=new SoapClient('http://'.$GLOBALS['SERVEUR_WEB'].':'.$GLOBALS['PORT_WEB'].'/Eurekaprim/Eurekaprim.wsdl',array('encoding'=>'ISO-8859-1'));
	}
	catch (SoapFault $exception)
	{   echo '<font color=red>Une Erreur est survenue, Si Vous voulez vous déconnecter Cliquer <a href="./connection.php?&Deconnect=2" target=_self>ici</a></br></font>';
	echo '<br>Texte Erreur'.$exception; 
	}

	try {
		$result=$client->Resoud_Portail($Variable,'1');
	}
	catch (Exception $exception)
	{echo 'Exception Resoud primitive:'.$exception;}
	foreach($result->RESU as $cle=>$valeur)
	{
		//echo $cle.' : '.$valeur.'<br>';
		$TITRE=$valeur;
		//echo $TITRE;

	}
	*/
	//exit();

	if(isset($_GET['libs']))
	{
		$Libs=$_GET['libs'];
		//echo 'Librairies='.$_GET['libs'];

		// Parse gadget URL and emit <script src=...</script> statements into the HTML output.
		// The <script src=...</script> statements will load the libraries passed in via the URL.
		$libraries = split(",", $_GET["libs"]);
		foreach ($libraries as $script) {
			if (preg_match('@^[a-z0-9/._-]+$@i', $script)
			&& !preg_match('@([.][.])|([.]/)|(//)@', $script)) {
				print "<script src='http://www.google.com/ig/f/$script'></script>";
			}
		}


	}
?>


<?
echo $TITRE;
//exit();
print "<script type='text/javascript'> var prefs = new _IG_Prefs(); ";
//print "alert('titre');";
print "prefs.set('TITRE', '$TITRE');";

if ($_GET['refresh']!='2')
{
	//print "window.open ('http://eurekapr:89/connection/connection.php?Chemin=http://eurekapr:89/EurekaPrim/WS_Secu_Portail.php?&up_VARIABLE=$Variable&refresh=2','_self');";
	//exit();
}
//print "window.open ('$Chemin?&up_VARIABLE=$Variable','_self');";
print "</script>";


$chem='Location:'.$Chemin.'?&up_VARIABLE='.$Variable.'&Login='.$Login_cook.'&User='.$User.'&libs='.$Libs.'&up_NBLIG='.$NBLIG.'&up_FLG1='.$FLG1.'&NDOS='.$NDOS.'&APPL='.$APPL;
//echo $chem;
//exit();
header($chem);

}

?>
</HTML>