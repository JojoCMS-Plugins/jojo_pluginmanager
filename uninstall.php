<?php

/* remove the pages from the menu */
JOJO::deleteQuery("DELETE FROM `page` WHERE pg_link='jojo_pluginmanager.php'");
JOJO::deleteQuery("DELETE FROM `page` WHERE pg_link='jojo_pluginupload.php'");

