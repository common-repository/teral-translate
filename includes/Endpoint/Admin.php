<?php
/**
* @package   Teral-Translate
*/

namespace Teral\Translate\Endpoint;
use Teral\Translate;

/**
* @subpackage REST_Controller
*/
class Admin {
	/**
	* Instance of this class.
	*
	* @since    0.8.1
	*
	* @var      object
	*/
	protected static $instance = null;

	/**
	* Initialize the plugin by setting localization and loading public scripts
	* and styles.
	*
	* @since     0.8.1
	*/
	private function __construct() {
		$plugin = Translate\Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}

	/**
	* Set up WordPress hooks and filters
	*
	* @return void
	*/
	public function do_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	* Return an instance of this class.
	*
	* @since     0.8.1
	*
	* @return    object    A single instance of this class.
	*/
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

	/**
	* Register the routes for the objects of the controller.
	*/
	public function register_routes() {
		$version = '1';
		$namespace = $this->plugin_slug . '/v' . $version;
		$endpoint = '/admin/';

		register_rest_route( $namespace, $endpoint, array(
			array(
				'methods'               => \WP_REST_Server::READABLE,
				'callback'              => array( $this, 'get_api_key' ),
				'permission_callback'   => array( $this, 'admin_permissions_check' ),
				'args'                  => array(),
			),
		) );

		register_rest_route( $namespace, $endpoint, array(
			array(
				'methods'               => \WP_REST_Server::CREATABLE,
				'callback'              => array( $this, 'update_api_key' ),
				'permission_callback'   => array( $this, 'admin_permissions_check' ),
				'args'                  => $this->get_api_args()
			),
		) );

		register_rest_route( $namespace, $endpoint, array(
			array(
				'methods'               => \WP_REST_Server::EDITABLE,
				'callback'              => array( $this, 'update_api_key' ),
				'permission_callback'   => array( $this, 'admin_permissions_check' ),
				'args'                  => $this->get_api_args()
			),
		) );

		register_rest_route( $namespace, $endpoint, array(
			array(
				'methods'               => \WP_REST_Server::DELETABLE,
				'callback'              => array( $this, 'delete_api_key' ),
				'permission_callback'   => array( $this, 'admin_permissions_check' ),
				'args'                  => array(),
			),
		) );

	}

	/**
	* Get Example
	*
	* @param WP_REST_Request $request Full data about the request.
	* @return WP_Error|WP_REST_Request
	*/
	public function get_api_key( $request ) {
		$config = get_option( 'teral_translate_config' );

		$sync = (bool) $request->get_param( 'sync' );
		$apiKey = $request->get_param( 'apiKey' );

		//$config = json_decode($config, true);



		// Don't return false if there is no option
		if ( !$sync && empty($apiKey) && (!$config || !isset($config['apiKey']) || empty($config['apiKey']))) {
			return new \WP_REST_Response( array(
				'success' => true,
				'value' => ''
			), 200 );
		}

		if ($this->isValidApiKey($apiKey)) {
			//Get the project details
			$response = $this->teral_get_api_request("/api/integration/wordpress?apiKey=" . $apiKey);

			if ($response && isset($response['body'])) {

				$body = $response['body'];

				if (!empty($body)) {
					$result = json_decode($body, true);
					$response = $result['data'];

					if ($response) {
						$config = $response;
						$config['apiKey'] = $apiKey;

						unset($config['translatedLanguageLimit'], $config['projectsCountLimit'], $config['trial']);
					}

					if ($sync) {
						//Save in db
						$updated = update_option( 'teral_translate_config', $config);
					}

				}

			}
		}

		return new \WP_REST_Response( array(
			'success' => true,
			'value' => $config
		), 200 );
	}

