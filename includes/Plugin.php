<?php
/**
 * @package   Teral-Translate
 */

namespace Teral\Translate;
use Teral\Translate\Lib\Util\Config;

/**
 * @subpackage Plugin
 */
class Plugin {

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'teral-translate';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Setup instance attributes
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		$this->plugin_version = WP_TERAL_TRANSLATE_VERSION;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return the plugin version.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'teral_translate_config' );
		add_option( 'teral_translate_version', WP_TERAL_TRANSLATE_VERSION);

		//Required
		flush_rewrite_rules();
	}


	private static function shouldLoadLibrary() {

		return true;
	}

	private static function loadLibrary() {
		$url = "//" . TERAL_CDN_HOST . "/js/wordpress.min.js";

		echo "<script src=\"$url\"></script>";
	}

	private static function initializeLibrary($initConfig) {

		echo "<script>";
		echo "Teral.init($initConfig)";
		echo "</script>";

	}

	public static function addFrontendJsWidget() {

		$should_load_library = apply_filters('teral_should_load_library', true);

		if (!$should_load_library) {
			return;
		}

		$config = new Config(get_option( 'teral_translate_config' ));

		$initConfig = [
			'apiKey'	=> $config->getApiKey(),
			'sourceLanguage' => $config->getSourceLanguage(),
			'targetLanguages'	=> $config->getTargetLanguages(),
			'buttonSettins'	=> $config->getButtonSettings()
		];

		$initConfig = json_encode($initConfig, true);

		self::loadLibrary();

		if (empty ($initConfig))
			return;

		$should_init_library = apply_filters('teral_should_init_library', true);

		if (!$should_init_library) {
			return;
		}

		self::initializeLibrary($initConfig);
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		//Required
		flush_rewrite_rules();
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
