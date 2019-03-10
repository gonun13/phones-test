<?php

namespace nuno\jumia;

require('../app/Customer.php');
require('../app/PhoneNumbers.php');

// check filter requests
$filterCountry = ''; 
$filterState = '';
$filterPage = '';
if (isset($_REQUEST['filterCountry']) && $_REQUEST['filterCountry']) {
    $filterCountry = $_REQUEST['filterCountry'];
}
if (isset($_REQUEST['filterState']) && $_REQUEST['filterState']) {
    $filterState = $_REQUEST['filterState'];
}
if (isset($_REQUEST['filterPage']) && $_REQUEST['filterPage']) {
    $filterPage = $_REQUEST['filterPage'];
}

// set regex map
$regexMap = array(
    array('Cameroon', '+237', '\(237\)\ ?[2368]\d{7,8}$'),
    array('Ethiopia','+251', '\(251\)\ ?[1-59]\d{8}$'),
    array('Morocco', '+212', '\(212\)\ ?[5-9]\d{8}$'),
    array('Mozambique', '+258', '\(258\)\ ?[28]\d{7,8}$'),
    array('Uganda', '+256', '\(256\)\ ?\d{9}$'),
);

// set db
$dbfile = $_SERVER['DOCUMENT_ROOT'] . '/../storage/sample.db';
// read db
$records = new Customer($dbfile);

// get results
$numbers = new PhoneNumbers($records, $regexMap);
$numbers->setFilter('filterCountry', $filterCountry);
$numbers->setFilter('filterState', $filterState);
$results = $numbers->list($filterPage);

// return json for frontend use
header("Content-type:application/json");
echo json_encode($results);