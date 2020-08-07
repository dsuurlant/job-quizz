<?php
declare(strict_types=1);

# Autoload
require __DIR__ .'/../src/bootstrap.php';

use App\Calculator\Calculator;

$calc = new Calculator();
$values = ['a' => 13, 'b' => 37, 'n' => null];

// Level 1
assert(
    $calc->calculate('$a + $b', $values) == 13 + 37,
    'We can add two numbers!'
);

// Level 2
assert(
    $calc->calculate('max($a, $b)', $values) == max(13, 37),
    'We can call functions'
);

// Level 3
assert(
    $calc->calculate('$a + $n', $values) === null,
    'We don\'t calculate with null'
);
assert(
    $calc->calculate('max($a, $n)', $values) === null,
    'We don\'t calculate with null at all'
);
assert(
    $calc->calculate('$a + max($b, $n)', $values) === null,
    'We don\'t calculate with null at all'
);

// Level 4
$error = null;
try {
    $calc->calculate('shell_exec("cat /etc/passwd")', $values);
} catch (Throwable $e) {
    $error = $e;
}
assert(
    $error !== null,
    'Should not be able to read passwords'
);

// Level 1337
// The point is to have a way to express and evaluate this type of logic
// You may require any syntax you like (e.g., if you can make it work with 'str_contains($s'

$values['s'] = 'Woah, sweet';
assert(
    $calc->calculate('$s contains "sweet" ? $a : $b', $values) == $values['a'],
    'Anything is possible!'
);