<?php
class Json_2_Table
{
    private $__table_name = '';
    private $__file_url = '';

    private $__columns = '';
    private $__entries = '';
    private $__primary_key = '';

    // For Development only.
    private $__files = [
        'EVENTS' => 'EVENTS_DATA_TABLE',
        'EVENT_LOCATIONS' => 'EVENT_LOCATIONS_DATA_TABLE',
        'EVENT_VENUE' => 'EVENT_VENUE_DATA_TABLE',
        'EVENT_PARTICIPANTS_SHORTLIST' => 'EVENT_PARTICIPANTS_SHORTLIST_DATA_TABLE',
        'EVENT_PARTICIPANTX' => 'EVENT_PARTICIPANTX_DATA_TABLE',
        'EVENT_PARTICIPANT' => 'EVENT_PARTICIPANT_DATA_TABLE',
        'EVENT_THEME' => 'EVENT_THEME_DATA_TABLE',
        'EVENT_ACTIVITY' => 'EVENT_ACTIVITY_DATA_TABLE',
        'EVENT_ACTIVITY_TYPES' => 'EVENT_ACTIVITY_TYPES_DATA_TABLE'
    ];

    function __construct($table_name, $file_url) {

        $this->__table_name = $table_name;
        $this->__file_url = $file_url;

    }

    public function get_table_name()
    {
        return $this->__table_name;
    }

    public function get_file_url()
    {
        return $this->__file_url;
    }

    public function get_primary_key()
    {
        return $this->__primary_key;
    }

    public function get_column_type($oracle_type)
    {
        switch ($oracle_type) {
            case 'NUMBER':
                return 'int(128)';
                break;
            case 'VARCHAR2':
                return 'varchar(255)';
                break;
            case 'DATE':
                return 'DATE';
                break;
            case 'NVARCHAR2':
                return 'varchar(255)';
                break;

            default:
                return 'varchar(255)';
                break;
        }
    }

    public function create_db_table($table_name, $primary_key, $columns)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $table_name;

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "";
        $sql .= "CREATE TABLE IF NOT EXISTS $table_name (";

        foreach ($columns as $column) {

            $auto_increment = '';

            if ( $primary_key == $column['name'] ) {
                $auto_increment = 'AUTO_INCREMENT';
            }

            $sql .= $column['name'].' '. $this->get_column_type($column['type']) ." NOT NULL ". $auto_increment .",";
        }

        $sql .= "PRIMARY KEY  (" . $primary_key . ")";
        $sql .= ") $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function insert($table_name, $data)
    {
        global $wpdb;
        $__table = $wpdb->prefix . $table_name;

        foreach ($data as $key => $value) {
            $table_data = [];

            $row = (array) $value;

            foreach ($row as $col_name => $col_value) {
                $table_data[$col_name] = $col_value;
            }

            $inserted = $wpdb->insert($__table, $table_data, $format = []);

        }
    }

    public function load_json()
    {
        try {

            $strJsonFileContents = file_get_contents( $this->get_file_url() );

            if ( !empty( $strJsonFileContents ) ) {

                $json_arr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $strJsonFileContents), true );

                $results = $json_arr['results'];

                $results = $results[0];

                $this->__columns = $results['columns'];

                $this->__entries = $results['items'];

                $this->__primary_key = $this->__columns[0]['name'];

                return $this;

            }

        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

    }

    public function creat_table()
    {
        $this->create_db_table( $this->__table_name, $this->__primary_key, $this->__columns );
        return $this;
    }

    public function insert_entries()
    {
        $this->insert( $this->__table_name, $this->__entries );
        return $this;
    }


    public function debug_and_print_json()
    {
        global $wpdb;

        $strJsonFileContents = file_get_contents( $this->get_file_url() );
        $strJsonFileContents = file_get_contents( 'http://asif.me/dev/oracle-table-json/EVENTS_DATA_TABLE.json' );
        if ( !empty( $strJsonFileContents ) ) {

            $json_arr = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $strJsonFileContents), true );

            echo "<pre>";print_r($json_arr);die;

        }

        die;
    }

}

function json_2_table($table_name, $file_url)
{
    return new Json_2_Table($table_name, $file_url);
}
