<?php
/**
 * Plugin Name:       License Manager
 * Description:       Rewrite the woocommerce to be suitable for add licenses
 * Version:           1.0.0
 * Requires PHP:      7.2
 * Author:            Nourhan Mahmoud
 * License:           GPL v2 or later
 */

require plugin_dir_path( __FILE__ ). '/src/RemoveMetaBox.php';
require plugin_dir_path( __FILE__ ). '/src/ChangeMyadmin.php';
require plugin_dir_path( __FILE__ ). '/src/ChangeMyaccount.php';
require plugin_dir_path( __FILE__ ). '/src/AfterPaymentProcess.php';
require plugin_dir_path( __FILE__ ). '/src/RestrictUsers.php';
require plugin_dir_path( __FILE__ ). '/src/PreventDuplicateLicense.php';



new RemoveMetaBox();
new ChangeMyadmin();
new ChangeMyaccount();
new AfterPaymentProcess();
new JPB_User_Caps();
new PreventDuplicateLicense();