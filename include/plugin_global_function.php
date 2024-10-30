<?php

// Couponzen Coustom css
function couponzen_coustom_css(){
    $buttonTextColor = couponzen_generate_css( 'color', esc_attr(get_option('couponzen_button_text_color', '#000')) , '', '!important');
    $buttonTextHoverColor = couponzen_generate_css( 'color', esc_attr(get_option('couponzen_button_text_hover_color')) , '!important');
    $buttonBgColor = couponzen_generate_css( 'background', esc_attr(get_option('couponzen_button_bg_color', '#fbc50b')) , '', '!important');
    $buttonBgHoverColor = couponzen_generate_css( 'background', esc_attr(get_option('couponzen_button_bg_hover_color', '')) , '', '!important');

    $all_style ="
        .couponzen__button-visit {{$buttonTextColor} {$buttonBgColor}}
        .couponzen__button-visit:hover {{$buttonBgHoverColor} {$buttonTextHoverColor}}
    ";

    $all_style .= get_option('couponzen_coustom_css');
    
    return $all_style;
}

// Coupon Generate Css
function couponzen_generate_css( $css_attr, $parameter, $unit = '', $important = '' ){

    $value = !empty( $parameter ) ? $parameter : '';
    if( !empty( $value ) ){
        $css_attr .= ":{$value}{$unit}";
        return $css_attr." {$important};";
    }else{
        return false;
    }
}

// Couponzen paginaton
function couponzen_paginaton($query, $currentPage, $catagoryName, $eventName = '', $className = "cuponzen-pagination"){

    $couponzen_par_pages;
    if(!empty(get_option('couponzen_par_pages'))){
        $couponzen_par_pages = get_option('couponzen_par_pages');
    }else{
        $couponzen_par_pages = 15;
    }

    $total_page = (int) ceil($query->found_posts/$couponzen_par_pages);


    couponzen_post_render($query, false);?>
    <div class="couponzen__col-lg-12">
        <div class="cuponzen-pagination-style <?php  echo  esc_attr( $className ) ?>">

            <?php 
            $paginatonNumber = $currentPage-1;
            if($paginatonNumber < 0){
                $paginatonNumber = 0;
            }

            if(!$currentPage == 0): ?>
                <a data-couponzenkey="<?php echo esc_attr( $catagoryName ); ?>" data-page="<?php echo esc_attr( $paginatonNumber ); ?>" <?php echo (isset($eventName) && !empty($eventName)) ? esc_attr( "data-couponevent={$eventName}" ) : '' ?> >
                    <?php echo esc_html__( '&laquo; Previous', 'couponzen' ) ?>
                </a>
            <?php endif;
            $more = true;
            for ($i = $paginatonNumber; $i < $total_page; $i++): 
                $j = $i + 1;

                if( $i > $currentPage + 2 && $total_page-1 > $i ){
                    
                    if($more){
                        ?><a class='cuponzen-pointer'>
                            <?php echo esc_html__( '...', 'couponzen' ) ?>
                        </a><?php
                        $more = false;
                    }
                    continue;
                }

                if($total_page == 1){
                    break;
                } ?>
                    <a  <?php if($currentPage == $i): ?> class="active" <?php endif; ?> data-couponzenkey="<?php echo esc_attr( $catagoryName ); ?>" data-page="<?php echo esc_attr( $i ); ?>" <?php echo (isset($eventName) && !empty($eventName)) ? esc_attr( "data-couponevent={$eventName}" ) : '' ?> >
                        <?php echo esc_html( $j ); ?>
                    </a>
                
            <?php endfor; $more = true;
            if(!($total_page-1 == $currentPage)): ?>
                <a data-couponzenkey="<?php echo esc_attr( $catagoryName ); ?>" data-page="<?php echo esc_attr( $currentPage+1 ); ?>" <?php echo (isset($eventName) && !empty($eventName)) ? esc_attr( "data-couponevent={$eventName}" ) : '' ?> >
                    <?php echo esc_html__( 'Next &raquo;', 'couponzen' )  ?>
                </a>
            <?php endif; ?>
        </div>  
    </div>
<?php
}

/*
** Couponzen Event filter Category and slug
*/

function event_coupon_menu_filter( $event_name ){

    $couponzenCatoragorys = get_terms( 'couponzen_category');
    $couponCatogry = array();
    foreach($couponzenCatoragorys as $catagory){
        $couponCatogry[] = $catagory->slug;
    }

    $args = array(
        'post_type' => 'couponzen',
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
                'terms'    => $event_name,
            ),
        ),
    );
    
    $query = new WP_Query( $args );
    $filter_catagory = array();
    $__catagory = array();
    if ($query->have_posts()){
        $i = 0;
        while ($query->have_posts()){
            $query->the_post();
            $mix_catagory = get_the_terms( get_the_ID(), 'couponzen_category');
            foreach($mix_catagory as $catagory){
                if(!in_array($catagory->slug, $__catagory)){
                    $__catagory[$i] =  $catagory->slug;
                    $filter_catagory[$i]['name'] = $catagory->name;
                    $filter_catagory[$i]['slug'] = $catagory->slug;
                    $i++;
                } 
            }                  
        }
    }
    wp_reset_query(); 

    return $filter_catagory;
}

function remainingDays($inputDateTime, $daysRemainingText) {    
    // Get current date
    $currentDate = (new DateTime())->modify('-1 day');
    
    // Check if the input date is in the past
    if ($inputDateTime < $currentDate) {
        return "Expired";
    }
    
    // Calculate the interval between current date and input date
    $interval = $currentDate->diff($inputDateTime);
    
    // Get the remaining days
    $remainingDays = $interval->days;

    $daysRemainingText = $daysRemainingText ? $daysRemainingText : 'days remaining';
    return "$remainingDays $daysRemainingText";
}

