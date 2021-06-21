<?php
// in browser, show errors if any TODO TODO
ini_set("display_errors", 1);
error_reporting(E_ALL);
 
require_once ('./core/helpers/dbTransaction.php');
require_once('./core/config/dbconfig.inc.php');
require_once('./core/classes/WebAddress.php'); 
require_once('./core/classes/Writer.php'); 
require_once 'core/config/linksFlatFileConfig.php';

/*
Adds new url info to two places: DB and FLATFILE-for-Apache
*/
$untaintedInput = htmlentities($_POST['longurl']);
$address = new WebAddress($untaintedInput);
$longurl    = $address->getLong();
do {
  $address->shortify();
} while ($address->notUnique());
$short      = $address->getShort();

// FILEWRITING: write short, long to flat file
// this file will be the redirect map for Apache
$handle = new Writer(APACHE_FLATFILE);
$line = $short . ' ' . $longurl . PHP_EOL;
$fileWriteSuccess = $handle->appendLine($line);

// QUERY 1: sessions table
$transaction = new DBTransaction();
$sessionQuery = 'insert into sessions (user_id, date) values (:user_id, :date)';
$sessionValues = 
[
    'user_id'     => 999 ,
    'date'        => date('Y-m-d H:i:s', time()),
];

// save the session, THEN ASK THE SQL WHICH SERIAL ID WAS IT?
// (this works because last_id is the serialized sql column)
$transaction->insertQuery($sessionQuery, $sessionValues);
$session_id = $transaction->last_insert_id;
if (!$session_id) {
    echo "todo todo todo";
    var_dump($transaction);
    die;
}

//    QUERY 2: links table
$linkQuery = "insert into links (session_id, longurl, short) values (:session_id , :longurl , :short  )";
$linkValues = 
[
    'longurl' =>  $longurl,
    'short' =>  $short,
    'session_id' => $session_id,
];

//  now that you know that serial id, write the linkl
$link_id = $transaction->insertQuery($linkQuery, $linkValues);
$transaction->startTransaction();
$result = $transaction->submitTransaction();
