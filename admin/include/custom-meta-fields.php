<?php
    add_action('admin_init', 'htcouponzen_add_meta_boxes', 2);
    function htcouponzen_add_meta_boxes(){
        add_meta_box(
            'htcouponzen-group', 
            __( 'Couponzen Options', 'couponzen' ), 
            'htcouponzen_meta_box_display', 
            'couponzen', 
            'normal', 
            'default'
        );
    }

    function htcouponzen_meta_box_display(){
        global $post;
        $couponzen_group = get_post_meta( $post->ID, 'couponzen_group', true );

        $CzenSticky = get_post_meta( $post->ID, 'htCzenSticky', true );

        $czenAutoCoupon = get_post_meta( $post->ID, 'htCzenAutoCoupon', true );
        $czenCouponCode = get_post_meta( $post->ID, 'htCzenCouponCode', true );
        $czenCouponDiscount = get_post_meta( $post->ID, 'htCzenCouponDiscount', true );
        $czenEndDate = get_post_meta( $post->ID, 'htCzenEndDate', true );
        $cZenSiteUrl = get_post_meta( $post->ID, 'htCzenSiteUrl', true );
        $czenUrlTarget = get_post_meta( $post->ID, 'htCzenUrlTarget', true );
        $sirveDoFollowPostBtn1 = get_post_meta( $post->ID, 'htCouponzenDoFollowPostBtn', true );

        wp_nonce_field( 'CouponCodeZen', 'htCouponCodeZen' );
        ?>

        <table  class="couponzen_meta_box_table">

            <!-- Sticky | Feature Enable / Disable -->
            <tr>
                <th>
                    <label for="htCzenSticky"><?php echo esc_html__( 'Sticky Post:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="htCzenSticky" name="htCzenSticky" value="1" <?php echo ( $CzenSticky == '1' ) ? esc_attr( 'checked' ) : '' ?> >
                    <p><?php echo esc_html__( 'Make the post sticky. With this feature, place the coupon at the top of the front page of coupons.', 'couponzen' ) ?></p>
                </td>
            </tr>
        </table>
        <hr>
        <table  class="couponzen_meta_box_table">
            <!-- Auto Coupon -->
            <tr>
                <th>
                    <label><?php echo esc_html__( 'Auto Coupon:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="couponzen_autoCoupon" name="htCzenAutoCoupon" value="yes" <?php echo ($czenAutoCoupon == 'yes') ? esc_attr( 'checked' ) : '' ?> >
                </td>
            </tr>    
        
            <!-- Coupon Code -->
            <tr>
                <th>
                    <label><?php echo esc_html__( 'Coupon Code:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="text" id="couponzen_coupon_text" placeholder="<?php echo esc_attr__( 'Please Ender the Coupon Code Here.', 'couponzen' ); ?>" name="htCzenCouponCode" value="<?php if( $czenCouponCode != '') echo esc_attr( $czenCouponCode ); ?>" />
                </td>
            </tr>

            <!-- Coupon Discount -->
            <tr>
                <th>
                    <label><?php echo esc_html__( 'Discount:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="text"  placeholder="<?php echo esc_attr__( '50% Off', 'couponzen' ); ?>" name="htCzenCouponDiscount" value="<?php if( $czenCouponDiscount != '') echo esc_attr__( $czenCouponDiscount, 'couponzen' ); ?>" />
                </td>
            </tr>
            <!-- Expired Date -->
            <tr>
                <th>
                    <label><?php esc_html_e( 'Expired Date:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="date" id="htCzenEndDate" name="htCzenEndDate" value="<?php if( $czenEndDate != '') echo date( esc_attr( $czenEndDate )); ?>">
                </td>
            </tr>

            <!-- Coupon Site URL -->
            <tr>
                <th>
                    <label><?php esc_html_e( 'Coupon Site URL:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="url"  placeholder="<?php echo esc_attr__( 'https://example.com', 'couponzen' ); ?>" name="htCzenSiteUrl" value="<?php if( $cZenSiteUrl != '') echo esc_attr__( $cZenSiteUrl, 'couponzen' ); ?>" />
                </td>
            </tr>

            <!-- DoFollow Button 1 Enable / Disable -->
            <tr>
                <th>
                    <label for="htCouponzenDoFollowPostBtn"><?php echo esc_html__( 'Link DoFollow Enable:', 'couponzen' ); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="htCouponzenDoFollowPostBtn" name="htCouponzenDoFollowPostBtn" value="1" <?php checked( $sirveDoFollowPostBtn1, '1' ); ?> >
                    <?php echo esc_html__( 'Do you want DoFollow Link?', 'sirve' ) ?>
                </td>
            </tr>

        </table>
        <?php
    }

    add_action('save_post', 'htcouponzen_meta_box_save');
    function htcouponzen_meta_box_save( $post_id ){
        

        if ( ! isset( $_POST['htCouponCodeZen'] ) || !wp_verify_nonce( $_POST['htCouponCodeZen'], 'CouponCodeZen' ) ){
            return;
        }

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
            return;
        }

        if (!current_user_can('edit_post', $post_id)){
            return;
        }

        // Social Icon Group
        $old = get_post_meta( $post_id, 'couponzen_group', true );
        $new = array();

        if ( !empty( $new ) && $new != $old ){
            update_post_meta( $post_id, 'couponzen_group', $new );

        }elseif ( empty($new) && $old ){
            delete_post_meta( $post_id, 'couponzen_group', $old );
        }

         // List Sticky
         $czenSticky = sanitize_text_field( absint( $_POST['htCzenSticky'] ));
         update_post_meta( $post_id, 'htCzenSticky', $czenSticky );

        // Auto Coupon
        $oldCzenAutoCoupon = get_post_meta( $post_id, 'htCzenAutoCoupon', true );
        $czenAutoCoupon = sanitize_text_field( $_POST['htCzenAutoCoupon'] );

        if ( !empty( $czenAutoCoupon ) && $czenAutoCoupon != $oldCzenAutoCoupon ){
            update_post_meta( $post_id, 'htCzenAutoCoupon', $czenAutoCoupon );

        }elseif ( empty($czenAutoCoupon) && $oldCzenAutoCoupon ){
            delete_post_meta( $post_id, 'htCzenAutoCoupon', $oldCzenAutoCoupon );
        }

        // Coupon Code
        $oldCzenCouponCode = get_post_meta( $post_id, 'htCzenCouponCode', true );
        $czenCouponCode = sanitize_text_field( $_POST['htCzenCouponCode'] );

        if ( !empty( $czenCouponCode ) && $czenCouponCode != $oldCzenCouponCode ){
            update_post_meta( $post_id, 'htCzenCouponCode', $czenCouponCode );

        }elseif ( empty($czenCouponCode) && $oldCzenCouponCode ){
            delete_post_meta( $post_id, 'htCzenCouponCode', $oldCzenCouponCode );
        }

        //  Coupon Discount
        $oldCzenCouponDiscount = get_post_meta( $post_id, 'htCzenCouponDiscount', true );
        $cZenCouponDiscount = sanitize_text_field( $_POST['htCzenCouponDiscount'] );

        if ( !empty( $cZenCouponDiscount ) && $cZenCouponDiscount != $oldCzenCouponDiscount ){
            update_post_meta( $post_id, 'htCzenCouponDiscount', $cZenCouponDiscount );

        }elseif ( empty($cZenCouponDiscount) && $oldCzenCouponDiscount ){
            delete_post_meta( $post_id, 'htCzenCouponDiscount', $oldCzenCouponDiscount );
        }

        //Coupon End Date
        $oldCzenEndDate = get_post_meta( $post_id, 'htCzenEndDate', true );
        $czenEndDate = sanitize_text_field( $_POST['htCzenEndDate'] );

        if ( !empty( $czenEndDate ) && $czenEndDate!= $oldCzenEndDate ){
            update_post_meta( $post_id, 'htCzenEndDate', $czenEndDate );

        }elseif ( empty( $czenEndDate ) && $oldCzenEndDate ){
            delete_post_meta( $post_id, 'htCzenEndDate', $oldCzenEndDate );
        }

        //Coupon Site Url
        $oldcZenSiteUrl = get_post_meta( $post_id, 'htCzenSiteUrl', true );
        $cZenSiteUrl = sanitize_text_field( $_POST['htCzenSiteUrl'] );

        if ( !empty( $cZenSiteUrl ) && $cZenSiteUrl != $oldcZenSiteUrl ){
            update_post_meta( $post_id, 'htCzenSiteUrl', $cZenSiteUrl );

        }elseif ( empty($cZenSiteUrl) && $oldcZenSiteUrl ){
            delete_post_meta( $post_id, 'htCzenSiteUrl', $oldcZenSiteUrl );
        }

        // Button 1 DoFollow
        $SirveDoFollowPostValue = !empty($_POST['htCouponzenDoFollowPostBtn']) ? $_POST['htCouponzenDoFollowPostBtn'] : 0;
        $sirveDoFollowPostBtn1 = sanitize_text_field( absint( $SirveDoFollowPostValue ));
        update_post_meta( $post_id, 'htCouponzenDoFollowPostBtn', $sirveDoFollowPostBtn1 );
    }