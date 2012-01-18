<?php

class HTML {
    private $_meta;
    private $_scripts;
    private $_styles;
    private $_title;
    private $_favicon;
    private $_context;

    public function __construct($type = '')
    {
        if ($type == 'admin') {
            $this->_header_template = DOC_ROOT . '/includes/templates/admin_header.php';
            $this->_footer_template = DOC_ROOT . '/includes/templates/admin_footer.php';
            $this->_context = 'admin';
        }
        else {
            $this->_header_template = DOC_ROOT . '/includes/templates/mobile-header.php';
            $this->_footer_template = DOC_ROOT . '/includes/templates/mobile-footer.php';
            $this->_context = '';
        }

        $this->_title = '';
        $this->_meta = array();
        $this->_scripts = array();
        $this->_styles = array();
        $this->_favicon = '';
    }


    public function use_script($script)
    {
        $this->_scripts[] = $script;
    }

    public function use_style($style)
    {
        $this->_styles[] = $style;
    }

    public function use_meta($meta)
    {
        $this->_meta[] = $meta;
    }

    public function set_title($title)
    {
        $this->_title = $title;
    }

    public function generate_header_mobile()
    {
        $header  = '<!DOCTYPE html>' . HTML_EOL;
        $header .= '<html> ' . HTML_EOL;
        $header .= '<head>' . HTML_EOL;
        $header .= '    <meta charset="utf-8"/>' . HTML_EOL;
        $header .= '    <meta-name="robots" content="noindex,nofollow"/>' . HTML_EOL;
        $header .= '    <!-- iOS Viewport -->' . HTML_EOL;
        $header .= '    <!-- <meta name = "viewport" content = "user-scalable=no, width=device-width"/>  --> ' . HTML_EOL;
        $header .= '    <meta name="viewport" content="width=device-width, initial-scale=1"/>' . HTML_EOL;
        $header .= '    <title>Meocracy</title>  ' . HTML_EOL;
        $header .= '    <!-- iOS Hides the status bar -->' . HTML_EOL;
        $header .= '    <meta name="apple-mobile-web-app-capable" content="yes" /> ' . HTML_EOL;
        $header .= '    <meta name="apple-mobile-web-app-status-bar-style" content="black" />' . HTML_EOL;
        $header .= '    <!-- iOS Icons -->' . HTML_EOL;
        $header .= '    <link rel="apple-touch-startup-image" href="../photos/logo/startup.jpg">' . HTML_EOL;
        $header .= '    <link rel="apple-touch-icon" href="../photos/logo/touch-icon-iphone.png" /> ' . HTML_EOL;
        $header .= '    <link rel="apple-touch-icon" sizes="72x72" href="../photos/logo/touch-icon-ipad.png" /> ' . HTML_EOL;
        $header .= '    <link rel="apple-touch-icon" sizes="114x114" href="../photos/logo/touch-icon-iphone4.png" /> ' . HTML_EOL;
        $header .= '    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/mobile/1.0rc3/jquery.mobile-1.0rc3.min.css" />' . HTML_EOL;
        $header .= '    <link type="text/css" rel="stylesheet" href="' . WEB_ROOT . '/includes/css-mobile/global.css"/>' . HTML_EOL;
        if (!empty($this->_styles)) {
            foreach ($this->_styles as $style) {
                $header .= '    <link type="text/css" rel="stylesheet" href="/includes/css-mobile/' . $style . '"/>' . HTML_EOL;
            }
        }
        $header .= '    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>' . HTML_EOL;
        $header .= '    <script type="text/javascript" src="http://code.jquery.com/mobile/1.0rc3/jquery.mobile-1.0rc3.min.js"></script>' . HTML_EOL;
        $header .= '    <script type="text/javascript" src="https://www.google.com/jsapi"></script>' . HTML_EOL;
        if (!empty($this->_scripts)) {
            foreach ($this->_scripts as $script) {
                $header .= '    <script type="text/javascript" src="/includes/js/' . $script . '"></script>' . HTML_EOL;
            }
        }
        $header .= '</head> ' . HTML_EOL;
        echo $header;
        include $this->_header_template;
    }

    public function generate_footer_mobile()
    {
        include $this->_footer_template;
    }


    public function generate_header()
    {
        $style_tags = array_map(function($file) { return '<style type="text/css" href="' . WEB_ROOT . '/includes/css/' . $file . '"/>' . "\n"; }, $this->_styles);
        if (!empty($style_tags)) {
            $style_tag_string = implode('', $style_tags);
        }
        $script_tags = array_map(function($file) { return WEB_ROOT . '/includes/js/' . $file; }, $this->_scripts);
        if (!empty($script_tags)) {
            $script_tag_string = implode('', $script_tags);
        }
        $header =  '<!DOCTYPE html>' . HTML_EOL;
        $header .= '<html>' . HTML_EOL;
        $header .= '<head>' . HTML_EOL;
        $header .= '    <title>' . $this->_title . '</title>' . HTML_EOL;
        $header .= '    <link rel="icon" href="' . $this->_favicon . '"/>' . HTML_EOL;
        $header .= '    <meta name="viewport" content="' . VIEWPORT_WIDTH . '"/>' . HTML_EOL;
        if (!empty($this->_meta)) {
            foreach ($this->_meta as $meta) {
                $header .= '    <meta name="' . $meta['name'] . '" content="' . $meta['content'] . '"/>' . HTML_EOL;
            }
        }
        $header .= '    <!-- GLOBAL STYLES -->' . HTML_EOL;
        $header .= '    <link type="text/css" rel="stylesheet" href="' . WEB_ROOT . '/includes/css/global.css"/>' . HTML_EOL;
        $header .= '    <link type="text/css" rel="stylesheet" href="' . WEB_ROOT . '/includes/css/images.css"/>' . HTML_EOL;
        if ($this->_context == 'admin') {
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/layout.css" rel="stylesheet"/>' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/colors.css" rel="stylesheet" />' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/header.css" rel="stylesheet" />' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/nav.css" rel="stylesheet" />' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/results.css" rel="stylesheet" />' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/charts.css" rel="stylesheet" />' . HTML_EOL;
            $header .= '    <link type="text/css" href="' . WEB_ROOT . '/includes/css/admin/forms.css" rel="stylesheet" />' . HTML_EOL;
        }

        if (!empty($this->_styles)) {
            foreach ($this->_styles as $style) {
                $header .= '    <link type="text/css" rel="stylesheet" href="/includes/css/' . $style . '"/>' . HTML_EOL;
            }
        }
        $header .= '    <!-- GLOBAL SCRIPTS -->' . HTML_EOL;
        $header .= '    <script type="text/javascript" src="/jquery/jquery-1.6.4.js"></script>' . HTML_EOL;
        if (!empty($this->_scripts)) {
            foreach ($this->_scripts as $script) {
                $header .= '    <script type="text/javascript" src="/includes/js/' . $script . '"></script>' . HTML_EOL;
            }
        }
        $header .= '    <script type="text/javascript" src="https://www.google.com/jsapi"></script>' . HTML_EOL;
        $header .= '<script type="text/javascript">
                    $(document).ready( function() {
                        $(".button").each( function() {
                            $(this).click( function() {
                                $(this).closest("form").submit();
                                return false;
                            });
                        });
                    });
                    </script>' . HTML_EOL;

        $header .= '</head>';

        echo $header;
        include $this->_header_template;
//        return $header;
    }


    public function generate_footer()
    {
        include $this->_footer_template;
    }
}
?>
