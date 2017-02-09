<html>
    
<head>
    <meta charset="utf-8">
    <title>Acces direct dossier </title>
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

 if(isset($_POST['btn_sub']) and isset($_post('NDOS')))
{

confirm();
}
else
{
   echo 'Il faut renseigner le n° du dossier !';

    
    echo '<form method = "POST" action="meubles_acces_direct.php" target="_self">';
		//echo '<input type="hidden" name="NDOS"  value ="' . $lignes ['NDOS'] . '">';
		echo '<input type="text" name="NDOS"><br>' ;
                echo '<input type="submit" name="btn_sub" value="Accés>>>">';
		echo "</form>";

 }


?>



</body>
</html>
