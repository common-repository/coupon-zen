<?php 
/**
 * Custom Post Type
 */
    class CouponZen_Custom_Post_Type{

        function __construct(){
            add_action( 'init', array( $this, 'ht_coupon_Zen' ), 0 );
        }

        function ht_coupon_Zen() {

            $labels = array(
                'name'                  => _x( 'Coupon Zen', 'Post Type General Name', 'couponzen' ),
                'singular_name'         => _x( 'coupon', 'Post Type Singular Name', 'couponzen' ),
                'menu_name'             => __( 'Coupon Zen', 'couponzen' ),
                'name_admin_bar'        => __( 'Post Type', 'couponzen' ),
                'archives'              => __( 'Coupon Archives', 'couponzen' ),
                'attributes'            => __( 'Coupon Attributes', 'couponzen' ),
                'parent_item_colon'     => __( 'Parent Coupon:', 'couponzen' ),
                'all_items'             => __( 'All Coupons', 'couponzen' ),
                'add_new_item'          => __( 'Add New Coupon', 'couponzen' ),
                'add_new'               => __( 'Add Coupon', 'couponzen' ),
                'new_item'              => __( 'New Coupon', 'couponzen' ),
                'edit_item'             => __( 'Edit Coupon', 'couponzen' ),
                'update_item'           => __( 'Update Coupon', 'couponzen' ),
                'view_item'             => __( 'View Coupon', 'couponzen' ),
                'view_items'            => __( 'View Coupons', 'couponzen' ),
                'search_items'          => __( 'Search Coupon', 'couponzen' ),
                'not_found'             => __( 'No Coupons found', 'couponzen' ),
                'not_found_in_trash'    => __( 'Not Coupons found in Trash', 'couponzen' ),
                'featured_image'        => __( 'Coupon Featured Image', 'couponzen' ),
                'set_featured_image'    => __( 'Set Coupon featured image', 'couponzen' ),
                'remove_featured_image' => __( 'Remove Coupon featured image', 'couponzen' ),
                'use_featured_image'    => __( 'Use as Coupon featured image', 'couponzen' ),
                'insert_into_item'      => __( 'Insert into Coupon', 'couponzen' ),
                'uploaded_to_this_item' => __( 'Uploaded to this Coupon', 'couponzen' ),
                'items_list'            => __( 'Coupons list', 'couponzen' ),
                'items_list_navigation' => __( 'Coupons list navigation', 'couponzen' ),
                'filter_items_list'     => __( 'Filter Coupons list', 'couponzen' ),
            );

            $args = array(
                'label'                 => __( 'coupon', 'couponzen' ),
                'description'           => __( 'Post Type Description', 'couponzen' ),
                'labels'                => $labels,
                'supports'              => array( 'title', 'thumbnail', 'editor' ),
                'hierarchical'          => false,
                'menu_icon'             => COUPONZEN_PL_URL . '/admin/assets/icon/ht-cupons.png',
                'public'                => false,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'publicly_queryable'    => false,
                'capability_type'       => 'post',
            );
            register_post_type( 'couponzen', $args );

            // Coupon zen Category
           $labels = array(
            'name'              => _x( 'Categories', 'couponzen' ),
            'singular_name'     => _x( 'Category', 'couponzen' ),
            'search_items'      => __( 'Search Category', 'couponzen' ),
            'all_items'         => __( 'All Category', 'couponzen' ),
            'parent_item'       => __( 'Parent Category', 'couponzen' ),
            'parent_item_colon' => __( 'Parent Category:', 'couponzen' ),
            'edit_item'         => __( 'Edit Category', 'couponzen' ),
            'update_item'       => __( 'Update Category', 'couponzen' ),
            'add_new_item'      => __( 'Add New Category', 'couponzen' ),
            'new_item_name'     => __( 'New Category Name', 'couponzen' ),
            'menu_name'         => __( 'Categories', 'couponzen' ),
           );

           $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'publicly_queryable'    => false,
            'rewrite'               => array( 'slug' => 'couponzen_category' ),
           );
           register_taxonomy('couponzen_category','couponzen',$args);

           // Coupons zen Event
           $labels = array(
            'name'              => _x( 'Event Coupons', 'couponzen' ),
            'singular_name'     => _x( 'Event', 'couponzen' ),
            'search_items'      => __( 'Search Event', 'couponzen' ),
            'all_items'         => __( 'All Event', 'couponzen' ),
            'parent_item'       => __( 'Parent Event', 'couponzen' ),
            'parent_item_colon' => __( 'Parent Event:', 'couponzen' ),
            'edit_item'         => __( 'Edit Event', 'couponzen' ),
            'update_item'       => __( 'Update Event', 'couponzen' ),
            'add_new_item'      => __( 'Add New Event', 'couponzen' ),
            'new_item_name'     => __( 'New Event Name', 'couponzen' ),
            'menu_name'         => __( 'Events', 'couponzen' ),
           );

           $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'publicly_queryable'    => false,
            'rewrite'               => array( 'slug' => 'couponzen_event' ),
           );
           register_taxonomy('couponzen_event','couponzen',$args);
        }  
    }

    new CouponZen_Custom_Post_Type();