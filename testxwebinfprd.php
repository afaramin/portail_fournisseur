<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
$start = microtime(true);
$client = new SoapClient('http://95.128.145.120:10010/web/services/XWEBINFPRD/xwebinfprd.wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));
    $response = $client->xwebinfprd(
        array(
          'args0' => array(
            'PART'=> 'EV6221 273',
          ),
        )
    );
//print_r($response);
echo microtime(true) - $start;

?>

