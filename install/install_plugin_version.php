<?php

$table = 'plugin_version';
$query = "
        CREATE TABLE {plugin_version} (
        `pluginversionid` INT(11) NOT NULL auto_increment ,
        `pv_pluginid` INT(11) NOT NULL default '0',
        `pv_version` VARCHAR(100) NOT NULL default '',
        `pv_datetime` INT(11) NOT NULL default '0',
        `pv_releasenotes` text NOT NULL,
        `pv_status` enum('stable','beta','developer','alpha') DEFAULT NULL ,
        `pv_file_zip` VARCHAR(100) NULL ,
        `pv_file_tgz` VARCHAR(100) NULL ,
        `pv_file_7z` VARCHAR(100) NULL ,
        `pv_downloads` int(11) NULL ,
        `total_votes` int(11) NOT NULL default '0',
        `total_value` int(11) NOT NULL default '0',
        `used_ips` longtext,
         PRIMARY KEY  (`pluginversionid`)
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