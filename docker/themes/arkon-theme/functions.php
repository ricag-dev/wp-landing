<?php

if(!class_exists('parse')):


    class Parser {

        public function __construct()
        {
            $this->set_delimiters();
        }

        public function parse($template, $data, $return = FALSE)
        {
            ob_start();
            include(__DIR__. '/' . $template);
            $template = ob_get_contents();
            @ob_end_clean();

            return $this->_parse($template, $data, $return);
        }

        /**
         * @param $template
         * @param $data
         * @param $return
         * @return false|string
         */
        public function parse_string($template, $data, $return = FALSE)
        {
            return $this->_parse($template, $data, $return);
        }

        /**
         * @param $template
         * @param $data
         * @return false|string
         */
        protected function _parse($template, $data)
        {
            if ($template === '')
            {
                return FALSE;
            }

            $replace = array();
            foreach ($data as $key => $val)
            {
                $replace = array_merge(
                    $replace,
                    is_array($val)
                        ? $this->_parse_pair($key, $val, $template)
                        : $this->_parse_single($key, (string) $val, $template)
                );
            }

            unset($data);
            $template = strtr($template, $replace);

            return $template;
        }

        /**
         * @param $l
         * @param $r
         * @return void
         */
        public function set_delimiters($l = '{', $r = '}')
        {
            $this->l_delim = $l;
            $this->r_delim = $r;
        }

        /**
         * @param $key
         * @param $val
         * @param $string
         * @return string[]
         */
        protected function _parse_single($key, $val, $string)
        {
            return array($this->l_delim.$key.$this->r_delim => (string) $val);
        }

        /**
         * @param $variable
         * @param $data
         * @param $string
         * @return array
         */
        protected function _parse_pair($variable, $data, $string)
        {
            $replace = array();
            preg_match_all(
                '#'.preg_quote($this->l_delim.$variable.$this->r_delim).'(.+?)'.preg_quote($this->l_delim.'/'.$variable.$this->r_delim).'#s',
                $string,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $match)
            {
                $str = '';
                foreach ($data as $row)
                {
                    $temp = array();
                    foreach ($row as $key => $val)
                    {
                        if (is_array($val))
                        {
                            $pair = $this->_parse_pair($key, $val, $match[1]);
                            if ( ! empty($pair))
                            {
                                $temp = array_merge($temp, $pair);
                            }

                            continue;
                        }

                        $temp[$this->l_delim.$key.$this->r_delim] = $val;
                    }

                    $str .= strtr($match[1], $temp);
                }

                $replace[$match[0]] = $str;
            }

            return $replace;
        }
    }

endif;



add_action('init','update_siteurl');
function update_siteurl()
{
    global $wpdb;
    $sq = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options  WHERE option_name = 'home' OR option_name = 'siteurl' ");
    $ip = $_SERVER['SERVER_ADDR'];
    $old_ip = '';
    $pasa = false;
    foreach ($sq as $li) {
        $site = parse_url($li->option_value);
        if ($site['host'] != $ip) {
            $old_ip = $site['host'];
            $pasa = true;
        }
    }

    if ($pasa) {
        $wpdb->query("UPDATE {$wpdb->prefix}options SET option_value = replace(option_value, '$old_ip', '$ip') WHERE option_name = 'home' OR option_name = 'siteurl';");
        $wpdb->query("UPDATE {$wpdb->prefix}posts SET guid = replace(guid, '$old_ip','$ip');");
        $wpdb->query("UPDATE {$wpdb->prefix}posts SET post_content = replace(post_content, '$old_ip', '$ip');");
        $wpdb->query("UPDATE {$wpdb->prefix}postmeta SET meta_value = replace(meta_value,'$old_ip','$ip');");
        echo '<script>location.reload();</script>';
    }

}

function eval_fn($fun){
    ob_start();
    eval($fun.';');
    $var = ob_get_contents();
    @ob_end_clean();
    return $var;
}