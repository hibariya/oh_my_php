<?php

class Parallel {
  private $args;
  private $block;
  private $tempfile;

  public static function process($args, $_) {
    $parallel = new self($args, debug_backtrace());
    return $parallel->run();
  }

  public function __construct($args, $trace) {
    $this->args = $args;

    $this->load_block($trace);
    $this->write_block();
  }

  public function run() {
    return exec('php -q ' . $this->tempfile . ' > /dev/null & ');
  }

  private function load_block($trace) {
    $caller = array_shift($trace);
    $block_part = join(array_slice(file($caller['file']), 0, $caller['line']));
    preg_match('/Parallel::process\(.*?function\(.*?\n(.+)\}\);$/smi', $block_part, $matches);

    $this->block = $matches[1];
  }

  private function write_block() {
    $f = tempnam('/tmp', 'php_parallel');
    $this->tempfile = $f;

    $serialized = serialize($this->args);

    $arg_block = "\$args = unserialize(<<<EOM\n${serialized}\nEOM\n);";
    $block = $this->block;
    $source = <<<EOM
<?php
  unlink("${f}");
  ${arg_block}
  ${block}
EOM;

    file_put_contents($f, $source);
  }
}

