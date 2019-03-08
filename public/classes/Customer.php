<?php

namespace nuno\jumia;

use SQLite3;

class Customer
{
    public 
}

// open db
$db = new SQLite3($dbfile, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
$tablesquery = $db->query("SELECT * FROM customer");

// build results
$results = array();
while ($table = $tablesquery->fetchArray(SQLITE3_ASSOC)) {
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
