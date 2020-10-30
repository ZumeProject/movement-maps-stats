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

    }
}
Movement_Maps_Stats_Post_To_WP::instance();
