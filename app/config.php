<?php
\F3::mset([
  'DEBUG'=>3,
  "UI"=>__DIR__ . "/UI/",
  "AUTOLOAD" => __DIR__ . "/;" . __DIR__ . "/Lib/;" . __DIR__ . "/f3/burger/",
  "TEMP" => __DIR__ . "/tmp/",
  "CACHE"=> __DIR__ . "/cache/",
  "SISTEM"=>['tdown'=>['penadah'=> __DIR__ . '/app/__donlotan/tdown/']]
]);
