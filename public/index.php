<?php

namespace nuno\jumia;

require('../app/Customer.php');
require('../app/PhoneNumbers.php');

// check filter requests
if (isset($_REQUEST['filterCountry']) && $_REQUEST['filterCountry'])
{
    $filterCountry = $_REQUEST['filterCountry'];
}
if (isset($_REQUEST['filterState']) && $_REQUEST['filterState'])
{
    $filterState = $_REQUEST['filterState'];
}
if (isset($_REQUEST['page']) && $_REQUEST['page'])
{
    $page = $_REQUEST['page'];
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
$numbers = new PhoneNumbers($regexMap);
$numbers->setFilter('filterCountry', $filterCountry);
$numbers->setFilter('filterState', $filterState);
$results = $numbers->list($records);

var_dump($results);