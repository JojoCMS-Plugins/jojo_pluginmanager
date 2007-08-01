<?php
/*
++$f;
$fields[$f]['field'] = 'FirstName';                     //The ID of the field - no spaces, keep it short but descriptive
$fields[$f]['display'] = 'First Name';                  //The display name - this will show on the form and in the resulting email
$fields[$f]['required'] = true;                         //true or false - is this a required field?
$fields[$f]['validation'] = '';                         //The type of validation to be used - options are 'email', 'url', 'text', 'integer' or leave blank for no validation.
$fields[$f]['type'] = 'text';                           //type of input - use 'text', 'textarea', or 'checkboxes' - more options to come
$fields[$f]['size'] = 40;                               //Used for 'text' type fields - the size of the input
$fields[$f]['value'] = '';                              //A default value if any
$fields[$f]['options'] = array('option 1','option 2');  //An array of options. Required for 'checkboxes' type
$fields[$f]['rows'] = '15';                             //number of rows - only needed for textareas
$fields[$f]['cols'] = '40'; 							 //number of columns - only needed for textareas
$fields[$f]['comment'] = '';     						// particular advice for a special form field
$fields[$f]['change'] = '';
*/

$from_name_fields = array('pluginname','author', 'webssite' ,'demolink', 'description', 'tags' , 'releasenotes' ,'status', 'file1','file2','file3' ,'pluginversion'); //this is an array of all fields to be used

++$f;
$fields[$f]['field'] = 'pluginname';
$fields[$f]['display'] = 'Plugin Name';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = 30;
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;
++$f;
$fields[$f]['field'] = 'author';
$fields[$f]['display'] = 'Author';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = 30;
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'website';
$fields[$f]['display'] = 'Website';
$fields[$f]['required'] = false;
$fields[$f]['validation'] = 'url';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = 30;
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'demolink';
$fields[$f]['display'] = 'Demolink';
$fields[$f]['required'] = false;
$fields[$f]['validation'] = 'url';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'license';
$fields[$f]['display'] = 'License';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'description';
$fields[$f]['display'] = 'Description';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'textarea';
$fields[$f]['rows'] = '10';
$fields[$f]['cols'] = '40';
$fields[$f]['value'] = '';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'tags';
$fields[$f]['display'] = 'Tags';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'textarea';
$fields[$f]['rows'] = '5';
$fields[$f]['cols'] = '40';
$fields[$f]['value'] = '';
$fields[$f]['comment'] = 'Please enter specified tags for your plugin comma sperated.';
$fields[$f]['change'] = false;

++$f;
$fields[$f]['field'] = 'pluginversion';
$fields[$f]['display'] = 'Pluginversion';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'text';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['comment'] = 'Please enter the versionnumber in this format: major release.minor release.patch level - 1.2.1';
$fields[$f]['change'] = true;

++$f;
$fields[$f]['field'] = 'releasenotes';
$fields[$f]['display'] = 'Releasenotes';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'textarea';
$fields[$f]['rows'] = '10';
$fields[$f]['cols'] = '40';
$fields[$f]['value'] = '';
$fields[$f]['change'] = true;

++$f;
$fields[$f]['field'] = 'status';
$fields[$f]['display'] = 'Status';
$fields[$f]['required'] = true;
$fields[$f]['validation'] = '';
$fields[$f]['type'] = 'radio';
$fields[$f]['options'] = array('stable','alpha' , 'beta' , 'developer');
$fields[$f]['rows'] = '15';
$fields[$f]['cols'] = '40';
$fields[$f]['value'] = '';
$fields[$f]['change'] = true;

++$f;
$fields[$f]['field'] = 'file1';
$fields[$f]['display'] = 'File 1';
$fields[$f]['required'] = false;
$fields[$f]['validation'] = 'file';
$fields[$f]['type'] = 'file';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['comment'] = 'It is possible to upload 3 different archive files. The extension has to be tgz, zip or 7z.';
$fields[$f]['change'] = true;

++$f;
$fields[$f]['field'] = 'file2';
$fields[$f]['display'] = 'File 2';
$fields[$f]['required'] = false;
$fields[$f]['validation'] = 'file';
$fields[$f]['type'] = 'file';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['change'] = true;

++$f;
$fields[$f]['field'] = 'file3';
$fields[$f]['display'] = 'File 3';
$fields[$f]['required'] = false;
$fields[$f]['validation'] = 'file';
$fields[$f]['type'] = 'file';
$fields[$f]['size'] = '30';
$fields[$f]['value'] = '';
$fields[$f]['change'] = true;


