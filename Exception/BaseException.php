<?php
namespace Nonda\Exception;

use \Exception as RootException;
use Swagger\Annotations as SWG;
use Throwable;

/**
 * Class BaseException
 * @package Nonda\Exception
 *
 * @author Rivsen
 *
 * @SWG\Definition(
 *     definition="ApiException/ErrorCode",
 *     type="integer",
 *     enum={
 *         400, 401, 403, 404,
 *         600, 601, 602, 603, 604, 605, 606, 607, 608, 609,
 *         610, 611, 612, 613, 614, 615, 616, 617, 618, 619,
 *         620, 621, 622, 623, 624, 625, 626, 627, 628, 629,
 *         630, 631, 632, 633, 634, 635, 636, 637, 638, 639,
 *         640, 641, 642, 643, 644, 645, 646, 647, 648, 649,
 *         650, 651, 652, 653, 654, 655, 656, 657,
 *         661, 662, 663, 664, 665, 666,
 *         670, 671, 672, 673, 674, 675, 676, 677, 678,
 *         700, 701, 702, 703, 704, 705, 706, 707, 708,
 *         731, 732, 733,
 *         740, 741, 742,
 *         800, 801, 802, 803, 804, 805, 806, 807,
 *     }, description="
* 400 - bad request
* 401 - 需要登录
* 403 - no privilege to access
* 404 - resource not found
* 600 - invalid argument
* 601 - create failed
* 602 - no share relation found
* 603 - share relation exist
* 604 - not owner
* 605 - device bound
* 606 - device bound by other user
* 607 - reach device bind limit
* 608 - update failed
* 609 - Trip 缺少结束时间
* 610 - 用户登录失败，installation 验证失败，需要提示用户清除app缓存再登录
* 611 - 用户登录失败，session 验证失败，需要重新登录
* 612 - 没有权限操作设备
* 613 - 没有权限操作车辆
* 614 - 没找到设备
* 615 - 没有找到车辆
* 616 - 没有找到免打扰区域
* 617 - 没权限操作免打扰区域
* 618 - 没有找到 parking
* 619 - 没有权限操作 parking
* 620 - 删除失败
* 621 - 没有找到分享关系
* 622 - 没有权限操作分享关系
* 623 - Trip 没有找到
* 624 - 没有权限操作 Trip
* 625 - mileage已关闭
* 626 - 缺少邮箱
* 627 - 用户已存在
* 628 - 删除车辆的所有Trip失败
* 629 - 不能删除最后一辆车
* 630 - 没有权限修改配置项
* 631 - 没有mileage权限
* 632 - 上传文件必须有name和base64编码的文件data
* 633 - 上传文件失败
* 634 - trip条数达到了60条
* 635 - Mileage没有关闭
* 636 - 兼容老数据时，没有找到一个有效的设备绑定
* 637 - 车辆没有绑定设备
* 638 - 用户A向用户B请求分享一辆车达到了次数
* 639 - 邮箱记录不存在
* 640 - 用户名和密码必填
* 641 - 用户名或密码错误
* 642 - parse signup failed
* 643 - 响应请求分享请求缺失参数
* 644 - 免打扰区域已关闭
* 645 - processing generate trip cache
* 646 - parse login failed
* 647 - 用户已验证过
* 648 - 下载文件MD5值校验失败
* 649 - 下载文件失败
* 650 - validate receipt failed
* 651 - receipt is null
* 652 - receipt is illegal
* 653 - receipt save failed
* 654 - 属于mac地址错误的obd
* 655 - 该用户已经冻结了一个mac，需要完成流程才能继续申请
* 656 - obd_mac_pool池子空了
* 657 - 非法烧录mac
* 661 - local_id 重复
* 662 - request_id 重复
* 663 - 已提交用户信息等待校验
* 664 - 已存在通过验证的信息，不需要重复提交
* 665 - 车辆最后一次保养日期不正确
* 666 - 没有可领取的token
* 667 - 密码太短
* 670 - 用户未开启挖矿
* 671 - 绑定的钱包未找到
* 672 - 钱包已绑定
* 673 - 超过钱包余额
* 674 - 超过每天提币次数
* 675 - 提币请求无法取消
* 676 - 没有关联的经销商
* 677 - 推送(ios)fwrite()失败
* 678 - 绑定经销商验证错误
* 700 - coupon code is not exist
* 701 - couponcode is illegal
* 702 - couponcode consume failed
* 703 - couponcode verify failed
* 704 - ios exist subscription
* 705 - android exist subscription
* 706 - coupon expired
* 707 - coupon has already been used
* 708 - produce coupon failed
* 731 - facebook validate error
* 732 - user email already exists
* 733 - app banner need version & platform
* 740 - voltage data is null
* 741 - voltage data upload failed
* 742 - can not share to yourself
* 800 - system error
* 801 - system error
* 802 - system error
* 803 - system error
* 804 - system error
* 805 - system error
* 806 - system error
* 807 - system error
", default=400
 * )
 */
