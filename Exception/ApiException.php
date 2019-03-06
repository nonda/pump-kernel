<?php
namespace Nonda\Exception;

use Exception;
use Swagger\Annotations as SWG;

/**
 * Class ApiException
 * @package Nonda\Exception
 *
 * @author Rivsen
 *
 * @SWG\Definition(
 *     definition="ApiException/ExceptionData",
 *     required={"error_code"},
 *     @SWG\Property(property="error_code", type="integer", ref="#/definitions/ApiException/ErrorCode"),
 *     @SWG\Property(property="error_msg", type="string", description="exception message"),
 *     @SWG\Property(property="extra_data", type="object", description="exception message", ref="#/definitions/ApiException/ExceptionExtraData"),
 * )
 *
 * @SWG\Definition(
 *     definition="ApiException/BindDeviceExceptionData",
 *     @SWG\Property(property="error_code", type="integer"),
 *     @SWG\Property(property="error_msg", type="string"),
 *     @SWG\Property(property="extra_data", type="object", ref="#/definitions/ApiException/BindDeviceExtraData"),
 * )
 *
 * @SWG\Definition(
 *     definition="ApiException/BindDeviceExtraData",
 *     @SWG\Property(property="email", type="string", description="device owner's email"),
 *     @SWG\Property(property="vehicle_id", type="string", description="which one vehicle bind device"),
 *     @SWG\Property(property="device_id", type="string", description="exists device record id"),
 *     @SWG\Property(property="owner_id", type="string", description="device owner's id"),
 *     @SWG\Property(property="owner_email", type="string", description="device owner's email"),
 *     @SWG\Property(property="request_user_id", type="string", description="request share user's id"),
 *     @SWG\Property(property="request_user_email", type="string", description="request share user's email"),
 * )
 */
class ApiException extends BaseException
{
    /**
     * @SWG\Definition(
     *     definition="ApiException/ExceptionExtraData",
     *     @SWG\Property(property="email", type="string"),
     * )
     */
    protected $data;

    public function __construct($message = "", $code = 0, $data = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous, $data);
    }
}
