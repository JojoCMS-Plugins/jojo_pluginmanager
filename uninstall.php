<?php

/* remove the pages from the menu */
Jojo::deleteQuery("DELETE FROM `page` WHERE pg_link='jojo_pluginmanager.php'");
Jojo::deleteQuery("DELETE FROM `page` WHERE pg_link='jojo_pluginupload.php'");