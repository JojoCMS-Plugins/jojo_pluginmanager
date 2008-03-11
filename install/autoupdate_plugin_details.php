<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Michael Cochrane <mikec@joojcms.org>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

$default_td['plugin_details'] = array(
        'td_name' => "plugin_details",
        'td_primarykey' => "pluginid",
        'td_displayfield' => "pd_name",
        'td_rolloverfield' => "pd_name",
        'td_menutype' => "list",
        'td_defaultpermissions' => "everyone.show=0\neveryone.view=0\neveryone.edit=0\neveryone.add=0\neveryone.delete=0\nadmin.show=0\nadmin.view=0\nadmin.edit=0\nadmin.add=0\nadmin.delete=0\nnotloggedin.show=0\nnotloggedin.view=0\nnotloggedin.edit=0\nnotloggedin.add=0\nnotloggedin.delete=0\nregistered.show=0\nregistered.view=0\nregistered.edit=0\nregistered.add=0\nregistered.delete=0\nsysinstall.show=0\nsysinstall.view=0\nsysinstall.edit=0\nsysinstall.add=0\nsysinstall.delete=0\n",
    );

$default_fd['plugin_details']['pd_tags'] = array(
        'fd_name' => "Tags",
        'fd_type' => "hidden",
        'fd_showlabel' => "no",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_details']['pluginid'] = array(
        'fd_name' => "Pluginid",
        'fd_type' => "readonly",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => "1",
    );

$default_fd['plugin_details']['pd_name'] = array(
        'fd_name' => "Name",
        'fd_type' => "text",
        'fd_required' => "yes",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => "2",
    );

$default_fd['plugin_details']['pd_author'] = array(
        'fd_name' => "Author",
        'fd_type' => "text",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => "3",
    );

$default_fd['plugin_details']['pd_demolink'] = array(
        'fd_name' => "Demo URL",
        'fd_type' => "url",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => "4",
    );

$default_fd['plugin_details']['pd_svn'] = array(
        'fd_name' => "SVN Repos URL",
        'fd_type' => "url",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_help' => "URL for the svn repository for this plugin",
        'fd_order' => "5",
    );

$default_fd['plugin_details']['pd_website'] = array(
        'fd_name' => "Website URL",
        'fd_type' => "url",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_help' => "URL for more info about this plugin",
        'fd_order' => "6",
    );

$default_fd['plugin_details']['pd_license'] = array(
        'fd_name' => "License",
        'fd_type' => "text",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_help' => "What licences is this plugin released as? eg GPL, LGPL, Modified BSD",
        'fd_order' => "7",
    );

$default_fd['plugin_details']['pd_image'] = array(
        'fd_name' => "Image",
        'fd_type' => "fileupload",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_help' => "A screen shot of this plugin in action",
        'fd_order' => "8",
    );

$default_fd['plugin_details']['pd_description'] = array(
        'fd_name' => "Description",
        'fd_type' => "textarea",
        'fd_size' => "0",
        'fd_rows' => "5",
        'fd_cols' => "40",
        'fd_order' => "9",
    );

$default_fd['plugin_details']['pd_userid'] = array(
        'fd_name' => "Owner",
        'fd_type' => "dblist",
        'fd_options' => "user",
        'fd_default' => "1",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_help' => "Owner of this plugin",
        'fd_order' => "10",
    );
