<?php
namespace Nonda\Util;

/**
 * Class MileageRateData
 * @package Nonda\Util
 *
 * @author Rivsen
 */
class MileageRateData
{
    /**
     * 各个国家地区的货币、距离单位、报销规则数据
     *
     * @var array
     * @see https://en.wikipedia.org/wiki/ISO_3166-1
     */
    static protected $data = [
        'AU' => [
            'name' => 'Australia',
            'code' => 'AU',
            'currency' => 'AUD',
            'currency_symbol' => 'AUD',
            //'currency_symbol' => '$',
            'unit' => 'km',
            'rules_source' => [
                //'https://www.ato.gov.au/Business/Income-and-deductions-for-business/Deductions/Motor-vehicle-expenses/Claiming-motor-vehicle-expenses-as-a-sole-trader/Cents-per-kilometre-method/', //过期
                'https://www.ato.gov.au/individuals/income-and-deductions/deductions-you-can-claim/vehicle-and-travel-expenses/car-expenses/#centsperkm',
                // 6月中更新
            ],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'date_range' => [
                        '2015-01-01,2018-12-31' => [
                            'deduction' => 0.66,
                            'limits' => [
                                'long_range' => [
                                    '0,5000' => 0.66,
                                    '5000,' => 0.0,
                                ],
                            ],
                            'start_date' => '2015-01-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.68,
                            'limits' => [
                                'long_range' => [
                                    '0,5000' => 0.68,
                                    '5000,' => 0.0,
                                ],
                            ],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
            ],
        ],
        'CA' => [
            'name' => 'Canada',
            'code' => 'CA',
            'currency' => 'CAD',
            'currency_symbol' => 'CAD',
            //'currency_symbol' => '$',
            'unit' => 'km',
            'rules_source' => [
                //'http://www.cra-arc.gc.ca/tx/bsnss/tpcs/pyrll/bnfts/tmbl/llwnc/rts-eng.html',
                'https://www.canada.ca/en/revenue-agency/services/tax/businesses/topics/payroll/benefits-allowances/automobile/automobile-motor-vehicle-allowances/automobile-allowance-rates.html',
                // 1月初更新
            ],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'date_range' => [
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.58,
                            'limits' => [
                                'long_range' => [
                                    '0,5000' => 0.58,
                                    '5000,' => 0.52,
                                ],
                            ],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                        '2018-01-01,2018-12-31' => [
                            'deduction' => 0.55,
                            'limits' => [
                                'long_range' => [
                                    '0,5000' => 0.55,
                                    '5000,' => 0.49,
                                ],
                            ],
                            'start_date' => '2018-01-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                        '2016-01-01,2017-12-31' => [
                            'deduction' => 0.54,
                            'limits' => [
                                'long_range' => [
                                    '0,5000' => 0.54,
                                    '5000,' => 0.48,
                                    //'5001,' => 0.54,
                                ],
                            ],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2017-12-31 23:59:59',
                        ],
                    ],
                ],
            ],
        ],
        'NZ' => [
            'name' => 'New Zealand',
            'code' => 'NZ',
            'currency' => 'NZD',
            'currency_symbol' => 'NZD',
            //'currency_symbol' => '$',
            'unit' => 'km',
            'rules_source' => [
                //'http://www.ird.govt.nz/business-income-tax/expenses/mileage-rates/emp-deductions-allowances-mileage.html',
                'https://www.ird.govt.nz/topics/income-tax/day-to-day-expenses/claiming-vehicle-expenses/kilometre-rates-for-business-use-of-vehicles-2018-2019-income-year',
                // 5月底更新
            ],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'date_range' => [
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.79,
                            'limits' => [
                                'long_range' => [
                                    '0,14000' => 0.79,
                                    '14000,' => 0.3,
                                ],
                            ],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                        '2016-04-01,2018-12-31' => [
                            'deduction' => 0.73,
                            'limits' => [],
                            'start_date' => '2016-04-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                    ],
                ],
            ],
        ],
        'UK' => [
            'name' => 'UK',
            'code' => 'UK',
            'currency' => 'GBP',
            'currency_symbol' => 'GBP',
            //'currency_symbol' => '£',
            'unit' => 'mile',
            'rules_source' => [
                'https://www.gov.uk/expenses-and-benefits-business-travel-mileage/rules-for-tax', // 很久没变过了
            ],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'date_range' => [
                        '2016-01-01,2020-12-31' => [
                            'deduction' => 0.45,
                            'limits' => [
                                'long_range' => [
                                    '0,10000' => 0.45,
                                    '10000,' => 0.25,
                                    //'10001,' => 0.45,
                                ],
                            ],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
            ],
        ],
        'US' => [
            'name' => 'USA',
            'code' => 'US',
            'currency' => 'USD',
            'currency_symbol' => 'USD',
            //'currency_symbol' => '$',
            'unit' => 'mile',
            'rules_source' => [
                'https://www.irs.gov/tax-professionals/standard-mileage-rates', // 每年12月初发布下一年的
                // 2018: https://www.irs.gov/newsroom/standard-mileage-rates-for-2018-up-from-rates-for-2017
                // 2019 https://www.irs.gov/newsroom/irs-issues-standard-mileage-rates-for-2019
            ],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'date_range' => [
                        '2016-01-01,2016-12-31' => [
                            'deduction' => 0.54,
                            'limits' => [],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2016-12-31 23:59:59',
                        ],
                        '2017-01-01,2017-12-31' => [
                            'deduction' => 0.535,
                            'limits' => [],
                            'start_date' => '2017-01-01 00:00:00',
                            'end_date' => '2017-12-31 23:59:59',
                        ],
                        '2018-01-01,2018-12-31' => [
                            'deduction' => 0.545,
                            'limits' => [],
                            'start_date' => '2018-01-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.58,
                            'limits' => [],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
                'Charity' => [
                    'name' => 'Charity',
                    'date_range' => [
                        '2016-01-01,2020-12-31' => [
                            'deduction' => 0.14,
                            'limits' => [],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
                'Medical' => [
                    'name' => 'Medical',
                    'date_range' => [
                        '2016-01-01,2016-12-31' => [
                            'deduction' => 0.19,
                            'limits' => [],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2016-12-31 23:59:59',
                        ],
                        '2017-01-01,2017-12-31' => [
                            'deduction' => 0.17,
                            'limits' => [],
                            'start_date' => '2017-01-01 00:00:00',
                            'end_date' => '2017-12-31 23:59:59',
                        ],
                        '2018-01-01,2018-12-31' => [
                            'deduction' => 0.18,
                            'limits' => [],
                            'start_date' => '2018-01-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.2,
                            'limits' => [],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
                'Moving' => [
                    'name' => 'Moving',
                    'date_range' => [
                        '2016-01-01,2016-12-31' => [
                            'deduction' => 0.19,
                            'limits' => [],
                            'start_date' => '2016-01-01 00:00:00',
                            'end_date' => '2016-12-31 23:59:59',
                        ],
                        '2017-01-01,2017-12-31' => [
                            'deduction' => 0.17,
                            'limits' => [],
                            'start_date' => '2017-01-01 00:00:00',
                            'end_date' => '2017-12-31 23:59:59',
                        ],
                        '2018-01-01,2018-12-31' => [
                            'deduction' => 0.18,
                            'limits' => [],
                            'start_date' => '2018-01-01 00:00:00',
                            'end_date' => '2018-12-31 23:59:59',
                        ],
                        '2019-01-01,2020-12-31' => [
                            'deduction' => 0.2,
                            'limits' => [],
                            'start_date' => '2019-01-01 00:00:00',
                            'end_date' => '2020-12-31 23:59:59',
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * 空的一条数据，帮助填充数据结构
     *
     * @var array
     */
    static protected $empty = [
        'currency' => 'USD',
        'currency_symbol' => 'USD',
        'CODE' => '',
        'name' => '',
        'unit' => 'mile',
        'rules' => [],
    ];

    /**
     * 格式化trip的类型标记
     *
     * @param string      $type
     * @param string|null $purpose
     *
     * @return array
     */
    public static function typeMark($type, $purpose = null)
    {
        $type = Str::ucfirst(Str::strtolower($type));
        $purpose = Str::ucfirst(Str::strtolower(rtrim($purpose ?: '', '($)')));
        $markType = $type;

        if ('Business' !== $type) {
            if (!$purpose || 'Unclassified' === $purpose) {
                $markType = $type;
            } else {
                $markType = $purpose;
            }
        }

        return [
            'type_mark' => $markType,
            'type' => $type,
            'purpose' => $purpose ?: null,
        ];
    }

    /**
     * 计算一个 Trip 数据的价值
     *
     * @param string      $country       两位字母的国家代码
     * @param string      $type          Trip 类型，Business or Personal
     * @param int         $distance      行驶距离，单位: 厘米
     * @param null|string $purpose       Trip 的目的
     * @param int         $totalDistance 当年的总里程
     * @param array|null  $customRule    自定义规则
     * @param string      $date          Trip 的结束时间
     *
     * @return array
     */
    public static function calc($country, $type, $distance, $purpose = null, $totalDistance = 0, $date = null, $customRule = null)
    {
        $typeMark = self::typeMark($type, $purpose);
        $type = $typeMark['type'];

        $result = [
            'type' => $typeMark['type_mark'],
            'value' => 0,
        ];

        if (!$country) {
            // Default country is US
            $country = 'US';
        }

        if ('CUSTOM' !== $country && !isset(self::$data[$country])) {
            return $result;
        }

        $result['value'] = self::calcOneRule($country, $type, $distance, $totalDistance, $date, $customRule);

        return $result;
    }

    /**
     * 计算一段距离的所有可能价值
     *
     * @param string      $country
     * @param int         $distance         单位厘米
     * @param int[]       $totalDistance    单位厘米
     * @param null|string $date
     * @param null|array  $customRule
     *
     * @return array
     */
    public static function calcPotentialRate($country, $distance, $totalDistance = [], $date = null, $customRule = null)
    {
        $result = [];
        $customRule = $customRule ? $customRule : [];
        $totalDistance = $totalDistance ?: [];

        if (!$country) {
            // Default country is US
            $country = 'US';
        }

        if ('CUSTOM' != $country AND !isset(self::$data[$country])) {
            return $result;
        }

        if ('CUSTOM' == $country) {
            $rules = $customRule;
        } else {
            $rules = self::$data[$country]['rules'];
        }

        foreach ($rules as $ruleName => $rule) {
            if ('unit' == $ruleName) {
                continue;
            }

            $ruleTotalDistance = 0;

            if (array_key_exists($ruleName, $totalDistance)) {
                $ruleTotalDistance = $totalDistance[$ruleName];
            }

            $result[$ruleName] = [
                'type' => $ruleName,
                'value' => self::calcOneRule($country, $ruleName, $distance, $ruleTotalDistance, $date, $customRule),
            ];
        }

        return $result;
    }

    public static function formatCustomRule($customRule)
    {
        return [
            'name' => 'Custom',
            'code' => 'CUSTOM',
            'currency' => 'CUSTOM',
            'currency_symbol' => 'USD',
            'unit' => isset($customRule['unit']) ? $customRule['unit'] : 'km',
            'rules_source' => [],
            'rules' => [
                'Business' => [
                    'name' => 'Business',
                    'deduction' => isset($customRule['business']) ? $customRule['business'] : '0',
                    'limits' => [],
                    'start_date' => '',
                    'end_date' => '',
                ],
                'Personal' => [
                    'name' => 'Personal',
                    'deduction' => isset($customRule['personal']) ? $customRule['personal'] : 0,
                    'limits' => [],
                    'start_date' => '',
                    'end_date' => '',
                ],
            ],
        ];
    }

    /**
     * @param string      $country
     * @param string      $ruleName
     * @param float       $distance      Must be cm
     * @param float       $totalDistance Must be cm
     * @param array       $customRule
     * @param null|string $date
     *
     * @return int
     */
    public static function calcOneRule($country, $ruleName, $distance, $totalDistance, $date = null, $customRule = null)
    {
        $result = 0;
        $customRule = $customRule ? $customRule : [];
        $distance = $distance/100000; // cm to km
        $totalDistance = $totalDistance/100000;

        if (!$date) {
            $date = date('Y-m-d');
        }

        if ('CUSTOM' == $country) {
            if ('unit' == $ruleName OR 'Unclassified' == $ruleName) {
                return $result;
            }

            if ('Business' != $ruleName AND !isset($customRule['personal'])) {
                return $result;
            }

            if ('Business' != $ruleName AND !isset($customRule[Str::strtolower($ruleName)])) {
                $rule = $customRule['personal'];
                $ruleName = 'Personal';
            } else {
                if (!isset($customRule[Str::strtolower($ruleName)])) {
                    return $result;
                }

                $rule = $customRule[Str::strtolower($ruleName)];
            }

            if ('mile' == $customRule['unit']) {
                $distance = self::kmToMile($distance);
            }

            $result = round($distance * $rule * 100);

            return $result;
        }

        if (!isset(self::$data[$country]['rules'][$ruleName])) {
            return $result;
        }

        $rule = self::getRuleDataByDate(self::$data[$country]['rules'][$ruleName], $date);

        if (!$rule) {
            return $result;
        }

        if ('mile' == self::$data[$country]['unit']) {
            $distance = self::kmToMile($distance);
            $totalDistance = self::kmToMile($totalDistance);
        }

        if (!isset($rule['limits']) OR count($rule['limits']) == 0) {
            $result = round($distance * $rule['deduction'] * 100);

            return $result;
        }

        foreach ($rule['limits'] as $limit => $rate) {
            switch ($limit) {
                /**
                 * Limit rules will explode and calc at here
                 *
                 * If need add more rules, add another case block
                 */
                case 'long_range':
                    if ( 0 == $totalDistance) {
                        $result = round($distance * $rule['deduction'] * 100);
                        break 2;
                    }

                    foreach ($rate as $limitRule => $limitRate) {
                        $limitRule = explode(',', $limitRule);

                        $limitMin = (int)$limitRule[0];
                        $limitMax = (int)$limitRule[1];

                        if ($totalDistance > $limitMin) {
                            if (!$limitMax OR $totalDistance <= $limitMax) {
                                $result = round($distance * (float)$limitRate * 100);

                                break 3; // Break the limits foreach
                            }
                        }
                    }
                    break;

                default:
                    $result = round($distance * $rule['deduction'] * 100);
            }
        }

        return $result;
    }

    /**
     * 根据参数获取相应的数据
     *
     * 1. 获取所有国家设定数据
     * 2. 获取单个国家设定数据
     * 3. 获取单个国家的单个规则的数据
     *
     * *Notice* 只传入规则名称不传国家名称，会自动忽略规则名称
     *
     * @param null|string $country   国家名称
     * @param null|string $rule      规则名称
     *
     * @return array
     *
     * examples:
     *
     * MileageRateData::get()                   获取所有国家或地区的数据
     * MileageRateData::get('US')               获取单个国家或地区的数据
     * MileageRateData::get('US', 'Business')   获取单个国家或地区的某个规则的数据
     */
    public static function getAll($country = null, $rule = null)
    {
        if (!$country) {
            return self::$data;
        }

        if (!isset(self::$data[$country])) {
            return self::$empty;
        }

        if (!$rule) {
            return self::$data[$country];
        }

        if (!isset(self::$data[$country]['rules'][$rule])) {
            return [];
        }

        return self::$data[$country]['rules'][$rule];
    }

    /**
     * 获取国家或地区的设定数据，默认当年数据
     *
     * 1. 获取特定日期的所有国家地区设定数据
     * 2. 获取特定日期的单个国家地区设定数据
     * 3. 获取特定日期的单个国家地区的单个规则数据
     *
     * *Notice* 只传入规则名称不传国家名称，会自动忽略规则名称
     *
     * 若 $assoc 为 true ，返回国家和规则关联数组，国家和规则名称作为key
     * 若为 false ，则返回国家和规则的纯数组
     *
     * @param null|string $date     日期字符串，例如：2017-01-01, 2017-01-01 12:25:34
     * @param null|string $country  国家名称
     * @param null|string $rule     规则名称    　
     * @param bool        $assoc    是否返回关联数组
     *
     * @return array|null
     */
    public static function get($date = null, $country = null, $rule = null, $assoc = true)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $data = self::getAll($country, $rule);

        if (count($data) == 0) {
            return $data;
        }

        if ($country) {
            if ($rule) {
                return self::getRuleDataByDate($data, $date);
            } else {
                foreach ($data['rules'] as $iRule => $ruleVal) {
                    $data['rules'][$iRule] = self::getRuleDataByDate($ruleVal, $date);

                    if (!$data['rules'][$iRule]) {
                        unset($data['rules'][$iRule]);
                    }
                }

                if (!$assoc) {
                    $data['rules'] = array_values($data['rules']);
                }

                return $data;
            }
        } else {
            foreach ($data as $c => &$countryVal) {
                foreach ($countryVal['rules'] as $iRule => $ruleVal) {
                    $countryVal['rules'][$iRule] = self::getRuleDataByDate($ruleVal, $date);

                    if (!$countryVal['rules'][$iRule]) {
                        unset($countryVal['rules'][$iRule]);
                    }
                }

                if (!$assoc) {
                    $countryVal['rules'] = array_values($countryVal['rules']);
                }
            }

            if (!$assoc) {
                $data = array_values($data);
            }

            return $data;
        }

        // Can't be here
    }

    /**
     * 从一个规则数据中获取指定日期的数据，如果未找到对应日期的数据，返回null
     *
     * @param array         $rule 规则数据数组，key是日期分段
     * @param null|string   $date 获取指定日期的数据
     *
     * @return null|array
     */
    public static function getRuleDataByDate($rule, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $date = new \DateTime($date);
        $dateRule = null;

        if (!isset($rule['date_range'])) {
            return $dateRule;
        }

        foreach ($rule['date_range'] as $range) {
            $start = new \DateTime($range['start_date']);

            if ($start > $date) {
                continue;
            }

            if (!$range['end_date']) {
                $range['end_date'] = date('Y-12-31 23:59:59');
            }

            $end = new \DateTime($range['end_date']);

            if ($date > $end) {
                continue;
            }

            $dateRule = $range;
            $dateRule['name'] = $rule['name'];
            break;
        }

        return $dateRule;
    }

    /**
     * Convert KM to Mile
     *
     * @param float $km
     * @param int   $precision
     *
     * @return float
     */
    public static function kmToMile($km, $precision = 2) {
        return round($km/1.609344, $precision);
    }

    /**
     * Convert Mile to KM
     *
     * @param float $mile
     * @param int   $precision
     *
     * @return float
     */
    public static function mileToKm($mile, $precision = 2) {
        return round($mile * 1.609344, $precision);
    }

    /**
     * @param $countryCode
     *
     * @return bool
     */
    public static function isSupportCountry($countryCode)
    {
        return isset(self::$data[$countryCode]);
    }
}
