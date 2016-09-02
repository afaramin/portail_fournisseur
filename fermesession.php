<HTML>
<HEAD>
<title>Reponse</title>
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