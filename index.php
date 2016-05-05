<?php
/*
          _ _,---._
       ,-','       `-.___
      /-;'               `._
     /\/          ._   _,'o \
    ( /\       _,--'\,','"`. )
     |\      ,'o     \'    //\
     |      \        /   ,--'""`-.
     :       \_    _/ ,-'         `-._
      \        `--'  /                )
       `.  \`._    ,'     ________,','
         .--`     ,'  ,--` __\___,;'
          \`.,-- ,' ,`_)--'  /`.,'                 CRAFTED WITH AWESOMENESS BY:
           \( ;  | | )      (`-/                _        _                      _
             `--'| |)       |-/                | |      | |                    | |
               | | |        | |                | |      | | COPYRIGHT (c) 2016 | |
               | | |,.,-.   | |_               | | _____| | _____  ___  ___  __| |
               | `./ /   )---`  )              | |/ / _ \ |/ / _ \/ __|/ _ \/ _` |
              _|  /    ,',   ,-'               |   <  __/   <  __/\__ \  __/ (_| |
     -hrr-   ,'|_(    /-<._,' |--,             |_|\_\___|_|\_\___||___/\___|\__,_|
             |    `--'---.     \/ \
             |          / \    /\  \           https://www.kekesed.id/
           ,-^---._     |  \  /  \  \          iam@kekesed.id
        ,-'        \----'   \/    \--`.
       /            \              \   \
*/
$_waktu = microtime();
$playlist = array('app/f3/base.php', 'app/config.php', 'app/app.php');
for($_p = 0; $_p<count($playlist); $_p++)
  require_once($playlist[$_p]);
Falsum\Run::handler();
\Middleware::instance()->run(); //we've settinged a middleware, be4.
\F3::run();
