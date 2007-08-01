<?php

/* Show plugin details*/
preg_match_all('%^plugins/details/([0-9]+)/(.*)?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[1]) {
    $_GET['action'] = 'details';
    $_GET['id'] = $result[1][0];
    $ret['url'] = 'plugins';
    $url = '';
    return $ret;
}

/* Download a plugin file*/
preg_match_all('%^plugins/download/([0-9]+)/([0-9a-zA-Z.\_\-]+)/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
    $_GET['action'] = 'download';
    $_GET['file'] = $result[2][0];
    $_GET['id'] = $result[1][0];
    $ret['url'] = 'plugins';
    return $ret;
}

/* Show all plugins with a specific tag */
preg_match_all('%^plugins/tags/?(.*)?/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[0]) {
    $_GET['action'] = 'tags';
    $_GET['tag'] = urldecode($result[1][0]);
    $ret['url'] = 'plugins';
    return $ret;
}

/* Sorting Plugins */
preg_match_all('%^plugins/(name|updated|rating|downloads|status|/)/?$%i', $url, $result, PREG_PATTERN_ORDER);
if ($result[1]) {
    $_GET['action'] = $result[1][0];
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


