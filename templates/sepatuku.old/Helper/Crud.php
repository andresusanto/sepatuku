<?php
class Helper_Crud
{
    public static $dummyNull = NULL;
    static function &selectFilterField(&$filterFields, $key)
    {
        if (is_array($filterFields)) {
            foreach ($filterFields as $fieldValue => &$field) {
                if (isset($field['sub_fields'])) {
                    $tempField =& self::selectFilterField($field['sub_fields'], $key);
                    if ($tempField) return $tempField;
                } else if ($fieldValue == $key) {
                    return $field;
                }
            }
        }
        return self::$dummyNull;
    }

    static function _buildSingleSearchFilter($filterField, $val)
    {
        $searchFilter = new SearchFilter();
        if (isset($filterField['meta'])) {
            $searchFilter->setField($filterField['meta']);
        } else {
            return NULL;
        }
        $searchFilter->setComparison($filterField['operator']);
        $filterVal = $val;
        if (isset($filterField['format']) && ($filterField['format']=='date' || $filterField['format']=='datetime')) {
            $filterVal = Helper_Date::formatSqlDateTime(strtotime($val));
        }
        $searchFilter->setValue($filterVal);
        return $searchFilter;
    }

    static function splitBetween($val)
    {
        $splitted = explode(',', $val);
        if (!isset($splitted[1])) {
            $splitted = explode(' - ', $val);
        }
        return $splitted;
    }

    static function _buildSearchFiltersOfField($filterField, $val)
    {
        if ($filterField['operator'] == 'between') {
            $field1 = $filterField; $field1['operator'] = SearchFilter::$GREATER_THAN_EQUAL;
            $field2 = $filterField; $field2['operator'] = SearchFilter::$LESS;
            $splitted = self::splitBetween($val);
            if (!isset($splitted[1])) {
                return array();
            }
            $val1 = $splitted[0];
            $val2 = $splitted[1];
            $searchFilters = array();
            if ($val1 !== '') {
                $searchFilters[] = self::_buildSingleSearchFilter($field1, $val1);
            }
            if ($val2 !== '') {
                $searchFilters[] = self::_buildSingleSearchFilter($field2, $val2);
            }
            return $searchFilters;
        } else if ($filterField['operator'] == SearchFilter::$NOT_CONTAIN) {
            $notNullField = $filterField; $notNullField['operator'] = SearchFilter::$IS;
            $notContain = self::_buildSingleSearchFilter($filterField, $val);
            $notNull = self::_buildSingleSearchFilter($notNullField, NULL);
            return array(array($notContain, $notNull));
        } else {
            $searchFilter = self::_buildSingleSearchFilter($filterField, $val);
            return array($searchFilter);
        }
    }

    static function buildSearchFilters($filters, $filterFields)
    {
        $searchFilters = array();
        if (is_array($filterFields)) {
            foreach ($filters as $key => $val) {
                $filterField = self::selectFilterField($filterFields, $key);
                if ($filterField) {
                    $newFilters = self::_buildSearchFiltersOfField($filterField, $val);
                    $searchFilters = array_merge($searchFilters, array_filter($newFilters, function ($x) {return $x;}));
                }
            }
        }
        return $searchFilters;
    }

