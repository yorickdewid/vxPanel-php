<?php

global $zdbh;
if (isset($_GET['module'])) {
    $CleanModuleName = fs_protector::SanitiseFolderName($_GET['module']);
    $sql = "SELECT mo_folder_path FROM x_modules WHERE mo_folder_vc = :moNameVc";
    $numrows = $zdbh->prepare($sql);
	$numrows->execute(array(':moNameVc' => $CleanModuleName));
    $result = $numrows->fetchColumn();

    if($result != null)
    {
        $CleanModuleName = $result . '/' . $CleanModuleName;
    }
    $ControlerPath = 'modules/' . $CleanModuleName . '/code/controller.ext.php';
    if (file_exists($ControlerPath)) {
        require_once $ControlerPath;
    }


    $ModulePath = 'modules/' . $CleanModuleName . '/code/' . $class_name . '.class.php';
    if (file_exists($ModulePath)) {
        require_once $ModulePath;
    }
}

