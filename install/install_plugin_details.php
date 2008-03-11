<?php

$table = 'plugin_details';
$query = "
    CREATE TABLE {plugin_details} (
    `pluginid` INT(11) NOT NULL auto_increment ,
    `pd_userid` INT(11) NOT NULL default '0',
    `pd_name` VARCHAR(100) NOT NULL default '',
    `pd_author` VARCHAR(100) NOT NULL default '',
    `pd_website` VARCHAR(255) NOT NULL default '',
    `pd_svn` VARCHAR(255) NOT NULL default '',
    `pd_image` VARCHAR(255) NOT NULL default '',
    `pd_license` VARCHAR(100) NOT NULL default '',
    `pd_description` TEXT NULL,
    `pd_demolink` VARCHAR(100) NULL,
    `pd_tags` VARCHAR(255) NOT NULL default '',
     PRIMARY KEY  (`pluginid`)
    );";

/* Check table structure */
$result = Jojo::checkTable($table, $query);

/* Output result */
if (isset($result['created'])) {
    echo sprintf("jojo_pluginmanager: Table <b>%s</b> Does not exist - created empty table.<br />", $table);
}

if (isset($result['added'])) {
    foreach ($result['added'] as $col => $v) {
        echo sprintf("jojo_pluginmanager: Table <b>%s</b> column <b>%s</b> Does not exist - added.<br />", $table, $col);
    }
}

if (isset($result['different'])) {
    Jojo::printTableDifference($table,$result['different']);
}

