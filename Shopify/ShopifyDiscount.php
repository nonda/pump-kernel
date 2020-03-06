<?php
/**
 * Created by PhpStorm.
 * User: kicoe
 * Date: 2018-12-29
 * Time: 13:34
 */

namespace Nonda\Shopify;

class ShopifyDiscount
{

    protected $client;
    protected $priceRuleId;
    protected $discountCodeId;

    public function __construct(ShopifyClient $client, $priceRuleId = 0, $discountCodeId = 0)
    {
        $this->client = $client;
        $this->priceRuleId = $priceRuleId;
        $this->discountCodeId = $discountCodeId;
    }

    public function setPriceRuleId($priceRuleId)
    {
        $this->priceRuleId = $priceRuleId;
    }

    public function getPriceRuleId()
    {
        return $this->priceRuleId;
    }

    public function setDiscountCodeId($discountCodeId)
    {
        $this->discountCodeId = $discountCodeId;
    }

    public function getDiscountCodeId()
    {
        return $this->discountCodeId;
    }

    public function get($endpoint)
    {
        $result = $this->client->get($endpoint);

        if ($result['code'] === 200) {
            return $result['data'];
        }
        return $result;
    }

    public function post($endpoint, $args)
    {
        $result = $this->client->post($endpoint, $args);

        if ($result['code'] === 201) {
            return $result['data'];
        }
        return $result;
    }

    public function delete($endpoint)
    {
        $result = $this->client->delete($endpoint);

        return $result['code'] === 204;
    }

    public function put($endpoint, $args)
    {
        $result = $this->client->put($endpoint, $args);

        if ($result['code'] === 200) {
            return $result['data'];
        }
        return $result;
    }

    public function getPriceRuleList()
    {
        return $this->client->getIter(['price_rules'], function ($data) {
            $responseBody = json_decode($data, true);

            if (empty($responseBody['price_rules'])) {
                return [];
            }

            return $responseBody['price_rules'];
        });
    }

    /**
     * 获取折扣
     * @return array
     */
    public function getPriceRuleInfo()
    {
       return $this->get(['price_rules', $this->priceRuleId]);
    }

    /**
     * 获取折扣码信息
     * @return array
     */
    public function getDiscountCodeInfo()
    {
        return $this->get(
            [
                'price_rules',
                $this->priceRuleId,
                'discount_codes',
                $this->discountCodeId
            ]
        );
    }

    /**
     * 新增折扣码
     *
     * @param $code string|array
     * @return array
     */
    public function addDiscountCode($code)
    {
        $body = ['discount_code' => ['code' => $code]];

        return $this->post(
            [
                'price_rules',
                $this->priceRuleId,
                'discount_codes'
            ],
            $body
        );
    }


    /**
     * 新增折扣
     *
     * @param       $title
     * @param       $target_type
     * @param       $target_selection
     * @param       $allocation_method
     * @param       $value_type
     * @param       $value
     * @param       $customer_selection
     * @param       $starts_at
     * @param       $ends_at
     * @param array $addition
     *
     * @return array
     */
    public function addPriceRule(
        $title,
        $target_type,
        $target_selection,
        $allocation_method,
        $value_type,
        $value,
        $customer_selection,
        $starts_at,
        $ends_at,
        $addition = []
    )
    {
        $body = [
            'price_rule' => [
                'title' => $title,
                'target_type' => $target_type,
                'target_selection' => $target_selection,
                'allocation_method' => $allocation_method,
                'value_type' => $value_type,
                'value' => $value,
                'customer_selection' => $customer_selection,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
            ]
        ];

        if ($addition) {
            $body['price_rule'] = array_merge($body['price_rule'], $addition);
        }

        return $this->post('price_rules.json', $body);
    }

    /**
     * 删除折扣规则
     *
     * @param null $priceRuleId
     *
     * @return bool
     */
    public function delPriceRule($priceRuleId = null)
    {
        if ($priceRuleId === null) {
            $priceRuleId = $this->priceRuleId;
        }

        return $this->delete([
            'price_rules',
            $priceRuleId
        ]);
    }

    /**
     * 删除折扣
     * @param null $priceRuleId
     * @param null $discountCodeId
     *
     * @return bool
     */
    public function delDiscountCode($priceRuleId = null, $discountCodeId = null)
    {
        if ($priceRuleId === null) {
            $priceRuleId = $this->priceRuleId;
        }

        if ($discountCodeId === null) {
            $discountCodeId = $this->discountCodeId;
        }

        return $this->delete([
            'price_rules',
            $priceRuleId,
            'discount_codes',
            $discountCodeId
        ]);
    }
    
}