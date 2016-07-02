<?php
/**
 * 信息模版类型模型
 *
 * 2016-01-14
 * @author Vincent<nyewon@gmail.com>
 */
namespace App\Models\Message\Template;

use App\Base\BaseModel;

class TypeModel extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'message_template_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'name'];

    public $searchFields = ['name'];

    protected $rules = [
        'create' => ['name' => 'required|unique:message_template_types,name'],
        'update' => ['name' => 'required|unique:message_template_types,name,{id}']
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Message\Template\TypeModel', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Message\Template\TypeModel', 'parent_id');
    }

    public function templates()
    {
        return $this->hasMany('App\Models\Message\TemplateModel', 'type_id');
    }

}
