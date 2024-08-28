<?php
/**
 * Main WP To Independent Analytics Child Plugin
 *
 * @author        Stingray82
 * @license       gplv2
 * @version       1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   Main WP To Independent Analytics Bridge
 * Plugin URI:    https://github.com/stingray82/MainWP-IAWP
 * Description:   Install on your dashboard and it will interface with the child sites you have IA

2/2

You said:
Can I have the full plugin with the debug check?
ChatGPT said:
ChatGPT
Certainly! Here's the updated plugin code with a debug check based on the existence of debug.conf. The plugin will conditionally enable debugging if the file is found.

Updated Plugin Code
php
Copy code
<?php
/**
 * Main WP To Independent Analytics Child Plugin
 *
 * @author        Stingray82
 * @license       gplv2
 * @version       1.01
 *
 * @wordpress-plugin
 * Plugin Name:   Main WP To Independent Analytics Bridge
 * Plugin URI:    https://github.com/stingray82/MainWP-IAWP
 * Description:   Install on your dashboard and it will interface with the child sites you have IA installed on;
 * Version:       1.01
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

// Check for the existence of the debug.conf file to enable debugging
$debug_enabled = file_exists(__DIR__ . '/debug.conf');

// Function to log messages if debugging is enabled
function debug_log($message) {
    global $debug_enabled;
    if ($debug_enabled) {
        debug_log($message);
    }
}

class MainWP_IAWP_Bridge_Extension {

    public function __construct() {
        add_filter( 'mainwp_getsubpages_sites', array( &$this, 'managesites_subpage' ), 10, 1 );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
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

    public static function renderPage() {
        global $mainwpIAWPBridgeActivator;

        // Ensure the global variable is accessible and initialized
        if (!$mainwpIAWPBridgeActivator) {
            echo 'MainWP Bridge Activator is not initialized.';
            return;
        }

        ?>
        <div class="ui segment">
                <div class="inside">
                    <p><?php _e('There is nothing to set in here, this is used for debug only'); ?></p>
                </div>
            </div>
        <?php
    }
}

add_filter('mainwp_pro_reports_custom_tokens', 'iwap_generate_custom_analytics_tokens', 10, 4);

function iwap_generate_custom_analytics_tokens($tokensValues, $report, $site, $templ_email) {
    debug_log('IAMP-Custom token filter applied');

    // Ensure the site URL is set
    $site_url = isset($site['url']) ? $site['url'] : '';
    if (!$site_url) {
        debug_log('IAMP-Error: Site URL is not set.');
        return $tokensValues;
    }

    // Add the site URL to the tokens array
    $tokensValues['[IPWA_SITE_URL]'] = $site_url;
    debug_log('IAMP-Site URL: ' . $site_url);

    // Debugging: Log the entire $report object to inspect its structure
    debug_log('IAMP-Report Object: ' . print_r($report, true));

    // Retrieve the date range from the report object and convert the timestamps to a readable format
    $from_date = isset($report->date_from) ? date('Y-m-d', $report->date_from) : '';
    $to_date = isset($report->date_to) ? date('Y-m-d', $report->date_to) : '';

    // Log the retrieved dates
    debug_log('IAMP-From Date: ' . $from_date);
    debug_log('IAMP-To Date: ' . $to_date);

    // Check if the dates are valid
    if (!$from_date || !$to_date) {
        debug_log('IAMP-Error: Invalid date format.');
        return $tokensValues; // Return early if the dates are invalid
    }

    // Add the dates to the tokens array
    $tokensValues['[IPWA_FROM_DATE]'] = $from_date;
    $tokensValues['[IPWA_TO_DATE]'] = $to_date;

    // Build the API URL for fetching analytics data
    $api_url = "{$site_url}/wp-json/iawp/v1/analytics/?from={$from_date}&to={$to_date}";
    debug_log('IAMP-API URL: ' . $api_url);

    // Fetch the data from the API
    $response = wp_remote_get($api_url);

    // Check if the request was successful
    if (is_wp_error($response)) {
        debug_log('IAMP-Error: Failed to retrieve data from API. ' . $response->get_error_message());
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
        debug_log('IAMP-Views: ' . $ipwa_views);
        debug_log('IAMP-Visitors: ' . $ipwa_visitors);
        debug_log('IAMP-Sessions: ' . $ipwa_sessions);

        // Add these values as tokens
        $tokensValues['[ipwa-views]'] = $ipwa_views;
        $tokensValues['[ipwa-visitors]'] = $ipwa_visitors;
        $tokensValues['[ipwa-sessions]'] = $ipwa_sessions;

        // Log success
        debug_log('IAMP-Successfully retrieved and processed data from API.');
    } else {
        // Log if data is invalid
        debug_log('IAMP-Error: Invalid data received from API.');
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
