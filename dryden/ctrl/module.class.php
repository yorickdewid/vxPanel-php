<?php

/**
 * Default module methods, module_controller classes should extend this class.
 * @package zpanelx
 * @subpackage dryden -> controller
 * @version 1.0.0
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright ZPanel Project (http://www.zpanelcp.com/)
 * @link http://www.zpanelcp.com/
 * @license GPL (http://www.gnu.org/licenses/gpl.html)
 */
require_once(__DIR__.'/../sys/pathLoader.php');

class ctrl_module
{

    /**
     * Returns the name of the module.
     * @return string
     */
    public static function getModuleName()
    {
        $module_name = ui_module::GetModuleName();
        return $module_name;
    }

    /**
     * Returns the modules description, this is pretty standard and by default is taken from the module description in the module table but
     * is designed to be overwritten in the module_controller class if a different alternative is required.
     * @return type
     */
    public static function getModuleDesc()
    {
        $module_desc = ui_language::translate(ui_module::GetModuleDescription());
        return $module_desc;
    }

    /**
     * Provides module icon functionality.
     * @global type $controller
     * @return type
     */
    public static function getModuleIcon()
    {
        global $controller;
        $mod_dir= $controller->GetControllerRequest('URL', 'module');
        $path = pathLoader::getPath($mod_dir);
        if($path != null)
        {
            $mod_dir = $path.'/'.$mod_dir;
        }
        // Check if the current userland theme has a module icon override
        if (file_exists('etc/styles/' . ui_template::GetUserTemplate() . '/images/' . $mod_dir . '/assets/icon.png'))
            return './etc/styles/' . ui_template::GetUserTemplate() . '/images/' . $mod_dir . '/assets/icon.png';
        return './modules/' . $mod_dir . '/assets/icon.png';
    }

    /**
     * Provides a simple method to access the current path to the module.
     * @global type $controller
     * @return string Directory path to the module root.
     */
    static function getModulePath()
    {
        global $controller;
        return pathLoader::createPath($controller->GetControllerRequest('URL', 'module'));
    }

    /**
     * Returns the CSFR tag of which the module should use when attempting to post FORM data.
     * @return string
     */
    public static function getCSFR_Tag()
    {
        return runtime_csfr::Token();
    }

}
