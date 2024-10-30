<?php 
    /*
    * Coupon Zen Settings 
    */

    class Coupon_Zen_Admin_Settings{

        function __construct(){
            add_action( 'admin_menu', [$this,'add_menu'], 99 );
            add_action( 'admin_init', [$this,'register_settings'] );
        }

        public function add_menu(){
            add_submenu_page( 
                'edit.php?post_type=couponzen', 
                __('Settings','couponzen'), 
                __('Settings','couponzen'), 
                'manage_options',
                'couponzen-setting', 
                [$this, 'couponzen_options'],  
                99
            );
        }
    
        public function couponzen_options(){
            ?>
                <div class="wrap">
                    <h1><?php echo esc_html__( 'Coupon Zen Settings', 'couponzen' ); ?></h1>
                    <?php $this->save_message(); ?>  
                    
                    <h2 class="nav-tab-wrapper">
                        <a class="nav-tab" id="defaultOpen" onclick="couponzen_setting_tab_options(event, 'couponzen_general_setting')"><?php echo esc_html__( 'General', 'couponzen' ); ?></a>
                        <a class="nav-tab" onclick="couponzen_setting_tab_options(event, 'couponzen_style_setting')"><?php echo esc_html__( 'Style', 'couponzen' )?></a>
                    </h2>

                    <form method="post" action="options.php">
                        <?php settings_fields( 'couponzen-plugin-settings-group' ); ?>
                        <?php do_settings_sections( 'couponzen-plugin-settings-group' ); ?>

                        <div id="couponzen_general_setting" class="couponzen_tabcontent">
                            <table class="form-table couponzen-form-table">
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Archive Page Style:', 'couponzen' ); ?></th>
                                    <td>
                                        <select class="couponzen_select_control" name="couponzen_archive_page_style" value="<?php echo esc_attr( get_option('couponzen_archive_page_style') ); ?>" >
                                            <option <?php selected( get_option('couponzen_archive_page_style'), 'style-1' ); ?> value="style-1" ><?php echo esc_html__('Style 1', 'couponzen'); ?></option>
                                            <option <?php selected( get_option('couponzen_archive_page_style'), 'style-2' ); ?> value="style-2"><?php echo esc_html__('Style 2', 'couponzen'); ?></option>
                                            <option <?php selected( get_option('couponzen_archive_page_style'), 'style-3' ); ?> value="style-3"><?php echo esc_html__('Style 3', 'couponzen'); ?></option>
                                            <option <?php selected( get_option('couponzen_archive_page_style'), 'style-4' ); ?> value="style-4"><?php echo esc_html__('Style 4', 'couponzen'); ?></option>
                                            
                                        </select></br>
                                        <span><?php echo esc_html__( 'Select the Archive Page style. The default is Style 1.', 'couponzen'); ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Coupon Per Pages: ', 'couponzen' ); ?></th>
                                    <td>
                                        <input type="number" name="couponzen_par_pages" placeholder="<?php echo esc_attr__( '15', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('couponzen_par_pages') ); ?>" /></br>
                                        <span><?php echo esc_html__( 'Select the number of coupons per page. The default is value 15.', 'couponzen') ?></span>
                                    </td>
                                    
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Post Order:', 'couponzen' ); ?></th>
                                    <td>
                                        <select class="couponzen_select_control" name="couponzen_post_order" value="<?php echo esc_attr( get_option('couponzen_post_order') ); ?>" >
                                            <option <?php selected( get_option('couponzen_post_order'), 'DESC' ); ?> value="<?php echo esc_attr( 'DESC' ); ?>" ><?php echo esc_html__('Descending ', 'couponzen'); ?></option>
                                            <option <?php selected( get_option('couponzen_post_order'), 'ASC' ); ?> value="<?php echo esc_attr( 'ASC' ); ?>"><?php echo esc_html__('Ascending', 'couponzen'); ?></option>                                            
                                        </select></br>
                                        <span><?php echo esc_html__( 'Select whether your coupon to be shown in Ascending or Descending order.', 'couponzen'); ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Post Order By:', 'couponzen' ); ?></th>
                                    <td>
                                        <select id="couponzen_post_order_by" class="couponzen_select_control" name="couponzen_post_order_by" value="<?php echo esc_attr( get_option('couponzen_post_order_by') ); ?>" >
                                            <option <?php selected( get_option('couponzen_post_order_by'), 'ID' ); ?> value="<?php echo esc_attr( 'ID' ); ?>">
                                                <?php echo esc_html__('ID', 'couponzen'); ?>
                                            </option>   
                                            <option <?php selected( get_option('couponzen_post_order_by'), 'title' ); ?> value="<?php echo esc_attr( 'title' ); ?>">
                                                <?php echo esc_html__('Title', 'couponzen'); ?>
                                            </option>
                                            <option <?php selected( get_option('couponzen_post_order_by'), 'date' ); ?> value="<?php echo esc_attr( 'date' ); ?>">
                                                <?php echo esc_html__('Date', 'couponzen'); ?>
                                            </option>                                          
                                            <option <?php selected( get_option('couponzen_post_order_by'), 'modified' ); ?> value="<?php echo esc_attr( 'modified' ); ?>">
                                                <?php echo esc_html__('Modified Date', 'couponzen'); ?>
                                            </option>         
                                            <option <?php selected( get_option('couponzen_post_order_by'), 'rand' ); ?> value="<?php echo esc_attr( 'rand' ); ?>">
                                                <?php echo esc_html__('Random Order', 'couponzen'); ?>
                                            </option>                                   
                                        </select></br>
                                        <span><?php echo esc_html__( 'Select whether your post order to be shown based on various attributes.', 'couponzen'); ?></span>
                                    </td>
                                </tr>
                                                               
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Coupon Text: ', 'couponzen' ); ?></th>
                                    <td>
                                        <input type="text" name="couponzen_coupon_text" placeholder="<?php echo esc_attr__( 'Coupon:', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('couponzen_coupon_text')); ?>" /></br>
                                        <span><?php echo esc_attr__( 'Texts to show what appears as the “coupon text”. The default text is “Coupon”.', 'couponzen'); ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Auto Coupon: ', 'couponzen' ); ?></th>
                                    <td>
                                        <input type="text" name="couponzen_auto_coupon" placeholder="<?php echo esc_attr__( 'Auto Applied', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('couponzen_auto_coupon')); ?>" /></br>
                                        <span><?php echo esc_attr__( 'Texts to show what appears as the “Auto Applied”. The default text is “Auto Applied”.', 'couponzen'); ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Remaining Days:', 'couponzen' ); ?></th>
                                    <td>
                                        <select class="couponzen_select_control" name="couponzen_remaining_days" value="<?php echo esc_attr( get_option('couponzen_remaining_days') ); ?>" >
                                            <option <?php selected( get_option('couponzen_remaining_days'), 'no_need' ); ?> value="no_need"><?php echo esc_html__('No Need', 'couponzen'); ?></option> 
                                            <option <?php selected( get_option('couponzen_remaining_days'), 'remaining_day' ); ?> value="remaining_day" ><?php echo esc_html__('Remaining Day', 'couponzen'); ?></option>
                                            <option <?php selected( get_option('couponzen_remaining_days'), 'expiry_day' ); ?> value="expiry_day" ><?php echo esc_html__('Expiry Day', 'couponzen'); ?></option>
                                        </select></br>
                                        <span><?php echo esc_html__( 'Select the remaining days options 1.No Need, 2.Remaining Day, 3.Expiry Day. The default is No Need.', 'couponzen'); ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Remaining Days Text: ', 'couponzen' ); ?></th>
                                    <td>
                                        <input type="text" name="remaining_days_text" placeholder="<?php echo esc_attr__( '1 days remaining', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('remaining_days_text') ); ?>" /></br>
                                        <span><?php echo esc_html__( 'Texts to show what appears as the “coupon remaining days text”. The default text is “days remaining”.', 'couponzen') ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Expired Text: ', 'couponzen' ); ?></th>
                                    <td>
                                        <input type="text" name="couponzen_expired_text" placeholder="<?php echo esc_attr__( 'Expired on:', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('couponzen_expired_text') ); ?>" /></br>
                                        <span><?php echo esc_html__( 'Texts to show what appears as the “coupon expired text”. The default text is “Expired on”.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                                
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Button Text: ', 'couponzen' )?></th>
                                    <td>
                                        <input type="text" name="couponzen_button_text" placeholder="<?php echo esc_attr__( 'Visit Website', 'couponzen' ) ?>" value="<?php echo esc_attr( get_option('couponzen_button_text') ); ?>" /></br>
                                        <span><?php echo esc_html__( 'Text to show what appears as “button text”. The default text is “Visit Website”.', 'couponzen') ?></span>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Coupon Site URL Target:', 'couponzen' ); ?></th>
                                    <td>
                                        <select class="couponzen_select_control" id="htCzenUrlTarget" name="couponzen_url_target" value="<?php echo esc_attr( get_option('couponzen_url_target') ); ?>" >
                                            <option <?php selected( get_option('couponzen_url_target'), '_blank' ); ?> value="<?php echo esc_attr( '_blank' ) ?>"><?php echo esc_html__('New Tab', 'couponzen') ?></option>    
                                            <option <?php selected( get_option('couponzen_url_target'), '_self' ); ?> value="<?php echo esc_attr( '_self' ) ?>" ><?php echo esc_html__('Self Page', 'couponzen') ?></option>
                                        </select><br>
                                        <span><?php echo esc_html__( 'Select if you want the coupon site to open in a new tab or not. The default value is “_blank”.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                            </table>
                            <?php submit_button() ?>
                        </div>

                        <!-- Style setting Section -->
                        <div id="couponzen_style_setting" class="couponzen_tabcontent">
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Button Text Color:', 'couponzen' ) ?></th>
                                    <td> 
                                        <input type="text" class="couponzen_color-picker"  data-alpha-enabled="true" name="couponzen_button_text_color" value="<?php echo esc_attr(  get_option('couponzen_button_text_color')) ?>"/></br>
                                        <span><?php echo esc_html__( 'Change the Button Text Color for the Coupon.', 'couponzen') ?></span>
                `                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Button Text Hover Color:', 'couponzen' ) ?></th>
                                    <td> 
                                        <input type="text" class="couponzen_color-picker" data-alpha-enabled="true" name="couponzen_button_text_hover_color" value="<?php echo esc_attr( get_option('couponzen_button_text_hover_color')) ?>"/></br>
                                        <span><?php echo esc_html__( 'Change the Button Text Hover Color for the Coupon.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Button Background Color:', 'couponzen' ) ?></th>
                                    <td> 
                                        <input type="text" class="couponzen_color-picker" data-alpha-enabled="true" name="couponzen_button_bg_color" value="<?php echo esc_attr(  get_option('couponzen_button_bg_color')) ?>"/></br>
                                        <span><?php echo esc_html__( 'Change the Button Background Color for the Coupon.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Button Background Hover Color:', 'couponzen' ) ?></th>
                                    <td> 
                                        <input type="text" class="couponzen_color-picker" data-alpha-enabled="true" name="couponzen_button_bg_hover_color" value="<?php echo esc_attr( get_option('couponzen_button_bg_hover_color')) ?>"/></br>
                                        <span><?php echo esc_html__( 'Change the Button Background  Hover Color for the Coupon.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php echo esc_html__( 'Coupon Zen Custom CSS:', 'couponzen' ) ?></th>
                                    <td> 
                                        <textarea rows="4" cols="50" name="couponzen_coustom_css" placeholder="<?php echo esc_attr__( '.example{ color: red; }', 'couponzen' ) ?>"><?php echo esc_attr( get_option('couponzen_coustom_css') ) ?></textarea></br>
                                        <span><?php echo esc_html__( 'Put Any Custom CSS as you want.', 'couponzen') ?></span>
                                    </td>
                                </tr>
                                
                            </table> 
                            <?php submit_button() ?>
                        </div>                       
                    </form>
                </div>

                <script>
                    function couponzen_setting_tab_options(evt, className) {
                        var i, tabcontent, tablinks;
                        tabcontent = document.getElementsByClassName("couponzen_tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                        }
                        tablinks = document.getElementsByClassName("nav-tab");
                        for (i = 0; i < tablinks.length; i++) {
                            tablinks[i].className = tablinks[i].className.replace(" nav-tab-active", "");
                        }
                        document.getElementById(className).style.display = "block";
                        evt.currentTarget.className += " nav-tab-active";
                    }

                    window.onload = function () {
                        startTab();
                    };

                    function startTab() {
                        document.getElementById("defaultOpen").click();
                    }

                </script>
            <?php
        }
    
        public function register_settings() { // whitelist options
            
            //register our settings
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_archive_page_style');
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_par_pages' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_post_order' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_post_order_by' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_show_par_click' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_titel_limit' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_coupon_text' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_auto_coupon' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_remaining_days' );
            register_setting( 'couponzen-plugin-settings-group', 'remaining_days_text' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_expired_text' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_button_text' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_url_target' );

            register_setting( 'couponzen-plugin-settings-group', 'couponzen_button_text_color' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_button_text_hover_color' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_button_bg_color' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_button_bg_hover_color' );
            register_setting( 'couponzen-plugin-settings-group', 'couponzen_coustom_css' );
            
        }
    
        public function save_message() {
            if( isset($_GET['settings-updated']) ): ?>
                <div class="updated notice is-dismissible"> 
                    <p><strong><?php esc_html_e('Successfully Settings Saved.', 'couponzen') ?></strong></p>
                </div>
        <?php
            endif;
        } 
    }

    new Coupon_Zen_Admin_Settings();