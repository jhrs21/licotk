<?php

/**
 * @package sfFlashMessagePlugin
 * 
 * @author Artur Rozek
 * @version 2.0.0
 * 
 */
flash_message();
function flash_message()
{
  if (!sfContext::hasInstance()) return;

  //add resources needed
  $response = sfContext::getInstance()->getResponse();
  $user = sfContext::getInstance()->getUser();
  $code = "";
  $html = "";

  //check if jqueryreloaded plugin is activated
  if (sfConfig::has('sf_jquery_web_dir') && sfConfig::has('sf_jquery_core'))
    $response->addJavascript(sfConfig::get('sf_jquery_web_dir'). '/js/'.sfConfig::get('sf_jquery_core'));
  else
    throw new Exception("Theres is no JqueryReloaded plugin !");

  $method = sfConfig::get("app_sf_flash_message_method");
  $delay = sfConfig::get("app_sf_flash_message_delay");

  if ($method=="growl")
  {
    $response->addJavascript(sfConfig::get('app_sf_flash_message_js_dir'). 'jquery.notice.js');
    $response->addStylesheet(sfConfig::get('app_sf_flash_message_css_dir'). 'jquery.notice.css');

    // Flash message creado por Lealtag para personalizar el feedback de los usuarios hacia los afiliados
    if ($user->hasFlash("feedback")){
        $arr = array();
        $q = Doctrine_Query::create()
                ->select('message, created_at')
                ->from('Feedback')
                ->where('user_id=?', 24)
                ->orderBy('created_at DESC')
                ->limit(3);
        $q = $q->fetchArray();

        foreach ($q as $o_id => $objeto) {
            foreach ($objeto as $key => $value) {
                if ($key == 'message')
                    array_push($arr, $value);
            }
        }
        $map_php = json_encode($arr);
      $code = <<<EOF
          var map = $map_php;
          
          $.each(map,function (key,value) {
          jQuery.noticeAdd({
            text: key + ': ' + value,
            stay: true,
            stayTime: $delay,
            type: 'feedback'
          });
        });
EOF;
    }
    // FIN => Flash message creado por Lealtag para personalizar el feedback de los usuarios hacia los afiliados
    if ($user->hasFlash("success"))
      $code = <<<EOF
          $(function () {
          jQuery.noticeAdd({
            text: '{$user->getFlash('success')}',
            stay: false,
            stayTime: $delay,
            type: 'success'
          });
        });
EOF;

    if ($user->hasFlash("notice"))
      $code .= <<<EOF
          $(function () {
          jQuery.noticeAdd({
            text: '{$user->getFlash('notice')}',
            stay: false,
            stayTime: $delay,
            type: 'notice'
          });
        });
EOF;

    $delay *= 2;

    if ($user->hasFlash("warning"))
      $code .= <<<EOF
          $(function () {
          jQuery.noticeAdd({
            text: '{$user->getFlash('warning')}',
            stay: false,
            stayTime: $delay,
            type: 'warning'
          });
        });
EOF;
    if ($user->hasFlash("error"))
      $code .= <<<EOF
          $(function () {
          jQuery.noticeAdd({
            text: '{$user->getFlash('error')}',
            stay: true,
            stayTime: $delay,
            type: 'error'
          });
        });
EOF;

  }
  else if ($method=="pop")
  {
    $response->addJavascript(sfConfig::get('app_sf_flash_message_js_dir'). 'jquery.notifyBar.js');
    $response->addStylesheet(sfConfig::get('app_sf_flash_message_css_dir'). 'flash_message.css');

    $html = <<<EOF
         <div id="flashMessageSuccess" class="flashMessageSuccess"></div>
         <div id="flashMessageNotice" class="flashMessageNotice"></div>
         <div id="flashMessageWarning" class="flashMessageWarning"></div>
         <div id="flashMessageError" class="flashMessageError"></div>
          <div id="flashMessageError" class="flashMessageFeedback"></div>
EOF;

  //activate it
    if ($user->hasFlash("success"))
      $code = <<<EOF
          $(function () {
          $.notifyBar({
            html: '{$user->getFlash('success')}',
            delay: $delay,
            animationSpeed: "normal",
            jqObject: $('#flashMessageSuccess')
          });
        });
EOF;
    if ($user->hasFlash("notice"))
      $code .= <<<EOF
          $(function () {
          $.notifyBar({
            html: '{$user->getFlash('notice')}',
            delay: $delay,
            animationSpeed: "normal",
            jqObject: $('#flashMessageNotice')
          });
        });
EOF;

    $delay *= 2;

    if ($user->hasFlash("warning"))
      $code .= <<<EOF
          $(function () {
          $.notifyBar({
            html: '{$user->getFlash('warning')}',
            delay: $delay,
            animationSpeed: "normal",
            jqObject: $('#flashMessageWarning')
          });
        });
EOF;

    if ($user->hasFlash("error"))
      $code .= <<<EOF
          $(function () {
          $.notifyBar({
            html: '{$user->getFlash('error')}',
            delay: $delay,
            animationSpeed: "normal",
            jqObject: $('#flashMessageError')
          });
        });
EOF;
  }
  else
    throw new Exception("Display method not recognized !");


  if ($code)
  {
    $js=javascript_tag($code);
    echo $html."\n".$js;
  }
}