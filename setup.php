<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007 Harvey Kane <code@ragepank.com>
 * Copyright 2007 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <code@gardyneholt.co.nz>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

/* Create entries in page table */
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='jojo_plugin_jojo_pluginmanager'");
if (count($data) == 0) {
    echo "Adding <b>Plugins</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Plugins', pg_link='jojo_plugin_jojo_pluginmanager', pg_url='plugins'");
}

$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='jojo_plugin_jojo_pluginupload'");
if (count($data) == 0) {
    echo "Adding <b>Plugins</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Plugin Upload', pg_link='jojo_plugin_jojo_pluginupload', pg_url='plugins/upload'");
}

// Edit Plugins
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_url = 'admin/edit/plugin_details'");
if (count($data) == 0) {
    echo "Adding <b>Edit Plugins</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'Edit Plugins', pg_link = 'Jojo_Plugin_Admin_Edit', pg_url = 'admin/edit/plugin_details', pg_parent= ?, pg_order=12, pg_mode = 'advanced'", array($_ADMIN_CONTENT_ID));
}

// Edit Plugin Versions
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_url = 'admin/edit/plugin_version'");
if (count($data) == 0) {
    echo "Adding <b>Edit Plugin Version</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title = 'Edit Plugin Versions', pg_link = 'Jojo_Plugin_Admin_Edit', pg_url = 'admin/edit/plugin_version', pg_parent = ?, pg_order=13, pg_mode = 'advanced'", array($_ADMIN_CONTENT_ID));
}
