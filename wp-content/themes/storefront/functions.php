<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

/** Custom Post for Cities */
 function create_cities_post_type() {
    register_post_type('cities',
        array(
            'labels' => array(
                'name' => __('Cities'),
                'singular_name' => __('City')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'cities'),
        )
    );
}
add_action('init', 'create_cities_post_type');

/** Meta Box for Latitude and Longtitude */

function add_cities_meta_box() {
    add_meta_box(
        'cities_meta_box',
        'City Details',
        'display_cities_meta_box',
        'cities',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_cities_meta_box');

function display_cities_meta_box($post) {
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    ?>
    <label for="latitude">Latitude:</label>
    <input type="text" name="latitude" value="<?php echo $latitude; ?>" />
    <br />
    <label for="longitude">Longitude:</label>
    <input type="text" name="longitude" value="<?php echo $longitude; ?>" />
    <?php
}

function save_cities_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['latitude'])) {
        update_post_meta($post_id, 'latitude', sanitize_text_field($_POST['latitude']));
    }
    if (isset($_POST['longitude'])) {
        update_post_meta($post_id, 'longitude', sanitize_text_field($_POST['longitude']));
    }
}
add_action('save_post', 'save_cities_meta_box');


/** Taxonomy */

function create_countries_taxonomy() {
    register_taxonomy(
        'countries',
        'cities',
        array(
            'label' => __('Countries'),
            'rewrite' => array('slug' => 'countries'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_countries_taxonomy');

/** Widget City and Temperature */
class City_Weather_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'city_weather_widget',
            __('City Weather Widget', 'text_domain'),
            array('description' => __('Displays the current temperature of a city from the Cities custom post type', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $city_id = $instance['city_id'];
        $api_key = '3cb9e2b689843d56dd3c362027f26b50';
        $city_name = get_the_title($city_id);
        $weather_data = $this->get_weather_data($city_name, $api_key);

        if ($weather_data) {
            echo '<p>City: ' . $city_name . '</p>';
            echo '<p>Temperature: ' . $weather_data->main->temp . '°C</p>';
        } else {
            echo '<p>Unable to retrieve weather data.</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>"><?php _e('City:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('city_id'); ?>" name="<?php echo $this->get_field_name('city_id'); ?>">
                <?php
                $cities = get_posts(array('post_type' => 'cities', 'numberposts' => -1));
                foreach ($cities as $city) {
                    echo '<option value="' . $city->ID . '" ' . selected($city_id, $city->ID, false) . '>' . $city->post_title . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? strip_tags($new_instance['city_id']) : '';
        return $instance;
    }

    private function get_weather_data($city_name, $api_key) {
        $response = wp_remote_get("http://api.openweathermap.org/data/2.5/weather?q={$city_name}&units=metric&appid={$api_key}");
        if (is_wp_error($response)) {
            return false;
        }
        $body = wp_remote_retrieve_body($response);
        return json_decode($body);
    }
}

function register_city_weather_widget() {
    register_widget('City_Weather_Widget');
}
add_action('widgets_init', 'register_city_weather_widget');


/** Enqueue Scripts for Ajax */
function enqueue_cities_weather_scripts() {
    wp_enqueue_script('cities-weather-ajax', get_template_directory_uri() . '/assets/js/cities-weather-ajax.js', array('jquery'), null, true);
    wp_localize_script('cities-weather-ajax', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_cities_weather_scripts');

/** AJAX HANDLER */
function filter_cities_weather() {
    global $wpdb;
    $city = sanitize_text_field($_POST['city']);
    $results = $wpdb->get_results($wpdb->prepare("SELECT country, city, temperature FROM {$wpdb->prefix}cities_weather WHERE city LIKE %s", '%' . $wpdb->esc_like($city) . '%'));

    if ($results) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->country) . '</td>';
            echo '<td>' . esc_html($row->city) . '</td>';
            echo '<td>' . esc_html($row->temperature) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="3">No results found</td></tr>';
    }

    wp_die();
}
add_action('wp_ajax_filter_cities_weather', 'filter_cities_weather');
add_action('wp_ajax_nopriv_filter_cities_weather', 'filter_cities_weather');


