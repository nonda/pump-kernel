<?php
namespace Nonda\Util;

class BlackList extends ListUtil
{
    public function check($list, $key)
    {
        if (!$this->keyExists($list, $key)) {
            return false;
        }

        $val = $this->getKey($list, $key);

        return (boolean)$val;
    }

    public function block($list, $key)
    {
        return $this->setKey($list, $key, true);
    }

    public function unblock($list, $key)
    {
        return $this->delKey($list, $key);
    }

    public function clear($list)
    {
        return $this->delList($list);
    }
}
