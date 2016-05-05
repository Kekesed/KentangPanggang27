<?php
Namespace Control;
Class tdown {
  function beforeroute($f3){
    if(!\Service\Launcher::instance()->LazyLoadSetting())
      $f3->reroute('@root');
  }
  function getinsta(){
    $insta = new \Instagram\Instagram([
      'apiKey'=>\F3::get('InstaSetting.key'),
      'apiSecret'=>\F3::get('InstaSetting.secret'),
      'apiCallback'=>( (empty(\F3::get('SERVER')['HTTPS'])?'http':'https') . '://' . \F3::get('SERVER.HTTP_HOST') . \F3::alias('callback'))
    ]);
    $insta->setAccessToken($f3->SESSION['access_token']);
    return $insta;
  }
  function get_index($f3){
    $f3->set('form.token', \Service\Validator::GetCSRF());
    $f3->clear('SESSION.tdown');
    \View\Template::instance()->lempar('app/tdown/tag_loader.htm', 'Tag Downloader');
  }
  function post_index($f3){
    if(!\Service\Validator::ValidateCSRF($f3->get('POST.token'))) {
      \Flash::instance()->addMessage('Session Invalid. Try again plz.', 'error');
      $this->get_index($f3);
      return;
    }
    $insta = $this->getinsta();
    $res = $insta->getTag(trim($f3->POST['tag']));
    $f3->set('tdown.hasil', $res->data->media_count);
    $f3->set('SESSION.tdown.recent', trim($f3->POST['tag']));
    \View\Template::instance()->lempar('app/tdown/tag_execute.htm', 'Tag Downloader');
  }
  function post_gaplok($f3){
    set_time_limit(0);
    $insta = $this->getinsta();
    if($f3->exists("SESSION.tdown.link")){
      if(empty($f3->get('SESSION.tdown.link')->pagination->next_url)) {
        header('Content-type: application/json');
        echo json_encode(['status'=>'bad', 'total'=>0]);
        return ;
      }
      $res = json_decode(file_get_contents($f3->get('SESSION.tdown.link')->pagination->next_url));
    } else {
      $res = $insta->getTagMedia($f3->get('SESSION.tdown.recent'));
      //bikin folder
      $folder = $f3->get('SISTEM.tdown.penadah') . $f3->get('SESSION.tdown.recent');
      if(!is_dir($folder)) {
        mkdir($folder);
      }
      $f3->set('SESSION.tdown.folder', $folder);
    }
    $bang=0;
    try {
      $f3->set('SESSION.tdown.link', $res);
      foreach($res->data as $r){
          if($r->type == 'image') {
          $folder = $f3->SESSION['tdown']['folder'];
          $namaImage = $r->id . "-" . $r->user->username;
          file_put_contents($folder . "/" . $namaImage . '.jpg', file_get_contents($r->images->standard_resolution->url));
          $bang++;
        }
      }
      header('Content-type: application/json');
      echo json_encode(['status'=>'ok', 'total'=>$bang]);
    } catch(\Exception $e){
      header('Content-type: application/json');
      echo json_encode(['status'=>'bad', 'total'=>$bang]);
    }
  }
  function post_packup($f3){
    //paking up...
    try {
    $pack = new \ZipArchive();
    $ret = $pack->open($f3->get('SISTEM.tdown.penadah') . $f3->get('SESSION.tdown.recent') . ".zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    if($ret !==true) {
      header('Content-type: application/json');
      echo json_encode(['status'=>'bad', 'detail'=>'Unable to create file. Have you CHMOD the folder?']);
      return;
    }
    $pack->addPattern('/.(jpg)$/', $f3->get('SISTEM.tdown.penadah') . $f3->get('SESSION.tdown.recent'),array('add_path'=>$f3->get('SESSION.tdown.recent') . '/' ,'remove_all_path'=>true));
    $pack->close();
    $f3->SESSION['tdown']['zipfile'] = $f3->get('SISTEM.tdown.penadah') . $f3->get('SESSION.tdown.recent') . ".zip";
    } catch(\Exception $e) {
      header('Content-type: application/json');
      echo json_encode(['status'=>'bad', 'detail'=>$e->getMessage()]);
      return;
    }
    header('Content-type: application/json');
    echo json_encode(['status'=>'ok', 'id'=>md5($f3->get('SESSION.tdown.recent') . time()), 'path'=>$f3->get('SISTEM.tdown.penadah') . $f3->get('SESSION.tdown.recent') . ".zip"]);
  }
  function get_file($f3){
    if($f3->devoid('SESSION.tdown.zipfile'))
      return $f3->error(404);
    if(!is_file($f3->get('SESSION.tdown.zipfile')))
      return $f3->error(500);
    //dah oke,
    return \Web::instance()->send($f3->get('SESSION.tdown.zipfile'));
  }
  function get_jinx($f3){
    header('Content-type: text/plain');
    var_dump($f3->get('SESSION.tdown'));
  }
}
