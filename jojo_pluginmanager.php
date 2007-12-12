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

require(_PLUGINDIR . '/jojo_pluginmanager/external/ajaxrating/_drawrating.php');

class JOJO_Plugin_Jojo_pluginmanager extends JOJO_Plugin
{
    function _getContent()
    {
        global $smarty;
        $content = array();
        /* that's the number of plugins shown per page */
        $pluginsPerPage = 10;

        $versionid = Util::getFormData('versionid', ''); //versiondetails and all comments
        $action = Util::getFormData('action', '');
        switch ($action) {
            case 'download':
                $file = Util::getFormData('file', ''); // download plugin
                $id = Util::getFormData('id', ''); // download plugin
                $data = Jojo::selectQuery("SELECT  *  FROM plugin_version, plugin_details WHERE plugin_version.pluginversionid = ? AND plugin_version.pv_pluginid = plugin_details.pluginid LIMIT 1", $id);
                $basedir = _DOWNLOADDIR."/plugins/".$data[0]['pd_name']."/".$data[0]['pv_version'];
                $filename = sprintf("%s/%s", $basedir, $file);
                $extension = getFileExtension($file);

                /* send header fot file typ*/
                switch ($extension) {
                    case 'zip': header("Content-Type: application/octet-stream"); break;
                    case 'tgz': header("Content-Type: application/x-compressed"); break;
                    case '7z': header("Content-Type: application/x-7z-compressed"); break;
                }
                $save_as_name = basename($file);
                header(sprintf('Content-Disposition: attachment; filename="%s"', $save_as_name));
                @readfile($filename) or die("File not found.");

                /* Update Downloadcounter */
                Jojo::updateQuery("UPDATE plugin_version SET pv_downloads = pv_downloads+1 WHERE pluginversionid = ?", $id);
                break;

            case 'details':
                $id = Util::getFormData('id', ''); // view details of plugin or download id

                /* check the form is button for new comment was pressed*/
                if (isset($_POST['btn_save'])) {
                    $message = $this->sendComment();
                    $fields = array();

                    if ($message != '') {

                        if ($_POST['name']) {
                            $fields['name'] = $_POST['name'];
                        }
                        if ($_POST['firstname']) {
                            $fields['firstname'] = $_POST['firstname'];
                        }
                        if ($_POST['email']) {
                            $fields['email'] = $_POST['email'];
                        }
                        if ($_POST['comment']) {
                            $fields['comment'] = $_POST['comment'];
                        }
                        if ($_POST['pluginversionid']) {
                            $fields['pluginversionid'] = $_POST['pluginversionid'];
                        }
                        $smarty->assign('fields',$fields);
                        $smarty->assign('message',$message);
                    }
                    else {
                        $smarty->assign('success','Your comment was successful transmitted.');
                        unset($fields);
                        unset($success);
                    }
                }
                /* view all details and comments of one pluginversion */
                if ($versionid != '' ) {
                    $plugindetails =  Jojo::selectQuery("SELECT * FROM plugin_version, plugin_details where plugin_version.pluginversionid = " . $versionid);
                    $plugindetails[0]['date'] = relativeDate(convertTimeZone($plugindetails[$i]['pv_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
                    $plugindetails[0]['stars'] = rating_bar($plugindetails[0]['pluginversionid'], 5);
                    $plugindetails[0]['tags']= explode(',', $plugindetails[0]['pd_tags']);


                    for ($i = 0; $i < sizeof($plugincomments); $i++ ) {
                        $plugincomments[$i]['date'] = relativeDate(convertTimeZone($plugincomments[$i]['pc_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
                    }
                    $breadcrumbname = $plugindetails[0]['pd_name'];
                    $smarty->assign('plugincomments',$plugincomments);
                    $smarty->assign('plugindetails',$plugindetails);
                    $smarty->assign('action','allComments');
                    /* view all versions of one plugin and the last comment*/
                } elseif ($id != '') {

                    $pluginversions = Jojo::selectQuery("SELECT * FROM plugin_version, plugin_details WHERE plugin_version.pv_pluginid = ? AND plugin_details.pluginid = ? ORDER BY pv_datetime DESC", array($id, $id));
                    $versioncomments =array();

                    for ($i = 0 ; $i < sizeof($pluginversions); $i++ ){
                        $versioncomments = Jojo::selectQuery("SELECT plugin_comments.* FROM plugin_comments, plugin_version WHERE plugin_comments.pc_pluginversionid = plugin_version.pluginversionid AND plugin_version.pluginversionid = ".$pluginversions[$i]['pluginversionid']." ORDER BY pc_datetime DESC");

                        if (count($versioncomments) > 1) {

                            $pluginversions[$i]['countComments'] = count($versioncomments);
                        }
                        $pluginversions[$i]['date'] = relativeDate(convertTimeZone($pluginversions[$i]['pv_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
                        $pluginversions[$i] ['stars'] = rating_bar($pluginversions[$i]['pluginversionid'], 5);
                        $pluginversions[$i] ['pc_comment'] =  $versioncomments [0]['pc_comment'];
                        $pluginversions[$i] ['pc_name'] =  $versioncomments [0]['pc_name'];
                        $pluginversions[$i] ['pc_date'] =  relativeDate(convertTimeZone($versioncomments[$i]['pc_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
                    }
                    $pluginversions[0] ['tags']= explode(',', $pluginversions[0]['pd_tags']);
                    $_SESSION['pluginversions'] = $pluginversions;
                    $breadcrumbname = $pluginversions[0]['pd_name'];
                    $smarty->assign('action','details');
                    $smarty->assign('pluginversions',$pluginversions);
                }

                $breadcrumbs = $this->_getBreadCrumbs();
                $breadcrumb = array();
                $breadcrumb['name'] = $breadcrumbname;
                $breadcrumb['rollover'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
                $breadcrumb['url'] =  Jojo::rewrite('plugins',$id,'details','');
                $breadcrumbs[count($breadcrumbs)] = $breadcrumb;

                $content['title'] = "Details ".$plugindetails[0]['pd_name'];
                $content['seotitle'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
                $content['breadcrumbs'] = $breadcrumbs;
                $content['content'] = $smarty->fetch('jojo_pluginmanger.tpl');
                return $content;

                break;

            case '':
            case 'name':
            case 'updated':
            case 'rating':
            case 'downloads':
            case 'status':
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

                $smarty->assign('action', 'all');
                $smarty->assign('plugins', $this->_getPLugins($sortBy, $sortDir, 0));
                $content['content'] = $this->page['pg_body'].$smarty->fetch('jojo_pluginmanger.tpl');
                return $content;

                break;

            case 'tags':
                $breadcrumbs = $this->_getBreadCrumbs();
                $breadcrumb = array();
                $breadcrumb['name'] = 'Tags';
                $breadcrumb['rollover'] = 'Tags';
                $breadcrumb['url'] =  'plugins/tags';
                $breadcrumbs[] = $breadcrumb;

                $tag = Util::getFormData('tag', false);
                if ($tag) {
                    /* All plugin with specific tag */
                    $query = sprintf("SELECT
                                        plugin_details.pluginid, plugin_details.pd_name,
                                        plugin_details.pd_description, plugin_details.pd_tags,
                                        plugin_version.pluginversionid, plugin_version.pv_status,
                                        plugin_version.total_votes, plugin_version.total_value,
                                        MAX(plugin_version.pv_version) AS version, plugin_version.pv_file_zip,
                                        plugin_version.pv_file_tgz, plugin_version.pv_file_7z,
                                        plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads,
                                        MAX(plugin_version.pv_datetime) AS datetime
                                      FROM
                                        plugin_details, plugin_version
                                      WHERE
                                        plugin_details.pluginid = plugin_version.pv_pluginid
                                       AND
                                        plugin_details.pd_tags LIKE '%%%s%%'
                                      GROUP BY
                                        plugin_version.pv_pluginid
                                      ORDER BY
                                        datetime ASC
                                      ", $tag);
                    $res = Jojo::selectQuery($query);
                    foreach($res as $k => $v) {
                        $res[$k]['date'] = relativeDate(convertTimeZone($res[$k]['datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
                        $res[$k]['stars'] = rating_bar($res[$k]['pluginversionid'], 5);
                        if ($res[$k]['total_votes'] > 0) {
                            $res[$k]['rating'] = $res[$k] ['total_value'] / $res[$k] ['total_votes'];
                        }
                        $res[$k]['tags'] = explode(',', strtolower($res[$k]['pd_tags']));
                        $res[$k]['url'] = Jojo::rewrite('plugins/details', $res[$k]['pluginid'], $res[$k]['pd_name'], '');
                    }
                    $smarty->assign('action', 'all');
                    $smarty->assign('plugins', $res);

                    $breadcrumb['name'] = $tag;
                    $breadcrumb['rollover'] = "Plugins tagged with ".$tag;
                    $breadcrumb['url'] =  Jojo::rewrite('plugins',$tag,'tag','');
                    $breadcrumbs[] = $breadcrumb;

                    $content['title'] = "Plugins tagged with ".$tag;
                    $content['seotitle'] = "Plugins tagged with ".$tag;
                    $content['breadcrumbs'] = $breadcrumbs;
                    $content['content'] = $smarty->fetch('jojo_pluginmanger.tpl');
                    return $content;
                } else {
                    /* Tag Cloud */
                    $res = Jojo::selectQuery("SELECT pd_tags FROM plugin_details");
                    $tags = array();
                    foreach($res as $r) {
                        $restags = explode(',', strtolower($r['pd_tags']));
                        foreach ($restags as $t) {
                            $t = trim($t);
                            if (!isset($tags[$t])) {
                                $tags[$t] = 1;
                            } else {
                                $tags[$t] ++;
                            }
                        }
                    }

                    ksort($tags);
                    $min = min($tags);
                    $max = max($tags);
                    $factor = 1 / ($max - $min);
                    foreach ($tags as $k => $v) {
                        $tags[$k] = ($v - $min) * $factor;
                    }

                    $smarty->assign('action', 'tagcloud');
                    $smarty->assign('tags', $tags);
                    $content['content'] = $smarty->fetch('jojo_pluginmanger.tpl');
                    return $content;
                }

                break;
        }

        return $content;
    }

    function _getPlugins($sortBy, $sortDir = 'ASC', $page = 0, $perPage = 10)
    {
        /* Select the latest version of each plugin from database */
        $query = sprintf("SELECT
                            plugin_details.pluginid, plugin_details.pd_name,
                            plugin_details.pd_description, plugin_details.pd_tags,
                            plugin_version.pluginversionid, plugin_version.pv_status,
                            plugin_version.total_votes, plugin_version.total_value,
                            MAX(plugin_version.pv_version) AS version, plugin_version.pv_file_zip,
                            plugin_version.pv_file_tgz, plugin_version.pv_file_7z,
                            plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads,
                            MAX(plugin_version.pv_datetime) AS datetime
                          FROM
                            plugin_details, plugin_version
                          WHERE
                            plugin_details.pluginid = plugin_version.pv_pluginid
                          GROUP BY
                            plugin_version.pv_pluginid
                          ORDER BY %s %s", $sortBy, $sortDir);
        $res = Jojo::selectQuery($query);

        foreach($res as $k => $v) {
            $res[$k]['date'] = relativeDate(convertTimeZone($res[$k]['datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
            $res[$k]['stars'] = rating_bar($res[$k]['pluginversionid'], 5);
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


    function sendComment()
    {
        global $smarty;

        include(_BASEDIR.'/includes/no-form-injection.inc.php');


        $fields = array();
        $errors = '';

        if (!checkEmailFormat($_POST['email'])) {
            $errors .= 'Email is not a valid or empty email format<br/>';
        }
        if ($_POST['name'] == '') {
            $errors .= 'The field Name is empty<br/>';
        }
        if ($_POST['firstname'] == '') {
            $errors .= 'The field Firstname is empty<br/>';
        }
        if ($_POST['comment'] == '') {
            $errors .= 'The field Comment is empty<br/>';
        }

        if (Jojo::getOption('contactcaptcha') == 'yes') {
            $captchacode = Util::getFormData('CAPTCHA','');
            require_once(_BASEDIR.'/external/php-captcha/php-captcha.inc.php');
            if (!PhpCaptcha::Validate($captchacode)) {
                $errors .= 'Incorrect code entered';
            }
        }
        if ($errors === '') {
            $name = Jojo::clean($_POST['firstname'])." ".Jojo::clean($_POST['name']);
            Jojo::insertQuery("INSERT INTO plugin_comments SET pc_datetime='".strtotime('now')."', pc_pluginversionid = '".Jojo::clean($_POST['pluginversionid']) ."' , pc_comment = '".Jojo::clean($_POST['comment'])."' , pc_email = '".Jojo::clean($_POST['email'])."', pc_name ='".$name."' ");

            return '';
        }
        else {
            return $errors;
        }
    }
}
