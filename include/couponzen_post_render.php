<?php
function couponzen_post_render( $query, $loder=true, $shortcode=false ){
    while($query->have_posts()):
        $query->the_post();
        //Coupon Meta Data
        $czenAutoCoupon = get_post_meta( get_the_ID(), 'htCzenAutoCoupon',true);
        $czenCouponCode = get_post_meta( get_the_ID(), 'htCzenCouponCode',true);
        $czenCouponDiscount = get_post_meta( get_the_ID(), 'htCzenCouponDiscount', true );
        $cZenSiteUrl = get_post_meta( get_the_ID(), 'htCzenSiteUrl', true );
        $cZenDoFollowPostBtn = get_post_meta( get_the_ID(), 'htCouponzenDoFollowPostBtn', true );
        //Featured Image
        $cZenCouponItemImage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');
        $cZenCouponItemImageAlt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
        // Couponzen Post Meta Expired Date Filed
        $cZenPostMetaExpired = get_post_meta( get_the_ID(), 'htCzenEndDate', true );
        //Expired Date
        $czenExpired = new DateTime($cZenPostMetaExpired);
        $toDay = new DateTime(); ?>

        <?PHP if(($czenExpired > $toDay->modify('-1 day')) || get_option('hide_expired_coupon', 'false') == "false" ): ?>
            
            <div class="<?php 
            if(!$shortcode){
                echo esc_attr((get_option('couponzen_archive_page_style', 'style-1') === 'style-1' || get_option('couponzen_archive_page_style') === 'style-3'  ) ? 'couponzen__col-lg-4 couponzen__mb-30' :  'couponzen__col-lg-6 couponzen__mb-30');
            }else{
                echo esc_attr('couponzen__col-lg-12');
            }
            echo esc_attr( ($shortcode) ? ' couponzen_single_coupon' : '' );
            ?>">
                <div class="<?php 
                if(!$shortcode){
                    if(get_option('couponzen_archive_page_style', 'style-1') === 'style-1'):
                        echo esc_attr( 'couponzen__card' );
                    elseif(get_option('couponzen_archive_page_style') === 'style-2'):
                        echo esc_attr( 'couponzen__card-horizontal couponzen__card--horizontal' );
                    elseif(get_option('couponzen_archive_page_style') === 'style-3'):
                        echo esc_attr( 'couponzen__card couponzen__card--three' );
                    elseif(get_option('couponzen_archive_page_style') === 'style-4'):
                        echo esc_attr( 'couponzen__card-horizontal couponzen__card--four' );
                    endif;
                }else{
                    echo esc_attr( 'couponzen__card-horizontal couponzen__card--four' );
                }
                    ?>">
                    <div class="couponzen__thum">
                        <?php if( isset( $cZenCouponItemImage[0] )): ?>
                            <img src="<?php the_post_thumbnail_url() ?>" alt="<?php echo esc_attr( $cZenCouponItemImageAlt )?>">
                        <?php endif; ?>

                        <?php if( $czenCouponDiscount ): ?>
                            <span class="couponzen__discount"><?php echo esc_attr( $czenCouponDiscount ) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="couponzen__content">
                        <?php the_title('<h3 class="couponzen__title">', '</h3>') ?>

                        <?php if((($czenExpired > $toDay) || empty($czenExpired )) && !empty($cZenPostMetaExpired)): ?>
                            <?php if(get_option('couponzen_remaining_days', 'no_need') === 'remaining_day'): ?>
                                <div class="couponzen_remaining_days"><?php echo esc_html__(remainingDays($czenExpired, get_option('remaining_days_text', 'days remaining'))) ?></div>
                            <?php elseif(get_option('couponzen_remaining_days', 'no_need') === 'expiry_day'): ?>
                                <div class="couponzen_remaining_days"><?php echo esc_html__("Expiry Date: ". $czenExpired->format('Y-m-d')) ?></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(!empty(get_the_content( ))): ?>
                            <div class="couponzen__des"><?php the_content() ?></div>
                        <?php endif; ?>

                        <div class="couponzen__button_box">

                            <!-- Coupon Expired check or not -->
                            <?PHP 
                                if((($czenExpired > $toDay) || empty($czenExpired )) ):
                                    
                                    if( $czenAutoCoupon == "yes"): ?>
                                        <!-- Auto Coupon -->
                                        <div class="couponzen-shareable-code <?php echo esc_attr((get_option('couponzen_archive_page_style') === 'style-3') ? 'couponzen__card--three-style' : ''); ?>">
                                            <button class="couponzen__button <?php echo esc_attr((get_option('couponzen_archive_page_style','style-1') === 'style-1') ? 'couponzen__button-auto-coupon' : 'couponzen__button-auto-coupon-2'); ?> " >
    
                                            <?php echo (get_option('couponzen_coupon_text')) ? esc_html__( get_option('couponzen_coupon_text'), 'couponzen') : esc_html__( 'Coupon: ', 'couponzen'); ?> 
    
                                            <span class="<?php 
                                                if(get_option('couponzen_archive_page_style', 'style-1') === 'style-1'):
                                                    echo esc_attr( 'couponzen__shareable-link couponzen__shareable-link--tooltip' );
                                                else:
                                                    echo esc_attr( 'couponzen__shareable-link' );
                                                endif; ?>"><?php echo (!empty(get_option('couponzen_auto_coupon'))) ? get_option('couponzen_auto_coupon') : esc_html__( 'Auto Applied', 'couponzen' ) ?></span></button>
                                        </div> 
                                    <?php
                                    elseif(!empty($czenCouponCode)):?>
                                        <div class="couponzen-shareable-code <?php echo esc_attr((get_option('couponzen_archive_page_style') === 'style-3') ? 'couponzen__card--three-style' : ''); ?>">
                                            <button class="couponzen-copy-code couponzen__button couponzen__button-coupon"  data-copytext="<?php echo esc_attr__( 'Copy', 'couponzen') ?>" >
 
                                            <?php echo (get_option('couponzen_coupon_text')) ? esc_html__( get_option('couponzen_coupon_text'), 'couponzen') : esc_html__( 'Coupon: ', 'couponzen'); ?> 

                                            <span class="<?php 
                                                if(get_option('couponzen_archive_page_style', 'style-1') === 'style-1'):
                                                    echo esc_attr( 'couponzen__shareable-link couponzen__shareable-link--tooltip' );
                                                else:
                                                    echo esc_attr( 'couponzen__shareable-link' );
                                                endif; ?>"><?php esc_html_e( $czenCouponCode, 'couponzen' ) ?></span></button>
                                            <p style="display: none;" class="coupon-copy-code"><?php esc_html_e( $czenCouponCode, 'couponzen' ) ?></p>
                                        </div>

                                <?php else: ?>
                                   

                                <?php endif;
                                
                                else: ?>
                                <button class="couponzen__button couponzen__button-coupon-expired">
                                    <?php echo (get_option('couponzen_expired_text')) ? esc_html__( get_option('couponzen_expired_text'), 'couponzen' )  : esc_html__( 'Expired', 'couponzen' ); ?>
                                </button>
                            <?php endif;?>

                            <?php
                                // SEO Button Configuration
                                $cZenDoFollowResult = '';

                                // BUtton 1
                                if(get_option('couponzen_url_target') == "_blank"){
                                    if($cZenDoFollowPostBtn == 1){
                                        $cZenDoFollowResult = 'rel="noopener noreferrer"';
                                    }else{
                                        $cZenDoFollowResult = 'rel="nofollow noopener noreferrer"';
                                    }
                                }else{
                                    if($cZenDoFollowPostBtn == 1){
                                        $cZenDoFollowResult = '';
                                    }else{
                                        $cZenDoFollowResult = 'rel="nofollow"';
                                    }
                                }                   
                            ?>

                            <?php if($cZenSiteUrl): ?> 
                                <a class="couponzen__button couponzen__button-visit" href="<?php echo esc_url( $cZenSiteUrl ) ?>" target="<?php echo (get_option('couponzen_url_target')) ? get_option('couponzen_url_target') :  esc_attr( '_blank' ) ?>" <?php echo $cZenDoFollowResult ?>>
                                    <?php echo (get_option('couponzen_button_text')) ? esc_html__( get_option('couponzen_button_text'), 'couponzen' ) : esc_html__( 'Visit Website', 'couponzen'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif;
    endwhile;
}