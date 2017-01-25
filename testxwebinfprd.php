<html>
    
<head>
    <meta charset="utf-8">
    <title>test webservice</title>
     <link rel="stylesheet" type="text/css"  href="./css/monstyle.css">
</head>
<body>

    
    
<?php

echo
 
$start = microtime(true);
$client = new SoapClient('http://95.128.145.120:10010/web/services/XWEBINFPRD/xwebinfprd.wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
    $response = $client->xwebinfprd(
        array(
          'args0' => array(
            'PART'=> 'EV6221 273',
          ),
        )
    );
    
print_r($response);
echo microtime(true) - $start;

?>
</body>
</html>
