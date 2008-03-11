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

$default_td['plugin_version'] = array(
        'td_name' => "plugin_version",
        'td_primarykey' => "pluginversionid",
        'td_displayfield' => "pv_version",
        'td_categorytable' => "plugin_details",
        'td_categoryfield' => "pv_pluginid",
        'td_orderbyfields' => "pv_datetime",
        'td_deleteoption' => "yes",
        'td_menutype' => "ajaxtree",
        'td_defaultpermissions' => "everyone.show=1\neveryone.view=1\neveryone.edit=1\neveryone.add=1\neveryone.delete=1\nadmin.show=1\nadmin.view=1\nadmin.edit=1\nadmin.add=1\nadmin.delete=1\nnotloggedin.show=1\nnotloggedin.view=1\nnotloggedin.edit=1\nnotloggedin.add=1\nnotloggedin.delete=1\nregistered.show=1\nregistered.view=1\nregistered.edit=1\nregistered.add=1\nregistered.delete=1\nsysinstall.show=1\nsysinstall.view=1\nsysinstall.edit=1\nsysinstall.add=1\nsysinstall.delete=1\n",
    );

$default_fd['plugin_version']['pluginversionid'] = array(
        'fd_name' => "ID",
        'fd_type' => "readonly",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['pv_pluginid'] = array(
        'fd_name' => "Plugin",
        'fd_type' => "dblist",
        'fd_options' => "plugin_details",
        'fd_default' => "0",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['pv_version'] = array(
        'fd_name' => "Version",
        'fd_type' => "text",
    );

$default_fd['plugin_version']['pv_datetime'] = array(
        'fd_name' => "Date Added",
        'fd_type' => "unixdate",
        'fd_default' => "0",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['pv_releasenotes'] = array(
        'fd_name' => "Releasenotes",
        'fd_type' => "textarea",
        'fd_size' => "0",
        'fd_rows' => "5",
        'fd_cols' => "40",
    );

$default_fd['plugin_version']['pv_status'] = array(
        'fd_name' => "Status",
        'fd_type' => "combobox",
        'fd_options' => "stable:Stable
\nbeta:Beta
\ndeveloper:Developer
\nalpha:Alpha",
    );

$default_fd['plugin_version']['pv_file_zip'] = array(
        'fd_name' => "File_zip",
        'fd_type' => "text",
    );

$default_fd['plugin_version']['pv_file_tgz'] = array(
        'fd_name' => "File_tgz",
        'fd_type' => "text",
    );

$default_fd['plugin_version']['pv_file_7z'] = array(
        'fd_name' => "File_7z",
        'fd_type' => "text",
    );

$default_fd['plugin_version']['pv_downloads'] = array(
        'fd_name' => "Number of Downloads",
        'fd_type' => "readonly",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['total_votes'] = array(
        'fd_name' => "Total_votes",
        'fd_type' => "hidden",
        'fd_showlabel' => "no",
        'fd_default' => "0",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['total_value'] = array(
        'fd_name' => "Total_value",
        'fd_type' => "hidden",
        'fd_showlabel' => "no",
        'fd_default' => "0",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['used_ips'] = array(
        'fd_name' => "Used_ips",
        'fd_type' => "hidden",
        'fd_showlabel' => "no",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
    );

$default_fd['plugin_version']['pv_image'] = array(
        'fd_name' => "Image",
        'fd_type' => "fileupload",
    );
