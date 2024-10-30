<?php  

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Couponzen_Filter{

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        // Ajax Call Coupon Search
		add_action( 'wp_ajax_couponzen_search', [ $this, 'search_request' ] );
		add_action( 'wp_ajax_nopriv_couponzen_search', [ $this, 'search_request' ] );
		
		// Ajax Call Coupon Category
		add_action( 'wp_ajax_couponzen_category_search', [ $this, 'couponzen_category' ] );
		add_action( 'wp_ajax_nopriv_couponzen_category_search', [ $this, 'couponzen_category' ]);
    }

    public function search_request(){

        $s = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$currentPage = ( isset($_REQUEST['page']) ) ? intval( $_REQUEST['page'] ) : 0;
		$couponzenEvent = (isset($_REQUEST['couponzenevent'])) ? sanitize_text_field( $_REQUEST['couponzenevent'] ) : '';
		check_ajax_referer('couponzen_itemsa_nonce', 'nonce');

		$couponzen_par_pages;
		if(!empty(get_option('couponzen_par_pages'))){
			$couponzen_par_pages = get_option('couponzen_par_pages');
		}else{
			$couponzen_par_pages = 15;
		}

		$args = array(
			'post_type'         => 'couponzen',
			'post_status' 		=> 'publish',
			'orderby'           => 'meta_value_num ' . get_option('couponzen_post_order_by', 'ID'),
			'order'             => get_option('couponzen_post_order', 'DESC'),
			'meta_key'       	=> 'htCzenSticky',
			'posts_per_page' 	=> $couponzen_par_pages,
			'offset' 			=> ($currentPage ) * $couponzen_par_pages,
			's' => $s,
		);

		if(isset($couponzenEvent) && !empty($couponzenEvent)){		
			$couponzenCatoragorys = get_terms( 'couponzen_category');
			$couponCatogry = array();
			foreach($couponzenCatoragorys as $catagory){
				$couponCatogry[] = $catagory->slug;
			}

			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'couponzen_category',
					'field'    => 'slug',
					'terms'    => $couponCatogry,
				),
				array(
					'taxonomy' => 'couponzen_event',
					'field'    => 'slug',
					'terms'    => $couponzenEvent,
				),
			);
		}

		$query = new WP_Query( $args );
		if( $query->have_posts() ):
			couponzen_paginaton($query, $currentPage, $s, $couponzenEvent, 'cuponzen-search-pagination');
		else:
			?><p class="text-center couponzen_psa_wrapper couponzen_no_result"><?php echo esc_html__( 'No Coupon Found', 'couponzen' ); ?> </p><?php 
		endif; 

		wp_reset_query(); 
		wp_die();
    }

	public	function couponzen_category(){
		//coupon Categorie
		$couponzenKeyValue = sanitize_text_field( $_REQUEST['couponzenCategorie'] );
		//coupon Event
		$couponzenEvent = (isset($_REQUEST['couponzenevent'])) ? sanitize_text_field( $_REQUEST['couponzenevent'] ) : '';
		// Page Number
		$currentPage = ( isset($_REQUEST['page']) ) ? intval( $_REQUEST['page'] ) : 0;
		//Shortcode attributes
		$options = ( isset( $_REQUEST['options']) && !empty($_REQUEST['options'])) ? array_map( 'sanitize_text_field', $_REQUEST['options'] ) : array();
		//nonce
		check_ajax_referer('couponzen_itemsa_nonce', 'nonce');

		$couponzen_par_pages;
		if(!empty(get_option('couponzen_par_pages'))){
			$couponzen_par_pages = get_option('couponzen_par_pages');
		}else{
			$couponzen_par_pages = 15;
		}

		$args = array(
			'post_type' 		=> 'couponzen',
			'post_status' 		=> 'publish',
			'orderby'        => (isset( $options['orderby']) && !($options['orderby'] == '')) ?  'meta_value_num ' . esc_attr( $options['orderby'] ) : 'meta_value_num ' . get_option( 'couponzen_post_order_by', 'ID' ),
            'order'          => (isset($options['order']) && !($options['order'] == '')) ? esc_attr( $options['order'] ) : get_option( 'couponzen_post_order', 'DESC' ),
			'meta_key'       	=> 'htCzenSticky',
			'posts_per_page' 	=> $couponzen_par_pages,
			'offset'			=> ($currentPage ) * $couponzen_par_pages,
		);

		if( $couponzenKeyValue !== "all" ){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'couponzen_category',
					'field'    => 'slug',
					'terms'    => $couponzenKeyValue,
				),
			);
		}

		if(isset($couponzenEvent) && !empty($couponzenEvent)){
			if($couponzenKeyValue == 'all'){
				$couponzenCatoragorys = get_terms( 'couponzen_category');
				$couponCatogry = array();
				foreach($couponzenCatoragorys as $catagory){
					$couponCatogry[] = $catagory->slug;
				}
			}

			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'couponzen_category',
					'field'    => 'slug',
					'terms'    => ( $couponzenKeyValue !== 'all' ) ? $couponzenKeyValue :  $couponCatogry,
				),
				array(
					'taxonomy' => 'couponzen_event',
					'field'    => 'slug',
					'terms'    => $couponzenEvent,
				),
			);
		}

		$query = new WP_Query( $args );
		if( $query->have_posts() ):
			couponzen_paginaton($query, $currentPage, $couponzenKeyValue, $couponzenEvent);
		else:
			?><p class="text-center couponzen_psa_wrapper couponzen_no_result"><?php echo esc_html__( 'No Coupon Found', 'couponzen' ); ?> </p><?php 
		endif; // have posts
		
		wp_reset_query(); 
		wp_die();		
	}
}
Couponzen_Filter::instance();


