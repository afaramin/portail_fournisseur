<HTML>
<HEAD>
<title> Welcome </title>
 <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</HEAD>

<BODY>

<script>
var qui;
function chargemenu(qui) 

{	if (qui == 'KN'  ){
	parent.frames["menu"].window.location = "menu_meubles.html" ;}
else {
	if (qui == 'CANCIO' ||  qui == 'PERFECTA'){
		parent.frames["menu"].window.location = "menu_confirm.html" ;}
	else {
		parent.frames["menu"].window.location = "menu.html" ;}}
}

</script>


<h1>Bienvenue sur le site LUISINA <br>		</h1>

 <?PHP 

if (isset($_POST['CodeUser'])  and !empty($_POST['MotPasse']))
 {
 $CodeUser = strtoupper($_POST['CodeUser']);
 $MotPasse = strtoupper($_POST['MotPasse']);
 session_start();
 $_SESSION['CodeUser'] = $CodeUser;
 $_SESSION['MotPasse'] = $MotPasse ;
 
  

 // la connexion � la BDD
 include("connexDB2.php");

 	if (db2_conn_errormsg($DB) == '')
 	{
		$session_id = session_id();
		echo  "Votre Numero de session &nbsp;" . $session_id;
		
	 	$requet = "SELECT P3EAMI , P3EAMA , P3LIB  , P3EMAIL , P3TARIF  , P3EAMIV , P3EAMAV , P3NFOU FROM  SPECSCD/PLUSER where P3USR = '" . $CodeUser . "'";
	 
		$result = db2_exec($DB, $requet);

			if (!$result){
			echo "<BR>";
			echo "�a va pas du tout !" . db2_conn_errormsg($DB);
			}else
			{
				$lignes = db2_fetch_assoc($result);
				$_SESSION['P3LIB']  	= $lignes['P3LIB'];
 				$_SESSION['P3EAMI'] 	= $lignes['P3EAMI'];
 				$_SESSION['P3EAMA'] 	= $lignes['P3EAMA'];
 				$_SESSION['P3EMAIL'] 	= $lignes['P3EMAIL'];
 				$_SESSION['P3TARIF']	= $lignes['P3TARIF'];
 				$_SESSION['P3EAMIV'] 	= $lignes['P3EAMIV'];
 				$_SESSION['P3EAMAV'] 	= $lignes['P3EAMAV'];
			    $_SESSION['P3NFOU']     = $lignes['P3NFOU'];       
 				
 				
 					
 				echo "<BR>";
				echo "Bienvenue " . $lignes['P3LIB'];
				 
			}	
		
	 }
 	else
 	{ echo  "Votre connexion � la BDD ne focntionne pas &nbsp;" . db2_conn_errormsg($DB); }
  }
  else
  {  	echo  "Votre identification ne convient pas  &nbsp;" . $_POST['CodeUser'];  }
  
 
  if  ($CodeUser == 'KN') { ?> 
  	<SCRIPT > chargemenu('KN'); </SCRIPT>
	<?php }
	else { 
	    if  ($CodeUser == 'VIMENS'  || $CodeUser == 'PERFECTA') { ?>
	    <SCRIPT > chargemenu('VIMENS'); </SCRIPT>
    	<?php }
    	else {
	       ?>
	       <SCRIPT > chargemenu('AF'); </SCRIPT>
	       <?php }
	}
	
	 // code PHP
?>
</BODY>
</HTML>
