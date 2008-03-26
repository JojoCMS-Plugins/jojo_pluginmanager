<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2007-2008 Harvey Kane <code@ragepank.com>
 * Copyright 2007-2008 Michael Holt <code@gardyneholt.co.nz>
 * Copyright 2007 Melanie Schulz <mel@gardyneholt.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Harvey Kane <code@ragepank.com>
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @author  Melanie Schulz <mel@gardyneholt.co.nz>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

/* Register URI patterns */
Jojo::registerURI("plugins/[action:details]/[id:integer]/[string]",  'JOJO_Plugin_Jojo_pluginmanager');
Jojo::registerURI("plugins/[action:allcomments]/[id:integer]/[string]",  'JOJO_Plugin_Jojo_pluginmanager');
Jojo::registerURI("plugins/[action:download]/[id:integer]/[file:.*]",  'JOJO_Plugin_Jojo_pluginmanager');