class BaseException extends RootException
{
    /**
     * STANDARD HTTP CODE
     */
    const HTTP_BAD_REQUEST = 400;

    const HTTP_NO_PRIVILEGE = 403;

    const HTTP_NOT_FOUND = 404;

    /**
     * LOGIC exception code
     */
    // 参数错误
    const INVALID_ARGUMENT = 600;

    // 创建失败
    const CREATE_FAILED = 601;

    // 未找到分享记录
    const NO_SHARE_RELATION = 602;

    // 已存在分享记录
    const EXISTS_SHARE_RELATION = 603;

    // 不是owner
    const NOT_OWNER = 604;

    // 设备已绑定
    const DEVICE_BOUND = 605;

    // 设备已被其他用户绑定
    const DEVICE_BOUND_BY_OTHER_USER = 606;

    // 设备绑定数量已达到上限
    const REACHED_DEVICE_BIND_LIMIT = 607;

    // 更新失败
    const UPDATE_FAILED = 608;

    // Trip缺少结束时间
    const TRIP_MISSING_END_TIME = 609;

    // 用户名密码登录失败，installation验证失败
    const LOGIN_FAILED_INSTALLATION_INVALID = 610;

    // 用户的parse session验证失败
    const LOGIN_FAILED_PARSE_SESSION_INVALID = 611;

    // 没有权限操作设备
    const NO_DEVICE_PRIVILEGE = 612;

    // 没有权限操作车辆
    const NO_VEHICLE_PRIVILEGE = 613;

    // 没有找到设备
    const DEVICE_NOT_FOUND = 614;

    // 没有找到车辆
    const VEHICLE_NOT_FOUND = 615;

    // 没有找到免打扰区域
    const DISTURB_FREE_ZONE_NOT_FOUND = 616;

    // 没有权限操作免打扰区域
    const NO_DISTURB_FREE_ZONE_PRIVILEGE = 617;

    // 没有找到parking
    const PARKING_NOT_FOUND = 618;

    // 没有权限操作parking
    const NO_PARKING_PRIVILEGE = 619;

    // 删除失败
    const DELETE_FAILED = 620;

    // 没有找到分享关系
    const SHARING_NOT_FOUND = 621;

    // 没有权限操作分享关系
    const NO_SHARING_PRIVILEGE = 622;

    // Trip 没有找到
    const TRIP_NOT_FOUND = 623;

    // 没有权限操作 Trip
    const NO_TRIP_PRIVILEGE = 624;

    // mileage 已关闭
    const MILEAGE_DISABLED = 625;

    // 缺少邮箱
    const EMAIL_REQUIRED = 626;

    // 用户邮箱已存在
    const EMAIL_ACCOUNT_EXISTS = 627;

    // 删除车辆的所有Trip失败
    const DELETE_VEHICLE_ALL_TRIP_FAILED = 628;

    //不能删除最后一辆车
    const DELETE_LAST_VEHICLE_NOT_ALLOWED = 629;

    // 没有权限修改车辆配置项
    const NO_UPDATE_VEHICLE_CONFIG_PRIVILEGE = 630;

    // 没有mileage权限
    const NO_MILEAGE_PRIVILEGE = 631;

    // 上传文件必须有name和base64编码的文件data
    const UPLOAD_FILE_NEED_NAME_AND_DATA = 632;

