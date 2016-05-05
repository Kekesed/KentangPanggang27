<?php
Namespace Control;
Class Page {
  function get_pertama_kali($f3) {
    $x = \Service\Validator::GetCSRF();
    $f3->cftoken=$x;
    if(!\F3::exists('InstaSetting.key') || !\F3::exists('InstaSetting.secret'))
      \View\Template::instance()->lempar('fronts/register.htm', 'Massukan Data');
    else
      $f3->reroute('@root');
  }
  function post_pertama_kali($f3){
    if(!\Service\Validator::ValidateCSRF($f3->get('POST.token'))) {
      \Flash::instance()->addMessage('Session invalid. Try again.', 'error');
      return $this->get_pertama_kali($f3);
    }
    //SaveSetting
    $f3->mset(['InstaSetting'=>['key'=>trim($f3->POST['key']), 'secret'=>trim($f3->POST['secret'])]]);
    \Service\Launcher::instance()->LazySaveSetting();
    $f3->clear('SESSION');
    $f3->reroute('@root');
  }
  function get_jeng($f3){
    echo substr('/',0,3);
  }


  function Helep($f3){
    $alamat = __DIR__ . '/laman_/' . $f3->PARAMS['id'] . '.md';
    if(!is_file($alamat))
      $f3->error(404, 'Bantuan tidak ditemukan. Maaf Bosq.');
    $konten = file_get_contents($alamat);
    $f3->set('bantuan.konten',\Markdown::instance()->convert($konten));
    \View\Template::instance()->lempar('fronts/tolong.htm', 'Bantuan');
  }

  function kentang($f3){
    \View\Template::instance()->lempar('fronts/about.htm', 'Tentang KentangPanggang27');
  }

}
