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

require(_BASEDIR.'\plugins\jojo_pluginmanager\external\ajaxrating\_drawrating.php');

class JOJO_Plugin_Jojo_pluginmanager extends JOJO_Plugin
{
	function _getContent()
	{
		global $smarty;
		$content = array();

		/* that's the number of plugins shown per page */
		$pluginsPerPage = 10;

		$sort = Util::getFormData('sort', ''); // sorting method all plugins
		$file = Util::getFormData('file', ''); // download plugin
		$id = Util::getFormData('id', ''); // view details of plugin or download id
		$tag = Util::getFormData('tag', ''); // view all plugins with one special tag
		$show = Util::getFormData('show', 0); // only 10 plugins per page
		$direction = Util::getFormData('direction', ''); //next 10 or previuos 10
		$versionid = Util::getFormData('versionid', ''); //versiondetails and all comments

		/* Download of any plugin - update download counter*/
		if ($file != '') {

			$data = JOJO::selectQuery("SELECT  *  FROM plugin_version, plugin_details WHERE plugin_version.pluginversionid = $id AND plugin_version.pv_pluginid = plugin_details.pluginid LIMIT 1");
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
			header("Content-Disposition: attachment; filename=\"$save_as_name\"");
			@readfile($filename) or die("File not found.");

			/* Update Downloadcounter*/
			JOJO::updateQuery("UPDATE plugin_version SET pv_downloads = pv_downloads+1 WHERE pluginversionid =".$id);

			/* details of one plugin*/
		}else if ($versionid != '' || $id != ''){

			$templateoptions['frajax'] = true;
			$smarty->assign('templateoptions', $templateoptions);

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

				if ($_SESSION['pluginversions']) {
					$plugindetails = $_SESSION['pluginversions'];
				}else {
					$plugindetails =  JOJO::selectQuery("SELECT * FROM plugin_version, plugin_details where plugin_version.pluginversionid = ".$versionid);
					$plugindetails[0]['date'] = relativeDate(convertTimeZone($plugindetails[$i]['pv_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
					$plugindetails[0]['stars'] = rating_bar($plugindetails[0]['pluginversionid'], 5);
					$plugindetails[0]['tags']= explode(',', $plugindetails[0]['pd_tags']);
				}
				$plugincomments =  JOJO::selectQuery("SELECT * FROM plugin_comments, plugin_version where plugin_comments.pc_pluginversionid = ".$versionid." AND plugin_version.pluginversionid = ".$versionid." ORDER BY plugin_comments.pc_datetime DESC");

				for ($i = 0; $i < sizeof($plugincomments); $i++ ) {
					$plugincomments[$i]['date'] = relativeDate(convertTimeZone($plugincomments[$i]['pc_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
				}
				$breadcrumbname = $plugindetails[0]['pd_name'];
				$smarty->assign('plugincomments',$plugincomments);
				$smarty->assign('plugindetails',$plugindetails);
				$smarty->assign('action','allComments');
				/* view all versions of one plugin and the last comment*/
			}else if ($id != '') {
				$pluginversions = JOJO::selectQuery("SELECT * FROM plugin_version, plugin_details WHERE plugin_version.pv_pluginid = ".$id." AND plugin_details.pluginid = ".$id);
				$versioncomments =array();

				for ($i = 0 ; $i < sizeof($pluginversions); $i++ ){
					$versioncomments = JOJO::selectQuery("SELECT plugin_comments.* FROM plugin_comments, plugin_version WHERE plugin_comments.pc_pluginversionid = plugin_version.pluginversionid AND plugin_version.pluginversionid = ".$pluginversions[$i]['pluginversionid']." ORDER BY pc_datetime DESC");
					
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
			$breadcrumb['url'] =  JOJO::rewrite('plugins',$id,'details','');
			$breadcrumbs[count($breadcrumbs)] = $breadcrumb;

			$content['title'] = "Details ".$plugindetails[0]['pd_name'];
			$content['seotitle'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
			$content['breadcrumbs'] = $breadcrumbs;
		}else {
			$plugins = array();
			/* thats important for page browsing */
			if ($_SESSION['sort'] && $sort == '') {
				$sort = $_SESSION['sort'];
			}
			/* get the sorting method */
			if ($sort != '') {
				switch ($sort) {
					case 'status': $sort = 'pv_status'; break;
					case 'name': $sort = 'pd_name'; break;
					case 'updated': $sort = 'pv_datetime'; break;
					case 'rating': $sort = 'rating'; break;
					case 'download': $sort = 'downloads'; break;
				}
				$_SESSION['sort']  = $sort;
				$array = $_SESSION['sortplugins'];
				$sort_values = array();

				for ($i = 0; $i < sizeof($array); $i++){
					//save only sorting parameter
					$sort_values[$i] = strtolower($array[$i][$sort]);
				}
				if ($sort === 'pd_name' || $sort === 'pv_datetime') {
					asort($sort_values);
				}else {
					arsort($sort_values);
				}
				reset($sort_values);
				//new array with sorting values
				while (list ($arr_key, $arr_val) = each ($sort_values)) {
					{$data[] = $array[$arr_key];}
				}
				$smarty->assign('action','all');
				/* all plugins with a special tag*/
			}else if ($tag != '') {
				$plugins = array();
				if ($_SESSION['sortplugins']) {
					foreach ($_SESSION['sortplugins'] as $p) {
						if (in_array($tag, $p['tags'])) {
							$data[] = $p;
						}
					}
				}else {
					$data = JOJO::selectQuery("SELECT plugin_details.pluginid, plugin_details.pd_name, plugin_details.pd_description, plugin_details.pd_tags, plugin_version.pluginversionid, plugin_version.pv_status, plugin_version.total_votes, plugin_version.total_value, MAX( plugin_version.pv_file ) AS FILE , plugin_version.used_ips, SUM( plugin_version.pv_downloads ) AS downloads, MAX( plugin_version.pv_datetime ) AS datetime FROM plugin_details, plugin_version WHERE plugin_details.pd_tags LIKE '%".$tag."%' AND plugin_details.pluginid = plugin_version.pv_pluginid GROUP BY plugin_version.pv_pluginid ORDER BY datetime ASC");
				}
				$smarty->assign('cat',$tag);
				$smarty->assign('action','tags');
				$breadcrumbs = $this->_getBreadCrumbs();
				$breadcrumb = array();
				$breadcrumb['name'] = $tag;
				$breadcrumb['rollover'] = "Plugins tagged with ".$tag;
				$breadcrumb['url'] =  JOJO::rewrite('plugins',$tag,'tag','');
				$breadcrumbs[count($breadcrumbs)] = $breadcrumb;

				$content['title'] = $tag." - Plugins";
				$content['seotitle'] = "Plugins tagged with ".$tag;
				$content['breadcrumbs'] = $breadcrumbs;
				/* show all */
			}else {
				/* select the latest version of each plugin from database*/
				$data = JOJO::selectQuery("SELECT plugin_details.pluginid, plugin_details.pd_name, plugin_details.pd_description, plugin_details.pd_tags, plugin_version.pluginversionid, plugin_version.pv_status, plugin_version.total_votes, plugin_version.total_value, MAX(plugin_version.pv_version) AS version, plugin_version.pv_file_zip, plugin_version.pv_file_tgz, plugin_version.pv_file_7z, plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads, MAX(plugin_version.pv_datetime) AS datetime FROM plugin_details, plugin_version WHERE plugin_details.pluginid = plugin_version.pv_pluginid GROUP BY plugin_version.pv_pluginid ORDER BY datetime ASC");
				$smarty->assign('action','all');
			}
			/* calculate the next or previous results*/
			if ($direction == 'previous') {
				$showPlugins = $show;
				$start = $show-$pluginsPerPage;
			}else {
				sizeof($data) - $show < $pluginsPerPage ? $showPlugins = sizeof($data) : $showPlugins = $show + $pluginsPerPage;
				$start = $show;
				$show +=$pluginsPerPage;
			}

			for ($i = 0; $i < sizeof($data); $i++ ) {
				$data[$i]['date'] = relativeDate(convertTimeZone($data[$i]['datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));
				$data[$i] ['stars'] = rating_bar($data[$i]['pluginversionid'], 5);
				if ($data[$i] ['total_votes'] > 0) {
					$data[$i] ['rating'] = 	$data[$i] ['total_value'] / $data[$i] ['total_votes'];
				}
				$data[$i] ['tags']= explode(',', $data[$i]['pd_tags']);
			}
			$_SESSION['sortplugins'] = $data;

			for ($i = $start; $i < ($showPlugins); $i++){
				$plugins[] = $data[$i];
			}

			$show >= sizeof($data) ? $show = 0 : '';
			$smarty->assign('next',$show);
			$smarty->assign('previous',$start);
			$smarty->assign('plugins',$plugins);
		}
		$content['content'] = $this->page['pg_body'].$smarty->fetch('jojo_pluginmanger.tpl');
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
			
		if (JOJO::getOption('contactcaptcha') == 'yes') {
			$captchacode = Util::getFormData('CAPTCHA','');
			require_once(_BASEDIR.'/external/php-captcha/php-captcha.inc.php');
			if (!PhpCaptcha::Validate($captchacode)) {
				$errors .= 'Incorrect code entered';
			}
		}
		if ($errors === '') {
			$name = JOJO::clean($_POST['firstname'])." ".JOJO::clean($_POST['name']);
			JOJO::insertQuery("INSERT INTO plugin_comments SET pc_datetime='".strtotime('now')."', pc_pluginversionid = '".JOJO::clean($_POST['pluginversionid']) ."' , pc_comment = '".JOJO::clean($_POST['comment'])."' , pc_email = '".JOJO::clean($_POST['email'])."', pc_name ='".$name."' ");

			return '';
		}
		else {
			return $errors;
		}
	}
}