    // 上传文件失败
    const UPLOAD_FILE_FAILED = 633;

    // 达到trip 60条限制
    const REACHED_TRIP_LIMIT = 634;

    // mileage 未关闭
    const MILEAGE_NOT_DISABLED = 635;

    // 兼容老数据时，没有找到一个有效的设备绑定
    const NO_VALID_ZUS_FOUND = 636;

    // No binding device
    const NO_BINDING_DEVICE = 637;

    // 达到请求分享限制
    const REACHED_REQUEST_SHARE_LIMIT = 638;

    // 邮箱记录不存在
    const EMAIL_NOT_EXIST = 639;

    // 用户名和密码必填
    const USERNAME_AND_PASSWORD_REQUIRED = 640;

    // 用户名或密码错误
    const USERNAME_OR_PASSWORD_INVALID = 641;

    // parse 注册失败
    const PARSE_SIGN_UP_FAILED = 642;

    // 响应请求分享请求缺失参数
    const RESPONSE_SHARE_REQUEST_OPERATION_INVALID = 643;

    // 免打扰区域已关闭
    const DISTURB_FREE_ZONE_DISABLED = 644;

    // 正在生成trip缓存
    const PROCESSING_GENERATE_TRIP_CACHE = 645;

    // 调用parse登录时抛异常，登录失败
    const PARSE_LOGIN_FAILED = 646;

    // 用户已验证过
    const USER_VERIFIED = 647;

    //下载文件失败
    const DOWNLOAD_FILE_FAILED = 649;

    //下载文件MD5值校验失败
    const DOWNLOAD_FILE_WITH_WRONG_MD5 = 648;

    /**
     * receipt exception code
     */
    // receipt信息验证失败
    const RECEIPT_VALIDATE_FAILED = 650;

    // receipt信息为空
    const RECEIPT_IS_NULL = 651;

    // receipt信息不合法
    const RECEIPT_IS_ILLEGAL = 652;

    // receipt信息保存失败
    const RECEIPT_SAVE_FAILED = 653;

    //属于mac地址错误的obd
    const WRONG_MAC_OBD = 654;

    //该用户已经冻结了一个mac，需要完成流程才能继续申请
    const HAVE_FREEZE_OBD_MAC = 655;

    //obd_mac_pool池子空了
    const OBD_MAC_POOL_IS_EMPTY = 656;

    //非法烧录mac
    const BURNED_OBD_MAC_ILLEGAL = 657;

    //local_id重复
    const LOCAL_ID_EXIST = 661;

    //request_id重复
    const REQUEST_ID_EXIST = 662;

    // 已提交用户信息等待校验
    const USER_INFO_VERIFY_WAITING = 663;

    // 已存在通过验证的信息，不需要重复提交
    const USER_INFO_VERIFIED = 664;

    // 车辆最后一次保养日期不正确
    const INVALID_VEHICLE_LAST_MAINTENANCE = 665;

    // 没有token可以领取
    const CAR_BLOCK_NO_TOKEN_CLAIM = 666;

    // 密码太短
    const USER_PASSWORD_TOO_SHORT = 667;

    // 用户邮箱未验证
    const USER_EMAIL_NOT_VERIFIED = 668;

    // 挖矿用户帐号未验证
    const MINING_USER_NOT_VERIFIED = 669;

    // 用户未开启挖矿
    const USER_MINING_NOT_ENABLED = 670;

    // 绑定的钱包未找到
    const CHAIN_WALLET_NOT_FOUND = 671;

    // 钱包已绑定
    const CHAIN_WALLET_ALREADY_BOUND = 672;

    // 超过钱包余额
    const WALLET_OUT_OF_BALANCE = 673;

    // 超过每天提币次数
    const OUT_OF_WITHDRAWAL_DAY_LIMIT = 674;

    // 提币请求无法取消
    const WRONG_WITHDRAWAL_STATUS = 675;

    // 没有关联的经销商
    const NO_DEALERSHIP_RELATED = 676;

    // 推送fwrite()返回broken错误
    const PUSH_WRITE_BROKEN = 677;

