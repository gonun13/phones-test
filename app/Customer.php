<?php

namespace nuno\jumia;

use SQLite3;
use ArrayIterator;

class Customer implements \IteratorAggregate
{
    private $collection;
    public $sqlitedb;

    /**
     * constructor
     * @param string $sqlitedb
     */
    public function __construct($sqlitedb)
    {
        $this->sqlitedb = $sqlitedb;
        $this->load();
    }

    /**  
     * return iterator
     */
    public function getIterator() 
    {
		return new ArrayIterator($this->collection);
    }

    /**
     * load database data
     * @param string $sqlitedb
     */ 
    protected function load()
    {
        $db = new SQLite3($this->sqlitedb, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $query = $db->query("SELECT * FROM customer");
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $this->collection[] = $row;
        }
    }

}