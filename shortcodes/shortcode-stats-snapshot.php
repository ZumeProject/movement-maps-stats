<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

class Movement_Shortcode_Stats_Snapshot
{
    public $namespace = 'movement_maps_stats/v1/';
    public $shortcode_token = 'stats_snapshot';

    private static $_instance = null;
    public static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        add_shortcode( $this->shortcode_token, [ $this, 'short_code' ] );
        add_action( 'rest_api_init', [ $this, 'add_api_routes' ] );
        add_action( 'movement_maps_stats_shortcodes_list', [ $this, 'instructions_for_shortcode'] );
    }

    public function short_code( $atts ){

        // begin echo cache
        ob_start();
        ?>
        <style>
            #mms-wrapper {
                width: 100%;
                max-width:600px;
                margin: auto;
            }
            .mms-row {
                width:100%;
                float:left;
                padding-bottom:10px;
            }
            .mms-left-box {
                width: 50%;
                float:left;
            }
            .mms-right-box {
                width: 50%;
                float:right;
            }
            .mms-header {
                padding-top: 15px;
                padding-bottom: 10px;
            }
            span.mms-up {
                /* &#8593; */
                font-weight: bold;
                color: white;
                background-color:green;
                padding: 0 10px;
                margin-top:1px;
                border-radius:15px;
                border: 1px solid white;
            }
            span.mms-down {
                /* &#8595; */
                font-weight: bold;
                color: white;
                content: "\2198";
                background-color:indianred;
                padding: 0 10px;
                margin-top:1px;
                border-radius:15px;
                border: 1px solid white;

            }
        </style>
        <div id="mms-wrapper">
            <div class="mms-row">
                <img src="https://via.placeholder.com/600x200" style="padding-top:10px" />
            </div>
            <div class="mms-row mms-header">
                Progress Report from Jan 7, 2020
            </div>

            <hr>
            <div class="mms-row mms-header">
                <h2>Blessings</h2>
                <em>"Knowing Jesus Better"</em>
            </div>
            <div class="mms-row">
                <strong>Saturation</strong><br>
                100 countries, 269 states, 345 counties with blessings in the last week.<br>
                100 countries, 269 states, 345 counties with blessings from Zúme start.<br>
                100 countries, 269 states, 345 counties in the world.<br>
                100 countries, 269 states, 345 counties yet to reach.<br>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Studying</strong><br>
                    <span class="mms-down">&#10136;</span>  .0056% from last week<br>
                    <span class="mms-down">&#10136;</span>  .0056% for the year<br>
                </div>
                <div class="mms-right-box">
                    <strong>Joining</strong><br>
                    <span class="mms-down">&#10136;</span> .0056% from last week<br>
                    <span class="mms-down">&#10136;</span> .0056% for the year<br>
                </div>
            </div>

            <hr>
            <div class="mms-row mms-header">
                <h2>Great Blessings</h2>
                <em>"Helping Others Know Jesus"</em>
            </div>

            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Top Languages</strong><br>
                    1. English<br>
                    2. Arabic<br>
                    3. Russian<br>
                    4. Portuguese<br>
                    5. Slovenian<br>
                </div>
                <div class="mms-left-box">
                    <strong>Top Locations</strong><br>
                    1. United States<br>
                    2. Philippines<br>
                    3. Russia<br>
                    4. Spain<br>
                    5. Brazil<br>
                </div>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Leading</strong><br>
                    <span class="mms-down">&#10136;</span> .0056% from last week<br>
                    <span class="mms-up">&#10138;</span> .0056% for the year<br>
                </div>
                <div class="mms-left-box">
                    <strong>Praying</strong><br>
                    <span class="mms-down">&#10136;</span> .0056% from last week<br>
                    <span class="mms-up">&#10138;</span> .0056% for the year<br>
                </div>
            </div>


            <hr>
            <div class="mms-row mms-header">
                <h2>Greater Blessings</h2>
                <em>"Starting Spiritual Families"</em>
            </div>
            <div class="mms-row">
                <p>
                    We saw 324 multiplication trainings reported in the last week, and have counted
                    2,456 movement trainings in the last year. Though reporting is often inconsistent,
                    we know about 15 new spiritual families forming in the last week and 543 in the last year.
                </p>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Training</strong><br>
                    <span class="mms-up">&#10138;</span> .0056% from last week<br>
                    <span class="mms-up">&#10138;</span> .0056% for the year<br>
                </div>
                <div class="mms-right-box">
                    <strong>Forming Spiritual Families</strong><br>
                    <span class="mms-up">&#10138;</span> .00012% from last week<br>
                    <span class="mms-up">&#10138;</span> .00012% for the year<br>
                </div>
            </div>



            <hr>
            <div class="mms-row mms-header">
                <h2>Greatest Blessings</h2>
                <em>"Helping Others Start Spiritual Families"</em>
            </div>
            <div class="mms-row">
                <p>
                    As for greatest blessings, we've identified 432 coaching events between coaches and learners or peer mentoring.
                    We also have 39 reports from disciple multipliers about generational growth withing their network.
                </p>
                <p>
                    United States, Brazil, Mexico, and Slovenia have been the most active in reporting in the last week.
                </p>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Coaching</strong><br>
                    <span class="mms-down">&#10136;</span>  .0056% from last week<br>
                    <span class="mms-down">&#10136;</span>  .0056% for the year<br>
                </div>
                <div class="mms-right-box">
                    <strong>Reporting</strong><br>
                    <span class="mms-down">&#10136;</span>  .0056% from last week<br>
                    <span class="mms-down">&#10136;</span>  .0056% for the year<br>
                </div>
            </div>
        </div>
        <br><br>
        <?php

        return ob_get_clean();
    }



}
Movement_Shortcode_Stats_Snapshot::instance();
