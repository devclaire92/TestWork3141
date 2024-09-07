<?php
/*
Template Name: Cities Weather
*/

get_header(); ?>

<div id="cities-weather-container">
    <?php do_action('before_cities_weather_table'); ?>

    <input type="text" id="city-search" placeholder="Search for a city...">
    <table id="cities-weather-table">
        <thead>
            <tr>
                <th>Country</th>
                <th>City</th>
                <th>Temperature (°C)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $results = $wpdb->get_results("SELECT country, city, temperature FROM {$wpdb->prefix}cities_weather");

            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . esc_html($row->country) . '</td>';
                echo '<td>' . esc_html($row->city) . '</td>';
                echo '<td>' . esc_html($row->temperature) . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <?php do_action('after_cities_weather_table'); ?>
</div>

<?php get_footer(); ?>

