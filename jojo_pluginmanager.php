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
		$direction = Util::getFormData('direction', ''); //next 10 pr previuos 10
		$versionid = Util::getFormData('versionid', ''); //next 10 pr previuos 10
		   
		/* Download of any plugin - update download counter*/
		if ($file != '') {				
			$basedir = "_DOWNLOADDIR/plugins/pluginName/Version/";
			$filename = sprintf("%s/%s", $basedir, $file);
			$extension = getFileExtension($file);
				
			JOJO::updateQuery("UPDATE plugin_version SET pv_downloads = pv_downloads+1 WHERE pluginversionid =".$id);
				
			/* save the updated Values in Session*/
			$data = JOJO::selectQuery("SELECT plugin_details.pluginid, plugin_details.pd_name, plugin_details.pd_description, plugin_details.pd_tags, plugin_version.pluginversionid, plugin_version.pv_status, plugin_version.total_votes, plugin_version.total_value, MAX(plugin_version.pv_file) AS file, plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads, MAX(plugin_version.pv_datetime) AS date FROM plugin_details, plugin_version WHERE plugin_details.pluginid = plugin_version.pv_pluginid GROUP BY plugin_version.pv_pluginid ORDER BY date DESC");

			for ($i = 0; $i < sizeof($data); $i++ ) {
				$data[$i]['date'] = relativeDate(convertTimeZone($data[$i]['date'],$_SERVERTIMEZONE,$_USERTIMEZONE));
				$data[$i] ['stars'] = rating_bar($data[$i]['pluginversionid'], 5);
				$data[$i] ['rating'] = 	$data[$i] ['total_value'] / $data[$i] ['total_votes'];
				$data[$i] ['downloadpath'] =  _DOWNLOADDIR."/plugins/pluginName/Version/".$data[$i]['file'];
				$data[$i] ['tags']= explode(',', $data[$i]['pd_tags']);
			}
			$_SESSION['sortplugins'] = $data;
			/* send header fot file typ*/
			switch ($extension) {
				case 'zip': header("Content-Type: application/octet-stream"); break;
				case 'tgz': header("Content-Type: application/x-compressed"); break;
				case '7z': header("Content-Type: application/x-7z-compressed"); break;
			}
			$save_as_name = basename($file);
			header("Content-Disposition: attachment; filename=\"$save_as_name\"");
			readfile($filename);
		/* details of one plugin*/
		}elseif ($versionid != ''){
			if ( $_SESSION['plugindetails']) {	
			$plugindetails = $_SESSION['plugindetails'];		
			$plugincomments =  JOJO::selectQuery("SELECT * FROM plugin_comments, plugin_version where plugin_comments.pc_pluginversionid = ".$versionid." AND plugin_version.pluginversionid = ".$versionid);
			$plugincomments[0] ['stars'] = rating_bar($plugincomments[0]['pluginversionid'], 5);
			
			for ($i = 0; $i < sizeof($plugincomments); $i++ ) {
			$plugincomments[$i]['date'] = relativeDate(convertTimeZone($plugincomments[$i]['pc_datetime'],$_SERVERTIMEZONE,$_USERTIMEZONE));			
			}
			
			$smarty->assign('plugincomments',$plugincomments);
			}else {				
			$plugindetails =  JOJO::selectQuery("SELECT * FROM plugin_comments, plugin_version, plugin_details where plugin_comments.pc_pluginversionid = ".$versionid." AND plugin_version.pluginversionid = ".$versionid." AND plugin_details.pluginid = plugin_version.pv_pluginid");
			$plugindetails[0]['date'] = relativeDate(convertTimeZone($data[$i]['date'],$_SERVERTIMEZONE,$_USERTIMEZONE));
			$plugindetails[0] ['stars'] = rating_bar($data[$i]['pluginversionid'], 5);
			}
			$plugindetails[0] ['tags']= explode(',', $plugindetails[0]['pd_tags']);
			$smarty->assign('plugindetails',$plugindetails);
		
			$smarty->assign('action','allComments');
			
			$breadcrumbs = $this->_getBreadCrumbs();
            $breadcrumb = array();
            $breadcrumb['name'] = $plugindetails[0]['pd_name'];
            $breadcrumb['rollover'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
            $breadcrumb['url'] =  JOJO::rewrite('plugins',$versionid,'showComments','');
            $breadcrumbs[count($breadcrumbs)] = $breadcrumb;

            $content['title'] = "Pluginversion Details";
            $content['seotitle'] = 'Details of Plugin Version - '.$plugindetails[0]['pd_name'];
            $content['breadcrumbs'] = $breadcrumbs;
		
		}elseif ($id != '') {
			/* select all Details of one selected Plugin and all pluginversions */
			$plugindetails = JOJO::selectQuery("SELECT * FROM plugin_details WHERE pluginid =".$id." LIMIT 1");			
			$pluginversions = JOJO::selectQuery("SELECT plugin_version.pluginversionid, plugin_version.pv_version, plugin_version.pv_datetime, plugin_version.pv_releasenotes, plugin_version.pv_status, plugin_version.pv_file, plugin_version.pv_downloads, plugin_version.total_votes, plugin_version.total_value, plugin_comments.pc_comment, MAX(plugin_comments.pc_datetime) AS date, plugin_comments.pc_name FROM plugin_version, plugin_details, plugin_comments WHERE plugin_version.pv_pluginid = ".$id." AND plugin_version.pv_pluginid = plugin_comments.pc_pluginversionid GROUP BY plugin_version.pluginversionid");
		
			/*no results for last query, because no comments for this plugin yet*/
			if (!$pluginversions) {
			$pluginversions = JOJO::selectQuery("SELECT plugin_version.pluginversionid, plugin_version.pv_version, plugin_version.pv_datetime, plugin_version.pv_releasenotes, plugin_version.pv_status, plugin_version.pv_file, plugin_version.pv_downloads, plugin_version.total_votes, plugin_version.total_value FROM plugin_version, plugin_details WHERE plugin_version.pv_pluginid = ".$id." GROUP BY plugin_version.pluginversionid");
			}
			
			for ($i = 0; $i < sizeof($pluginversions); $i++ ){				
				$plugincomments =  JOJO::selectQuery("Select Count(*) AS count FROM plugin_comments WHERE pc_pluginversionid = ".$pluginversions[$i]['pluginversionid']);
				$pluginversions[$i]['countComments'] = $plugincomments [0]['count'];
			}
			
			$_SESSION['plugindetails'] = $plugindetails;
			$_SESSION['pluginversion'] = $pluginversions;
			for ($i = 0 ; $i < sizeof($pluginversions); $i ++) {
				$pluginversions[$i] ['stars'] = rating_bar($pluginversions[$i]['pluginversionid'], 5);
			}
			$smarty->assign('action','details');
			$smarty->assign('plugindetails',$plugindetails);
			$smarty->assign('pluginversions',$pluginversions);
			
			$breadcrumbs = $this->_getBreadCrumbs();
            $breadcrumb = array();
            $breadcrumb['name'] = $plugindetails[0]['pd_name'];
            $breadcrumb['rollover'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
            $breadcrumb['url'] =  JOJO::rewrite('plugins',$id,'details','');
            $breadcrumbs[count($breadcrumbs)] = $breadcrumb;

            $content['title'] = "Details ".$plugindetails[0]['pd_name'];
            $content['seotitle'] = 'Details of Plugin '.$plugindetails[0]['pd_name'];
            $content['breadcrumbs'] = $breadcrumbs;
			
			
		/* general call auf plugin page -. sorting method or all plugins with a defined tag*/
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
					case 'updated': $sort = 'date'; break;
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
			arsort($sort_values);
			reset($sort_values);
			//new array with sorting values
			while (list ($arr_key, $arr_val) = each ($sort_values)) {
				{$data[] = $array[$arr_key];}
			}			
			$smarty->assign('action','all');
			/* all plugins with a special tag*/
			}elseif ($tag != '') {

				$plugins = array();
				if ($_SESSION['sortplugins']) {						
					foreach ($_SESSION['sortplugins'] as $p) {
						if (in_array($tag, $p['tags'])) {
							$data[] = $p;
						}
					}
				}else {
					$data = JOJO::selectQuery("SELECT plugin_details.pluginid, plugin_details.pd_name, plugin_details.pd_description, plugin_details.pd_tags, plugin_version.pluginversionid, plugin_version.pv_status, plugin_version.total_votes, plugin_version.total_value, MAX( plugin_version.pv_file ) AS FILE , plugin_version.used_ips, SUM( plugin_version.pv_downloads ) AS downloads, MAX( plugin_version.pv_datetime ) AS date FROM plugin_details, plugin_version WHERE plugin_details.pd_tags LIKE '%".$tag."%' AND plugin_details.pluginid = plugin_version.pv_pluginid GROUP BY plugin_version.pv_pluginid ORDER BY date DESC");
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
				$data = JOJO::selectQuery("SELECT plugin_details.pluginid, plugin_details.pd_name, plugin_details.pd_description, plugin_details.pd_tags, plugin_version.pluginversionid, plugin_version.pv_status, plugin_version.total_votes, plugin_version.total_value, MAX(plugin_version.pv_file) AS file, plugin_version.used_ips, SUM(plugin_version.pv_downloads)AS downloads, MAX(plugin_version.pv_datetime) AS date FROM plugin_details, plugin_version WHERE plugin_details.pluginid = plugin_version.pv_pluginid GROUP BY plugin_version.pv_pluginid ORDER BY date DESC");
				for ($i = 0; $i < sizeof($data); $i++ ) {
					$data[$i]['date'] = relativeDate(convertTimeZone($data[$i]['date'],$_SERVERTIMEZONE,$_USERTIMEZONE));
					$data[$i] ['stars'] = rating_bar($data[$i]['pluginversionid'], 5);
					if ($data[$i] ['total_votes'] > 0) {
						$data[$i] ['rating'] = 	$data[$i] ['total_value'] / $data[$i] ['total_votes'];
					}
					
					$data[$i] ['downloadpath'] =  _DOWNLOADDIR."/plugins/pluginName/Version/".$data[$i]['file'];
					$data[$i] ['tags']= explode(',', $data[$i]['pd_tags']);
				}
				$_SESSION['sortplugins'] = $data;
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
		
			for ($i = $start; $i < ($showPlugins); $i++){
				$plugins[] = $data[$i];
			}

			$show >= sizeof($data) ? $show = 0 : '';
			$smarty->assign('next',$show);
			$smarty->assign('previous',$start);
			$smarty->assign('plugins',$plugins);	
					
		}

		$content['content'] = $smarty->fetch('jojo_pluginmanger.tpl');
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

}
