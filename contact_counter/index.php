<?php
/*
  Plugin Name: Contact counter
  Plugin URI: https://github.com/garciademarina/contact_counter
  Description: This plugin helps to know how many times an user has contacted another
  Version: 1.0
  Author: @garciademarina
  Short Name: contact_counter
  Author URI: twitter.com/garciademarina
  Plugin update URI: contact_counter
 */

require_once 'ModelContactCounter.php';
require_once 'StatsContactCounter.php';

//Delete item
osc_add_hook('delete_item', 'contact_counter_delete');
function contact_counter_delete($item) {
    ModelContactCounter::newInstance()->deleteItemStat($item) ;
}

osc_add_hook('posted_item', 'contact_counter_insert');
function contact_counter_insert($item) {
    ModelContactCounter::newInstance()->insertItemStat($item['pk_i_id']) ;
}

osc_add_hook('hook_email_item_inquiry', 'contact_counter_increase');
function contact_counter_increase($item) {
    ModelContactCounter::newInstance()->increaseItemStat($item['id']) ;
}

osc_register_plugin(osc_plugin_path(__FILE__), 'contact_counter_install');
function contact_counter_install() {
    // create table structure
    ModelContactCounter::newInstance()->import("contact_counter/struct.sql") ;

    // initialize stats
    ModelContactCounter::newInstance()->init() ;

}

osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", 'contact_counter_uninstall');
function contact_counter_uninstall() {
    // remove table structure
    ModelContactCounter::newInstance()->uninstall();
}

function contact_counter_more_actions_link( $row, $aRow) {
    // get number of contact by listing
    $num_contacts = ModelContactCounter::newInstance()->getTotalContactsByItemId($aRow['pk_i_id']);
    $row['title'] = $row['title'] . '<a style="padding-left: 25px;" href="' . osc_route_admin_url('stats-contact-counter', array('id' => $aRow['pk_i_id'])) . '">' . sprintf(__('<b>%s</b> contacts +'),$num_contacts) . '</a>';

    return $row;
}
osc_add_hook('items_processing_row', 'contact_counter_more_actions_link');
//osc_add_hook('actions_manage_items', 'contact_counter_more_actions_link');

// admin menu
function contact_counter_admin_menu() {
    if (osc_version() < 320) {
        echo '<h3><a href="#">'.__('Contact counter', 'contact_counter').'</a></h3>
            <ul>
                <li><a href="' . osc_admin_configure_plugin_url("contact_counter/admin/stats.php") . '">&raquo; ' . __('Contact stats', 'contact_counter') . '</a></li>
                <li><a href="' . osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/help.php') . '">&raquo; ' . __('Help', 'contact_counter') . '</a></li>
            </ul>';
    } else {
        osc_add_admin_submenu_divider('plugins', __('Contact counter', 'contact_counter'), 'contac_counter_divider', 'administrator');
        osc_add_admin_submenu_page('plugins', __('Help contact counter', 'voting'), osc_route_admin_url('help-contact-counter') , '', 'administrator');
        osc_add_admin_submenu_page('stats', __('View contact stats', 'contact_counter'), osc_route_admin_url('stats-contact-counter', array('id' => '')), '', 'administrator');
    }
}

if(osc_version()<320) {
    osc_add_hook('admin_menu', 'contact_counter_admin_menu');
} else {
    osc_add_hook('admin_menu_init', 'contact_counter_admin_menu');
}

//  routes
osc_add_route('stats-contact-counter', 'stats-contact-counter/(.+)/([0-9]+)', 'stats-contact-counter/{type_stat}/{id}', osc_plugin_folder(__FILE__).'admin/stats.php');
osc_add_route('help-contact-counter', 'help-contact-counter', 'help-contact-counter', osc_plugin_folder(__FILE__).'admin/help.php');

// custom title/header stats page
if(Params::getParam('page') == 'plugins' && Params::getParam('file') == 'contact_counter/admin/stats.php' || Params::getParam('route') == 'stats-contact-counter') {
    osc_add_hook('admin_header',        'contact_counter_remove_title_header');
    osc_add_hook('admin_page_header',   'contact_counter_PageHeader_stats');
    osc_add_filter('admin_title',       'contact_counter_customPageTitle_stats');
} else if(Params::getParam('page') == 'plugins' && Params::getParam('file') == 'contact_counter/admin/help.php' || Params::getParam('route') == 'help-contact-counter') {
    osc_add_hook('admin_header',        'contact_counter_remove_title_header');
    osc_add_hook('admin_page_header',   'contact_counter_PageHeader_help');
    osc_add_filter('admin_title',       'contact_counter_customPageTitle_help');
}
function contact_counter_remove_title_header() {
    osc_remove_hook('admin_page_header','customPageHeader');
}

function contact_counter_PageHeader_stats() { ?>
    <h1><?php _e('Contact stats', 'contact_counter'); ?>
    </h1>
<?php
}
function contact_counter_PageHeader_help() { ?>
    <h1><?php _e('Help contact counter plugin', 'contact_counter'); ?>
    </h1>
<?php
}
function contact_counter_customPageTitle_stats($string) {
    return sprintf(__('Contact Statistics &raquo; %s', 'contact_counter'), $string);
}
function contact_counter_customPageTitle_help($string) {
    return sprintf(__('Help contact counter plugin &raquo; %s', 'contact_counter'), $string);
}

// helper functions
function cc_contacts_by_listing($id) {
    return ModelContactCounter::newInstance()->getTotalContactsByItemId( $id );
}
function cc_contacts_by_user($id) {
    return ModelContactCounter::newInstance()->getTotalContactsByUser( $id );
}

?>