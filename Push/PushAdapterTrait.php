<?php
namespace Nonda\Push;

trait PushAdapterTrait
{
    protected $supportApps = [];

    public function setSupportApps($apps)
    {
        $this->supportApps = $apps;
    }

    public function getSupportApps()
    {
        return $this->supportApps;
    }
}