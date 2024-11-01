<?php
/**
 * @package   Teral-Translate
 */

namespace Teral\Translate;

/**
 * @subpackage Widget
 */
class Widget extends \WP_Widget {

	/**
	 * Initialize the widget
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$plugin = Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->version = $plugin->get_plugin_version();

		$widget_ops = array(
			'description' => esc_html__( 'Teral Language Switcher Widget.', $this->plugin_slug ),
		);

		parent::__construct( 'tl-switcher-widget', esc_html__( 'Teral Language Switcher', $this->plugin_slug ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$object_name = 'tl-widget-' . uniqid();

		?><div class="tl-switcher-container" data-object-id="<?php echo $object_name ?>"></div><?php

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php esc_html_e( 'Title:', 'Teral' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}
}
