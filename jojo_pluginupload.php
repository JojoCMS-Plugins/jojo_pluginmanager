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

class JOJO_Plugin_Jojo_pluginupload extends JOJO_Plugin
{
    function _getContent()
    {
        global $smarty, $_USERID, $ajaxid;
        $content = array();
        $templateoptions['frajax'] = true;
        $smarty->assign('templateoptions', $templateoptions);

        $uploadpluginid = Util::getFormData('select-plugin', ''); /* upload new version for existing plugin  */

        /* only upload a new version for existing plugin */
        if ($uploadpluginid){
            $plugindetails = JOJO::selectQuery("SELECT * FROM plugin_details WHERE pluginid = '".$uploadpluginid."' LIMIT 1 ");
            $_SESSION['uploadpluginid'] = $plugindetails;
            $smarty->assign('version','version');
            $smarty->assign('plugindetails',$plugindetails);
        }

        $f=-1;
        $fields = array();
        if ( JOJO::fileExists(_PLUGINDIR.'/jojo_pluginmanager/pluginupload_fields.php')) {
            include(_PLUGINDIR . '/jojo_pluginmanager/pluginupload_fields.php');
        }
        $smarty->assign('fields',$fields);

        if (isset($_POST['submit'])) {$this->sendEnquiry();}

        /* Find username - seelct all userplugins */
        if (isset($_USERID)) {
            $userplugins = JOJO::selectQuery("SELECT pd_name, pluginid FROM plugin_details WHERE pd_userid = '".JOJO::cleanInt($_USERID)."' ");
            $smarty->assign('userplugins',$userplugins);
            $smarty->assign('userid',$_USERID);
            $thisuser = JOJO::selectQuery("SELECT us_login FROM user WHERE userid=".JOJO::cleanInt($_USERID)." LIMIT 1");
            $smarty->assign('username',$thisuser[0]['us_login']);
            $smarty->assign('userurl', JOJO::rewrite('profile',$_USERID,$thisuser[0]['us_login']));
        }
        $content['content'] = $this->page['pg_body'].$smarty->fetch('jojo_pluginupload.tpl');
        return $content;
    }

    function upperCaseEverything($data)
    {
        if (strpos(_SITEURI, 'manage-plugins') !== false) {
            /* Don't uppercase the manage plugins page, breaks the javascript
             and you can't disable this plugin again */
            return $data;
        }
        return strtoupper($data);
    }


    function getCorrectUrl()
    {		//Assume the URL is correct
        return _PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }


    function sendEnquiry()
    {
        global $smarty, $_USERID;

        include(_BASEDIR.'/includes/no-form-injection.inc.php');
        $pluginid = Util::getFormData('pluginid', ''); //upload new version for existing plugin

        /*save only the new version of plugin - plugin already exists*/
        if ($pluginid){
            if (isset($_SESSION['plugindetails'])){
                $plugindetails = $_SESSION['plugindetails'];
            }
            else{
                $plugindetails = JOJO::selectQuery("SELECT * FROM plugin_details WHERE pluginid = '".$pluginid."' LIMIT 1 ");
                $smarty->assign('version','version');
                $smarty->assign('plugindetails',$plugindetails);
            }
        }

        $f=-1;
        $fields = array();

        if ( JOJO::fileExists(_PLUGINDIR.'/jojo_pluginmanager/pluginupload_fields.php')) {
            include(_PLUGINDIR.'/jojo_pluginmanager/pluginupload_fields.php');
        }

        $files = array();
        $errors = array();

        /* replace spaces in tags*/
        if ($_POST['form_tags']) {
            $_POST['form_tags'] = str_replace(array("\n") , array(',') ,  strtolower($_POST['form_tags']));
        }
        /* check unique plugin name */
        if ($_POST['form_pluginname']) {
            $data = JOJO::selectQuery("SELECT pd_name FROM plugin_details WHERE pd_name = '".$_POST['form_pluginname']."' ");
            if (sizeof($data) > 0) {
                $errors[] = "A plugin with the same name already exists, please choose another name for your plugin.";
            }
        }

        /* check format of version number*/
        if($_POST['form_pluginversion']) {
            $parts = explode("." , $_POST['form_pluginversion']);
            if (count($parts) > 3) {
                $errors[] = "Wrong format for versionnumber.";
            }
        }

        $n = count($fields);
        /* check the content and validate the form fields */
        for ($i=0;$i<$n;$i++) {

            if (($fields[$i]['change'] == true && $pluginid != '') || $pluginid == '' ) {
                /* set field value from POST */
                if (is_array($_POST['form_'.$fields[$i]['field']])) {
                    /* convert array to string */
                    $fields[$i]['value'] = implode(", ",$_POST['form_'.$fields[$i]['field']]);
                } else {
                    $fields[$i]['value'] = Util::getFormData('form_'.$fields[$i]['field'],'');
                }
                /* check value is set on required fields */
                if (($fields[$i]['required']) && (empty($fields[$i]['value']))) {
                    $errors[] = $fields[$i]['display'].' is a required field';
                }
                /* validation */
                if (!empty($fields[$i]['value'])) {
                    switch ($fields[$i]['validation']) {
                        case 'email':
                            if (!checkEmailFormat($fields[$i]['value'])) {$errors[] = $fields[$i]['display'].' is not a valid email format';}
                            break;
                        case 'url':
                            $fields[$i]['value'] = addHttp($fields[$i]['value']);
                            if (!checkUrlFormat($fields[$i]['value'])) {$errors[] = $fields[$i]['display'].' is not a valid URL format';}
                            break;
                        case 'integer':
                            if (!is_numeric($fields[$i]['value'])) {$errors[] = $fields[$i]['display'].' is not an integer value';}
                            break;
                        case 'numeric':
                            if (!is_numeric($fields[$i]['value'])) {$errors[] = $fields[$i]['display'].' is not an integer value';}
                            break;
                    }
                }
            }
        }
        /* check uploaded files */
        if (isset($_FILES)) {
            $filenames = array(); /* array to check double extensions and save the names of files for database update */
            $uploadfiles = array(); /* to upload the files later  */
            foreach ($_FILES as $file) {

                $ex = getFileExtension($file['name']);
                if ($file['name'] != '') {
                    if ($ex == 'zip' || $ex == 'tgz' || $ex == '7z') {

                        if($filenames[$ex] != '') {
                            $error[] = 'Please upload one '.$ex.' File only';
                        }else{
                            $filenames[$ex] = $file['name'];
                            switch ($file['error']) {
                                case UPLOAD_ERR_INI_SIZE: //1
                                    $errors[] = "The uploaded file exceeds the maximum size allowed (1Mb)";
                                    break;
                                case UPLOAD_ERR_FORM_SIZE: //2
                                    $errors[] = "The uploaded file exceeds the maximum size allowed in PHP.INI";
                                    break;
                                case UPLOAD_ERR_PARTIAL: //3
                                    $errors[] = "The file has only been partially uploaded. There may have been an error in transfer, or the server may be having technical problems . ";
                                    break;
                                case UPLOAD_ERR_NO_FILE: //4 - this is only a problem if it's a required field
                                    $errors[] = "File missing";
                                    break;
                                case 6: // UPLOAD_ERR_NO_TMP_DIR - for some odd reason the constant wont work
                                    $errors[] = "There is no temporary folder on the server";
                                    //log for administrator
                                    break;
                                case UPLOAD_ERR_OK: //0
                                    //check for empty file
                                    if($file["size"] == 0) {
                                        $errors[] = "The uploaded file is empty . ";
                                    }
                                    else{
                                        $uploadfiles[] = $file;

                                    }
                            }
                        }
                    }else {
                        $errors[] = 'Please upload only zip, tgz and 7z archive files';
                    }
                }
            }
        }else {
            $errors[] = 'The file is missing';
        }

        /* update database and move files to download - plugin directory */
        if (count($errors) == 0) {

            if ($pluginid == '') {
                JOJO::insertQuery("INSERT INTO plugin_details SET pd_userid='".$_USERID."', pd_name = '".JOJO::clean($_POST['form_pluginname']) ."' , pd_author = '".JOJO::clean($_POST['form_author'])."', pd_website ='".JOJO::clean($_POST['form_website'])."' , pd_license ='".JOJO::clean($_POST['form_license'])."' , pd_description ='".JOJO::clean($_POST['form_description'])."' , pd_demolink ='".JOJO::clean($_POST['form_demolink'])."' , pd_tags ='".JOJO::clean($_POST['form_tags'])."' ");
                $data = JOJO::selectQuery("SELECT pluginid FROM plugin_details WHERE pd_name = '".JOJO::clean($_POST['form_pluginname'])."' ");
                $pluginid = $data[0]['pluginid'];
                $uploadpath = _DOWNLOADDIR."/plugins/".$_POST['form_pluginname']."/".$_POST['form_pluginversion'];
                JOJO::recursiveMkdir($uploadpath);
            } else {
                $data = JOJO::selectQuery("SELECT * FROM plugin_details WHERE pluginid = ?", $pluginid);
                $uploadpath = _DOWNLOADDIR . "/plugins/" . $data[0]['pd_name'] . "/" . $_POST['form_pluginversion'];
                JOJO::recursiveMkdir($uploadpath);
            }

            JOJO::insertQuery("INSERT INTO plugin_version SET pv_pluginid='".$pluginid."', pv_version = '".JOJO::clean($_POST['form_pluginversion']) ."' , pv_datetime='".strtotime('now')."', pv_releasenotes = '".JOJO::clean($_POST['form_releasenotes'])."' , pv_status = '".JOJO::clean($_POST['form_status'])."' , pv_file_zip ='".$filenames['zip']."' , pv_file_tgz ='".$filenames['tgz']."' ,pv_file_7z ='".$filenames['7z']."' ");
            JOJO::recursiveMkdir(_DOWNLOADDIR."/plugins/".$_POST['form_pluginname']."/".$_POST['form_pluginversion']);

            foreach ($uploadfiles as $upload) {
                move_uploaded_file($upload['tmp_name'], $uploadpath."/".$upload['name']);
            }

            $smarty->assign('message', 'Your pluginupload was successful');

        } else {
            $smarty->assign('message', implode("<br />\n",$errors));
            $smarty->assign('fields',$fields);
        }
    }
}
