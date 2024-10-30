<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
class HTCoupon_Zen {
    const MINIMUM_PHP_VERSION = '7.0';

    private $templates = array();
    
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_action( 'wp_enqueue_scripts', [ $this,'assets_management'] );
        add_action( 'wp', [ $this,'sticky_post_check'] );
        
        // Create CouponZen Template
        add_filter('theme_page_templates', array( $this, 'couponzen_template' ));
        add_filter('template_include', array( $this, 'view_couponzen_template'));
        $this->templates = array('couponzen-full-width-page-template.php' => __('CouponZen Full Width Page', 'couponzen'));

        // Register New Coupon Page
        register_activation_hook( COUPONZEN_PL_ROOT, [ $this, 'couponzen_insert_page_on_activation'] );
    }

    public function i18n() {
        load_plugin_textdomain( 'couponzen', false, dirname( plugin_basename( COUPONZEN_PL_ROOT ) ) . '/languages/' );   
    }
    public function init() {
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Plugins Required File
        $this->includes();
    }

    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'couponzen' ),
            '<strong>' . esc_html__( 'Coupon Zen', 'couponzen' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'couponzen' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    //Add Coupon Template  
    public function couponzen_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

    //Coupon Template View  
    public function view_couponzen_template( $template ) {
		
		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta( $post->ID, '_wp_page_template', true )] ) ) {
			return $template;
		} 

		$file = COUPONZEN_PL_FRONTEND.'/templates/'. get_post_meta( $post->ID, '_wp_page_template', true);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo esc_attr( $file );
		}
		// Return template
		return $template;
	}

    // Active Plugin Create new coupon Page
    function couponzen_insert_page_on_activation(){
        if ( ! current_user_can( 'activate_plugins' ) ) return;
        $page_slug = 'coupons'; 
        $new_page = array(
            'post_type'     => 'page',              
            'post_title'    => esc_html__('Coupon Code','couponzen'),    
            'post_content'  => '<!-- wp:shortcode -->[couponzen_page]<!-- /wp:shortcode -->',  
            'post_status'   => 'publish',                                
            'post_name'     => $page_slug,
            'page_template' => 'couponzen-full-width-page-template.php'
        );
        if (!get_page_by_path( $page_slug, OBJECT, 'page')) {
            wp_insert_post($new_page);
        }
        
        $this->sticky_post_update();
    }

    public function sticky_post_check(){
        // Sticky function
        if(empty(get_option( 'sticky_post_update_check'))){
            add_option( 'sticky_post_update_check', 'yes' );
        }
        if(get_option( 'sticky_post_update_check') === 'yes'){
            $this->sticky_post_update();
            update_option( 'sticky_post_update_check', 'no' );
        }
    }
    
    public function sticky_post_update(){
        $args = array( 
            'post_type'         => 'couponzen',
			'post_status' 		=> 'publish',
            'posts_per_page'    => -1
        );
        $query = new WP_Query( $args );

        while ( $query->have_posts() ) {
            $query->the_post();
            if(empty(get_post_meta( get_the_ID(), 'htCzenSticky',true))){
                update_post_meta( get_the_ID(), 'htCzenSticky', '0' );
            }
        }
        wp_reset_query();
    }
   
    /*
    * Assest Management
    */
    public function assets_management( $hook ){
        global $post;
        $elementor_editor_mode_check = false;
        if( class_exists( '\Elementor\Plugin' ) && ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) ){
            $elementor_editor_mode_check = true;
        }

        if ( ( is_object( $post ) && isset( $post->post_content ) && ( $elementor_editor_mode_check  || has_shortcode( $post->post_content, 'couponzen_page' ) || has_shortcode( $post->post_content, 'couponzen' )))) {
            //Couponzen CSS
            wp_enqueue_style( 'couponzes-css', COUPONZEN_PL_URL . 'assets/css/style.css', '', COUPONZEN_VERSION );
            wp_add_inline_style('couponzes-css', couponzen_coustom_css());

            //Couponzen JS
            wp_enqueue_script( 'couponzes-main', COUPONZEN_PL_URL . 'assets/js/main.js','', COUPONZEN_VERSION, TRUE );
            wp_enqueue_script( 'couponzen-filter', COUPONZEN_PL_URL . 'assets/js/couponzen-filter.js',array('jquery'), COUPONZEN_VERSION, TRUE );

            //Localize Scripts
            $localizeargs = array(
                'ajaxurl'           => admin_url( 'admin-ajax.php' ),
                'ajaxnonce'         => wp_create_nonce( 'couponzen_itemsa_nonce' ),
                'couponBtnClick'    => esc_attr__( 'Copied', 'couponzen' ),
                'couponBtnHover'    => esc_attr__( 'Copy', 'couponzen' )
            );
            wp_localize_script( 'couponzen-filter', 'couponzen', $localizeargs );   
        }
    }

    public function includes() {
        require_once COUPONZEN_PL_PATH.'/include/class.couponzen-filter.php';
        require_once COUPONZEN_PL_PATH.'/include/shortcodes.php';
        require_once COUPONZEN_PL_INCLUDE.'/couponzen_post_render.php';
        require_once COUPONZEN_PL_INCLUDE.'/plugin_global_function.php';
    }
}