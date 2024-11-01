<?php

namespace Teral\Translate\src;

/**
 * Class Requirements
 */
class Requirements {

  protected static $instance;

  private static $permalinkEnabled;
  private static $configEnabled;

  public function __construct() {
    $this->checkPermalink();
    $this->checkConfig();
  }

  /**
  * Checks whether all the requirements are meet
  * @return bool
  */
  public function meet() {
    return $this->isPermalinkEnabled() && $this->isConfigEnabled();
  }

  /**
  * Checks whether permalink are enabled
  * @return bool
  */
  private function checkPermalink() {
    $permalink_structure = get_option('permalink_structure');
    $this->permalinkEnabled = !empty($permalink_structure);
  }

  /**
  * Checks whether the config are enabled
  * @return bool
  */
  private function checkConfig() {
    $teral_config = get_option('teral_translate_config');
    $this->configEnabled = !empty($teral_config) && isset($teral_config['apiKey']) && !empty($teral_config['apiKey']);
  }

  /**
  * Checks whether all the permalink is enabled
  * @return bool
  */
  public function isPermalinkEnabled() {

    return $this->permalinkEnabled;
  }

  /**
  * Checks whether all the permalink is enabled
  * @return bool
  */
  public function isConfigEnabled() {

    return $this->configEnabled;
  }

  /**
  * Return the requirements info as an array
  * @return array
  */
  public function toArray() {
    return [
      'isPermalinkEnabled' => $this->isPermalinkEnabled(),
      'isConfigEnabled' => $this->isConfigEnabled(),
    ];
  }

  public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

?>
