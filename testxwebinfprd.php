<html>
    
<head>
    <meta charset="utf-8">
    <title>test client  webservice</title>
     <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</head>
<body>
    
    <script language="JavaScript">
   
    
    function ferme_fenetre() {
        top.close();
        }

    
  </script>
   

    <a href="javascript: void ferme_fenetre()">Fermer</a>
    
<?php

 if(isset($_POST['btn_sub']))
{
 
//$start = microtime(true);
//$client = new SoapClient('http://95.128.145.120:10010/web/services/XWEBINFPRD/xwebinfprd.wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
$critere = $_POST["nom"]; 
echo $critere;

$client = new SoapClient('http://95.128.145.120:10010/web/services/XWEBINFPRD/xwebinfprd.wsdl');


$err= $client->getError();
echo $err;
if($err) { die('erreur de connexion au service: '.$err);
}
else {
    $param= array("args0" => array("PART" => $critere));
    $response = $client->xwebinfprd($param);
    $err= $client->getError();
    if($err) die('erreur d\'execution du service: '.$err);
        
     echo $response ;
             
    // echo microtime(true) - $start;
    echo 'test  fin ';
    }
}

else {
 echo'<form method="post" action="testxwebinfprd.php">
<label for="article">Nom: </label>
<input type="text" name="nom"><br>';
echo $response ;
echo'<input type="submit" name="btn_sub" value="Envoyer">
</form>'; 

 }


?>



</body>
</html>
