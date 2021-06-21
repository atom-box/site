<?php
// ABANDON THIS       MOVING TO MONO FUNCTION AT addLinkTodb
// ABANDON THIS       MOVING TO MONO FUNCTION AT addLinkTodb
// ABANDON THIS       MOVING TO MONO FUNCTION AT addLinkTodb
// ABANDON THIS       MOVING TO MONO FUNCTION AT addLinkTodb

ini_set("display_errors", 1);
error_reporting(E_ALL);

// require_once ('./core/helpers/dbTransaction.php');
require_once('./core/classes/WebAddress.php');  
require_once('./core/config/dbconfig.inc.php');


/*
0. make an URL memory object.then use it in step b
a. FORM GIVES YOU LONG URL ==>
b. SQL LOOKUP, USING LONG URL GIVES YOU SHORT ==>
c. WRITE BOTH TO FLATFILE
*/


// next actions (0) and (a)
// next actions (0) and (a)
// next actions (0) and (a)
// next actions (0) and (a)
// follow these pseudocodes by imitating OTHER ADD FILE
// "IF GIVEN THE LONG URL, GET FROM SQL THE SHORT URL
$user = USER;
$password = SECRET;
$database = NAMEOFDATABASE;  
$host = 'localhost';

$address = new WebAddress( $_POST['longurl']);
$longurl    = $address->getLong();

try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);  
    $pdoObject = $db->query("
      select short 
        from links where 
    ");
    $ascending = $pdoObject->fetchAll();
} catch (PDOException $e) {
    print "Whoa, error!: " . $e->getMessage() . "<br/>";
}  