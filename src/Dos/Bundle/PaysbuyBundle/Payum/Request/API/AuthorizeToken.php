<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Request\API;

use Payum\Core\Request\Generic;
use Payum\Core\Security\TokenInterface;

class AuthorizeToken extends Generic
{
    /**
     * @param TokenInterface $token
     */
    public function setToken(TokenInterface $token)
    {
        $this->token = $token;
    }
}
