<?php

$fib = function () {
  static $a;
  static $b;

  if ($a === null && $b === null) {
    $a = 0;
    $b = 1;
  }

  $a_was = $a;
  $a = $b;
  $b = $a_was + $b;

  return $a;
};

$result = array();

for($i; $i < 10; $i++) {
  array_push($result, $fib());
}

var_dump($result);
