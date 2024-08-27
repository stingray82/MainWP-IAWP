<?php
/**
 * Main WP To Independent Analytics Child Plugin
 *
 * @author        Stingray82
 * @license       gplv2
 * @version       1.00
 *
 * @wordpress-plugin
 * Plugin Name:   Main WP To Independent Analytics Bridge
 * Plugin URI:    https://github.com/stingray82/MainWP-IAWP
 * Description:   Install on your dashboard and it will interface with the child sites you have IA installed on;
 * Version:       1.00
 * Author:        Stingray82
 * Author URI:    https://github.com/stingray82
 * Text Domain:   main-wp-to-independent-analytics-bridge-extention
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Main WP To Independent Analytics Child Plugin. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class MainWP_IAWP_Bridge_Extension {

    public function __construct() {
        add_filter( 'mainwp_getsubpages_sites', array( &$this, 'managesites_subpage' ), 10, 1 );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );
    }

    public function admin_init() {
        // Register settings
        register_setting('mainwp_iawp_bridge_options_group', 'iamwp_custom_from_date');
        register_setting('mainwp_iawp_bridge_options_group', 'iamwp_custom_to_date');
    }

    public function managesites_subpage( $subPage ) {
        $subPage[] = array(
            'title'       => 'IAWP Bridge',
            'slug'        => 'IAWPBridge',
            'sitetab'     => true,
            'menu_hidden' => true,
            'callback'    => array( static::class, 'renderPage' ),
        );

        return $subPage;
    }

    public function enqueue_admin_scripts() {
        // Enqueue jQuery UI Datepicker
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    }

    public static function renderPage() {
        global $mainwpIAWPBridgeActivator;

        // Ensure the global variable is accessible and initialized
        if (!$mainwpIAWPBridgeActivator) {
            echo 'MainWP Bridge Activator is not initialized.';
            return;
        }

        // Output the inline script to initialize the datepicker
        echo '<script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".custom-date-picker").datepicker({
                    dateFormat: "yy-mm-dd"
                });
            });
        </script>';
        
        ?>
        <div class="ui segment">
            <div class="inside">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('mainwp_iawp_bridge_options_group');
                    do_settings_sections('mainwp_iawp_bridge_options_group');

                    $from_date = get_option('iamwp_custom_from_date', '');
                    $to_date = get_option('iamwp_custom_to_date', '');
                    ?>

                    <h2><?php _e('Set Date Range for Analytics'); ?></h2>

                    <p>
                        <label for="iamwp_custom_from_date"><?php _e('From Date'); ?>:</label><br>
                        <input type="text" id="iamwp_custom_from_date" name="iamwp_custom_from_date" value="<?php echo esc_attr($from_date); ?>" class="custom-date-picker" />
                    </p>

                    <p>
                        <label for="iamwp_custom_to_date"><?php _e('To Date'); ?>:</label><br>
                        <input type="text" id="iamwp_custom_to_date" name="iamwp_custom_to_date" value="<?php echo esc_attr($to_date); ?>" class="custom-date-picker" />
                    </p>

                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
        <?php
    }
}

// Close the MainWP_IAWP_Bridge_Extension class here

//Have kept this in as if we can get it working automatically wont need to pass the paramaters manually as currently needed 
/* function iwap_generate_Custom_analytics_tokens_old($tokensValues, $report, $website) {
    // Log for debugging
    error_log('Custom token filter applied');

    // Get the site URL and report date range
    //$site_url2 = [client.site.url]; // Accessing as an object, not an array
    //error_log('Site URL:'.$site_url2);
    //$date_range = $report->daterange; // Accessing as an object, not an array

    // Split the date range into from and to dates
    ///list($from_date, $to_date) = explode(' - ', $date_range);

    // Convert the dates into the format needed for the API (Y-m-d)
    //$from_date_obj = DateTime::createFromFormat('d-m-Y', trim($from_date));
    //$to_date_obj = DateTime::createFromFormat('d-m-Y', trim($to_date));
    
    /* As I can't process the "token" need supports help for this one will temp add them in to check everything else works */
    // Variables
    /*$from_date_obj = "2024-08-01";
    $to_date_obj = "2024-08-27";
    $site_url = "https://home-heroes.co.uk";

    // Use the passed $site_url
    $from_date_obj = "2024-08-01";
    $to_date_obj = "2024-08-27";

    
    // Check if the DateTime objects were created successfully
    if (!$from_date_obj || !$to_date_obj) {
        error_log('Error: Invalid date format in report.daterange.');
        return $tokensValues; // Return early if the dates are invalid
    }

   // $from_date = $from_date_obj->format('Y-m-d');
    //$to_date = $to_date_obj->format('Y-m-d');
    $from_date = $from_date_obj;
    $to_date = $to_date_obj;

    // Build the API URL
    //$api_url = "{$site_url}/test.php?from={$from_date}&to={$to_date}";
    // The Below works with the new child plugin for wider testing
    $api_url = "{$site_url}/wp-content/plugins/main-wp-to-independent-analytics-child-plugin/api.php?from={$from_date}&to={$to_date}";

    // Fetch the data from the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_wp_error($response)) {
        error_log('Error: Failed to retrieve data from API. ' . $response->get_error_message());
        return $tokensValues;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    // Check if the data is valid
    if (isset($data->views) && isset($data->visitors) && isset($data->sessions)) {
        // Assign the values to variables
        $ipwa_views = $data->views;
        $ipwa_visitors = $data->visitors;
        $ipwa_sessions = $data->sessions;
        error_log('Views:'.$ipwa_views);
        error_log('Visitors:'.$ipwa_visitors);
        error_log('Sessions:'.$ipwa_sessions);

        // Add these values as tokens
        $tokensValues['[ipwa-views]'] = $ipwa_views;
        $tokensValues['[ipwa-visitors]'] = $ipwa_visitors;
        $tokensValues['[ipwa-sessions]'] = $ipwa_sessions;

        // Log success
        error_log('Successfully retrieved and processed data from API.');
    } else {
        // Log if data is invalid
        error_log('Error: Invalid data received from API.');
    }

    return $tokensValues;
} */

 function iwap_generate_Custom_analytics_tokens($tokensValues, $report, $website, $site_url) {
    error_log('Custom token filter applied');

 // Get the date range from the options set by the user
    $from_date_obj = get_option('iamwp_custom_from_date', '');
$to_date_obj = get_option('iamwp_custom_to_date', '');

    // Check if the DateTime objects were passed successfully
    if (!$from_date_obj || !$to_date_obj) {
        error_log('Error: Invalid date format.');
        return $tokensValues; // Return early if the dates are invalid
    }

    // Convert the dates to the required format
    $from_date = $from_date_obj;
    $to_date = $to_date_obj;

    // Build the API URL
    //$api_url = "{$site_url}/test.php?from={$from_date}&to={$to_date}";
    // The Below works with the new child plugin for wider testing
    $api_url = "{$site_url}/wp-content/plugins/main-wp-to-independent-analytics-child-plugin/api.php?from={$from_date}&to={$to_date}";

    // Fetch the data from the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_wp_error($response)) {
        error_log('Error: Failed to retrieve data from API. ' . $response->get_error_message());
        return $tokensValues;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    // Check if the data is valid
    if (isset($data->views) && isset($data->visitors) && isset($data->sessions)) {
        // Assign the values to variables
        $ipwa_views = $data->views;
        $ipwa_visitors = $data->visitors;
        $ipwa_sessions = $data->sessions;
        error_log('Views:'.$ipwa_views);
        error_log('Visitors:'.$ipwa_visitors);
        error_log('Sessions:'.$ipwa_sessions);

        // Add these values as tokens
        $tokensValues['[ipwa-views]'] = $ipwa_views;
        $tokensValues['[ipwa-visitors]'] = $ipwa_visitors;
        $tokensValues['[ipwa-sessions]'] = $ipwa_sessions;

        // Log success
        error_log('Successfully retrieved and processed data from API.');
    } else {
        // Log if data is invalid
        error_log('Error: Invalid data received from API.');
    }

    return $tokensValues;
}


