<?php
namespace ZohoCRM\Api;

use ZohoCRM\Auth\AuthInterface;
use ZohoCRM\Exception\ContextNotFoundException;

class Api
{
    /**
     * @var AuthInterface $auth
     */
    protected $auth;

    /**
     * @param AuthInterface $auth
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth    = $auth;
    }
}