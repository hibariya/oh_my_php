<?php

# _require: Useful require

function _require($file) {
  $dirname = dirname($file);

  require_once $dirname . '/' . basename($file, '.*') . '.php';
}

