<?php

namespace Nonda\Firebase;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Firebase
{
    private $auth;

    private $dbUri;

    /**
     * @var array
     */
    private $supportApps;

    /**
     * @var \Kreait\Firebase
     */
    private $firebase;

    public function __construct($authFile, $dbUri, array $supportApps)
    {
        $this->auth = $authFile;
        $this->dbUri = $dbUri;
        $this->supportApps = $supportApps;
    }

    /**
     * @return \Kreait\Firebase
     */
    private function getFirebase()
    {
        if (!$this->firebase) {
            $this->firebase = (new Factory())
                ->withServiceAccount(ServiceAccount::fromJsonFile($this->auth))
                ->withDatabaseUri($this->dbUri)
                ->create()
            ;
        }

        return $this->firebase;
    }

    /**
     * @return \Kreait\Firebase\Messaging
     */
    public function messaging()
    {
        return $this->getFirebase()->getMessaging();
    }

    /**
     * @param string $appIdentifier
     *
     * @return bool
     */
    public function supportApp($appIdentifier)
    {
        return in_array($appIdentifier, $this->supportApps);
    }

    public function supportApps()
    {
        return $this->supportApps;
    }
}
