<?php
/* Options pages */
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Options',
        'menu_title' => 'Options',
        'menu_slug' => 'coexya-options',
        'capability' => 'edit_posts',
        'redirect' => false
    ));

}

/* SAVE fields */
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point($path)
{

    // update path
    $path = get_stylesheet_directory() . '/acf-json';


    // return
    return $path;

}

/* LOAD fields */
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point($paths)
{

    // remove original path (optional)
    unset($paths[0]);


    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';


    // return
    return $paths;

}

/* Populate icones field */
add_filter('acf/load_field/name=icon_selector', 'coexya_populate_icon_selector');
function coexya_populate_icon_selector($field)
{
    $icons_exclude = ['FACEBOOK.svg', 'IN.svg', 'INSTA.svg', 'TWITTER.svg', 'YOUTUBE.svg'];
    // reset choices
    $field['choices'] = array();

    // get all icones
    $icons_dir = get_stylesheet_directory() . '/assets/img/svg/';
    $icons = scandir($icons_dir);

    $field['choices'][0] = '-';
    // loop through array and add to field 'choices'
    if (is_array($icons)) {
        foreach ($icons as $icon) {
            $icon_info = pathinfo($icon);
            if ($icon_info['extension'] === 'svg' && !in_array($icon, $icons_exclude)) {
                $field['choices'][$icon] = $icon_info['filename'];
            }
        }
    }

    $field['default_value'] = 0;

    // return the field
    return $field;
}


