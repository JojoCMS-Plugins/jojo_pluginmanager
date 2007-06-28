<?php

/* sorting plugins 
preg_match_all('%^plugins/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
    $ret['url'] = 'plugins';
    unset($_SESSION['sort']);
    return $ret;
}*/       

/* sorting plugins */
preg_match_all('%^plugins/([a-z])/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['sort'] = $result[1][0];
	$_GET['id'] = $result[2][0];	
    $ret['url'] = 'plugins';
    return $ret;
}

/*show details*/
preg_match_all('%^plugins/([0-9]+)/details/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['id'] = $result[1][0];
    $ret['url'] = 'plugins';
    return $ret;
}

/*show next plugin page*/
preg_match_all('%^plugins/([0-9]+)/next/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['show'] = $result[1][0];
	$_GET['direction'] = 'next';
    $ret['url'] = 'plugins';
    return $ret;
}

/*show previous plugin page*/
preg_match_all('%^plugins/([0-9]+)/previous/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['show'] = $result[1][0];
	$_GET['direction'] = 'previous';
    $ret['url'] = 'plugins';
    return $ret;
}

/*download a file*/
preg_match_all('%^plugins/([0-9a-zA-Z.\_\-]+)/([0-9]+)/download/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['file'] = $result[1][0];
	$_GET['id'] = $result[2][0];
    $ret['url'] = 'plugins';
    return $ret;
}

/*show all plugins with a special tag*/
preg_match_all('%^plugins/([0-9a-zA-Z.\_\-^ ]+)/tag/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['tag'] = $result[1][0];
    $ret['url'] = 'plugins';
    return $ret;
}

/*show all plugins with a special tag - and the next page*/
preg_match_all('%^plugins/([0-9]+)/(([0-9a-zA-Z.\_\-^ ]+))/next/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['tag'] = $result[2][0];
	$_GET['show'] = $result[1][0];
	$_GET['direction'] = 'next';
    $ret['url'] = 'plugins';
    return $ret;
}

/*show all plugins with a special tag - and the previous page*/
preg_match_all('%^plugins/([0-9]+)/(([0-9a-zA-Z.\_\-^ ]+))/previous/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['tag'] = $result[2][0];
	$_GET['show'] = $result[1][0];
	$_GET['direction'] = 'previous';
    $ret['url'] = 'plugins';
    return $ret;
}
/*show all plugins with a special tag - and the previous page*/
preg_match_all('%^plugins/([0-9]+)/showComments/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
	$_GET['versionid'] = $result[1][0];
    $ret['url'] = 'plugins';
    return $ret;
}

 
