<?php
/**
 * Example Application
 *
 * @package Example-application
 */

$var = parse_ini_file(".sirclo-tdk");

require $var['smarty_dir'].'/libs/SmartyBC.class.php';
include 'PageView.php';
include 'PhpwebArray.php';
include 'Helper/Paypal.php';
include 'Helper/Cart.php';
include 'Helper/Krco.php';
include 'Helper/Xml.php';
include 'Helper/URL.php';
include 'Helper/String.php';
include 'Helper/Structure.php';
include 'Helper/Renderer.php';
include 'Helper/File.php';
include 'Renderer/Cart.php';

$smarty = new SmartyBC;

$smarty->force_compile = true;
$smarty->addPluginsDir('./plugins');
if (isset($var['temp_dir']) && strlen($var['temp_dir']) > 0) {
  $smarty->setCompileDir($var['temp_dir']  . DS . $_SERVER['SERVER_NAME'] . DS);
  $smarty->setCacheDir($var['temp_dir'] . DS . $_SERVER['SERVER_NAME'] . DS);  
}

define("MAIN_TEST_DATA_FILE", 'tests/data.json');

/**
 * Handle '/' request
 * @return always true
 */
function HandlePageList() {
  $pagenames = array_map(
    function ($page) {
      preg_match('|templates/(?P<pagename>[^\.]+)\.tpl|',$page,$matches);
      $pagename = $matches['pagename'];
      return ($pagename != 'index') ? $pagename : null;
    }
    ,glob('templates/*.tpl')
  );
  include 'pagelist.php';
  return true;
}
/**
 * Handle '/pages/<page name>' request
 * @param array $matches should only contain 'pagename'
 * @return true if scenario directory exists, false otherwise
 */
function HandleScenarioList($matches) {
  $pagename = $matches['pagename'];
  $scenariodir = 'tests/'.$pagename;
  $handled = file_exists($scenariodir);

  if ($handled) {
    $scenarios = glob($scenariodir.'/*.json');

    $scenarios = array_map(
      function ($data) use ($scenariodir) {
        preg_match('|'.$scenariodir.'/(?P<jsonname>[^\.]+)\.json|',$data,$matches);
        $jsonname = $matches['jsonname'];
        return $jsonname;
      }
      ,$scenarios
    );

    include 'scenariolist.php';
  }
  return $handled;
}

/**
 * Handle '/pages/<page name>/tests/<scenario>' request
 * @param array $matches should contain 'pagename' and 'scenario'
 * @return true if scenario exists, false otherwise
 */
function HandleScenarioViewData($matches) {
  $pagename = $matches['pagename'];
  $scenario = urldecode("tests/$pagename/$matches[scenario].json");
  $handled = file_exists($scenario);

  if ($handled) {
    include 'viewdata.php';
  }
  return $handled;
}

/**
 * Handle '/pages/<page name>/tests/<scenario>/execute' request
 * @param array $matches should contain 'pagename' and 'scenario'
 * @return true if scenario exists, false otherwise
 */
function HandleScenarioExecute($matches) {
  $pagename = $matches['pagename'];
  $scenario = urldecode("tests/$pagename/$matches[scenario].json");
  $handled = file_exists($scenario);

  if ($handled) {
    global $smarty;
    
    include 'execute.php';
  }
  return $handled;
}

function HandleViewMainTestData() {
  header('Content-Type: application/json');
  include MAIN_TEST_DATA_FILE;
}

/**
* Merge scenario data with main test data. Scenario data will override main test data
* @param   string  Variable length  Path to the scenario file (e.g. "tests/account/1-normal.json").
* @return  array                    Main data merged with scenario data and returned as associative array.
*/
function MergeScenarioDataWithMain()
{
  $merged_data = json_decode(file_get_contents(MAIN_TEST_DATA_FILE), true);
  $scenario_paths = func_get_args();
  foreach ($scenario_paths as $path) {
    $scenario = json_decode(file_get_contents($path), true);
    if (JSON_ERROR_NONE != json_last_error()) {
      echo "JSON error on scenario data file. Error message: \"" . json_last_error_msg() . "\"";
      die;
    }
    $merged_data = array_replace($merged_data, $scenario);
  }
  return $merged_data;
}

// main routing body

$routes = array(
  '/pages/tests/main'                                                => 'HandleViewMainTestData',
  '/pages/(?P<pagename>[^/]+)/tests/(?P<scenario>[^/]+)/execute(/)?' => 'HandleScenarioExecute',
  '/pages/(?P<pagename>[^/]+)/tests/(?P<scenario>[^/]+)(/)?'         => 'HandleScenarioViewData',
  '/pages/(?P<pagename>[^/]+)(/)?'                                   => 'HandleScenarioList',
  '/'                                                                => 'HandlePageList',
);

$uri = $_SERVER['REQUEST_URI'];

$handled = false;
foreach ($routes as $route => $handler) {
  if (preg_match('|^'.$route.'$|',$uri,$matches) === 1) {
    $handled = $handler($matches);
    break;
  }
}

if (!$handled) {
  // http_response_code(404);
  echo <<< EOD
    <html>
      <head>
        <title>$uri</title>
      </head>
      <body>
        <h1>Path $uri not found</h1>
        <p>Back to <a href="/">index</a><p>
      </body>
    </html>
EOD;
}
