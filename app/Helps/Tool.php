<?php
namespace App\Helps;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Tool
{
    public function dir($directory)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }
    }

    public function getFileExtension($fileName)
    {
        return pathinfo($fileName, PATHINFO_EXTENSION);
    }

    /**
     * 创建sku对应属性的笛卡尔积
     * 2015-12-18 10:43:48 YJ
     * @param array $data
     * @return array
     */
    public function createDikaer($data)
    {
        $cnt = count($data);
        $result = array();
        foreach ($data[0] as $item) {
            $result[] = array($item);
        }
        for ($i = 1; $i < $cnt; $i++) {
            $result = $this->combineArray($result, $data[$i]);
        }
        return $result;

    }

    /**
     * 2个数组对笛卡尔积的处理
     * 2015-12-18 10:43:48 YJ
     * @param array $arr1 ,$arr2
     * @return array
     */
    function combineArray($arr1, $arr2)
    {
        $result = array();
        foreach ($arr1 as $item1) {
            foreach ($arr2 as $item2) {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;
    }

    /**
     * 随机创建sku
     * 2015-12-18 10:43:21 YJ
     * @return str
     */
    public function createSku()
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < 5; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;

    }

    public function isSelected($field, $value, $model = null)
    {
        if (old($field) == $value) {
            return 'selected';
        } elseif ($model) {
            if ($model->$field == $value) {
                return 'selected';
            }
        }
        return false;
    }

    public function isChecked($field, $value, $model = null, $default = false)
    {
        if (old($field) == $value) {
            return 'checked';
        } elseif ($model) {
            if ($model->$field == $value) {
                return 'checked';
            }
        } elseif ($default) {
            return 'checked';
        }
        return false;
    }

    public function show($value)
    {
        echo "<pre>";
        var_dump($value);
        exit;
    }
}