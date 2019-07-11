<?php

namespace PublicFunction\Setup;


use PublicFunction\Core\Container;
use PublicFunction\Core\RunableAbstract;

class GoogleTagManager extends RunableAbstract
{
    protected $option_name;

    protected $option_group;

    protected $config;

    public function __construct(Container $c)
    {
        parent::__construct($c);

        $this->option_name = 'pf_gtm_tag';
        $this->option_group = $this->option_name . '_group';
    }

    public function init_options_page()
    {
        register_setting($this->option_group, $this->option_name);

        add_settings_section(
            'settings',
            'Setup',
            [$this, 'group_description'],
            $this->option_group
        );

        add_settings_field(
            'tag',
            'Tag ID',
            [$this, 'render_tag_field'],
            $this->option_group,
            'settings'
        );
    }

    public function add_options_page()
    {
        add_options_page(
            'Google Tag Manager Settings',
            'PF GTM Settings',
            'manage_options',
            $this->option_group,
            [$this, 'render_options_page']
        );
    }

    public function render_options_page()
    {
        ?>
        <div class="wrap">
            <h1>Google Tag Manager Settings</h1>
            <form action="options.php" method="post">
        <?php
            settings_fields($this->option_group);
            do_settings_sections($this->option_group);
            submit_button();
        ?>
            </form>
        </div>
        <?php
    }

    public function group_description()
    {
        ?>
        <p>Enter a tag key below to add the appropriate Google Tag Manager code to this site.</p>
        <?php
    }

    public function render_tag_field()
    {
        ?>
        <input
            type="text"
            id="pf_gtm_tag"
            name="<?= $this->option_name ?>[tag]"
            value="<?= $this->get_value('tag') ?>"
            placeholder="GTM-ABCD123"
        >
        <?php
    }

    public function get_value($field = '')
    {
        if (!isset($this->config)) {
            $this->config = get_option($this->option_name);
            if (!$this->config)
                return null;
        }
        if ($field) {
            if (is_array($field)) {
                $value = $this->config;
                foreach ($field as $item) {
                    if (isset($value[$item])) {
                        $value = $value[$item];
                    } else {
                        return null;
                    }
                }
            } else {
                $value = isset($this->config[$field]) ? $this->config[$field] : null;
            }
            return $value;
        }
        return $this->config;
    }

    public function head()
    {
        if ($tag = $this->get_value('tag')) { ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?= $tag ?>');</script>
        <!-- End Google Tag Manager -->
        <?php
        }
    }

    public function body()
    {
        if ($tag = $this->get_value('tag')) { ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $tag ?>"
                          height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
        }
    }


    public function run()
    {
        $this->loader()->addAction('admin_init', [$this, 'init_options_page']);
        $this->loader()->addAction('admin_menu', [$this, 'add_options_page']);
    }

}
