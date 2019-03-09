<?php

namespace nuno\jumia;

require('../app/Customer.php');

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

// build regex map
$regexMap = array(
    array('Cameroon', '+237', '\(237\)\ ?[2368]\d{7,8}$'),
    array('Ethiopia','+251', '\(251\)\ ?[1-59]\d{8}$'),
    array('Morocco', '+212', '\(212\)\ ?[5-9]\d{8}$'),
    array('Mozambique', '+258', '\(258\)\ ?[28]\d{7,8}$'),
    array('Uganda', '+256', '\(256\)\ ?\d{9}$'),
);

// read db
$dbfile = $_SERVER['DOCUMENT_ROOT'] . '/../storage/sample.db';

// open db
/*
$db = new SQLite3($dbfile, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
$tablesquery = $db->query("SELECT * FROM customer");
*/
$records = new Customer($dbfile);


// build results
$results = array();
foreach ($records as $table) {
    $parts = explode(' ', $table['phone']);
    $country = $country_code = $valid = false;
    foreach ($regexMap as $regex)
    {
        // skip unmatched filter
        if (isset($filterCountry) && $filterCountry != $regex[0]) continue;        
        // check for country code first
        if (preg_match("/\(".preg_replace("/\+/", "", $regex[1])."\)/", $parts[0]))
        {
            $country = $regex[0];
            $country_code = $regex[1];
        }
        // check for valid number
        if (preg_match("/$regex[2]/", $table['phone']))
        {
            $results['valid'][] = array(
                'country' => $country,
                'state' => 'OK',
                'country_code' => $country_code,
                'phone_number' => $parts[1],
            );
            $valid = true;
        }
    }
    if (!$valid && isset($country) && $country && isset($country_code) && $country_code)
    {
        $results['invalid'][] = array(
            'country' => $country,
            'state' => 'NOK',
            'country_code' => $country_code,
            'phone_number' => $parts[1],
        );
    }
}
var_dump($results);
