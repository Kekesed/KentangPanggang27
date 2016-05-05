<?php
Namespace Service;
Class Launcher extends \Prefab{
  const
    SETTING_PATH = 'app/settings.ka';

  function LazyLoadSetting(){
    if(!file_exists(self::SETTING_PATH))
      return false;
    try {
      \F3::set('InstaSetting', json_decode(file_get_contents(self::SETTING_PATH), true));
      if(!\F3::exists('InstaSetting.key') || !\F3::exists('InstaSetting.secret'))
        return false;
      if(\F3::devoid('InstaSetting.key') || \F3::devoid('InstaSetting.secret'))
        return false;
      return true;
    } catch(\Exception $e ){
      return false;
    }
  }

  function greet(){
    foreach (['LICENSE','README.md'] as $f)
      if(!is_file($f))
        return false;
    return true;
  }

  function LazySaveSetting(){
    file_put_contents(self::SETTING_PATH, json_encode(\F3::get('InstaSetting')));
  }
}
