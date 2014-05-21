<?php
/*
 * Plugin Name: Call From Web
 * Plugin URI: http://www.call-from-web.com/plugins/wordpress
 * Description: Let your visitors call you by phone for free.
 * Version: 2.3
 * Author: Call From Web
 * Author URI: http://www.call-from-web.com
 * License: GPL2
 * */
?>
<?php

add_action('admin_menu', 'cfw_add_page_fn');
// Add sub page to the Settings Menu
function cfw_add_page_fn() {
  add_options_page('Call From Web', 'Call From Web', 'administrator', __FILE__, 'cfw_options_page_fn');
}

function cfw_options_page_fn() {
?>
  <div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Call From Web</h2>
    Set up your domain widget ID here to be able to view your comments. <h3>Need a valid widget ID? <a href="http://www.call-from-web.com/users/sign_up?utm_source=wordpress&utm_medium=plugin&utm_campaign=settings">Sign up</a>.</h3>
    <form action="options.php" method="post">
    <?php settings_fields('cfw_plugin_options'); ?>
    <?php do_settings_sections(__FILE__); ?>
    <p class="submit">
      <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
    </p>
    <h3><a href="http://www.call-from-web.com/contact_attempts?utm_source=wordpress&utm_medium=plugin&utm_campaign=settings">Log in</a> to check for incoming calls.</h3>
    </form>
  </div>
<?php
}

add_action('admin_init', 'cfw_options_init_fn' );
// Register our settings. Add the settings section, and settings fields
function cfw_options_init_fn(){
  register_setting('cfw_plugin_options', 'cfw_plugin_options', 'cfw_plugin_options_validate' );
  add_settings_section('cfw_main_section', 'Button Settings', 'cfw_section_text_fn', __FILE__);
  add_settings_field('cfw_profile', 'Widget ID', 'cfw_setting_profile', __FILE__, 'cfw_main_section');
  add_settings_field('cfw_button_title', 'Button Title', 'cfw_setting_button_title', __FILE__, 'cfw_main_section');
}

function cfw_section_text_fn(){}

function cfw_setting_profile() {
  $options = get_option('cfw_plugin_options');
  echo "<input id='plugin_text_string' name='cfw_plugin_options[cfw_profile]' size='40' type='text' value='{$options['cfw_profile']}' />";
}

function cfw_setting_key() {
  $options = get_option('cfw_plugin_options');
  echo "<input id='plugin_text_string' name='cfw_plugin_options[cfw_key]' size='40' type='text' value='{$options['cfw_key']}' />";
}

function cfw_setting_button_title() {
  $options = get_option('cfw_plugin_options');
  $button_title = empty($options['cfw_button_title']) ? 'Contact Us' : $options['cfw_button_title'];
  echo "<input id='plugin_text_string' name='cfw_plugin_options[cfw_button_title]' size='40' type='text' value='{$button_title}' />";
}


function cfw_plugin_options_validate($input) {
  // Check our textbox option field contains no HTML tags - if so strip them out
  $input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);
  return $input; // return validated input
}


// add meta info
add_action('wp_footer', 'cfw_addButton');
function cfw_addButton(){
  $o = get_option('cfw_plugin_options');
  $profile = empty($o['cfw_profile']) ? "" : "/{$o['cfw_profile']}";
  $button_title = empty($o['cfw_button_title']) ? 'Contact Us' : $o['cfw_button_title'];
  echo "<a href=\"http://www.call-from-web.com/request{$profile}\" class=\"call-from-web\">{$button_title}</a>";
  echo '<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://cdn.call-from-web.com/assets/form/v1.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","call-from-web-js");</script>';
}

function cfw_no_api_key_provided_admin_notice() {
    $no_api_key_provided =<<<EOS
<p>You have not entered your Widget ID for <b>Call From Web</b> plugin. <a href="options-general.php?page=call-from-web/call-from-web.php">Please enter your Widget ID here</a>.</p>
<p><a href="http://www.call-from-web.com/users/sign_up?utm_source=wordpress&utm_medium=plugin&utm_campaign=notice">Get your Widget ID if you don't have one here.</a></p>
EOS;
    ?>
    <div class="error">
        <p><?php _e( $no_api_key_provided ); ?></p>
    </div>
    <?php
}

$o = get_option('cfw_plugin_options');
if (empty($o['cfw_profile'])) { add_action( 'admin_notices', 'cfw_no_api_key_provided_admin_notice'); }

