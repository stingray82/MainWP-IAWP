<?php
/**
 * Main WP To Independent Analytics Child Plugin
 *
 * @package       MAINWPTOIN
 * @author        Stingray82
 * @license       gplv2
 * @version       1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   Main WP To Independent Analytics Child Plugin
 * Plugin URI:    https://github.com/stingray82/MainWP-IAWP
 * Description:   Install on MainWP Child Sites to work with the Main WP IA Bridge Extention
 * Version:       1.01
 * Author:        Stingray82
 * Author URI:    https://github.com/stingray82
 * Text Domain:   main-wp-to-independent-analytics-child-plugin
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Main WP To Independent Analytics Child Plugin. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

//Creates a new rest api endpoint "{$site_url}/wp-json/iawp/v1/analytics/?from={$from_date}&to={$to_date}";
// Hook into the rest_api_init action to register your custom endpoint
add_action('rest_api_init', function () {
    register_rest_route('iawp/v1', '/analytics/', array(
        'methods' => 'GET',
        'callback' => 'iawp_get_analytics_data',
        'permission_callback' => '__return_true', // Public endpoint
    ));
});

// The callback function that handles the API request
function iawp_get_analytics_data(WP_REST_Request $request) {
    if (function_exists('IAWP_FS')) {
        // Get 'from' and 'to' query parameters
        $from_param = $request->get_param('from');
        $to_param = $request->get_param('to');

        if ($from_param && $to_param) {
            try {
                // Convert the parameters to DateTime objects
                $from_date = new DateTime($from_param);
                $to_date = new DateTime($to_param);

                // Ensure 'from' date is not later than 'to' date
                if ($from_date > $to_date) {
                    return new WP_REST_Response(array('error' => "'from' date cannot be later than 'to' date."), 400);
                }

                // Call the iawp_analytics function with the date range
                $analytics = iawp_analytics($from_date, $to_date);

                // Check if analytics data is valid
                if ($analytics && isset($analytics->views) && isset($analytics->visitors) && isset($analytics->sessions)) {
                    // Return the data as a JSON response
                    return new WP_REST_Response(array(
                        'views' => $analytics->views,
                        'visitors' => $analytics->visitors,
                        'sessions' => $analytics->sessions
                    ), 200);
                } else {
                    return new WP_REST_Response(array('error' => 'Invalid analytics data received.'), 500);
                }
            } catch (Exception $e) {
                return new WP_REST_Response(array('error' => $e->getMessage()), 400);
            }
        } else {
            return new WP_REST_Response(array('error' => 'Missing required parameters: from and to'), 400);
        }
    } else {
        return new WP_REST_Response(array('error' => 'IAWP_FS function not available'), 403);
    }
}

