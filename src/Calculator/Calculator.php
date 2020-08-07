<?php

declare(strict_types=1);

namespace App\Calculator;

final class Calculator {

	// TODO: improve me please!
	public function calculate (string $formula, array $values) {
		$formula = preg_replace('/\$(\w+)/', '$values["$1"]', $formula);
		return eval("return $formula;");
	}

}

