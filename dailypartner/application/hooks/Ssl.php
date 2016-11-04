<?php
function redirect_ssl()
{
   
    $CI =& get_instance();
    $CI->load->helper('url_helper');
    $class = $CI->router->fetch_class();
    $exclude = array('Main', 'Login', 'File', 'Maps', 'Calendar', 'Todolist', 'AjaxHandler');
    if (!in_array($class, $exclude)) {
        $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] != 443) redirect($CI->config->config['base_url'].$_SERVER['REQUEST_URI']);
    } else {
        $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
        if ($_SERVER['SERVER_PORT'] == 443) redirect($CI->uri->uri_string());
    }

}

?>
