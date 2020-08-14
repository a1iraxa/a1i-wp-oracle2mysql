<?php
class O2M_Admin_Page
{

    // class instance
    static $instance;

    /** Singleton instance */
    public static function get_instance()
    {

        if (!isset(self::$instance)) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    // class constructor
    public function __construct()
    {

        add_filter('set-screen-option', [__CLASS__, 'oracle2mysql_set_screen'], 10, 3);
        add_action('admin_menu', [$this, 'oracle2mysql_menu']);
    }

    public static function oracle2mysql_set_screen($status, $option, $value)
    {

        return $value;
    }

    public function oracle2mysql_menu()
    {

        $hook = add_menu_page(
            'Oracle2Mysql',
            'Oracle2Mysql',
            'manage_options',
            'o2m_admin_page',
            [$this, 'orcale2mysql_page']
        );
    }
    /**
     * Plugin settings page
     */
    public function orcale2mysql_page()
    {
        ?>
            <div class="wrap">
                <h2>Oracle 2 Mysql</h2>

                <div id="poststuff">
                    <div id="post-body" class="metabox-holder">
                        <div id="post-body-content">
                            <div class="meta-box-sortables ui-sortable">
                                <h4 class="o2m-status"></h4>

                               <form method="get" name="o2m-importer" id="o2m-importer" class="o2m-importer">
                                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                                    <input type="hidden" name="action" value="o2m_importer" />

                                    <input type="text" name="table_name" class="o2m-importer__table-name" id="o2m-importer__table-name" placeholder="Enter Table Name" >
                                    <input type="text" name="json_file_url" class="o2m-importer__json-file-url" id="o2m-importer__json-file-url" placeholder="Enter File json URL" >

                                    <input type="submit" name="o2m-run-importer" id="o2m-run-importer" class="button button-primary o2m-run-importer" value="Run Importer">
                                </form>

                            </div>
                        </div>
                    </div>
                    <br class="clear">
                </div>
            </div>
        <?php
    }
}
