<?php
namespace App\Helps;

use Tool;

class DataList
{
    public function sortsDecode($sortsQuery)
    {
        $sorts = [];
        foreach (explode(',', urldecode($sortsQuery)) as $key => $sort) {
            $sort = explode('.', $sort);
            $sorts[$key]['field'] = $sort[0];
            $sorts[$key]['direction'] = $sort[1];
        }
        return $sorts;
    }

    public function filtersEncode($filtersQuery, $clear = false)
    {
        $filters = [];
        if (count($filtersQuery[0]) > 1) {
            foreach ($filtersQuery as $filter) {
                $filters[$filter[0]]['field'] = $filter[0];
                $filters[$filter[0]]['oprator'] = urlencode($filter[1]);
                $filters[$filter[0]]['value'] = urlencode($filter[2]);
                $filters[$filter[0]] = implode('.', $filters[$filter[0]]);
            }
        } else {
            $filters[$filtersQuery[0]]['field'] = $filtersQuery[0];
            $filters[$filtersQuery[0]]['oprator'] = urlencode($filtersQuery[1]);
            $filters[$filtersQuery[0]]['value'] = urlencode($filtersQuery[2]);
            $filters[$filtersQuery[0]] = implode('.', $filters[$filtersQuery[0]]);
        }
        if (request()->getQueryString()) {
            if (request()->has('filters')) {
                $urlFilters = [];
                foreach (explode(',', request()->input('filters')) as $urlFilter) {
                    $urlFilter = explode('.', $urlFilter);
                    $urlFilters[$urlFilter[0]]['field'] = $urlFilter[0];
                    $urlFilters[$urlFilter[0]]['oprator'] = $urlFilter[1];
                    $urlFilters[$urlFilter[0]]['value'] = $urlFilter[2];
                    $urlFilters[$urlFilter[0]] = implode('.', $urlFilters[$urlFilter[0]]);
                }
                $filters = $clear == true ? $filters : array_merge($urlFilters, $filters);

                $queries = [];
                foreach (explode('&', request()->getQueryString()) as $query) {
                    $query = explode('=', $query);
                    if ($query[0] == 'filters') {
                        $queries[] = 'filters=' . implode(',', $filters);
                    } else {
                        $queries[] = $query[0] == 'filterClear' ? '' : $query[0] . '=' . $query[1];
                    }
                }
                $url = request()->url() . '?' . implode('&', $queries);
            } else {
                $url = request()->fullUrl() . '&filters=' . implode(',', $filters);
            }
        } else {
            $url = request()->fullUrl() . '?filters=' . implode(',', $filters);
        }
        return $clear == true ? $url . '&filterClear=true' : '';
    }

    public function filtersDecode($filtersQuery)
    {
        $filters = [];
        foreach (explode(',', $filtersQuery) as $filter) {
            $filter = explode('.', $filter);
            $filters[$filter[0]]['field'] = $filter[0];
            $filters[$filter[0]]['oprator'] = urldecode($filter[1]);
            $filters[$filter[0]]['value'] = urldecode($filter[2]);
        }
        return $filters;
    }

}