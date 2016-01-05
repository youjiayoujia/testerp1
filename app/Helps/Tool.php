<?php
namespace App\Helps;

class Tool
{
	
	/**
	*获取文件名
	*
	* @param $dirPath
	* @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
    public function getDirName($dirPath)
    {
		$dir=opendir($dirPath);
		$dirName=array();
		while (($file = readdir($dir)) !== false)
		  {
		   if($file!='.' && $file!='..'){
			  
			  $dirName[]=$file;
			   }
		  }
		  closedir($dir);
		  return $dirName;	
    }
}