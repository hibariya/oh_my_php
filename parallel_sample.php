<?php

require '_require.php';

_require('parallel');

for($i;$i<100;$i++) {
  $args = array('count' => $i);

  Parallel::process($args, function() {
    $fname = '/tmp/php_parallel_sample';

    touch($fname);

    file_put_contents($fname, $args['count'] . "\n", FILE_APPEND);
  });
}
