<?php

/* remove the hello page from the menu */
JOJO::deleteQuery("DELETE FROM `page` WHERE pg_link='jojo_pluginmanager.php'");

