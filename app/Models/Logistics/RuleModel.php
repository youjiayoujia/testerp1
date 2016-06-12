<?php
/**
 * 物流分配规则模型
 *
 * Created by PhpStorm.
 * User: bianhaiwei
 * Date: 16/4/14
 * Time: 下午2:52
 */

namespace App\Models\Logistics;

use App\Base\BaseModel;
use App\Models\CountriesModel;
use App\Models\ChannelModel;
use App\Models\CatalogModel;
use App\Models\Logistics\Rule\CatalogModel as RuleCatalogModel;
use App\Models\Logistics\Rule\ChannelModel as RuleChannelModel;
use App\Models\Logistics\Rule\CountryModel as RuleCountryModel;
use App\Models\Logistics\Rule\LimitModel as RuleLimitModel;
use App\Models\Logistics\LimitsModel;

class RuleModel extends BaseModel
{
    protected $table = 'logistics_rules';

    public $searchFields = ['country', 'weight_from', 'weight_to', 'order_amount', 'is_clearance', 'priority', 'type_id'];

    protected $fillable = [
        'name',
        'weight_from',
        'weight_to',
        'order_amount_from',
        'order_amount_to',
        'is_clearance',
        'priority',
        'type_id',
    ];

    public $rules = [
        'create' => [
            'countrys' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount_from' => 'required',
            'order_amount_to' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
            'channels' => 'required', 
            'catalogs' => 'required',
        ],
        'update' => [
            'countrys' => 'required',
            'weight_from' => 'required',
            'weight_to' => 'required',
            'order_amount_from' => 'required',
            'order_amount_to' => 'required',
            'is_clearance' => 'required',
            'priority' => 'required',
            'type_id' => 'required',
            'channels' => 'required', 
            'catalogs' => 'required',
        ],
    ];

    public function logistics()
    {
        return $this->belongsTo('App\Models\LogisticsModel', 'type_id', 'id');
    }

    public function rule_catalogs()
    {
        return $this->hasMany('App\Models\Logistics\Rule\CatalogModel', 'logistics_rule_id', 'id');
    }

    public function rule_catalogs_through()
    {
        return $this->belongsToMany('App\Models\CatalogModel', 'logistics_rule_catalogs', 'logistics_rule_id', 'catalog_id');
    }

    public function rule_channels()
    {
        return $this->hasMany('App\Models\Logistics\Rule\ChannelModel', 'logistics_rule_id', 'id');
    }

    public function rule_channels_through()
    {
        return $this->belongsToMany('App\Models\ChannelModel', 'logistics_rule_channels', 'logistics_rule_id', 'channel_id');
    }

    public function rule_countries()
    {
        return $this->hasMany('App\Models\Logistics\Rule\CountryModel', 'logistics_rule_id', 'id');
    }

    public function rule_countries_through()
    {
        return $this->belongsToMany('App\Models\CountriesModel', 'logistics_rule_countries', 'logistics_rule_id', 'country_id');
    }

    public function rule_limits()
    {
        return $this->hasMany('App\Models\Logistics\Rule\LimitModel', 'logistics_rule_id', 'id');
    }

    public function rule_limits_through()
    {
        return $this->belongsToMany('App\Models\Logistics\LimitsModel', 'logistics_rule_limits', 'logistics_rule_id', 'logistics_limit_id')->withPivot('type');
    }

    public function getCountrysNameAttribute()
    {
        $countrys = $this->countrys;
        $arr = explode(',', $countrys);
        $str = '';
        foreach($arr as $key => $value) {
            $country = CountriesModel::find($value);
            if($key == 0) {
                $str = $country->cn_name;
                continue;
            }
            $str .=','.$country->cn_name;
        }

        return $str;
    }

    public function getChannelsNameAttribute()
    {
        $channels = $this->channels;
        $arr = explode(',', $channels);
        $str = '';
        foreach($arr as $key => $value) {
            $channel = ChannelModel::find($value);
            if($key == 0) {
                $str = $channel->name;
                continue;
            }
            $str .=','.$channel->name;
        }

        return $str;
    }

    public function getCatalogsNameAttribute()
    {
        $catalogs = $this->catalogs;
        $arr = explode(',', $catalogs);
        $str = '';
        foreach($arr as $key => $value) {
            $catalog = CatalogModel::find($value);
            if($key == 0) {
                $str = $catalog->name;
                continue;
            }
            $str .=','.$catalog->name;
        }

        return $str;
    }

    public function createAll($arr)
    {
        if(array_key_exists('catalogs', $arr)) {
            foreach($arr['catalogs'] as $catalog) {
                $this->rule_catalogs()->create(['catalog_id' => $catalog]);
            }
        }
        if(array_key_exists('channels', $arr)) {
            foreach($arr['channels'] as $channel) {
                $this->rule_channels()->create(['channel_id' => $channel]);
            }
        }
        if(array_key_exists('countrys', $arr)) {
            foreach($arr['countrys'] as $country) {
                $this->rule_countries()->create(['country_id' => $country]);
            }
        }
        if(array_key_exists('limits', $arr)) {
            foreach($arr['limits'] as $key => $limit) {
                $this->rule_limits()->create(['logistics_limit_id' => $key, 'type' => $limit]);
            }
        }
    }

    public function innerType($type1, $id, $type = NULL)
    {
        switch($type1) {
            case 'catalog':
                $catalogs = $this->rule_catalogs_through;
                foreach($catalogs as $catalog) {
                    if($catalog->pivot->catalog_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'channel':
                $channels = $this->rule_channels_through;
                foreach($channels as $channel) {
                    if($channel->pivot->channel_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'country':
                $countries = $this->rule_countries_through;
                foreach($countries as $country) {
                    if($country->pivot->country_id == $id) {
                        return true;
                    }
                }
                return false;
                break;

            case 'limit':
                $limits = $this->rule_limits_through;
                foreach($limits as $limit) {
                    if($limit->pivot->logistics_limit_id == $id && $limit->pivot->type == $type) {
                        return true;
                    }
                }
                return false;
                break;
        }
    }

    public function updateAll($arr)
    {
        $this->update($arr);
        if(array_key_exists('catalogs', $arr)) {
            $catalogs = $this->rule_catalogs;
            foreach($catalogs as $catalog) {
                $catalog->forceDelete();
            }
            foreach($arr['catalogs'] as $catalog) {
                $this->rule_catalogs()->create(['catalog_id' => $catalog]);
            }
        }
        if(array_key_exists('channels', $arr)) {
            $channels = $this->rule_channels;
            foreach($channels as $channel) {
                $channel->forceDelete();
            }
            foreach($arr['channels'] as $channel) {
                $this->rule_channels()->create(['channel_id' => $channel]);
            }
        }
        if(array_key_exists('countrys', $arr)) {
            $countrys = $this->rule_countries;
            foreach($countrys as $country) {
                $country->forceDelete();
            }
            foreach($arr['countrys'] as $country) {
                $this->rule_countries()->create(['country_id' => $country]);
            }
        }
        if(array_key_exists('limits', $arr)) {
            $limits = $this->rule_limits;
            foreach($limits as $limit) {
                $limit->forceDelete();
            }
            foreach($arr['limits'] as $key => $limit) {
                $this->rule_limits()->create(['logistics_limit_id' => $key, 'type' => $limit]);
            }
        }
    }
}