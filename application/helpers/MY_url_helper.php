<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets URL
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('domain'))
{
    function domain($uri = '')
    {
        return 'http://localhost/!!cisaleem/';
    }
}
if ( ! function_exists('assets_url'))
{
    function assets_url($uri = '')
    {
        $CI =& get_instance();

        $assets_url = $CI->config->item('assets_url');

        return $assets_url . trim($uri, '/');
    }
}
if ( ! function_exists('uploads_url'))
{
    function uploads_url($uri = '')
    {
        $CI =& get_instance();

        $uploads_url = $CI->config->item('uploads_url');

        return $uploads_url . trim($uri, '/');
    }
}
if ( ! function_exists('files_url'))
{
    function files_url($uri = '')
    {
        $CI =& get_instance();

        $files_url = $CI->config->item('files_url');

        return $files_url . trim($uri, '/');
    }
}
if ( ! function_exists('themes_url'))
{
    function themes_url($uri = '')
    {
        $CI =& get_instance();

        $themes_url = $CI->config->item('themes_url');

        return $themes_url . trim($uri, '/');
    }
}

if ( ! function_exists('redirect')){
    function redirect($uri = '', $method = 'location', $http_response_code = 302){
        if ( ! preg_match('#^https?://#i', $uri)){
            $uri = site_url($uri);
        }

        header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', false );
        header( 'Pragma: no-cache' );   

        switch($method)
        {
            case 'refresh'  : header("Refresh:0;url=".$uri);
                break;
            default         : 
                header("Location: ".$uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }

}
if( ! function_exists('secure_site_url') )
{
    function secure_site_url($uri = '')
    {
        $CI =& get_instance();
        return $CI->config->secure_site_url($uri);
    }
}
 
if( ! function_exists('secure_base_url') )
{
    function secure_base_url()
    {
        $CI =& get_instance();
        return $CI->config->slash_item('secure_base_url');
    }
}
 
if ( ! function_exists('secure_anchor'))
{
    function secure_anchor($uri = '', $title = '', $attributes = '')
    {
        $title = (string) $title;
 
        if ( ! is_array($uri))
        {
            $secure_site_url = ( ! preg_match('!^\w+://! i', $uri)) ? secure_site_url($uri) : $uri;
        }
        else
        {
            $secure_site_url = secure_site_url($uri);
        }
 
        if ($title == '')
        {
            $title = $secure_site_url;
        }
 
        if ($attributes != '')
        {
            $attributes = _parse_attributes($attributes);
        }
 
        return '<a href="'.$secure_site_url.'" ' . $attributes . '>'.$title.'</a>';
    }
}
 
if ( ! function_exists('secure_redirect'))
{
    function secure_redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".secure_site_url($uri));
                break;
            default            : header("Location: ".secure_site_url($uri), TRUE, $http_response_code);
                break;
        }
        exit;
    }
}
/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */