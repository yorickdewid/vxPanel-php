<?php

/**
 * The ZPanelX loader and default handler file.
 * @package zpanelx
 * @subpackage core
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright ZPanel Project (http://www.zpanelcp.com/)
 * @link http://www.zpanelcp.com/
 * @license GPL (http://www.gnu.org/licenses/gpl.html)
 */
require __DIR__ . '/vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
session_start();
require_once 'dryden/loader.inc.php';
require_once 'cnf/db.php';
require_once 'inc/dbc.inc.php';
require_once 'dryden/moduleLoader.php';
debug_phperrors::SetMode('dev');
debug_phperrors::SetMode(ctrl_options::GetSystemOption('debug_mode'));
require_once 'inc/init.inc.php';
//This is where we check the session for hi-jacking
if(!runtime_sessionsecurity::antiSessionHijacking()){
    exit(header("location: ./?sessionIssue"));
}
?>
