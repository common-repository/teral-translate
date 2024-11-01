<?php

namespace Teral\Translate\Lib;

use Teral\Translate\Lib\Dom\DomParser;
use Teral\Translate\Lib\Request\Translate as Request;

/**
 * Class Teral
 */
class Teral {

   private $config;
   private $currentLagnuage;
   private $url;

   public function __construct ($config, $url, $currentLagnuage) {

       $this->config = $config;
       $this->url = $url;
       $this->currentLagnuage = $currentLagnuage;
   }

   /**
    * @return bool
    */
   public function shouldTranslate() {
       return !$this->isExludedPath() && $this->currentLagnuage && $this->currentLagnuage != $currentLanguage = $this->config->getSourceLanguage();
   }

   public function init() {
       if ($this->shouldTranslate()) {
           ob_start([$this, 'process']);
       }
   }

   /**
    * @return bool
    */
   private function isExludedPath() {

     $isExcludedPath = false;

     if($this->url && $this->url->get()) {

         foreach ($this->config->getExcludePaths() as $excludedPath) {
           $regex = '/^' . preg_quote($excludedPath, '/') . '/';
           if (!empty($excludedPath) && preg_match($regex, $this->url->getPath())) {
             $isExcludedPath = true;
             break;
           }
         }
     }

     return $isExcludedPath;
   }

   public function process($source) {

       if (empty($source))
           return false;

       // simple_html_dom
       $dom = str_get_html(
           $source,
           true,
           true,
           TL_DEFAULT_TARGET_CHARSET,
           false
       );

       //Make sure we have a valid dom
       if (!$dom)
           return $source;

       $domParser = new DomParser();

       $domParser->process($dom, $this->config->getIgnoreClasses());

       $uniqueWords = $domParser->getUniqueWords();

       $request = new Request($this->config->getApiKey(), $this->config->getSourceLanguage(), $this->currentLagnuage, $uniqueWords, $this->url->get());

       $translatedText = $request->process();

       if ($translatedText) {
           $domParser->setTranslation($translatedText);
           $domParser->replaceLinks($this->url->getHost(), $this->currentLagnuage);
           $domParser->updateRoot($this->currentLagnuage);
           $domParser->updateCanonicalLink($this->url, $this->currentLagnuage);

           return $domParser->getSource();
       }

       //If for the translation failed, return the original content
       return $source;

   }
}

?>
