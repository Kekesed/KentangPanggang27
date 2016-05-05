<?php
Namespace Control;
Class Root {
  function getinsta(){
    return new \Instagram\Instagram([
      'apiKey'=>\F3::get('InstaSetting.key'),
      'apiSecret'=>\F3::get('InstaSetting.secret'),
      'apiCallback'=>( (empty(\F3::get('SERVER')['HTTPS'])?'http':'https') . '://' . \F3::get('SERVER.HTTP_HOST') . \F3::alias('callback'))
    ]);
  }
  function index($f3){
    //check apa wis login apa durung.
    if(\F3::exists('GET.revoke')){
      $f3->clear('SESSION');
      $f3->reroute('@root');
    }
    if(\F3::exists('SESSION.access_token')) {
      \View\Template::instance()->lempar('fronts/index.htm', 'KentangPanggang27-Index');
    } else {
      $insta= $this->getinsta();
      $f3->set('form.oauthurl', $insta->getLoginUrl(['basic','relationships']));
      \View\Template::instance()->lempar('fronts/oauth.htm', 'KentangPanggang27-Index');
    }
  }
  function callbacknya($f3){
    if(!$f3->exists('GET.code'))
      $f3->reroute('@root');
    $insta = $this->getinsta();

    $hasil = $insta->getOAuthToken($f3->get('GET.code'));
    $f3->set('SESSION.access_token', $hasil->access_token);
    $f3->set('SESSION.user', $hasil->user);

    $f3->reroute('@root');
  }
}