    static function _isCommaBetween($n, $b)
    {
        $exploded = self::splitBetween($b);
        if (count($exploded) >= 2) {
            $lower = $exploded[0];
            $upper = $exploded[1];
            if ($upper === '') {
                $upper = PHP_INT_MAX;
            }
            if (($n >= $lower) && ($n < $upper)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    static function _normalizeOptions($options)
    {
        $nOptions = array();
        foreach ($options as $option) {
            $opt = $option;
            if (!is_array($option)) {
                $opt = array(
                    'title' => $option,
                    'value' => $option,
                );
            }
            $nOptions[] = $opt;
        }
        return $nOptions;
    }

    static function _getTempKeys($options, $key, $operator)
    {
        $keys = array();
        if ($operator == SearchFilter::$EQUAL) {
            $theKey = $key;
            $keys = array($theKey);
        }
        $options = self::_normalizeOptions($options);
        if ($operator == SearchFilter::$CONTAIN) {
            foreach ($options as $option) {
                if (strpos($key, $option['value']) !== FALSE) {
                    $keys[] = $option['value'];
                }
            }
        }
        if ($operator == SearchFilter::$BEGIN_WITH) {
            foreach ($options as $option) {
                if (strpos($key, $option['value']) === 0) {
                    $keys[] = $option['value'];
                }
            }
        }
        if ($operator == 'between') {
            foreach ($options as $option) {
                if (self::_isCommaBetween($key, $option['value'])) {
                    $keys[] = $option['value'];
                }
            }
        }
        return $keys;
    }

    static function _filterOptions($options, $values, $operator, $withCount=TRUE)
    {
        $temp = array();
        foreach ($values as $key => $val) {
            $tempKeys = self::_getTempKeys($options, $key, $operator);
            foreach ($tempKeys as $tempKey) {
                if (!isset($temp[$tempKey])) {
                    $temp[$tempKey] = 0;
                }
                $temp[$tempKey] += $val;
            }
        }
        $newOptions = array();
        foreach ($options as $option) {
            if (isset($temp[$option['value']]) && $temp[$option['value']]) {
                $count = $temp[$option['value']];
                if ($withCount) {
                    $option['title'] = $option['title'] . " ($count)";
                }
                $newOptions[] = $option;
            }
        }
        return $newOptions;
    }

    static function getFieldOptionsFromFieldValues($field, $values)
    {
        $rawOptions = array_keys($values);
        $options = $rawOptions;
        if (!empty($field['is_comma_separated'])) {
            $fieldOptions = array();
            foreach ($rawOptions as $opt) {
                $splits = Helper_String::commaStrToArr($opt);
                foreach ($splits as $split) {
                    $fieldOptions[$split] = TRUE;
                }
            }
            ksort($fieldOptions);
            $options = array_keys($fieldOptions);
        }
        return $options;
    }

    static function getFilterFields($controller, $filterFields, $params, $fieldValues=NULL)
    {
        $options = self::getFilterOptions($params);
        if (isset($options['filters'])) {
            foreach ($options['filters'] as $key => $val) {
                $filterField =& self::selectFilterField($filterFields, $key);
                if ($filterField) {
                    $filterField['selected'] = $val;
                }
            }
        }
        if ($filterFields) {
            foreach ($filterFields as $key => &$field) {
                $fieldOptions = NULL;
                $curFieldValues = Helper_Structure::getArrayValue($fieldValues, $key);
                if (isset($field['options'])) {
                    $fieldOptions = $field['options'];
                    if ($field['options'] === 'content') {
                        $field['options'] = array();
                        $fieldOptions = array();
                        if (is_array($curFieldValues)) {
                            $fieldOptions = self::getFieldOptionsFromFieldValues($field, $curFieldValues);
                        }
                    }
                } else if (isset($field['options_source'])) {
                    $fieldOptions = Helper_Crud::getOptionsFromSource($controller, $field['options_source']);
                    $field['options'] = $fieldOptions;
                }
                if (isset($field['options']) && is_array($field['options'])) {
                    $field['options'] = self::_normalizeOptions($field['options']);
                }
                if (isset($fieldOptions) && isset($curFieldValues) && (($withFilterOptions = Helper_Structure::getArrayValue($field, 'with_filter_options')) !== FALSE)) {
                    $fieldOptions = self::_normalizeOptions($fieldOptions);
                    $withCount = ($withFilterOptions !== 'nocount');
                    $field['options'] = self::_filterOptions($fieldOptions, $curFieldValues, $field['operator'], $withCount);
                }
            }
        }
        return $filterFields;
    }

    static function getSearchFilters($params, $filterFields)
    {
        $options = Helper_Crud::getFilterOptions($params);
        $filters = $options['filters'];
        $searchFilters = Helper_Crud::buildSearchFilters($filters, $filterFields);
        return $searchFilters;
    }

    static function getFilterOptions($params)
    {
        $options = array();
        $paramArr = new PhpwebArray($params);
        $options['filters'] = array();
        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (isset($val) && ($val !== '') && (strpos($key, 'filter_') === 0)) {
                    $filterKey = substr($key, 7, strlen($key)-1);
                    $options['filters'][$filterKey] = $val;
                }
            }
        }
        return $options;
    }

    static function _setObjProp($obj, $val, $lang, $method)
    {
        $method_name = '';
        $newVal = $val;
        if (is_string($method)) {
            $method_name = 'set' . $method;
            if (strpos($method, 'Array') === 0 && is_string($val)) {
                $newVal = Helper_String::commaStrToArr($val);
            }
        } else if (is_array($method)) {
            if (!empty($method['attr'])) {
                $obj->setAttr($method['attr'], $val);
                return;
            } else if (!empty($method['extra_attribute'])) {
                $extra = $obj->getExtraAttribute();
                if ($extra) {
                    $setMethod = 'set' . $method['extra_attribute'];
                    $extra->$setMethod($val);
                }
                return;
            } else {
                $method_name = 'set' . $method['method'];
                $newVal = $method['filter']($val);
            }
        } else if (is_callable($method)) {
            $method($obj, $val, $lang);
            return;
        }
        $obj->$method_name($newVal, $lang);
    }

    static function setObjPropertiesByMap($map, $lang, $obj, $post)
    {
        $valArr = new PhpwebArray($post);
        foreach ($map as $key => $desc) {
            $val = $valArr->get($key);
            if (isset($val)) {
                self::_setObjProp($obj, $val, $lang, $desc);
            }
        }
    }

    static function extractArrByMap($map, $lang, $obj)
    {
        $arr = array();
        foreach ($map as $key => $val) {
            $getMethod = 'get' . $val;
            $arr[$key] = $obj->$getMethod($lang);
        }
        return $arr;
    }

    static function getSalGenderOptions()
    {
        return array(
            array(
                'title' => 'Male',
                'value' => 'Mr',
            ),
            array(
                'title' => 'Female',
                'value' => 'Ms',
            ),
        );
    }

    static function getObjFormFiltered($man, $mode, $objType)
    {
        $form = $man->getObjForm($mode, $objType);
        if (isset($man->disabledFields)) {
            foreach ($form['fields'] as &$field) {
                if (isset($field['name']) && in_array($field['name'], $man->disabledFields)) {
                    $field = NULL;
                }
            }
        }
        return $form;
    }

    static function getFieldMapFromFormFields($fields)
    {
        $map = array_map(function ($x) {
            return $x['obj_map'];
        }, array_filter($fields, function ($x) {
            return isset($x['obj_map']);
        }));
        return $map;
    }

    static function getOptionsRaw($controller, $optionsSource)
    {
        $optionsRaw = array();
        if (isset($optionsSource['function'])) {
            $function = $optionsSource['function'];
            $optionsRaw = $function($controller);
        } else if (isset($optionsSource['product_option'])) {
            $optionsRaw = $controller->getConfigProductOption($optionsSource['product_option']);
        } else if (isset($optionsSource['depconfig_key'])) {
            $optionsRaw = $controller->getOptionsFromDepConfig($optionsSource['depconfig_key']);
        } else if (isset($optionsSource['db'])) {
            $db = $optionsSource['db'];
            $method = $optionsSource['method'];
            $attrs = array();
            if (isset($optionsSource['method_attrs'])) {
                $attrs = $optionsSource['method_attrs'];
            }
            $optionsRaw = $controller->getObjects($db, $method, $attrs);
        } else if (isset($optionsSource['configKey'])) {
            $getMethod = $optionsSource['method'];
            $optionsRaw = $controller->$getMethod($optionsSource['configKey']);
        } else if (isset($optionsSource['krco_config_keys']) && isset($controller->krco_config)) {
            $configArr = new PhpwebArray($controller->krco_config);
            $optionsRaw = $configArr->get($optionsSource['krco_config_keys']);
            if (!isset($optionsRaw)) {
                $optionsRaw = array();
            }
        } else if (isset($optionsSource['class']) && isset($optionsSource['static_attribute'])) {
            $class = $optionsSource['class'];
            $attr = $optionsSource['static_attribute'];
            $optionsRaw = $class::$$attr;
        }
        return $optionsRaw;
    }

    static function getMapperFunction($optionsSource)
    {
        if (isset($optionsSource['mapper'])) {
            $mapper = $optionsSource['mapper'];
            if (is_callable($mapper)) {
                return $mapper;
            }
            if ($mapper == 'text') {
                return function ($x) {
                    return array(
                        'title' => $x,
                        'value' => $x,
                    );
                };
            }
            if (is_array($mapper)) {
                return function ($obj) use ($mapper) {
                    $arr = array();
                    foreach ($mapper as $key => $fieldName) {
                        $getMethod = 'get' . $fieldName;
                        $val = $obj->$getMethod();
                        $arr[$key] = $val;
                    }
                    return $arr;
                };
            }
        }
        return NULL;
    }

    static function getOptionsFromSource($controller, $optionsSource)
    {
        $optionsRaw = Helper_Crud::getOptionsRaw($controller, $optionsSource);
        $options = $optionsRaw;
        $mapper = Helper_Crud::getMapperFunction($optionsSource);
        if ($mapper) {
            $options = array_map($mapper, $optionsRaw);
        }
        $filteredOptions = array();
        foreach ($options as $opt) {
            if (isset($opt)) {
                $filteredOptions[] = $opt;
            }
        }
        if (!empty($optionsSource['add_options'])) {
            $filteredOptions = array_merge($filteredOptions, $optionsSource['add_options']);
        }
        if (!empty($optionsSource['add_options_source'])) {
            $filteredOptions = Helper_Crud::combineOptions($filteredOptions, self::getOptionsFromSource($controller, $optionsSource['add_options_source']));
        }
        return $filteredOptions;
    }

    static function combineOptions($options1, $options2)
    {
        $combined = $options2;
        if (isset($options1)) {
            $oldOptions = $options1;
            foreach ($options2 as $newOpt) {
                $isExist = FALSE;
                foreach ($options1 as $opt) {
                    if (isset($opt['value']) && isset($newOpt['value']) && ($opt['value'] == $newOpt['value'])) {
                        $isExist = TRUE;
                    }
                }
                if (!$isExist) {
                    $oldOptions[] = $newOpt;
                }
            }
            $combined = $oldOptions;
        }
        return $combined;
    }
}
