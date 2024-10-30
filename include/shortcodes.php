<?php

/* ShortCode */
function couponzen_shortcode( $attributes ){

    extract( shortcode_atts( array(
        'id'         => '',
        'event'      => '',
        'order'        => '',
        'orderby'     => '',
    ), $attributes ) );

    ob_start();
    
    if(!empty($id) && !empty($event)){
       ?> <p><b><?php echo esc_html__( 'Note: ', 'couponzen') ?> </b> <?php echo esc_html__( 'Please insert a single attribute at a time while using a shortcode to filter the coupons. Example: [couponzen id="Post ID"] OR [couponzen event="Event Name"]', 'couponzen' ) ?> </p> <?php 

    }elseif(isset($id) && !empty($id)){
        
        //ID Query Coupon
        $args = array(
            'post_type' => 'couponzen',
            'post_status' => 'publish',
            'p' => $id );
        $query = new WP_Query( $args );
        
        couponzen_post_render($query, false, true);
        wp_reset_query();
        

    }elseif(isset($event) && !empty($event)){ ?>
        <div class="<?php echo esc_attr( (get_option('couponzen_archive_page_style', 'style-1') == 'style-4') ? 'couponzen__container--xl' :  'couponzen__container'); ?> couponzen_options" <?php if(isset($attributes)){ echo esc_attr( "data-couponzenoptions=" ). json_encode(  $attributes );}?>>
                
            <?php $terms_couponzen_category = event_coupon_menu_filter( $event ); ?>
                <div class="couponzen__row">
                    <div class="couponzen__col-lg-12">
                        <!-- Header Area Start -->
                        <div class="couponzen__header">
                            <ul class="nav couponzen__nav-tabs" data-tab-target="#couponzenTabContent">
                                <?php
                                    $manuActive = true;
                                    if ( !is_wp_error( $terms_couponzen_category ) ):
                                        ?>
                                        <li class="couponzen__nav-item">
                                            <button class="couponzen__nav-link scarch-item <?php if($manuActive == true){ echo esc_attr( 'active' ); $manuActive = false;}  ?>" data-category="all" data-target="#all" data-couponevent='<?php echo esc_attr( $event )?>' type="button">
                                                <?php echo esc_html__( 'All Coupon', 'couponzen' ) ?>
                                            </button>
                                        </li>

                                        <?php
                                        foreach ( $terms_couponzen_category as $single_couponzen_category ) : ?>  
                                            <li class="couponzen__nav-item">
                                                <button class="couponzen__nav-link  <?php if($manuActive == true){ echo esc_attr( 'active' ); $manuActive = false;}  ?>" data-target="#<?php echo esc_attr( $single_couponzen_category['slug'] ) ?>"  data-couponevent='<?php echo esc_attr( $event )?>' data-category="<?php echo esc_attr( $single_couponzen_category['slug'] ) ?>" type="button">
                                                    <?php echo esc_html( $single_couponzen_category['name'] ) ?>
                                                </button>
                                            </li>
                                        <?php
                                        endforeach;
                                    endif;
                                ?>
                            </ul>
                            <div class="couponzen__search">
                                <input id="couponzen__search-input-field" class="couponzen__search-input" type="text" placeholder="<?php echo esc_attr__( 'Search here', 'couponzen' ) ?>">
                                <button class="search_button"><img src="<?php echo esc_url( COUPONZEN_PL_URL .'/assets/icons/search.svg') ?>" alt=""></button>
                            </div>
                        </div>
                        <!-- Header Area Start -->
                    </div>
                </div>
                
                <!-- Coupon Body Start-->

                <div class="couponzen__body">
                
                <div class="couponzen__tab-content" id="couponzenTabContent"> <?php
                    $postActive = true;
                    if ( !is_wp_error( $terms_couponzen_category ) ):
                        
                        ?><div class="couponzen__tab-pane <?php if($postActive == true){ echo esc_attr( 'active' ); $postActive = false;}  ?>" id="all">
                            
                                <div class="couponzen-ajax-search couponzen__row ">
                                    
                                    <?php 
                                    $couponzen_par_pages;
                                    if(!empty(get_option('couponzen_par_pages'))){
                                        $couponzen_par_pages = get_option('couponzen_par_pages');
                                    }else{
                                        $couponzen_par_pages = 15;
                                    }
            
                                    $currentPage = ( isset($_REQUEST['page']) ) ? intval( $_REQUEST['page'] ) : 0;
            
                                    $couponzenCatoragorys = get_terms( 'couponzen_category');
                                    $couponCatogry = array();
                                    foreach($couponzenCatoragorys as $catagory){
                                        $couponCatogry[] = $catagory->slug;
                                    }

                                    $args = array(
                                        'post_type'         => 'couponzen',
                                        'post_status'       => 'publish',
                                        'orderby'           => (isset($attributes['orderby']) && !($attributes['orderby'] == '')) ?  'meta_value_num ' . esc_attr( $attributes['orderby'] ) : 'meta_value_num ' . get_option( 'couponzen_post_order_by', 'ID' ),
                                        'order'             => (isset($attributes['order']) && !($attributes['order'] == '')) ? esc_attr( $attributes['order'] ) : get_option( 'couponzen_post_order', 'DESC' ),
                                        'meta_key'       	=> 'htCzenSticky',
                                        'posts_per_page'    => $couponzen_par_pages,
                                        'offset' => ( $currentPage ) * $couponzen_par_pages,
                                        'tax_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'taxonomy' => 'couponzen_category',
                                                'field'    => 'slug',
                                                'terms'    => $couponCatogry
                                            ),
                                            array(
                                                'taxonomy' => 'couponzen_event',
                                                'field'    => 'slug',
                                                'terms'    => esc_attr( $event ),
                                            ),
                                        ),
                                    );
            
                                    $query = new WP_Query( $args );
                                    couponzen_paginaton($query, $currentPage, 'all', $event );
                                    wp_reset_query();
                                    ?>
                                </div>
                                
                            </div>
                        <?php
                        foreach ( $terms_couponzen_category as $single_couponzen_category ): ?>
                            <div class="couponzen__tab-pane <?php if($postActive == true){ echo esc_attr( 'active' ); $postActive = false;}  ?>" id="<?php echo esc_attr( $single_couponzen_category['slug'] ) ?>">
                                <div class="couponzen__row couponzen-ajax-search"></div>
                            </div>
                        <?php
                        endforeach;
            
                    endif;
                    ?>
                </div>
            </div>
            <div class="couponzen-loader"></div>
        </div>
        <?php
    }else{
        ?> <p><b><?php echo esc_html__( 'Note: ', 'couponzen') ?> </b> <?php echo esc_html__( 'Please insert correct couponzen shortcode attribute.', 'couponzen' ) ?> </p> <?php 
    }
    return ob_get_clean();
    
}
add_shortcode( 'couponzen', 'couponzen_shortcode');  
 
//couponzen Archive page
function couponzen_archive_page_shortcode($attributes){
    extract( shortcode_atts( array(
        'order'        => '',
        'orderby'     => '',
    ), $attributes ) );
    
    ob_start();
        include( COUPONZEN_PL_FRONTEND . 'templates/archive-couponzen.php' );
    return ob_get_clean();
}
add_shortcode( 'couponzen_page', 'couponzen_archive_page_shortcode');