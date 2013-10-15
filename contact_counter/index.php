<?php
/*
  Plugin Name: Contact counter
  Plugin URI:
  Description: This plugin counts listing contacts
  Version: 1
  Author: garciademarina
  Short Name: image_uploader
  Author URI:
  Plugin update URI:
 */

require_once 'ModelContactCounter.php';

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
?>