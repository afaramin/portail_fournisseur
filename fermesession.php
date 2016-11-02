<HTML>
<HEAD>
<title>Reponse</title>
 <link rel="stylesheet" type="text/css"  href="./monstyle.css">
</HEAD>
<BODY>


 <script language="JavaScript">
    function ferme_fenetre1() {
    opener=self;
    self.close();
    }
    
    function ferme_fenetre2() {
        top.close();
        }

    
  </script>

<?php 


 session_destroy();
 
?>


<a href="javascript: void ferme_fenetre2()">Fermer</a>


</BODY>
</HTML>