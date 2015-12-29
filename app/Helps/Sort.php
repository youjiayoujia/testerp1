<?php
namespace App\Helps;

class Sort
{
    public function url($field)
    {
        if (request()->getQueryString()) {
            if (request()->has('sorts')) {
                $sorts = [];
                foreach (explode(',', request()->input('sorts')) as $sort) {
                    $sort = explode('.', $sort);
                    if ($sort[0] == $field) {
                        $sort[1] = $sort[1] == 'asc' ? 'desc' : 'asc';
                    }
                    $sorts[$sort[0]] = $sort[0] . '.' . $sort[1];
                }
                if (!isset($sorts[$field])) {
                    $sorts[$field] = $field . '.' . 'desc';
                }

                $queries = [];
                foreach (explode('&', request()->getQueryString()) as $query) {
                    $query = explode('=', $query);
                    if ($query[0] == 'sorts') {
                        $queries[] = 'sorts=' . implode(',', $sorts);
                    } else {
                        $queries[] = $query[0] . '=' . $query[1];
                    }
                }
                $url = request()->url() . '?' . implode('&', $queries);
            } else {
                $url = request()->fullUrl() . '&sorts=' . $field . '.desc';
            }
        } else {
            $url = request()->fullUrl() . '?sorts=' . $field . '.desc';
        }

        return $url;
    }

    public function label($field)
    {
        $label = '';
        if (request()->has('sorts')) {
            foreach (explode(',', request()->input('sorts')) as $sort) {
                $sort = explode('.', $sort);
                if ($sort[0] == $field) {
                    $label = $sort[1] == 'asc' ? '<span class="sign arrow up"></span>' : '<span class="sign arrow"></span>';
                }
            }
        }

        return $label;
    }
	
	/**
	*获取文件名
	*
	* @param $dir_path
	* @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	*/
	
	public function get_dirname($dir_path){
		$dir=opendir($dir_path);
		$dir_name=array();
		while (($file = readdir($dir)) !== false)
		  {
		   if($file!='.' && $file!='..'){
			  
			  $dir_name[]=$file;
			   }
		  }
		  closedir($dir);
		  return $dir_name;
		} 
	
}