<?php

namespace Vcare\Bundle\WebBundle\OAuth;

use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Provider\OAuthProvider as BaseOAuthProvider;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @deprecated https://github.com/hwi/HWIOAuthBundle/issues/847
 */
class OAuthProvider extends BaseOAuthProvider
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return;
        }

        if ($token->isAuthenticated()) {
            return $token;
        }

        return parent::authenticate($token);
    }
}
