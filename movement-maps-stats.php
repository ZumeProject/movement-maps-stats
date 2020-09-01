<?php
/**
 * Plugin Name: Movement Maps and Stats
 * Plugin URI: https://github.com/ZumeProject/movement-maps-stats
 * Description: Short code pack of maps and stats for public display of Disciple Tools Network. Designed for the Zúme Vision, but open source and can be forked.
 * Version:  0.1
 * Author URI: https://github.com/ZumeProject
 * GitHub Plugin URI: https://github.com/ZumeProject/movement-maps-stats
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.5
 *
 * @package Zume
 * @link    https://github.com/ZumeProject
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

add_action( 'after_setup_theme', function (){
    $wp_theme = wp_get_theme();
    $is_theme_dt = strpos( $wp_theme->get_template(), "disciple-tools-theme" ) !== false || $wp_theme->name === "Disciple Tools";
    if ( $is_theme_dt ) {
        add_action( 'admin_notices', 'movement_maps_stats_plugin_must_not_be_disciple_tools' );
        return false;
    }
    if ( ! is_multisite() ) {
        add_action( 'admin_notices', 'movement_maps_stats_plugin_must_be_multisite' );
        return false;
    }
    return Movement_Maps_Stats::instance();
}, 99 );


/**
 * Class Movement_Maps_Stats
 */
class Movement_Maps_Stats {

    public $token = 'movement_maps_stats';
    public $title = 'Movement Maps and Stats';
    public $permissions = 'manage_options';

    /**  Singleton */
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Constructor function.
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {
        $this->setup_db();

        // load short codes
        $files = scandir(plugin_dir_path(__FILE__) . 'shortcodes');
        foreach ( $files as $file ) {
            if ( 'shortcode' === substr( $file, 0, 9 ) ){
                require_once( plugin_dir_path(__FILE__) . 'shortcodes/' . $file );
            }
        }

        if ( is_admin() ) {
            add_action( "admin_menu", [ $this, "register_menu" ] );
            // adds links to the plugin description area in the plugin admin list.
            add_filter( 'plugin_row_meta', [ $this, 'plugin_description_links' ], 10, 4 );
        }
    } // End __construct()

    public function setup_db(){
        global $wpdb;

        if ( is_multisite() ) {
            $enabled_sites = get_site_option( 'movement_map_approved_sites' );
            // test if this site is approved through the multisite plugin
            if ( isset( $enabled_sites[get_current_blog_id()] ) ) {
                $wpdb->dt_movement_log = $enabled_sites[get_current_blog_id()]['table'];
                return true;
            } else {
                return false;
            }
        } else {
            // plugin cannot run on single site disciple tools system.
            return false;
        }
    }


    /**
     * Loads the subnav page
     * @since 0.1
     */
    public function register_menu() {
        add_menu_page( 'Extensions (DT)', 'Extensions (DT)', $this->permissions, 'dt_extensions', [ $this, 'extensions_menu' ], 'dashicons-admin-generic', 59 );
        add_submenu_page( 'dt_extensions', $this->title, $this->title, $this->permissions, $this->token, [ $this, 'content' ] );
    }

    /**
     * Menu stub. Replaced when Disciple Tools Theme fully loads.
     */
    public function extensions_menu() {}

