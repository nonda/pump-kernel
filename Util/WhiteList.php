<?php
namespace Nonda\Util;

class WhiteList extends ListUtil
{
    public function check($list, $key)
    {
        if (!$this->keyExists($list, $key)) {
            return false;
        }

        $val = $this->getKey($list, $key);

        return (boolean)$val;
    }

    public function permit($list, $key)
    {
        return $this->setKey($list, $key, true);
    }

    public function forbid($list, $key)
    {
        return $this->delKey($list, $key);
    }

    public function clear($list)
    {
        return $this->delList($list);
    }
}
