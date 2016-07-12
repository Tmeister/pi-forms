<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://enriquechavez.co
 * @since      1.0.0
 *
 * @package    Pi_Forms
 * @subpackage Pi_Forms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pi_Forms
 * @subpackage Pi_Forms/admin
 * @author     Enrique Chavez <me@enriquechavez.co>
 */
class Pi_Forms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      object $ac Proxy Class.
	 */
	private $ac;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name          = $plugin_name;
		$this->version              = $version;
		$this->ac_credentials_error = false;
		$this->pi_forms_options     = get_option( 'pi_forms_settings' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pi_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pi_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'form-builder-css', plugin_dir_url( __FILE__ ) . 'css/form-builder.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pi-forms-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pi_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pi_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'jquery-custom', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js', $this->version, false );
		wp_enqueue_script( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array( 'jquery-custom' ), $this->version, false );
		wp_enqueue_script( 'form-builder', plugin_dir_url( __FILE__ ) . 'js/form-builder.min.js', array( 'jquery-ui' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pi-forms-admin.js', array( 'form-builder' ), $this->version, false );

	}

	public function add_sub_menus() {
		add_menu_page( 'Forms Builder', 'Forms Builder', 'manage_options', 'forms-builder', array( $this, 'provider_options' ), '' );
	}

	/**
	 * Draw the Options pages (Tabs).
	 *
	 * @since 1.0.0
	 */
	public function provider_options() {
		$this->providers = $this->get_providers_tabs();
		$current_tab     = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		include_once 'partials/pi-forms-admin-display.php';
	}

	/**
	 * Set the initial Providers list.
	 *
	 * @return [array] Array with the providers list.
	 */
	public function get_providers_tabs() {
		$providers                    = array();
		$providers['general']         = __( 'General', $this->plugin_name );
		$providers['active_campaign'] = __( 'Active Campaign', $this->plugin_name );
		$providers['click_again']     = __( 'Click Again', $this->plugin_name );
		$providers['form_builder']    = __( 'Form Builder', $this->plugin_name );

		return apply_filters( 'pi_forms_providers', $providers );
	}

	/**
	 * Inital fields for the default providers.
	 *
	 * @return array
	 */
	private function get_providers_settings() {
		if ( isset( $this->pi_forms_options['active_campaign_api_url'] ) && isset( $this->pi_forms_options['active_campaign_api_key'] ) ) {
			$this->ac = new ActiveCampaignProxy( $this->pi_forms_options['active_campaign_api_url'], $this->pi_forms_options['active_campaign_api_key'] );

			if ( ! $this->ac->testCredentials() ) {
				$this->ac = false;
			}
			if ( $this->ac && $this->ac->testCredentials() === - 1 ) {
				$this->ac_credentials_error = true;
			}
		}

		$pi_forms_prov_settings = array(

			/* General Settings */
			'general'         => apply_filters( 'pi_forms_general_settings',
				array(
					'provider_list' => array(
						'id'      => 'mailing_provider',
						'name'    => __( 'Mailing Provider', 'pi-forms' ),
						'desc'    => __( 'Please select the Mailing Provider where you want to store your leads.', 'pi-forms' ),
						'type'    => 'select',
						'options' => array(
							'none'            => __( 'None', 'pi-forms' ),
							'active_campaign' => __( 'Active Campaign', 'pi-forms' )
						),
					),
				)
			),
			/* Active Campaign Settings */
			'active_campaign' => apply_filters( 'pi_forms_active_campaign_settings',
				array(
					'ac_api_url' => array(
						'id'   => 'active_campaign_api_url',
						'name' => __( 'Active Campaign API URL', $this->plugin_name ),
						'desc' => 'Please get your account API URL in your <a href="http://www.activecampaign.com/" target="_blank">Active Campaign Settings</a>',
						'type' => 'text',
					),
					'ac_api_key' => array(
						'id'   => 'active_campaign_api_key',
						'name' => __( 'Active Campaign API Key', $this->plugin_name ),
						'desc' => 'Please get your account API URL in your <a href="http://www.activecampaign.com/" target="_blank">Active Campaign Settings</a>',
						'type' => 'text',
					),
				)
			),
			/* Form Builder Settings */
			'form_builder'    => apply_filters( 'pi_forms_form_builder_settings',
				array(
					'ac_form_html_top' => array(
						'id'   => 'active_campaign_form_html_top',
						'name' => __( 'Top Form Content', $this->plugin_name ),
						'type' => 'textarea',
					),
					'ac_form_html_bottom' => array(
						'id'   => 'active_campaign_form_html_bottom',
						'name' => __( 'Bottom Form Content', $this->plugin_name ),
						'type' => 'textarea',
					),
					'ac_form_html_button_label' => array(
						'id'   => 'active_campaign_form_html_button_label',
						'name' => __( 'Button Label', $this->plugin_name ),
						'type' => 'text',
					),
					'ac_form_css'  => array(
						'id'   => 'active_campaign_form_css',
						'name' => __( 'Custom Form CSS', $this->plugin_name ),
						'type' => 'textarea',
					)
				)
			),
			'click_again'     => apply_filters( 'pi_forms_click_again_settings',
				array(
					'ca_lead_url' => array(
						'id'   => 'click_again_lead_url',
						'name' => __( 'Form URL', $this->plugin_name ),
						'desc' => 'This is the URL to save the lead in ClickAgain.<br>ex. https://secure.leads360.com/Import.aspx?Provider=RegalAssets&Client=RegalAssets&CampaignId=1058&url=https://www.regalassets.com/thanks.html',
						'type' => 'text',
						'size' => 'large'
					)
				)
			),
		);

		return apply_filters( 'pi_forms_providers_settings', $pi_forms_prov_settings );
	}

	/**
	 * Output a message to let the user know that there is no handler for that field.
	 *
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public function pi_forms_missing_callback( $args ) {
		printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', $this->plugin_name ), $args['id'] );
	}

	/**
	 * Add the sections and fields according with the providers list.
	 */
	public function add_providers_settings() {
		if ( false == get_option( 'pi_forms_settings' ) ) {
			add_option( 'pi_forms_settings' );
		}

		foreach ( $this->get_providers_settings() as $tab => $settings ) {
			add_settings_section(
				'pi_forms_settings_' . $tab,
				__return_null(),
				'__return_false',
				'pi_forms_settings_' . $tab
			);

			foreach ( $settings as $option ) {
				$name = isset( $option['name'] ) ? $option['name'] : '';

				add_settings_field(
					'pi_forms_settings[' . $option['id'] . ']',
					$name,
					method_exists( $this, 'pi_forms_' . $option['type'] . '_callback' ) ? array( $this, 'pi_forms_' . $option['type'] . '_callback' ) : array( $this, 'pi_forms_missing_callback' ),
					'pi_forms_settings_' . $tab,
					'pi_forms_settings_' . $tab,
					array(
						'section' => $tab,
						'id'      => isset( $option['id'] ) ? $option['id'] : null,
						'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
						'name'    => isset( $option['name'] ) ? $option['name'] : null,
						'size'    => isset( $option['size'] ) ? $option['size'] : null,
						'options' => isset( $option['options'] ) ? $option['options'] : '',
						'std'     => isset( $option['std'] ) ? $option['std'] : '',
						'min'     => isset( $option['min'] ) ? $option['min'] : null,
						'max'     => isset( $option['max'] ) ? $option['max'] : null,
						'step'    => isset( $option['step'] ) ? $option['step'] : null,
					)
				);
			}
		}

		// Creates our settings in the options table
		register_setting( 'pi_forms_settings', 'pi_forms_settings', array( $this, 'pi_forms_settings_sanitize' ) );
	}

	/**
	 * Global Callback function to draw the checkbox option in the settings page.
	 *
	 * @param array $args [description]
	 *
	 * @since 1.0.0
	 */
	public function pi_forms_checkbox_callback( $args ) {
		$checked = isset( $this->pi_forms_options[ $args['id'] ] ) ? checked( 1, $this->pi_forms_options[ $args['id'] ], false ) : '';
		$html    = '<input type="checkbox" id="pi_forms_settings[' . $args['id'] . ']" name="pi_forms_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		$html .= '<br><label for="pi_forms_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';
		echo $html;
	}

	/**
	 * Global Callback function to draw the text option in the settings page.
	 *
	 * @param array $args [description]
	 *
	 * @since 1.0.0
	 */
	public function pi_forms_text_callback( $args ) {
		if ( isset( $this->pi_forms_options[ $args['id'] ] ) ) {
			$value = $this->pi_forms_options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . $size . '-text" id="pi_forms_settings[' . $args['id'] . ']" name="pi_forms_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<br><label for="pi_forms_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Global Callback function to draw the password option in the settings page.
	 *
	 * @param array $args [description]
	 *
	 * @since 1.0.0
	 */
	public function pi_forms_password_callback( $args ) {
		if ( isset( $this->pi_forms_options[ $args['id'] ] ) ) {
			$value = $this->pi_forms_options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="password" class="' . $size . '-text" id="pi_forms_settings[' . $args['id'] . ']" name="pi_forms_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
		$html .= '<label for="pi_forms_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

		echo $html;
	}

	public function pi_forms_select_callback( $args ) {
		if ( isset( $this->pi_forms_options[ $args['id'] ] ) ) {
			$value = $this->pi_forms_options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
		if ( isset( $args['placeholder'] ) ) {
			$placeholder = $args['placeholder'];
		} else {
			$placeholder = '';
		}
		$html = '<select id="pi_forms_settings[' . $args['id'] . ']" name="pi_forms_settings[' . $args['id'] . ']" ' . 'data-placeholder="' . $placeholder . '" />';
		foreach ( $args['options'] as $option => $name ) :
			$selected = selected( $option, $value, false );
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
		endforeach;
		$html .= '</select>';
		$html .= '<label for="pi_forms_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';
		echo $html;
	}

	public function pi_forms_info_callback( $args ) {
		$html = sprintf( '%s', $args['desc'] );
		echo $html;
	}

	/**
	 * Textarea Callback
	 *
	 * Renders textarea fields.
	 *
	 * @since 1.0
	 *
	 * @param array $args Arguments passed by the setting
	 *
	 * @return void
	 */
	public function pi_forms_textarea_callback( $args ) {
		if ( isset( $this->pi_forms_options[ $args['id'] ] ) ) {
			$value = $this->pi_forms_options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$html = '<textarea class="large-text" cols="50" rows="5" id="pi_forms_settings[' . $args['id'] . ']" name="pi_forms_settings[' . esc_attr( $args['id'] ) . ']">' . $value . '</textarea>';
		$html .= '<label for="pi_forms_settings[' . $args['id'] . ']"> ' . wp_kses_post( $args['desc'] ) . '</label>';
		echo $html;
	}

	public function pi_forms_builder_callback( $args ) {
		$html = '<textarea id="fb-holder"></textarea>';
		echo $html;
	}

	/**
	 * Sanitize function.
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	public function pi_forms_settings_sanitize( $input = array() ) {
		error_log( print_r( $input, true ) );

		$this->pi_forms_options = is_array( $this->pi_forms_options ) ? $this->pi_forms_options : array();

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		parse_str( $_POST['_wp_http_referer'], $referrer );

		$settings = $this->get_providers_settings();
		$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

		$input = $input ? $input : array();
		$input = apply_filters( 'pi_forms_settings_' . $tab . '_sanitize', $input );

		foreach ( $input as $key => $value ) {
			$type = isset( $settings[ $tab ][ $key ]['type'] ) ? $settings[ $tab ][ $key ]['type'] : false;
			if ( $type ) {
				$input[ $key ] = apply_filters( 'pi_forms_settings_sanitize_' . $type, $value, $key );
			}
			$input[ $key ] = apply_filters( 'pi_forms_settings_sanitize', $input[ $key ], $key );
		}

		// Loop through the whitelist and unset any that are empty for the tab being saved
		if ( ! empty( $settings[ $tab ] ) ) {
			foreach ( $settings[ $tab ] as $key => $value ) {

				// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
				if ( is_numeric( $key ) ) {
					$key = $value['id'];
				}

				if ( empty( $input[ $key ] ) ) {
					unset( $this->pi_forms_options[ $key ] );
				}
			}
		}

		// Merge our new settings with the existing
		$output = array_merge( $this->pi_forms_options, $input );

		add_settings_error( 'pi_forms-notices', '', __( 'Settings updated.', $this->plugin_name ), 'updated' );

		error_log( print_r( $output, true ) );

		return $output;
	}

	private function parse_get_response_campaigns( $campaigns ) {
		$out = array();
		foreach ( $campaigns as $key => $campaign ) {
			$out[ $key ] = $campaign['name'];
		}

		return $out;
	}
}
