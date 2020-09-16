<?php

class Movement_Maps_Stats_Post_To_WP
{
    private static $_instance = null;
    public static function instance() {
        if (is_null( self::$_instance )) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {

    }

    public function create_post( $args = [] ){
        $settings = wp_parse_args( $args, [
            'post_type' => 'reports',
            'category' => 'zume-community'
        ]);



    }

    public function html_template(){
//        ob_start();
        ?>
        <style>
            #mms-wrapper {
                width: 100%;
            }
            .mms-row {
                width:100%;
            }
            .mms-left-box {
                width: 50%;
                float:left;
            }
            .mms-right-box {
                width: 50%;
                float:right;
            }
        </style>
        <div id="mms-wrapper">
            <div class="mms-row">
                <h2>Blessings</h2>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">

                </div>
                <div class="mms-right-box">
                    These
                </div>
            </div>
            <div class="mms-row">
                <h2>Great Blessings</h2>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Obedience Events</strong>
                </div>
                <div class="mms-right-box">
                    These
                </div>
            </div>
            <div class="mms-row">
                <h2>Greater Blessings</h2>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Obedience Events</strong>
                </div>
                <div class="mms-right-box">
                    These
                </div>
            </div>
            <div class="mms-row">
                <h2>Greatest Blessings</h2>
            </div>
            <div class="mms-row">
                <div class="mms-left-box">
                    <strong>Obedience Events</strong>
                </div>
                <div class="mms-right-box">
                    These
                </div>
            </div>
        </div>
        <?php

//        $contents = ob_get_contents();
//        wp_ob_end_flush_all();
//        return $contents;
    }
}
Movement_Maps_Stats_Post_To_WP::instance();
