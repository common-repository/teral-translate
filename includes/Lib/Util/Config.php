<?php

namespace Teral\Translate\Lib\Util;

/**
 * Class config
 */
class Config
{

    private $apiKey;

    private $sourceLanguage;

    private $targeLanguages;

    private $excludePaths;

    private $ignoreClasses;

    private $buttonSettings;

    private $autoRedirect;

    public function __construct($config) {

        $this->apiKey = $config['apiKey'];
        $this->sourceLanguage = $config['sourceLanguage'];
        $this->targeLanguages = isset($config['targetLanguages']) ?  $config['targetLanguages'] : [];

        $this->excludePaths = isset($config['excludePaths']) ? $config['excludePaths'] : [];
        $this->ignoreClasses = isset($config['ignoreClasses']) ? $config['ignoreClasses'] : [];

        $this->buttonSettings = isset($config['buttonSettings']) ? $config['buttonSettings'] : [];
        $this->autoRedirect = isset($config['autoRedirect']) ? $config['autoRedirect'] : false;
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getSourceLanguage() {
        return $this->sourceLanguage;
    }

    /**
     * @return array
     */
    public function getTargetLanguages() {
        return $this->targeLanguages;
    }

    /**
     * @return array
     */
    public function getExcludePaths() {
        return $this->excludePaths;
    }

    /**
     * @return array
     */
    public function getIgnoreClasses() {
        return $this->ignoreClasses;
    }

    /**
     * @return array
     */
    public function getButtonSettings() {
        return $this->buttonSettings;
    }

    /**
     * @return bool
     */
    public function isAutoRedirect() {
        return $this->autoRedirect;
    }
}