    /**
     * Builds page contents
     * @since 0.1
     */
    public function content() {

        if ( !current_user_can( $this->permissions ) ) { // manage dt is a permission that is specific to Disciple Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        ?>
        <div class="wrap">
            <h2><?php echo esc_html( $this->title ) ?></h2>
            <div class="wrap">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <!-- Main Column -->

                            <?php $this->main_column(); ?>

                            <!-- End Main Column -->
                        </div><!-- end post-body-content -->
                        <div id="postbox-container-1" class="postbox-container">
                            <!-- Right Column -->

                            <?php $this->right_column(); ?>

                            <!-- End Right Column -->
                        </div><!-- postbox-container 1 -->
                        <div id="postbox-container-2" class="postbox-container">
                        </div><!-- postbox-container 2 -->
                    </div><!-- post-body meta box container -->
                </div><!--poststuff end -->
            </div><!-- wrap end -->
        </div><!-- End wrap -->

        <?php
    }

    public function main_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr>
                <th>Short Codes</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php do_action( 'movement_maps_stats_shortcodes_list' ) ?>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    public function right_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr><th>Information</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    /**
     * Filters the array of row meta for each/specific plugin in the Plugins list table.
     * Appends additional links below each/specific plugin on the plugins page.
     *
     * @access  public
     * @param   array       $links_array            An array of the plugin's metadata
     * @param   string      $plugin_file_name       Path to the plugin file
     * @param   array       $plugin_data            An array of plugin data
     * @param   string      $status                 Status of the plugin
     * @return  array       $links_array
     */
    public function plugin_description_links( $links_array, $plugin_file_name, $plugin_data, $status ) {

        if ( strpos( $plugin_file_name,  basename( __FILE__ ) ) ) {
            // You can still use `array_unshift()` to add links at the beginning.

            $links_array[] = '<a href="https://github.com/ZumeProject/zume-vision-maps-stats">Github Project</a>';

            // add other links here
        }

        return $links_array;
    }

    /**
     * Method that runs only when the plugin is activated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function activation() {

    }

    /**
     * Method that runs only when the plugin is deactivated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function deactivation() {

    }

    /**
     * Magic method to output a string if trying to use the object as a string.
     *
     * @since  0.1
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->token;
    }

    /**
     * Magic method to keep the object from being cloned.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, esc_html( 'Whoah, partner!' ), '0.1' );
    }

    /**
     * Magic method to keep the object from being unserialized.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, esc_html( 'Whoah, partner!' ), '0.1' );
    }

    /**
     * Magic method to prevent a fatal error when calling a method that doesn't exist.
     *
     * @param string $method
     * @param array $args
     *
     * @return null
     * @since  0.1
     * @access public
     */
    public function __call( $method = '', $args = array() ) {
        // @codingStandardsIgnoreLine
        _doing_it_wrong( __FUNCTION__, esc_html('Whoah, partner!'), '0.1' );
        unset( $method, $args );
        return null;
    }
}

// Register activation hook.
register_activation_hook( __FILE__, [ 'Movement_Maps_Stats', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'Movement_Maps_Stats', 'deactivation' ] );

if ( ! function_exists('persecuted_countries' ) ){
    function persecuted_countries() : array {
        return [
            'North Korea',
            'Afghanistan',
            'Somolia',
            'Libya',
            'Pakistan',
            'Eritrea',
            'Sudan',
            'Yemen',
            'Iran',
            'India',
            'Syria',
            'Nigeria',
            'Saudi Arabia',
            'Maldives',
            'Iraq',
            'Egypt',
            'Algeria',
            'Uzbekistan',
            'Myanmar',
            'Laos',
            'Vietnam',
            'Turkmenistan',
            'China',
            'Mauritania',
            'Central African Republic',
            'Morocco',
            'Qatar',
            'Burkina Faso',
            'Mali',
            'Sri Lanka',
            'Tajikistan',
            'Nepal',
            'Jordan',
            'Tunisia',
            'Kazakhstan',
            'Turkey',
            'Brunei',
            'Bangladesh',
            'Ethiopia',
            'Malaysia',
            'Colombia',
            'Oman',
            'Kuwait',
            'Kenya',
            'Bhutan',
            'Russian Federation',
            'United Arab Emirates',
            'Cameroon',
            'Indonesia',
            'Niger'
        ];
    }
}

function movement_maps_stats_plugin_must_be_multisite() {
    $message = __( "'Movement Maps & Stats' plugin must be run on a multisite server with a network dashboard enabled disciple tools system. Please disable plugin.", "dt_dashboard_plugin" );
    ?>
    <div class="notice notice-error notice-dt-dashboard is-dismissible" data-notice="dt-dashboard">
        <p><?php echo esc_html( $message );?></p>
    </div>
    <?php
}
function movement_maps_stats_plugin_must_not_be_disciple_tools() {
    $message = __( "'Movement Maps & Stats' plugin is to be installed on a non-disciple tools website on a multisite server with a network dashboard enabled disciple tools system.", "dt_dashboard_plugin" );
    ?>
    <div class="notice notice-error notice-dt-dashboard is-dismissible" data-notice="dt-dashboard">
        <p><?php echo esc_html( $message );?></p>
    </div>
    <?php
}
