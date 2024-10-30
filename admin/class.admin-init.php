<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class HTCoupon_Zen_Admin_Options{
    public function __construct(){
        add_action('admin_enqueue_scripts', [ $this, 'htcouponzen_enqueue_admin_style' ] );
        $this->htcouponzen_admin_setting_post();

         //Coupon Post States Init
         add_filter('display_post_states', [ $this, 'coupon_custom_post_states' ]);
    }

    public function htcouponzen_admin_setting_post(){
        require_once( COUPONZEN_PL_PATH. '/admin/classes/class.custom-post-type.php');
        require_once( COUPONZEN_PL_PATH. '/admin/classes/class.manage.post-columns.php');
        require_once( COUPONZEN_PL_PATH. '/admin/classes/class.recommended_plugins.php');
        require_once( COUPONZEN_PL_PATH. '/admin/classes/class.recommended_plugins_menu_call.php');
        require_once( COUPONZEN_PL_PATH. '/admin/classes/class.admin-settings.php');
        require_once( COUPONZEN_PL_PATH. '/admin/include/custom-meta-fields.php');
    }

    public function htcouponzen_enqueue_admin_style(){
        $post_type = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : '';
        if( 'couponzen' === get_post_type() || 'couponzen' === $post_type){
            wp_enqueue_style( 'couponzen-admin', COUPONZEN_PL_URL . 'admin/assets/css/admin-options-panel.css', FALSE, COUPONZEN_VERSION );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'couponzen-color-picker', COUPONZEN_PL_URL . 'admin/assets/js/admin-main.js', array( 'wp-color-picker' ), COUPONZEN_VERSION, TRUE );
        }
    }

    //Couponzen Post States 
    public function coupon_custom_post_states( $states ) {
        
        if ( ( '1' == get_post_meta( get_the_ID(), 'htCzenSticky', true ) ) ) {
            $states[] = __('Sticky', 'couponzen');
        }
        return $states;
    }
}

new HTCoupon_Zen_Admin_Options();