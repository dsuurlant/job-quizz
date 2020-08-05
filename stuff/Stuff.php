<?php

class Stuff {
	
	protected $data;
	
	protected function __construct (array $data) {
		$this->data = $data;
	}
	
	protected static function createFromTuple (array $tuple): self {
		return new self($tuple);
	}
	
	public static function load (int $id): self {
		$tuple = current(DB::getInstance()->query(
			"SELECT * FROM stuff WHERE id = $id"	// what could possibly go wrong?
		));
		
		if (!$tuple) {
			throw new Exception('Uh oh!');
		}
		
		return self::createFromTuple($tuple);
	}

	/**
	 * @return self[]
	 */
	public static function loadAll (): array {
		return array_map(
			[self::class, 'createFromTuple'],
			DB::getInstance()->query("SELECT * FROM stuff")
		);
	}
	
}

/*
 * Stuff:loadAll sure consumes a lot of memory if the table gets big.
 * Please implement a way to loop over tuples that doesn't load everything at once
 */
foreach (Stuff::oneByOne() as $stuff) {
	// 
}
