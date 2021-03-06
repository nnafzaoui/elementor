<?php

class Xoo_Aff_Admin{

	protected $page_slug;

	public function __construct( Xoo_Aff $aff ){
		$this->aff = $aff;
		$this->hooks();
	}

	public function hooks(){
		add_action( 'admin_footer', array( $this, 'custom_admin_css') );
	}

	public function register_page( $page_slug ){

		$this->page_slug = $page_slug;

		if( !isset( $_GET['page'] ) || $_GET['page'] !== $this->page_slug ) return; // Load scripts only on plugin page

		//Load dependencies on register
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) ); 
		add_action( 'admin_footer', array( $this, 'templates') );
		
	}

	public function enqueue_scripts(){

		wp_enqueue_style( 'jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' ); // Jquery UI CSS
		wp_enqueue_style( 'xoo-aff-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' ); //Font Awesome
		wp_enqueue_style( 'xoo-aff-fa-picker', XOO_AFF_URL.'/lib/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css', array(), XOO_AFF_VERSION, 'all' ); //Font Awesome Icon Picker
		wp_enqueue_style( 'xoo-aff-admin-style', XOO_AFF_URL . '/admin/assets/css/xoo-aff-admin-style.css', array(), XOO_AFF_VERSION, 'all' );

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_script( 'xoo-aff-fa-pickers', XOO_AFF_URL.'/lib/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js', array( 'jquery'), XOO_AFF_VERSION, false );

		wp_enqueue_script( 'xoo-aff-admin-js', XOO_AFF_URL . '/admin/assets/js/xoo-aff-admin-js.js', array( 'jquery','wp-color-picker'), XOO_AFF_VERSION, false );
		wp_localize_script( 'xoo-aff-admin-js', 'xoo_aff_localize', array(
			'ajax_url' 		=>  admin_url().'admin-ajax.php',
			'submit_nonce'	=> wp_create_nonce( 'xoo-aff-submit-nonce' )
		));
	}


	public function templates(){
		include XOO_AFF_DIR.'/admin/templates/xoo-aff-template-scripts.php';
	}

	//Called by main plugin to display settings
	public function display_layout(){

		do_action( 'xoo_aff_before_displaying_fields' );

		$args = array(
			'sidebar_template' => xoo_aff_get_template( 'xoo-aff-field-selector.php',  XOO_AFF_DIR.'/admin/templates/', array(
				'field_types' => $this->aff->fields->get_field_types()
			), true ),
		);
		xoo_aff_get_template( 'xoo-aff-page-markup.php',  XOO_AFF_DIR.'/admin/templates/', $args );
	}

	//Add css to admin footer
	public function custom_admin_css(){
		?>
		<style type="text/css">
			p.xoo-aff-description {font-style: italic;}

			.xoo-aff-input-group label {
			    display: block;
			    margin-bottom: 10px;
			}

			.xoo-aff-group input[type="checkbox"], .xoo-aff-group input[type="radio"] {
			    margin-right: 10px;
			}
		</style>
		<?php
	}

}


?>