class MainWP_IAWP_Bridge_Activator {

    protected $mainwpIAWPBridgeActivated = false;
    protected $childEnabled = false;
    protected $childKey = false;
    protected $childFile;
    protected $plugin_handle = 'mainwp-IAWP-Bridge-extension';

    public function __construct() {
        $this->childFile = __FILE__;
        add_filter( 'mainwp_getextensions', array( &$this, 'get_this_extension' ) );

        // This filter will return true if the main plugin is activated
        $this->mainwpIAWPBridgeActivated = apply_filters( 'mainwp_activated_check', false );

        if ( $this->mainwpIAWPBridgeActivated !== false ) {
            $this->activate_this_plugin();
        } else {
            // Listening to the 'mainwp_activated' action
            add_action( 'mainwp_activated', array( &$this, 'activate_this_plugin' ) );
        }
        add_action( 'admin_notices', array( &$this, 'mainwp_error_notice' ) );
    }

    function get_this_extension( $pArray ) {
        $pArray[] = array(
            'plugin'   => __FILE__,
            'api'      => $this->plugin_handle,
            'mainwp'   => false,
            'callback' => array( &$this, 'settings' ),
        );
        return $pArray;
    }

    function settings() {
        do_action( 'mainwp_pageheader_extensions', __FILE__ );
        if ( $this->childEnabled ) {
            MainWP_IAWP_Bridge_Extension::renderPage();
        } else {
            ?>
            <div class="mainwp_info-box-yellow"><?php _e( 'The Extension has to be enabled to change the settings.' ); ?></div>
            <?php
        }
        do_action( 'mainwp_pagefooter_extensions', __FILE__ );
    }

    function activate_this_plugin() {
        $this->mainwpIAWPBridgeActivated = apply_filters( 'mainwp_activated_check', $this->mainwpIAWPBridgeActivated );
        $this->childEnabled = apply_filters( 'mainwp_extension_enabled_check', __FILE__ );
        $this->childKey = $this->childEnabled['key'];

        new MainWP_IAWP_Bridge_Extension();
    }

    function mainwp_error_notice() {
        global $current_screen;
        if ( $current_screen->parent_base == 'plugins' && $this->mainwpIAWPBridgeActivated == false ) {
            echo '<div class="error"><p>MainWP IAWP_Bridge! Extension ' . __( 'requires ' ) . '<a href="http://mainwp.com/" target="_blank">MainWP</a>' . __( ' Plugin to be activated in order to work. Please install and activate' ) . '<a href="http://mainwp.com/" target="_blank">MainWP</a> ' . __( 'first.' ) . '</p></div>';
        }
    }

    public function getChildKey() {
        return $this->childKey;
    }

    public function getChildFile() {
        return $this->childFile;
    }
}

global $mainwpIAWPBridgeActivator;
$mainwpIAWPBridgeActivator = new MainWP_IAWP_Bridge_Activator();
