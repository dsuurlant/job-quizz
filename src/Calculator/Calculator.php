<?php

declare(strict_types=1);

namespace App\Calculator;

/**
 * From the assignment I would say that we are trying to do a lot of different things here.
 *
 * - If we want to evaluate basic math expressions, we should use https://github.com/langleyfoxall/math_eval
 *
 * - If we want to use PHP's own math functions from a string combined with other variables there is a way to do it
 *   using regular expressions, limiting the called functions to an 'allowed list', and using call_user_func
 *   instead of eval. I would also move this functionality away from the calculator.
 *   The resulting string can then be put back into the safer math_eval.
 *
 * - We should avoid using eval() at all costs; this method is highly unsafe and vulnerable to code injections.
 *   It should never be connected to any kind of user input!
 *
 * - If we want to interpret words like 'contains' we would need more regular expressions, and it may be more 'neat'
 *   to abstract this away from the calculator to some kind of keyword parser.
 *
 * - Architecture-wise I would use middleware to go through the input in passes. In the case of 'level 1337'
 *   we're not even dealing with math, it's a string comparison and ternary operator, so either don't use the calculator
 *   for this, or forward the input to another kind of interpreter.
 */
final class Calculator {

	// TODO: improve me please!
	public function calculate (string $formula, array $values) {
		$formula = preg_replace('/\$(\w+)/', '$values["$1"]', $formula);
		return eval("return $formula;");
	}

}

