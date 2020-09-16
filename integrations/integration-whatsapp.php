<?php
/**
 * Integration
 */

class Movement_Maps_Stats_WhatsApp
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
}
