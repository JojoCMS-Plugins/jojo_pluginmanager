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

class JOJO_Plugin_Jojo_pluginmanager extends JOJO_Plugin
{
    function _getContent()
    {
        global $smarty;
        $content = array();

        /* Do we have the reflect plugin available? */
        $smarty->assign('reflect', class_exists('Jojo_plugin_jojo_reflect'));

        $action = Jojo::getFormData('action', 'list');
        switch ($action) {
            case 'download':
                /* Download a plugin file */
                $file = Jojo::getFormData('file', '');
                $id = Jojo::getFormData('id', '');
                $data = Jojo::selectQuery("SELECT  *  FROM {plugin_version} as plugin_version, {plugin_details} as plugin_details WHERE plugin_version.pluginversionid = ? AND plugin_version.pv_pluginid = plugin_details.pluginid LIMIT 1", $id);
                if (!isset($data[0])) {
                    /* Not valid, 404 */
                    header("HTTP/1.0 404 Not Found", true, 404);
                    exit;
                }

                $basedir = _DOWNLOADDIR . "/plugins/" . $data[0]['pd_name'] . "/" . $data[0]['pv_version'];
                $filename = sprintf("%s/%s", $basedir, $file);
                if (!file_exists($filename)) {
                    /* Not valid, 404 */
                    header("HTTP/1.0 404 Not Found", true, 404);
                    exit;
                }

                /* send header for file type */
                $extension = getFileExtension($file);
                switch ($extension) {
                    case 'zip':
                        header("Content-Type: application/octet-stream");
                        break;
                    case 'tgz':
                        header("Content-Type: application/x-compressed");
                        break;
                    case '7z':
                        header("Content-Type: application/x-7z-compressed");
                        break;
                }
                header(sprintf('Content-Disposition: attachment; filename="%s"', basename($filename)));
                @readfile($filename);

                /* Update Downloadcounter */
                Jojo::updateQuery("UPDATE {plugin_version} SET pv_downloads = pv_downloads+1 WHERE pluginversionid = ?", $id);
                break;

            case 'details':
            case 'allcomments':
                /* Valid plugin id? */
                $id = Jojo::getFormData('id', false);
                if (!$id) {
                    header('Location: ' . _SITRURL . '/plugins');
                    exit;
                }

                /* Plugin id match to a plugin? */
                $plugin = Jojo::selectQuery('SELECT * FROM {plugin_details} WHERE pluginid = ?', $id);
                if (!isset($plugin[0])) {
                    header('Location: ' . _SITRURL . '/plugins');
                    exit;
                }
                $plugin = $plugin[0];

                /* Saving a comment? */
                if (Jojo::getFormData('btn_save')) {
                    /* Save the comment */
                    $result = $this->saveComment();
                    if ($result !== true) {
                        $fields = array();
                        foreach($_POST as $k => $v) {
                            $fields[$k] = Jojo::getPost($k);
                        }
                        $smarty->assign('fields', $fields);
                        $smarty->assign('message', $errors);
                    } else {
                        $smarty->assign('success', 'Your comment was saved.');
                    }
                }

                /* View all versions of this plugin */
                $versions = Jojo::selectQuery("SELECT * FROM {plugin_version} WHERE pv_pluginid = ? ORDER BY pv_datetime DESC", array($id));

                /* Get comments for this plugin */
                $comments = Jojo::selectQuery("SELECT * FROM {plugin_comments} WHERE pc_pluginid = ? ORDER BY pc_datetime DESC", array($id));

                $content['title'] = $plugin['pd_name'];
                $smarty->assign('plugin', $plugin);
                $smarty->assign('versions', $versions);

                $breadcrumbs = $this->_getBreadCrumbs();
                $breadcrumb = array();
                $breadcrumb['name'] = $plugin['pd_name'];
                $breadcrumb['rollover'] = 'Details of Plugin ' . $plugin['pd_name'];
                $breadcrumb['url'] =  Jojo::rewrite('plugins', $id, 'details','');
                $breadcrumbs[count($breadcrumbs)] = $breadcrumb;

                $content['seotitle'] = 'Details of Plugin '. $plugin['pd_name'];
                $content['breadcrumbs'] = $breadcrumbs;
                $content['content'] = $smarty->fetch('jojo_pluginmanger_details.tpl');
                return $content;

                break;

            case 'list':
            default:
                $plugins = array();

                /* Get the sorting column */
                $sortBy = isset($_SESSION['sortby']) ? $_SESSION['sortby'] : '';
                $sortDir = isset($_SESSION['sortdir']) ? $_SESSION['sortdir'] : '';
                if (!$sortBy) {
                    switch ($action) {
                        case 'status':
                            $sortBy = 'pv_status';
                            $sortDir = 'ASC';
                            break;
                        case 'updated':
                            $sortBy = 'pv_datetime';
                            $sortDir = 'DESC';
                            break;
                        case 'rating':
                            $sortBy = 'total_value';
                            $sortDir = 'DESC';
                            break;
                        case 'downloads':
                            $sortBy = 'pv_downloads';
                            $sortDir = 'ASC';
                            break;
                        case 'name':
                        default:
                            $sortBy = 'pd_name';
                            $sortDir = 'ASC';
                            break;
                    }
                }
                $_SESSION['sortBy']  = $sortBy;
                $_SESSION['sortDir']  = $sortDir;

                $smarty->assign('plugins', $this->_getPLugins($sortBy, $sortDir, 0));
                $content['content'] = $this->page['pg_body'].$smarty->fetch('jojo_pluginmanger_list.tpl');
                return $content;

                break;
        }

        return $content;
    }

