<html>
<head>
<title>Test SOA SCD</title>
</head>
<body>
<h2>Visu du client                  </h2>

 
<?php
try {
   $client=new SoapClient("http://192.168.35.3:10088/SCD/SOA_Eureka_SCD.wsdl");
   }
   catch (SoapFault $exception)
   {
    echo $exception->getMessage();
       die();
     }


    try {
   $result=$client->Get_Customer_Informations(" "," ","05060001");
  }
  catch (Exception $exception)
  {

  echo $exception->getMessage();
       die();
    }

    echo 'Raison sociale:'.$result->CLIENT_RSSC.'</br>';
    echo 'dresse 1:'.$result->CLIENT_ADR1.'</br>';
    echo 'Adresse 2:'.$result->CLIENT_ADR2.'</br>';
    echo 'Code Postal'.$result->CLIENT_CPOS.'</br>';
    echo 'Ville:'.$result->CLIENT_VILL.'</br>';
?>

 
</body>
</html>
