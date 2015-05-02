<?php

class pathLoader {
	
	public static function getPath($name){   
        global $zdbh;
        $sql = "SELECT mo_folder_path FROM x_modules WHERE mo_folder_vc = :moNameVc";
        $numrows = $zdbh->prepare($sql);
        $numrows->execute(array(':moNameVc' => $name));
        $result = $numrows->fetchColumn();
        return $result;
    }
    /**
     * [createPath description]
     * @param  string $moduleName  only the name, used to retrieve optional pathdir of module
     * @param  string $partOfPath  The other part to create the full path
     * @return string full path to a file
     */
    public static function createPath($moduleName,$partOfPath = null){
        $path = self::getPath($moduleName);
        if($path != null)
        {
            $path = "modules/" . $path . '/'. $moduleName;
        }
        else{
            $path = "modules/" . $moduleName;
        }
        if($partOfPath != null)
        {
            $path = $path . $partOfPath;
        }
        else {
            $path = $path . '/';
        }
        return $path;
    }
}

?>