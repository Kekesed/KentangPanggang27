<?php
Namespace View;
Class Template extends \Prefab {
  function lempar($konten, $page_title = ''){
    \F3::mset(['page'=>['content'=>$konten,'title'=>$page_title]]);
    echo \Template::instance()->render('layout.htm');
  }
}
