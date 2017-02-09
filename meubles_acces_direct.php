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

function surligne(champ, erreur)
{
   if(erreur)
      champ.style.backgroundColor = "#fba";
   else
      champ.style.backgroundColor = "";
}

function verifPseudo(champ)
{
   if(champ.value.length < 2 || champ.value.length > 10)
   {
      surligne(champ, true);
      return false;
   }
   else
   {
      surligne(champ, false);
      return true;
   }
}
 
 
 function verifForm(f)
{
   var pseudoOk = verifPseudo(f.VREF);
   
   if(pseudoOk)
      return true;
   else
   {
      alert("Veuillez remplir correctement tous les champs");
      return false;
   }
}
  </script>
   

    <a href="javascript: void ferme_fenetre()">Fermer</a>
    
<?php
    echo '<BR>';
    echo 'Il faut renseigner le n° du dossier !';
    echo '<BR>';
    echo '<form method = "POST" action="confirm_vref.php" target="_self"   onsubmit="return verifForm(this)"  >';
    echo '<input type="text" name="VREF" onblur="verifPseudo(this)" /><br>' ;
    echo '<input type="submit" name="btn_sub" value="Accés>>>">';
    echo "</form>";
 ?>



</body>
</html>
