<?php

class Movement_Shortcode_Utilities {

    public static function create_initials( $longitude, $latitude, array $payload ) : string {
        $letters = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'N', 'S',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'D', 'E',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'E', 'A', 'R', 'I', 'T', 'N', 'S', 'L', 'E', 'A', 'R', 'I', 'N', 'S',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'C', 'D',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'A', 'B',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'A', 'B',
            'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'N', 'S',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'D', 'E',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'E', 'A', 'R', 'I', 'T', 'N', 'S', 'L', 'E', 'A', 'R', 'I', 'N', 'S',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'C', 'D',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'A', 'B',
            'E', 'A', 'R', 'I', 'T', 'N', 'S', 'L', 'E', 'A', 'R', 'I', 'N', 'S',
            'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'E', 'A', 'R', 'I',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'N', 'S',
        ];
        $fnum = abs( round( $longitude ) );
        $lnum = abs( round( $latitude ) );
        $list = str_split( hash( 'sha256', maybe_serialize( $payload ) ) );
        foreach( $list as $character ){
            if ( is_numeric( $character ) ) {
                $fnum = $fnum + $character;
                $lnum = $lnum + $character;
                break;
            }
        }
        return strtoupper( $letters[$fnum] . $letters[$lnum] );
    }

    public static function create_time_string( $timestamp, $timezone_offset ) : string {
        $adjusted_time = $timestamp + $timezone_offset;
        if ( $timestamp > strtotime('-1 hour') ) {
            $time_string = self::_time_elapsed_string('@'.$timestamp);
        }
        else if ( $timestamp > strtotime('today+00:00') + $timezone_offset ) {
            $time_string = date( 'g:i a', $adjusted_time );
        }
        else {
            $time_string = date( 'D g:i a', $adjusted_time );
        }
        return $time_string;
    }

    public static function _time_elapsed_string( $datetime, $full = false ) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function create_in_language_string( $payload ) : string {
        $in_language = '';
        if ( isset( $payload['language_name'] ) && ! empty( $payload['language_name'] ) && 'English' !== $payload['language_name'] ) {
            $in_language = ' in ' . $payload['language_name'];
        }
        return $in_language;
    }

    public static function create_location_precision( $lng, $lat, $label, $payload ) : array {
        $location = [
           'lng' => $lng,
           'lat' => $lat,
           'label' => $label
        ];

        $restricted = self::_persecuted_countries();

        if ( ! isset( $payload['country'] ) ) { // if country is not set, reduce precision to 111km
            $location['lng'] = round($location['lng'], 0 );
            $location['lat'] = round($location['lat'], 0 );
            $location['label'] = '';
        }
        else if ( in_array( $payload['country'], $restricted ) ) { // if persecuted country, reduce precision to 111km
            $location['label'] = ' (' . $payload['country'] . ')';
            $location['lng'] = round( $location['lng'], 0 );
            $location['lat'] = round( $location['lat'], 0 );
        } else { // if non-persecuted country, reduce precision to 11km
            $location['label'] = ' (' . $location['label'] . ')';
            $location['lng'] = round( $location['lng'], 1 );
            $location['lat'] = round( $location['lat'], 1 );
        }

        return $location;
    }

    public static function _persecuted_countries() : array {

        // Top 50 most persecuted countries
        // @link https://www.opendoorsusa.org/christian-persecution/world-watch-list/

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

    public static function create_note_data( $category, $action, $initials, $in_language, $location_label ) : array {
        $data = [
            'note' => '',
            'type' => 'blessing',
        ];

        switch( $category ) {
            case 'leading':
                switch($action){
                    case 'starting_group':
                        $data['note'] =  $initials . ' is starting a training group' . $in_language . '! ' . $location_label;
                        $data['type'] = 'greatest_blessing';
                        break;
                    case 'building_group':
                        $data['note'] =  $initials . ' is growing a training group' . $in_language . '! ' . $location_label;
                        $data['type'] = 'greatest_blessing';
                        break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':
                    case '7':
                    case '8':
                    case '9':
                    case '10':
                        if ( isset($payload['group_size']) && $payload['group_size'] > 1 ) {
                            $data['note'] =  $initials . ' is leading a group of '. $payload['group_size'] .' through session ' . $action . $in_language . '! ' . $location_label;
                        } else {
                            $data['note'] =  $initials . ' is leading a group through session ' . $action . $in_language . '! ' . $location_label;
                        }
                        $data['type'] = 'greatest_blessing';
                        break;
                } // leading actions
                break;
            case 'joining':
                switch($action){
                    case 'coaching':
                        $data['note'] =  $initials . ' is requesting coaching from Zúme coaches' . $in_language . '! ' . $location_label;
                        $data['type'] = 'great_blessing';
                        break;
                    case 'zume_training':
                        $data['note'] =  $initials . ' is registering for Zúme training' . $in_language . '! ' . $location_label;
                        $data['type'] = 'great_blessing';
                        break;
                    case 'zume_vision':
                        $data['note'] =  $initials . ' is joining the Zúme community to engage in Disciple Making Movements' . $in_language . '! ' . $location_label;
                        $data['type'] = 'greatest_blessing';
                        break;
                } // leading actions
                break;
            case 'studying':
                switch($action){
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':
                    case '7':
                    case '8':
                    case '9':
                    case '10':
                    case '11':
                    case '12':
                    case '13':
                    case '14':
                    case '15':
                    case '16':
                    case '17':
                    case '18':
                    case '19':
                    case '20':
                    case '21':
                    case '22':
                    case '23':
                    case '24':
                    case '25':
                    case '26':
                    case '27':
                    case '28':
                    case '29':
                    case '30':
                    case '31':
                    case '32':
                        $title = ' disciple making movement principles';
                        if ( isset( $payload['title'] ) && ! empty( $payload['title'] ) ) {
                            $title = ' "' . $payload['title'] . '"';
                        }
                        $data['note'] =  $initials . ' is studying' . $title . $in_language . '! ' . $location_label;
                        $data['type'] = 'blessing';
                        break;
                    default:
                        $string = '';
                        if ( isset( $payload['note'] ) && ! empty( $payload['note'] ) ) {
                            $string = esc_html( wp_unslash( $payload['note'] ) ) ;
                        }
                        $data['note'] =  $initials . ' ' . $string . $in_language . '! ' . $location_label;
                        $data['type'] = 'blessing';
                        break;
                }
                break;
            case 'committing':
                switch($action){
                    case 'updated_3_month':
                    default:
                        $data['note'] =  $initials . '  made a three month plan to multiply disciples' . $in_language . '! ' . $location_label;
                        $data['type'] = 'great_blessing';
                        break;
                }
                break;
            default:
                break;
        }

        return $data;
    }
}
