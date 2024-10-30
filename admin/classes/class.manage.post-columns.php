<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Coupon_Zen_Manager_Columns{

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
		// Template type column.
		add_action( 'manage_couponzen_posts_columns', [ $this, 'manage_columns' ] );
		add_action( 'manage_couponzen_posts_custom_column', [ $this, 'columns_content' ], 10, 2 );	
        // Coupon Event List Column
		add_action( 'manage_edit-couponzen_event_columns', [ $this, 'manage_columns_event' ] );
		add_action( 'manage_couponzen_event_custom_column', [ $this, 'columns_content_event' ], 10, 3 );		
    }

    // Manage Post Table columns
	public function manage_columns( $columns ) {

		$column_date 	= $columns['date'];

		unset( $columns['date'] );

		$columns['shortcode'] 	    = esc_html__('Shortcode', 'couponzen');
        $columns['discount'] 	    = esc_html__('Coupon Discount', 'couponzen');
		$columns['expirydate'] 	    = esc_html__('Expiry Date', 'couponzen');
		$columns['expirydateleft'] 	= esc_html__('Coupon Day Left', 'couponzen');
		$columns['date'] 		    = esc_html( $column_date );

		return $columns;
	}

    public function columns_content( $column_name, $post_id ) {
        // Coupon Date
        $czenExpired = new DateTime(get_post_meta( get_the_ID(), 'htCzenEndDate', true ));
        $toDay = new DateTime();

        if( $column_name === 'discount' ){
            echo esc_html__( get_post_meta( get_the_ID(), 'htCzenCouponDiscount', true ) );

        }elseif( $column_name === 'shortcode' ){
            echo esc_html__( '[couponzen id="' . $post_id . '"]', 'couponzen' );

        }elseif( $column_name === 'expirydate' ){
            if(empty(get_post_meta( get_the_ID(), 'htCzenCouponCode',true))){
                ?><span class="coupon_expired"><?php echo esc_html__( 'Coupon Not Set', 'couponzen' ); ?></span><?php 

            }elseif(empty( get_post_meta( get_the_ID(), 'htCzenEndDate', true ) )){
                echo esc_html__( 'Lift Time', 'couponzen' );

            }elseif(($czenExpired >= $toDay->modify('-1 day')) ){
                echo esc_attr( $czenExpired->format('d-m-Y'));

            }else{
                ?><span class="coupon_expired"><?php echo esc_html__( 'Expired ', 'couponzen' ); ?></span><?php 
                
            }

        }elseif( $column_name === 'expirydateleft' ){

            if(empty(get_post_meta( get_the_ID(), 'htCzenCouponCode',true))){
                ?><span class="coupon_expired"><?php echo esc_html__( 'Coupon Not Set', 'couponzen' ); ?></span><?php 

            }elseif(empty( get_post_meta( get_the_ID(), 'htCzenEndDate', true ) )){
                echo esc_html__( 'Lift Time', 'couponzen' );
                
            }elseif(($czenExpired >= $toDay->modify('-1 day')) ){
                $datLeft = date_diff($toDay, $czenExpired);
                echo esc_html( $datLeft->format("%a days"));

            }else{
                ?><span class="coupon_expired"><?php echo esc_html__( 'Expired ', 'couponzen' ); ?></span><?php 
            }
        }
    }

    public function manage_columns_event( $columns ){

        $column_count	= $columns['posts'];
		unset( $columns['posts'] );

		$columns['shortcode'] 	    = esc_html__('Shortcode', 'couponzen');
        $columns['posts'] 		    = esc_html( $column_count );

		return $columns;
    }
    
    public function columns_content_event( $string, $column_name, $post_id ) {
        if( $column_name === 'shortcode' ){

            echo esc_html__( '[couponzen event="' . get_term_by('id', $post_id, 'couponzen_event')->slug. '"]', 'couponzen' );
        }
    }
}
Coupon_Zen_Manager_Columns::instance();