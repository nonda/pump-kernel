<?php
// kaifei@nonda.us

namespace Nonda\Util;

class Token {
    /**
     * @param array $params 纯value, 不能超过20个元素，超了后挂了后果自负。会按照key排序后取array_values
     * @param string $chkSalt 不合法返空字符串；不传随机生成；传的就当check了
     * @return string
     */
    public static function generateEdmSecret(array $params, $chkSalt = '') {
        $cnt = count($params);
        if ($chkSalt && $cnt != strlen($chkSalt)) return '';
        
        $params = array_values($params);
        
        $salt = $str = '';
        $it = 0;
        foreach ($params as $data) {
            $det = $chkSalt ? $chkSalt[$it ++] : dechex(rand(0, 15));
            $salt .= $det;
            
            $str .= $data . $salt;
        }
        return substr(md5($str), $cnt) . $salt;
    }
    
    public static function verifyEdmSecret(array $params, $token) {
        if (!$token) return false;
        
        $cnt = count($params);
        $salt = substr($token, -$cnt);
        return $token == self::generateEdmSecret($params, $salt);
    }
}