	/**
	* Create OR Update Example
	*
	* @param WP_REST_Request $request Full data about the request.
	* @return WP_Error|WP_REST_Request
	*/
	public function update_api_key( $request ) {

		$apiKey = $request->get_param( 'apiKey' );

		if (!$this->isValidApiKey($apiKey)) {
			return new \WP_REST_Response( array(
				'success' => false,
				'value' => ''
			), 200 );
		}

		$config = [
			'apiKey' => $apiKey,
			'sourceLanguage' => $request->get_param( 'sourceLanguage' ),
			'targetLanguages' => $request->get_param( 'targetLanguages' ),
			'buttonPosition' => $request->get_param( 'buttonPosition' ),
			'buttonType' => $request->get_param( 'buttonType' ),
			'excludePaths' => $request->get_param( 'excludePaths' ),
			'ignoreClasses' => $request->get_param( 'ignoreClasses' ),
			'addFlags' => $request->get_param( 'addFlags' ),
			'autoRedirect' => $request->get_param( 'autoRedirect' ),
		];


		//Get the project details
		$response = $this->teral_get_api_request("/api/integration/wordpress?apiKey=" . $apiKey);

		if ($response && isset($response['body'])) {

			$body = $response['body'];

			if (!empty($body)) {
				$result = json_decode($body, true);
				$response = $result['data'];

				if ($response) {
					//Get the limit
					$translatedLanguageLimit = (int) $response['translatedLanguageLimit'];

					//Check the limit
					if (sizeof($config['targetLanguages']) > $translatedLanguageLimit) {
						$config['targetLanguages'] = array_slice($config['targetLanguages'], 0, $translatedLanguageLimit);
					}
				}
			}
		}

		$updated = update_option( 'teral_translate_config', $config);

		return new \WP_REST_Response( array(
			'success'   => $updated,
			'value'     => $config
		), 200 );
	}

	/**
	* Delete Example
	*
	* @param WP_REST_Request $request Full data about the request.
	* @return WP_Error|WP_REST_Request
	*/
	public function delete_api_key( $request ) {
		$deleted = delete_option( 'teral_translate_config' );

		return new \WP_REST_Response( array(
			'success'   => $deleted,
			'value'     => ''
		), 200 );
	}

	/**
	* Check if a given request has access to update a setting
	*
	* @param WP_REST_Request $request Full data about the request.
	* @return WP_Error|bool
	*/
	public function admin_permissions_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	public function teral_get_api_request($path) {
		$url = TERAL_API_HOST . $path;

		return wp_remote_get($url);
	}

	public function teral_post_api_request($path, $data) {
		$url = TERAL_API_HOST . $path;

		return wp_remote_post( $url, [
			"body" => $data,
			"headers" => [
				"Content-Type" => "application/json"
			]
			] );
	}

	public function get_api_args() {
		return array(
			'apiKey' => array(
				'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'string',
				'description' => 'Your api key',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'sourceLanguage' => array(
				'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'string',
				'description' => 'The current language of your site',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'targetLanguages' => array(
				'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'array',
				'description' => 'Target languages to use',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'buttonType' => array(
				'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'string',
				'description' => 'Type of language switcher',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'buttonPosition' => array(
				'required' => true, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'string',
				'description' => 'Position of the language switcher',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'excludePaths' => array(
				'required' => false, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'array',
				'description' => 'Array of paths to exclude',
			),
			'excludeClasses' => array(
				'required' => false, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'array',
				'description' => 'Array of css classes to ignore',
			),
			'addFlags' => array(
				'required' => false, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'boolean',
				'description' => 'Add flags or not',
				'validate_callback' => function( $param, $request, $key ) { return ! empty( $param ); } // prevent submission of empty field
			),
			'autoRedirect' => array(
				'required' => false, // means that this parameter must be passed (whatever its value) in order for the request to succeed
				'type' => 'boolean',
				'description' => 'Automatically redirect the user based on his browser language',
			),
		);
	}

	private function isValidApiKey($apiKey) {
		return ctype_alnum($apiKey) && strlen($apiKey) === 32;
	}
}
