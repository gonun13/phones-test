<?php

namespace nuno\jumia;

use SQLite3;
use ArrayIterator;

class Customer implements \IteratorAggregate
{
    private $collection;
    public $sqlitedb;

    // consctructor
    public function __construct($sqlitedb)
    {
        if ($sqlitedb) 
        {
            $this->load($sqlitedb);
        }
    }

    // return iterator
	public function getIterator() {
		return new ArrayIterator($this->collection);
    }

    // load database records
    protected function load($sqlitedb)
    {
        $db = new SQLite3($sqlitedb, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $query = $db->query("SELECT * FROM customer");
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) 
        {
            $this->collection[] = $row;
        }
    }

}