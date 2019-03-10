<?php

namespace nuno\jumia;

class PhoneNumbers
{
    private $regexMap;
    private $records;
    public $filters;
    public $limit = 5;

    /**
     * constructor
     * 
     * @param array $records
     * @param array $regexMap
     */ 
    public function __construct($records, $regexMap)
    {
        $this->records = $records;
        $this->regexMap = $regexMap;
    }

    /**
     * thin regex map with country filter
     */ 
    protected function filterCountry()
    {
        // check for active filter
        if (isset($this->filters['filterCountry'])) {
            // find country of filter
            // if not found keep original map
            foreach ($this->regexMap as $regex) {
                // remap just for one country
                if ($regex[0] == $this->filters['filterCountry']) {
                    $this->regexMap = array();
                    $this->regexMap[] = $regex;
                    break;
                }
            }
        }
    }

    /**
     * list numbers
     * 
     * @param array $page
     * @return array
     */
    public function list($page='')
    {
        $results = array();
        // cycle all records
        foreach ($this->records as $record) {
            // parse the number for info
            $info = $this->parsePhone($record['phone']);
            // no info, means a filter occured
            if ($info) {
                // add a result for new list
                $results[] = array(
                    'country' => $info['country'],
                    'state' => $info['state'],
                    'country_code' => $info['country_code'],
                    'phone_number' => $info['phone_number'],
                );
            }
        }
        // apply page filter 
        if ($page) {
            $results = array_slice($results, ($page-1)*$this->limit, $this->limit);
        }
        return $results;
    }

    /** 
     * extract info from phone number
     * 
     * @param string $phoneNumber
     * @return array
     */ 
    private function parsePhone($phoneNumber)
    {
        $result = array();
        $isValid = false;
        // break number into parts for quicker matches
        $parts = explode(' ', $phoneNumber);
        // find our number in the map
        foreach ($this->regexMap as $regex) {        
            // match country code first
            if (preg_match("/\(".preg_replace("/\+/", "", $regex[1])."\)/", $parts[0])) {
                $result['country'] = $regex[0];
                $result['country_code'] = $regex[1];
                // match validity
                if (preg_match("/$regex[2]/", $phoneNumber)) {
                    $isValid = true;
                }
                // we had a match already
                break;
            }
        }
        // for an active country filter skip no matches
        if ($this->filters['filterCountry'] && !isset($result['country'])) {
            return false;
        }
        // apply validity filter
        if (isset($this->filters['filterState'])) {
            // we need valid numbers
            if ($this->filters['filterState']=='OK' && !$isValid) {
                return false;
            }
            // we need invalid numbers
            if ($this->filters['filterState']=='NOK' && $isValid) {
                return false;
            }
        }
        // no match, we set blank country/code
        if (!isset($result['country']) or !isset($result['country_code'])) {
            $result['country'] = $result['countryCode'] = '';
        }
        // finish building our results
        $result['state'] = ($isValid ? 'OK' : 'NOK');
        $result['phone_number'] = $parts[1];
        return $result;
    }


    /**
     * set filter
     * 
     * @param string $filter
     * @param string $filterValue
     */ 
    public function setFilter($filter, $filterValue)
    {
        $this->filters[$filter] = $filterValue;
        // run prefilter on country for performance
        if ($filter == 'filterCountry') {
            $this->filterCountry();
        }
    }

    
}