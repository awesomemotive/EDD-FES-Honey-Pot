<?php
/*
 * Plugin Name: Easy Digital Downloads - Frontend Submissions Honey Pot
 * Description: Adds a honey pot field to the registration and vendor contact forms in the Frontend Submissions plugin for Easy Digital Downloads
 * Author: Pippin Williamson
 * Version: 1.0
 */

class EDD_FES_Honey_Pot {
	
	public function __construct() {

		$this->init();

	}

	private function init() {

		if( ! class_exists( 'EDD_Front_End_Submissions' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'text_domain' ) );
		add_action( 'fes_pre_process_registration_form', array( $this, 'check_honeypot' ), 10, 3 );
		add_action( 'fes_pre_process_contact_form', array( $this, 'check_honeypot' ), 10, 3 );
		add_action( 'fes_registration_form_below_submit_buttons', array( $this, 'render_honeypot' ), 10, 3 );
		add_action( 'fes_vendor_contact_below_submit_buttons', array( $this, 'render_honeypot' ), 10, 3 );

	}

	/**
	 * Load our plugin's text domain
	 *
	 * @access  public
	 * @since   1.0
	*/
	public function text_domain() {

		// Set filter for plugin's languages directory
		$lang_dir      = dirname( plugin_basename( __FILE__ ) ) . '/languages/';

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'edd-fes-honeypot' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'edd-fes-honeypot', $locale );

		// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/edd-fes-honeypot/' . $mofile;

		if ( file_exists( $mofile_global ) ) {

			// Look in global /wp-content/languages/edd-fes-honeypot folder
			load_textdomain( 'edd-fes-honeypot', $mofile_global );

		} elseif ( file_exists( $mofile_local ) ) {

			// Look in local /wp-content/plugins/transients-manager/languages/ folder
			load_textdomain( 'edd-fes-honeypot', $mofile_local );

		} else {

			// Load the default language files
			load_plugin_textdomain( 'edd-fes-honeypot', false, $lang_dir );

		}

	}

	/**
	 * save the input values when the submission form is submitted
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function check_honeypot( $class_instance, $form_id, $form_vars ) {
		if ( ! empty( $_POST['fes_honeypot'] ) ) {
			$class_instance->signal_error( __( 'Nice try Mr. Spammer, don\'t touch our honey', 'edd-fes-honeypot' ) );
		}
	}

	/**
	 * Render our honey pot field
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function render_honeypot( $form_id, $read_only, $args ) {
?>
		<input type="hidden" name="fes_honeypot" value=""/>
<?php
	}

}

function edd_fes_honey_pot_load() {
	new EDD_FES_Honey_Pot;
}
add_action( 'plugins_loaded', 'edd_fes_honey_pot_load' );