<?php
namespace Nonda\Http;

use Nonda\Exception\BaseException;

/**
 * Class ResponseWrapper
 * @package Nonda\Http
 *
 * @author Rivsen
 *
 * 组装响应数据，统一返回格式
 *
 * input data is:
 * ```
 * ["some" => "value"]
 * ```
 * then, output json data is:
 * ```
 * {
 *     "meta": {
 *         "stime":1489111106,
 *         "code":200,
 *         "msg":"OK"
 *     },
 *     "data": {
 *         "some":"value"
 *     }
 * }
 * ```
 */
class ResponseWrapper
{
    /**
     * 组装响应数据，统一数据格式
     *
     * @param mixed  $data
     * @param int    $code
     * @param string $msg
     * @param array  $extraMeta
     *
     * @return array
     */
    public function wrap($data, $code = 200, $msg = 'OK', $extraMeta = [])
    {
        $meta = is_array($extraMeta) ? $extraMeta : ['extra' => $extraMeta];
        $meta['stime'] = round(microtime(true) * 1000);
        $meta['code'] = $code;
        $meta['msg'] = $msg;

        return [
            'meta' => $meta,
            'data' => $data,
        ];
    }

    /**
     * 转换Exception并组装成统一的格式
     *
     * @param \Exception $e
     * @param bool       $withDetail
     * @param int        $code
     * @param string     $msg
     * @param array      $extraMeta
     *
     * @return array
     */
    public function wrapException(\Exception $e, $withDetail = false, $code = 400, $msg = 'BAD REQUEST!', $extraMeta = [])
    {
        return $this->wrap(
            $this->exceptionToArray($e, $withDetail),
            $code,
            $msg,
            $extraMeta
        );
    }

    /**
     * 将Exception转成数组
     *
     * @param \Exception $e
     * @param bool       $withDetail
     *
     * @return array
     */
    public function exceptionToArray(\Exception $e, $withDetail = false)
    {
        $errors = [
            'error_code' => $e->getCode(),
            'error_msg' => $e->getMessage(),
        ];

        if ($e instanceof BaseException && $e->getData()) {
            $errors['extra_data'] = $e->getData();
        }

        if ($withDetail) {
            $errors['file'] = $e->getFile();
            $errors['line'] = $e->getLine();
            $errors['trace'] = $e->getTrace();

            if ($e->getPrevious()) {
                $errors['previous'] = $this->exceptionToArray($e->getPrevious(), $withDetail);
            }
        }

        return $errors;
    }

    /**
     * 解组装数据中的传输的数据，去掉外层包装数据
     *
     * @param $data
     *
     * @return mixed|null
     */
    public function unwrap($data)
    {
        if (!is_array($data) || !isset($data['data'])) {
            return null;
        }

        return $data['data'];
    }
}
