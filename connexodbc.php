<?php  
$DB =  odbc_connect("AS400", "" , "");
odbc_setoption($DB ,1,  SQL_ATTR_DBC_DEFAULT_LIB , "BDVIN1");
odbc_setoption($DB ,1, SQL_ATTR_COMMIT , 1);
?>