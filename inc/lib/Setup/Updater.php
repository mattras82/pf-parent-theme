<?php

namespace PublicFunction\Setup;

use PublicFunction\Setup\MenuPageAbstract;
use PublicFunction\Core\Container;

class Updater extends MenuPageAbstract
{
    protected $option_group;

    private const PLUGIN_LIST = [
        'pf-wp-toolkit' => [
            'name'  => 'PF WP Toolkit'
        ],
        'pf-cf7-extras' => [
            'name'  => 'PF CF7 Extras'
        ],
        'contact-form-7-to-database-extension' => [
            'name'  => 'CF7 to DB Extension',
            'file'  => 'contact-form-7-db.php'
        ]
    ];

    public function __construct(Container &$c)
    {
        parent::__construct($c);

        $this->option_name = 'pf_updater';
        $this->option_group = $this->option_name . '_group';
    }

    public function add_submenu_page()
    {
        add_submenu_page(
            'tools.php',
            'Public Function Updater',
            'PF Updater',
            'activate_plugins',
            $this->option_name,
            [$this, 'render_updater_page']
        );
    }

    public function init_options_group()
    {
        add_settings_section(
            'settings',
            'Package List',
            [$this, 'group_description'],
            $this->option_group
        );

        add_settings_field(
            'pf-parent-theme',
            'PF Parent Theme',
            [$this, 'render_checkbox_field'],
            $this->option_group,
            'settings',
            [
                'key'            => 'pf-parent-theme',
            ]
        );

        foreach (self::PLUGIN_LIST as $key => $plugin) {
            $uri = $key . '/' . (!empty($plugin['file']) ? $plugin['file'] : "$key.php");
            if (is_plugin_active($uri)) {
                add_settings_field(
                    $key,
                    $plugin['name'],
                    [$this, 'render_checkbox_field'],
                    $this->option_group,
                    'settings',
                    [
                        'key'    => $key,
                    ]
                );
            }
        }
    }

    public function group_description()
    {
?>
        <p>Select which Public Function packages you want to update, then click submit.</p>
        <p><em><strong>Note:</strong> Packages are not guaranteed to have updates. Please know what you're doing before using this tool.</em></p>
    <?php
    }

    public function render_updater_page()
    {
        $results = null;
        if (!empty($_POST['action']) && $_POST['action'] == 'update' && !empty($_POST[$this->option_name])) {
            $results = $this->run_updates();
        }
    ?>
        <div class="wrap">
            <h1>Public Function Updater</h1>
            <form action="<?= admin_url("tools.php?page={$this->option_name}") ?>" method="post">
                <input type="hidden" name="action" value="update">
                <?php
                settings_fields($this->option_group);
                do_settings_sections($this->option_group);
                submit_button();
                ?>
            </form>
            <?php if ($results) pre($results) ?>
        </div>
<?php
    }

    public function run_updates()
    {
        $result = [];
        if (!empty($_POST[$this->option_name]['pf-parent-theme'])) {
            exec('wp theme install --force https://github.com/mattras82/pf-parent-theme/archive/master.zip 2>&1', $result);
        }
        foreach (array_keys(self::PLUGIN_LIST) as $plugin) {
            if (!empty($_POST[$this->option_name][$plugin])) {
                exec("wp plugin install --activate --force https://github.com/mattras82/$plugin/archive/master.zip 2>&1", $result);
            }
        }

        return $result;
    }

    public function run()
    {
        $this->loader()->addAction('admin_menu', [$this, 'add_submenu_page']);
        $this->loader()->addAction('admin_init', [$this, 'init_options_group']);
    }
}
