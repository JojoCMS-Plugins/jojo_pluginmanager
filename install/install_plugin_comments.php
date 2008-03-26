<?php

$table = 'plugin_comments';
$query = "
        CREATE TABLE {plugin_comments} (
        `plugincommentsid` INT(11) NOT NULL auto_increment ,
        `pc_pluginid` INT(11) NOT NULL default '0',
        `pc_comment` TEXT NULL,
        `pc_email` VARCHAR(100) NOT NULL default '',
        `pc_name` VARCHAR(100) NOT NULL default '',
        `pc_datetime` INT(11) NOT NULL default '0',
         PRIMARY KEY  (`plugincommentsid`)
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