<?php

function teral_get_current_language() {

  return preg_match("%\A/([a-z]{2})/%", $_SERVER['REQUEST_URI'], $matches) ? $matches[1] : false;
}

function teral_get_supported_user_language($targetLanguages) {
  $userLanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

  $fistLanguage = null; $supportedLanguages = [];

  foreach ($targetLanguages as $targeLanguage) {
    // code...

    if (substr($userLanguages, 0, 2) == $targeLanguage){
      $fistLanguage = $targeLanguage;
      $supportedLanguages[] = $targeLanguage;
      break;
    }

    if (strpos($userLanguages, $targeLanguage) > -1){
      $supportedLanguages[] = $targeLanguage;
    }

  }

  return $fistLanguage ? $fistLanguage : sizeof($supportedLanguages) > 0 ? $supportedLanguages[0] : null;
}

function teral_get_current_url() {
  return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function teral_remove_lang_from_url($lang, $isValidLang) {
  $_SESSION['tl_lang'] = $isValidLang ? $lang : false;

  if ($isValidLang) {
    $_SERVER['REQUEST_URI'] = preg_replace("%\A/[a-z]{2}/%", '/', $_SERVER['REQUEST_URI'] );
  }
}

function teral_is_valid_language($lang, $targetLanguages) {
  return $lang && in_array($lang, $targetLanguages);
}

function teral_get_url_with_language($url, $language) {

  if ($url && isset($url['host'])) {

    $portPart = isset($url['port']) ? ':' . $url['port'] : '';
    $queryPart = isset($url['query']) ? '?' . $url['query'] : '';
    $fragmentPart = isset($url['fragment']) ? '#' . $url['fragment'] : '';
    $languagePart = $language ? '/' . $language : '' ;

    return $url['scheme'] . '://' . $url['host'] . $portPart . $languagePart . $url['path'] . $queryPart . $fragmentPart;
  }

  return false;
}

function teral_add_href_lang($url, $sourceLanguage, $targetLanguages) {

  echo '<link rel="alternate" href="' . $url . '" hreflang="' . $sourceLanguage . '" />';

  $url = parse_url($url);

  foreach ($targetLanguages as $language) {
    $urlWithLang = teral_get_url_with_language($url, $language);

    if ($urlWithLang) {
      echo '<link rel="alternate" href="' . $urlWithLang . '" hreflang="' . $language . '" />';
    }
  }
}

function teral_is_wordpress_path($path) {
  return substr($path, 0, 4) === "/wp-";
}

?>
