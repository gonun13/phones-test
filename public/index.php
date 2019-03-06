<?php

namespace nuno\jumia;

use SQLite3;

// check filter requests
if (isset($_REQUEST['filterCountry']) && $_REQUEST['filterCountry'])
{
    $filterCountry = $_REQUEST['filterCountry'];
}
if (isset($_REQUEST['filterValid']) && $_REQUEST['filterValid'])
{
    $filterCountry = $_REQUEST['filterValid'];
}
if (isset($_REQUEST['paginate']) && $_REQUEST['paginate'])
{
    $paginate = $_REQUEST['paginate'];
}

$dbfile = $_SERVER['DOCUMENT_ROOT'] . '/../storage/sample.db';
var_dump($dbfile);
// open db
$db = new SQLite3($dbfile, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
$tablesquery = $db->query("SELECT * FROM customer");

    while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
        var_dump( $table) . '<br />';
    }

/** 
// read db
if ($db = sqlite_open('mysqlitedb', 0666, $sqliteerror)) { 
    sqlite_query($db, 'CREATE TABLE foo (bar varchar(10))');
    sqlite_query($db, "INSERT INTO foo VALUES ('fnord')");
    $result = sqlite_query($db, 'select bar from foo');
    var_dump(sqlite_fetch_array($result)); 
} else {
    die($sqliteerror);
}
*/

// response list
phpinfo();
