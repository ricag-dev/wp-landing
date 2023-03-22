<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ARKON
 * @since ARKON THEME 1.0
 */

$tpl = new Parser();

$body_class = eval_fn('body_class()');
$body_class = str_replace(['class="','"'],'',$body_class);

echo $tpl->parse('tpl.html',[
    'DIR'       => get_template_directory_uri().'/',
    'title'     => get_bloginfo(),
    'head'      => eval_fn('wp_head()'),
    'footer'    => eval_fn('wp_footer()'),
    'body_class'=> $body_class,
    'body_open' => eval_fn('wp_body_open()'),
]);
