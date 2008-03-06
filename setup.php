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

$table = 'plugin_details';
$query = "
    CREATE TABLE {plugin_details} (
    `pluginid` INT(11) NOT NULL AUTO_INCREMENT ,
    `pd_userid` INT(11) NOT NULL default '0',
    `pd_name` VARCHAR(100) NOT NULL default '',
    `pd_author` VARCHAR(100) NOT NULL default '',
    `pd_website` VARCHAR(100) NOT NULL default '',
    `pd_license` VARCHAR(100) NOT NULL default '',
    `pd_description` TEXT NULL,
    `pd_demolink` VARCHAR(100) NULL,
    `pd_tags` VARCHAR(255) NOT NULL default '',
     PRIMARY KEY  (`pluginid`)
    ) ENGINE = MyISAM;";

/* Check table structure */
$result = JOJO::checkTable($table, $query);



/* create plugin_version table */
if (!Jojo::tableexists('plugin_version')) {
    echo "Table <b>plugin_version</b> Does not exist - creating empty table<br />";
    $query = "
        CREATE TABLE {plugin_version} (
        `pluginversionid` INT(11) NOT NULL AUTO_INCREMENT ,
        `pv_pluginid` INT(11) NOT NULL default '0',
        `pv_version` VARCHAR(100) NOT NULL default '',
        `pv_datetime` INT(11) NOT NULL default '0',
        `pv_releasenotes` TEXT NOT NULL default '',
        `pv_status` ENUM('stable','beta' ,'developer', 'alpha') NULL ,
        `pv_file_zip` VARCHAR(100) NULL ,
        `pv_file_tgz` VARCHAR(100) NULL ,
        `pv_file_7z` VARCHAR(100) NULL ,
        `pv_image` VARCHAR(255) NULL ,
        `pv_downloads` int(11) NULL ,
        `total_votes` int(11) NOT NULL default '0',
        `total_value` int(11) NOT NULL default '0',
        `used_ips` longtext,
         PRIMARY KEY  (`pluginversionid`)
        ) ENGINE = MyISAM;
    ";
    Jojo::updateQuery($query);
}
/* create plugin_version table */
if (!Jojo::tableexists('plugin_comments')) {
    echo "Table <b>plugin_comments</b> Does not exist - creating empty table<br />";
    $query = "
        CREATE TABLE {plugin_comments} (
        `plugincommentsid` INT(11) NOT NULL AUTO_INCREMENT ,
        `pc_pluginversionid` INT(11) NOT NULL default '0',
        `pc_comment` TEXT NULL default '',
        `pc_email` VARCHAR(100) NOT NULL default '',
        `pc_name` VARCHAR(100) NOT NULL default '',
        `pc_datetime` INT(11) NOT NULL default '0',
         PRIMARY KEY  (`plugincommentsid`)
        ) ENGINE = MyISAM;
    ";
    Jojo::updateQuery($query);
}

/*create entry in page table */
$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='jojo_plugin_jojo_pluginmanager'");
if (count($data) == 0) {
    echo "Adding <b>Plugins</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Plugins', pg_link='jojo_plugin_jojo_pluginmanager', pg_url='plugins'");
}

$data = Jojo::selectQuery("SELECT * FROM {page} WHERE pg_link='jojo_plugin_jojo_pluginupload'");
if (count($data) == 0) {
    echo "Adding <b>Plugins</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Plugin Upload', pg_link='jojo_plugin_jojo_pluginupload', pg_url='plugins/plugin-upload'");
}

