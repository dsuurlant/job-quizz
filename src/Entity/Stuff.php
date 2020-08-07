<?php

declare(strict_types=1);

namespace App\Entity;

final class Stuff
{
	private array $data;

    private function __construct (array $data)
    {
		$this->data = $data;
	}
	
	public static function createFromTuple (array $tuple): self
    {
		return new self($tuple);
	}
}
