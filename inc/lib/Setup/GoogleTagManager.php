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
            [$this, 'render_text_field'],
            $this->option_group,
            'settings',
            [
                'key'            => 'tag',
                'placeholder'   => 'GTM-ABCD123'
            ]
        );

        add_settings_field(
            'add_flicker',
            'Add Anti-Flicker Snippet?',
            [$this, 'render_checkbox_field'],
            $this->option_group,
            'settings',
            [
                'key'    => 'add_flicker',
            ]
        );

        if ($this->get_value('add_flicker') === 'on') {
            add_settings_field(
                'async_timeout',
                'Anti-Flicker Timeout (Milliseconds)',
                [$this, 'render_text_field'],
                $this->option_group,
                'settings',
                [
                    'key'           => 'async_timeout',
                    'default'       => '1000'
                ]
            );

            add_settings_field(
                'async_slugs',
                'Anti-Flicker Page Slugs',
                [$this, 'render_textarea_field'],
                $this->option_group,
                'settings',
                [
                    'key'           => 'async_slugs',
                    'desc'          => 'If you only want the Anti-Flicker Snippet to run on certain pages, please add the page slug(s) here.<br>One slug per line. Use "home" for homepage.<br>Leave this field blank to add the snippet to all pages (this is not recommended).',
                    'placeholder'   => 'ie. about-us&#10;contact-us'
                ]
            );
        }
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

    private function setup_field_args(&$args)
    {
        $key = $args['key'];
        unset($args['key']);
        $desc = '';
        if (!empty($args['desc'])) {
            $desc = $args['desc'];
            unset($args['desc']);
        }
        return [
            $key,
            $desc
        ];
    }

    public function render_text_field($args = [])
    {
        list($id, $desc) = $this->setup_field_args($args);
        $attr = wp_parse_args($args, [
            'id'        => "pf_gtm_$id",
            'name'      => "{$this->option_name}[{$id}]",
            'value'     => $this->get_value($id, !empty($args['default']) ? $args['default'] : null),
            'type'      => 'text'
        ]);
        echo sprintf('<input %s />', $this->attr_to_string($attr));
        if ($desc) {
            echo sprintf('<p>%s</p>', $desc);
        }
    }

    public function render_textarea_field($args = [])
    {
        list($id, $desc) = $this->setup_field_args($args);
        $attr = wp_parse_args($args, [
            'id'        => "pf_gtm_$id",
            'name'      => "{$this->option_name}[{$id}]",
            'style'     => 'min-height:100px'
        ]);
        $val = $this->get_value($id, !empty($args['default']) ? $args['default'] : null);
        echo sprintf('<textarea %s>%s</textarea>', $this->attr_to_string($attr), $val);
        if ($desc) {
            echo sprintf('<p>%s</p>', $desc);
        }
    }

    public function render_checkbox_field($args = [])
    {
        list($id, $desc) = $this->setup_field_args($args);
        $attr = wp_parse_args($args, [
            'id'        => "pf_gtm_$id",
            'name'      => "{$this->option_name}[{$id}]",
            'value'     => 'on',
            'type'      => 'checkbox'
        ]);
        $val = $attr['value'];
        echo sprintf(
            '<input %s />',
            $this->attr_to_string(
                $attr,
                checked($this->get_value($id), $val, false)
            )
        );
        if ($desc) {
            echo sprintf('<p>%s</p>', $desc);
        }
    }

    private function attr_to_string($attr, $str = '')
    {
        foreach ($attr as $a => $value) {
            if (substr($a, 0, 5) === 'maybe') continue;
            $value = esc_attr__($value, $this->get('textdomain'));
            $str .= " {$a}=\"{$value}\"";
        }
        return $str;
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
        if ($tag = $this->get_value('tag')) {
            $this->anti_flicker($tag); ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $tag ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?= $tag ?>');
            </script>
            <!-- End Google tag -->
        <?php
        }
    }

    private function anti_flicker($tag)
    {
        $display = false;
        if ($this->get_value('add_flicker') === 'on') {
            $display = true;
            $timeout = $this->get_value('async_timeout', '1000');
            $slugs = $this->get_value('async_slugs');
            if ($slugs) {
                $display = false;
                $url = $_SERVER['REQUEST_URI'];
                $url = explode('?', $url)[0];
                $url = explode('#', $url)[0];
                $url_slugs = explode('/', $url);
                $is_home = is_home() || is_front_page();
                foreach (explode(PHP_EOL, $slugs) as $slug) {
                    $slug = trim($slug);
                    if (empty($slug)) continue;
                    if (
                        in_array($slug, $url_slugs)
                        || ($slug === 'home' && $is_home)
                    ) {
                        $display = true;
                        break;
                    }
                }
            }
        }
        if ($display) { ?>
            <!-- anti-flicker snippet  -->
            <style>.async-hide { opacity: 0 !important} </style>
            <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
            h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
            (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
            })(window,document.documentElement,'async-hide','dataLayer',<?= $timeout ?>,
            {'<?= $tag ?>':true});</script>
        <?php
        }
    }

    public function body()
    {
        if ($tag = $this->get_value('tag')) { ?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $tag ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
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
