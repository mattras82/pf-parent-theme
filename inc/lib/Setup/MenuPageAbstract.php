<?php

namespace PublicFunction\Setup;

use PublicFunction\Core\RunableAbstract;

abstract class MenuPageAbstract extends RunableAbstract
{

    protected $config;

    protected $option_name;

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
}