    // 绑定经销商设备code验证错误
    const DEALERSHIP_VERIFY_FAILED = 678;

    /**
     * couponcode exception code
     */
    // couponcode 不存在
    const COUPON_IS_NOT_EXIST = 700;

    // couponcode不合法
    const COUPON_IS_ILLEGAL = 701;

    // couponcode 兑换失败
    const COUPON_CONSUME_FAILED = 702;

    // couponcode 验证失败
    const COUPON_VERIFY_FAILED = 703;

    // ios平台下已经存在订阅，不能使用coupon
    const IOS_EXIST_SUBSCRIPTION = 704;

    // android平台下已经存在订阅，不能使用coupon
    const ANDROID_EXIST_SUBSCRIPTION = 705;

    // coupon code 过期
    const COUPON_EXPIRED = 706;

    // coupon code 已兑换过
    const COUPON_USED = 707;

    const PRODUCE_COUPON_FAILED = 708;

    /**
     * user exception code
     */
    // Facebook验证失败
    const FACEBOOK_INVALID = 731;
    
    // email已经存在
    const EMAIL_EXIST = 732;

    // app banner 需要
    const APP_BANNER_NEED_PLATFORM_VERSION = 733;

    /**
     * voltage data exception code
     */
    // voltage data is null
    const VOLTAGE_DATA_IS_NULL = 740;

    // voltage data uplaod failed
    const VOLTAGE_DATA_UPLOAD_FAILED = 741;

    // Can not share to yourself
    const CAN_NOT_SHARE_TO_YOURSELF = 742;

    /**
     * tire exception code
     */
    const TIRE_DATA_UPLOAD_EMPTY = 743;

    /**
     * shopify exception code
     */
    const SHOPIFY_REQUEST_DISCOUNT_CODE_FAILED = 750;
    const SHOPIFY_REQUEST_PRICE_RULE_FAILED = 751;

    /**
     * vip exception code
     */
    // 续费vip超过一个月过期时间
    const VIP_EXPIRES_OUT_A_MONTH = 760;

    /**
     * Nonda's kernel exception code
     */
    // Registered service must callable
    const EVENT_NO_CALLABLE_METHOD = 800;

    // Subscriber must define static method: getSubscribedEvents()
    const EVENT_SUBSCRIBER_NO_STATIC_METHOD = 801;

    // Subscriber's getSubScribedEvents() must return a valid array
    const EVENT_SUBSCRIBER_STATIC_METHOD_RETURN_INVALID = 802;

    // Unknown service tag type
    const EVENT_UNKNOWN_TAG_TYPE = 803;

    // Event stopped by listener
    const EVENT_STOPPED_BY_LISTENER = 804;

    // No AsyncEvent
    const NO_ASYNC_EVENT_OBJECT = 805;

    // Async event need Nonda\Laravel\Event\Dispatcher dispatche
    const ASYNC_EVENT_NEED_ASYNC_DISPATCHER = 806;

    const UNKNOWN_PARSE_EVENT = 807;

    // 请求时间不正确，客户端时间不准确
    const INCORRECT_REQUEST_TIME = 808;

    // 废弃的接口
    const OBSOLETE_API = 809;

    // 非法的延迟事件对象
    const INVALID_RUN_DELAYED_JOB_EVENT = 810;

    // 需要登录
    const NEED_LOGIN = 401;

