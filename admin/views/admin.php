<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Pinit
 * @author    Eugenio Petullà <support@codeat.co>
 * @license   GPL-2.0+
 * @link      http://codeat.co
 * @copyright 2016 GPL v2 and Later
 */
?>

<div class="wrap">

    <h2>Pinit General Settings</h2>

    <div id="tabs" class="settings-tab">
        <ul>
            <li><a href="#tabs-1"><?php _e( 'Settings', $this->plugin_slug ); ?></a></li>
        </ul>
        <div id="tabs-1" class="wrap">
            <?php
            $cmb = new_cmb2_box( array(
                'id' => $this->plugin_slug . '_options',
                'hookup' => false,
                'show_on' => array( 'key' => 'options-page', 'value' => array( $this->plugin_slug ), ),
                'show_names' => true,
                    ) );
            $cmb->add_field( array(
                'name' => __( 'Insert Pinit Button on hover', $this->plugin_slug ),
                'desc' => __('This feature will be activated on EVERY image of your website.', $this->plugin_slug ),
                'id' => 'on_hover',
                'type' => 'checkbox',
            ) );
            $cmb->add_field( array(
                'name' => __( 'Activate Pinit buttons in single post', $this->plugin_slug ),
                'desc' => __('This can be combined with pages and prevent activation in other post types, archives and frontpage.', $this->plugin_slug ),
                'id' => 'single_post',
                'type' => 'checkbox',
            ) );
            $cmb->add_field( array(
                'name' => __( 'Activate Pinit buttons in single pages', $this->plugin_slug ),
                'desc' => __('This can be combined with post and prevent activation in other post types, archives and frontpage.', $this->plugin_slug ),
                'id' => 'single_page',
                'type' => 'checkbox',
            ) );
            $cmb->add_field( array(
                'name' => __( 'Round Button', $this->plugin_slug ),
                'id' => 'round',
                'type' => 'checkbox',
            ) );
            $cmb->add_field( array(
                'name' => __( 'Large Button', $this->plugin_slug ),
                'id' => 'large',
                'type' => 'checkbox',
            ) );
            $cmb->add_field( array(
                'name' => __( 'Language', $this->plugin_slug ),
                'desc' => __('Useless if round button setting is active', $this->plugin_slug ),
                'id' => 'language',
                'type' => 'select',
                'options' => array(
                    'en' => __( 'English', $this->plugin_slug ),
                    'ja' => __( 'Japanese', $this->plugin_slug ),
                )
            ) );
            cmb2_metabox_form( $this->plugin_slug . '_options', $this->plugin_slug . '-settings' );
            ?>
        </div>
    </div>
    <!-- Begin MailChimp  -->
    <div class="right-column-settings-page metabox-holder">
        <div class="postbox codeat newsletter">
            <h3 class="hndle"><span><?php _e( 'Codeat Newsletter', $this->plugin_slug ); ?></span></h3>
            <div class="inside">
            <!-- Begin MailChimp Signup Form -->
                <div id="mc_embed_signup">
                    <form action="//codeat.us12.list-manage.com/subscribe/post?u=07eeb6c8b7c0e093817bd29d1&amp;id=8e8f10fb4d" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll"> 
                            <div class="mc-field-group">
                                <label for="mce-EMAIL">Email Address </label>
                                <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                            </div>
                            <div id="mce-responses" class="clear">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>
                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                <input type="text" name="b_07eeb6c8b7c0e093817bd29d1_8e8f10fb4d" tabindex="-1" value="">
                            </div>
                            <div class="clear">
                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                            </div>
                        </div>
                    </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
                <script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
            </div>
        </div>
    </div>
    <!-- Begin Social Links -->
    <div class="right-column-settings-page metabox-holder">
        <div class="postbox codeat social">
            <h3 class="hndle"><span><?php _e( 'Follow us', $this->plugin_slug ); ?></span></h3>
            <div class="inside">
                <a href="https://facebook.com/codeatco/" target="_blank"><img src="http://i2.wp.com/codeat.co/wp-content/uploads/2016/03/social-facebook-light.png?w=52" alt="facebook"></a>
                <a href="https://twitter.com/codeatco/" target="_blank"><img src="http://i0.wp.com/codeat.co/wp-content/uploads/2016/03/social-twitter-light.png?w=52" alt="twitter"></a>
                <a href="https://linkedin.com/company/codeat/" target="_blank"><img src="http://i1.wp.com/codeat.co/wp-content/uploads/2016/03/social-linkedin-light.png?w=52" alt="linkedin"></a>
            </div>
        </div>
    </div>
    <!-- Begin Plugin List -->
    <div class="right-column-settings-page metabox-holder">
        <div class="postbox codeat">
            <h3 class="hndle"><span><?php _e( 'A Codeat Plugin', $this->plugin_slug ); ?></span></h3>
            <div class="inside">
                <a href="http://codeat.co" target="_blank"><img src="http://i2.wp.com/codeat.co/wp-content/uploads/2016/02/cropped-logo-light.png?w=236" alt="Codeat"></a>
                <a href="http://deshack.net/" class="deshack" target="_blank">Made with &hearts; in collaboration with deshack</a>
                <a href="http://codeat.co/glossary/" target="_blank"><img src="http://i0.wp.com/codeat.co/glossary/wp-content/uploads/sites/3/2016/02/cropped-Glossary_logo-ori-Lite-1.png?w=236" alt="Glossary For WordPress"></a>
                <a href="http://codeat.co/pinit/" target="_blank"><img src="http://i1.wp.com/codeat.co/pinit/wp-content/uploads/sites/2/2016/02/cropped-PinterestForWP_logo-ori-Lite-1.png?w=236" alt="Pinterest for WordPress"></a>
            </div>
        </div>
    </div>
</div>