    public static function getPlugins($num = 3, $musthaveimages = false) {
        $query = "SELECT * FROM {plugin_details}";
        if ($musthaveimages) {
            $query .= ' WHERE pd_image != ""';
        }
        $plugins = Jojo::selectQuery($query . " ORDER BY RAND() DESC LIMIT 0, $num");
        foreach ($plugins as $i => $p){
            $plugins[$i]['name'] = Jojo::html2text($p['pd_name']);
            $plugins[$i]['url'] = Jojo::rewrite('plugins/details', $p['pluginid'], $p['pd_name'], '');
        }
        return $plugins;
    }


    function _getPlugins($sortBy, $sortDir = 'ASC', $page = 0, $perPage = 10)
    {
        /* Select the latest version of each plugin from database */
        $query = sprintf("SELECT
                            plugin_details.pluginid, plugin_details.pd_name,
                            plugin_details.pd_description, plugin_details.pd_tags,
                            plugin_details.pd_image, plugin_details.pd_svn,
                            plugin_version.pluginversionid, plugin_version.pv_status,
                            plugin_version.total_votes, plugin_version.total_value,
                            MAX(plugin_version.pv_version) AS version, plugin_version.pv_file_zip,
                            plugin_version.pv_file_tgz, plugin_version.pv_file_7z,
                            plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads,
                            MAX(plugin_version.pv_datetime) AS datetime
                          FROM
                            {plugin_details} as plugin_details, {plugin_version} as plugin_version
                          WHERE
                            plugin_details.pluginid = plugin_version.pv_pluginid
                          GROUP BY
                            plugin_version.pv_pluginid
                          ORDER BY %s %s", $sortBy, $sortDir);
        $res = Jojo::selectQuery($query);

        foreach($res as $k => $v) {
            $res[$k]['date'] = Jojo::relativeDate($res[$k]['datetime']);
            if ($res[$k]['total_votes'] > 0) {
                $res[$k]['rating'] = $res[$k] ['total_value'] / $res[$k] ['total_votes'];
            }
            $res[$k]['tags'] = explode(',', $res[$k]['pd_tags']);
            $res[$k]['url'] = Jojo::rewrite('plugins/details', $res[$k]['pluginid'], $res[$k]['pd_name'], '');
        }
        return $res;
    }

    function getCorrectUrl()
    {
        //Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }


    function saveComment()
    {
        global $smarty;

        include(_BASEDIR . '/includes/no-form-injection.inc.php');

        $errors = '';
        if (!checkEmailFormat(Jojo::getFormData('email'))) {
            $errors .= 'Email is not a valid or empty email format<br/>';
        }
        if (!Jojo::getFormData('name')) {
            $errors .= 'The field Name is empty<br/>';
        }
        if (!Jojo::getFormData('comment')) {
            $errors .= 'The field Comment is empty<br/>';
        }

        /*
        if (Jojo::getOption('contactcaptcha') == 'yes' && !PhpCaptcha::Validate(Jojo::getFormData('CAPTCHA'))) {
            $errors .= 'Incorrect code entered';
        }
        */

        /* Any errors? */
        if ($errors) {
            /* Yes, return them */
            return $errors;
        }

        /* Save the comment */
        Jojo::insertQuery("INSERT INTO {plugin_comments} SET pc_datetime=?, pc_pluginversionid = ? , pc_comment = ? , pc_email = ?, pc_name =?",
                array(strtotime('now'), Jojo::getFormData('pluginversionid'), Jojo::getFormData('comment'), Jojo::getFormData('email'), Jojo::getFormData('name')));
        return true;
    }
}