    // 忽略上报sentry的错误码
    public static $ignoreErrorCode = [
        // 客户端时间不正确
        self::INCORRECT_REQUEST_TIME,
        // 需要登录
        self::NEED_LOGIN,
        //Forbidden
        self::HTTP_NO_PRIVILEGE,
        //Receipt not found!
        self::HTTP_NOT_FOUND,
        //lockout in UserController
        423,
        //参数错误
        self::INVALID_ARGUMENT,
        //存在分享关系
        self::EXISTS_SHARE_RELATION,
        // 不是owner
        self::NOT_OWNER,
        // 设备已经绑定
        self::DEVICE_BOUND,
        // 设备已经被其他用户绑定
        self::DEVICE_BOUND_BY_OTHER_USER,
        //同类设备到达上限
        self::REACHED_DEVICE_BIND_LIMIT,
        // session token验证不通过
        self::LOGIN_FAILED_PARSE_SESSION_INVALID,
        // 没有权限操作设备
        self::NO_DEVICE_PRIVILEGE,
        // 没有权限操作车辆
        self::NO_VEHICLE_PRIVILEGE,
        // 没有找到设备
        self::DEVICE_NOT_FOUND,
        // 没有找到车辆
        self::VEHICLE_NOT_FOUND,
        // 没有找到freezone
        self::DISTURB_FREE_ZONE_NOT_FOUND,
        // parking没有找到
        self::PARKING_NOT_FOUND,
        // 没有找到sharing
        self::SHARING_NOT_FOUND,
        // 没有找到trip
        self::TRIP_NOT_FOUND,
        // mileage未开启
        self::MILEAGE_DISABLED,
        // 缺少邮箱
        self::EMAIL_REQUIRED,
        // 邮箱用户已经存在
        self::EMAIL_ACCOUNT_EXISTS,
        // 不能删除最后一辆车
        self::DELETE_LAST_VEHICLE_NOT_ALLOWED,
        // 上传文件必须有name和base64编码的文件data
        self::UPLOAD_FILE_NEED_NAME_AND_DATA,
        // trip达到限制
        self::REACHED_TRIP_LIMIT,
        // mileage 未关闭
        self::MILEAGE_NOT_DISABLED,
        // 没有找到一个有效的设备绑定
        self::NO_VALID_ZUS_FOUND,
        // 没有绑定设备
        self::NO_BINDING_DEVICE,
        // share达到限制
        self::REACHED_REQUEST_SHARE_LIMIT,
        // email不存在
        self::EMAIL_NOT_EXIST,
        // 需要填写用用户名密码
        self::USERNAME_AND_PASSWORD_REQUIRED,
        // 用户名或密码错误
        self::USERNAME_OR_PASSWORD_INVALID,
        // 免打扰区域没有开启
        self::DISTURB_FREE_ZONE_DISABLED,
        // 正在计算trip cache
        self::PROCESSING_GENERATE_TRIP_CACHE,
        //下载文件失败
        self::DOWNLOAD_FILE_FAILED,
        //下载文件MD5校验失败
        self::DOWNLOAD_FILE_WITH_WRONG_MD5,
        //属于mac地址错误的obd
        self::WRONG_MAC_OBD,
        //该用户已经冻结了一个mac，需要完成流程才能继续申请
        self::HAVE_FREEZE_OBD_MAC,
        // local id 验证不通过
        self::LOCAL_ID_EXIST,
        // request id 验证不通过
        self::REQUEST_ID_EXIST,
        // coupon 不存在
        self::COUPON_IS_NOT_EXIST,
        // coupon 不合法
        self::COUPON_IS_ILLEGAL,
        // 存在ios下的订阅
        self::IOS_EXIST_SUBSCRIPTION,
        // 存在android的订阅
        self::ANDROID_EXIST_SUBSCRIPTION,
        // coupon已经过期
        self::COUPON_EXPIRED,
        // coupon已经兑换过
        self::COUPON_USED,
        // 邮箱已经存在
        self::EMAIL_EXIST,
        // app banner 需要
        self::APP_BANNER_NEED_PLATFORM_VERSION,
        // voltage data is null
        self::VOLTAGE_DATA_IS_NULL,
        // 不能分享给自己
        self::CAN_NOT_SHARE_TO_YOURSELF,
        self::TIRE_DATA_UPLOAD_EMPTY,
        self::USER_INFO_VERIFIED,
        self::USER_INFO_VERIFY_WAITING,
        self::INVALID_VEHICLE_LAST_MAINTENANCE,
        self::WRONG_WITHDRAWAL_STATUS,
    ];

    protected $data;

    public function __construct($message = "", $code = 0, Throwable $previous = null, $data = null)
    {
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        $string = '';

        if ($this->data) {
            $string .= '[code: '.$this->getCode().', data: '.json_encode($this->data).']';
        }

        $string .= parent::__toString();

        return $string;
    }
}
