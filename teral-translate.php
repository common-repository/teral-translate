<?php
/**
 * Teral Translate
 *
 *
 * @package   Teral Translate
 * @author    Teral
 * @license   GPL-3.0
 * @link      https://teral.io
 *
 * @wordpress-plugin
 * Plugin Name:       Teral Translate
 * Plugin URI:        http://wordpress.org/plugins/teral/
 * Description:       Make your website multilingual
 * Version:           0.2.0
 * Author:            https://teral.io
 * Author URI:        https://teral.io
 * Text Domain:       Teral Translate
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 */


namespace Teral\Translate;

use Teral\Translate\Lib\Teral;
use Teral\Translate\Lib\Util\Config;
use Teral\Translate\Lib\Util\Url;
use Teral\Translate\src\Requirements;

use TLSimpleHtmlDom;

require_once  __DIR__ . '/vendor/autoload.php';
require_once  __DIR__ . '/includes/functions.php';


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_TERAL_TRANSLATE_VERSION', '0.2.0' );
define ('TERAL_API_HOST', 'https://app.teral.io');
define ('TERAL_CDN_HOST', 'cdn.teral.io');

/**
 * Autoloader
 *
 * @param string $class The fully-qualified class name.
 * @return void
 *
 *  * @since 1.0.0
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = __NAMESPACE__;

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/includes/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

function init_teral() {

	$requirements = Requirements::get_instance();

	if (!$requirements->meet()) {
		return;
	}

	$config_option = get_option('teral_translate_config');

	$config = new Config($config_option);

	$lang = teral_get_current_language();

	if (!$lang && $config->isAutoRedirect() && !isset($_COOKIE['tl_autoredirect'])) {
		$lang = teral_get_supported_user_language($config->getTargetLanguages());
		$redirectUrl = teral_get_url_with_language(parse_url(teral_get_current_url()), $lang);

		if ($lang) {
			setcookie('tl_autoredirect', $lang, time() + (86400 * 30), "/");

			header("Location: $redirectUrl");
			die();
		}
	}

	$isValidLang = teral_is_valid_language($lang, $config->getTargetLanguages());

	teral_remove_lang_from_url($lang, $isValidLang);

	$fullUrl = teral_get_current_url();

	$url = new Url($fullUrl);

	if ($lang && $isValidLang) {

		require_once(__DIR__ . '/includes/Lib/Util/simple_html_dom.php');

		$teral = new Teral($config, $url, $lang);

		$teral->init();
	}

	add_action('wp_head', function () use ($fullUrl, $config) {
		teral_add_href_lang($fullUrl, $config->getSourceLanguage(), $config->getTargetLanguages());
	});
}

/**
 * Initialize Plugin
 *
 * @since 1.0.0
 */
function init() {
	$wpr = Plugin::get_instance();
	$wpr_shortcode = Shortcode::get_instance();
	$wpr_admin = Admin::get_instance();
	$wpr_rest_admin = Endpoint\Admin::get_instance();

	init_teral();
}

add_action( 'plugins_loaded', 'Teral\\Translate\\init' );

add_action('wp_head', array( 'Teral\\Translate\\Plugin', 'addFrontendJsWidget' ));


/**
 * Register the widget
 *
 * @since 1.0.0
 */
function widget_init() {
	return register_widget( new Widget );
}
add_action( 'widgets_init', 'Teral\\Translate\\widget_init' );


/**
 * Register activation and deactivation hooks
 */
register_activation_hook( __FILE__, array( 'Teral\\Translate\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Teral\\Translate\\Plugin', 'deactivate' ) );
