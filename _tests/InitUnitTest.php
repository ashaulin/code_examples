<?php
date_default_timezone_set( 'Europe/Moscow' );
$path = str_replace("_tests","",realpath(__DIR__));
$path = str_replace("//", "/", $path);

if(!defined('CMS_DOCUMENT_ROOT')) define('CMS_DOCUMENT_ROOT', $path);
$path_lib = $path."/includes/lib/";
if(!defined('CMS_LIB_PATH')) define('CMS_LIB_PATH', $path_lib);
$path_inc = $path."/includes/";
if(!defined('CMS_INC_PATH')) define('CMS_INC_PATH', $path_inc);
if(!defined('CMS_MODEL_PATH')) define('CMS_MODEL_PATH', CMS_INC_PATH."model/");
if(!defined('TEST_PATH')) define('TEST_PATH', $path);

ini_set('include_path', join(substr(PHP_OS,0,3)=="WIN"?';':':', array(CMS_DOCUMENT_ROOT, CMS_LIB_PATH, CMS_INC_PATH, TEST_PATH)));

if (empty($_SERVER['HTTP_HOST'])) $_SERVER['HTTP_HOST'] = '';
if (empty($_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI'] = '';
if (empty($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = '';
if (empty($_SERVER['REMOTE_ADDR'])) $_SERVER['REMOTE_ADDR'] = '';

function classAutoLoader($className){
    $file_class = null;
    if(file_exists(CMS_DOCUMENT_ROOT.'/includes/'.str_replace('_', '/', $className) . '.php'))
        $file_class = CMS_DOCUMENT_ROOT.'/includes/'.str_replace('_', '/', $className) . '.php';
    elseif(file_exists(CMS_DOCUMENT_ROOT.'/includes/lib/'.str_replace('_', '/', $className) . '.php'))
         $file_class = CMS_DOCUMENT_ROOT.'/includes/lib/'.str_replace('_', '/', $className) . '.php';
    if($file_class != null)
        $res = require_once($file_class);
}
    
class InitUnitTest extends PHPUnit_Framework_TestCase
{
    public final function __construct( $name=null, array $data=array(), $dataName='') {
        parent::__construct( $name, $data, $dataName);
        spl_autoload_register( 'classAutoLoader');
    }

    protected function toCache($key, $value) {
        $filename = realpath(__DIR__) . '/cache/' . get_class($this);
        $data = file_get_contents($filename);
        $data = json_decode($data, true);

        if(empty($data)) $data = array();
        $data[$key] = $value;

        file_put_contents($filename, json_encode($data));
    }

    protected function fromCache($key) {
        $filename = realpath(__DIR__) . '/cache/' . get_class($this);
        $data = file_get_contents($filename);
        $data = json_decode($data, true);

        if(!empty($data[$key])) return $data[$key];
        else return false;
    }
}
    
?>
