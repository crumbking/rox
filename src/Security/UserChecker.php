<?php

namespace App\Security;

use App\Entity\Member;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     *
     * @throws AccountBannedException
     * @throws AccountMailNotConfirmedException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Member) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if ($user->isBanned()) {
            throw new AccountBannedException();
        }
        // user is deleted, show a generic Account Not Found message.
        if ($user->isNotConfirmedYet()) {
            throw new AccountMailNotConfirmedException();
        }
    }

    /**
     * @param UserInterface $user
     *
     * @throws AccountExpiredException
     * @throws AccountDeniedLoginException
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Member) {
            return;
        }

        // user account is expired, the user may be notified
        if ($user->isExpired()) {
            throw new AccountExpiredException();
        }

        if ($user->isDeniedAccess()) {
            throw new AccountDeniedLoginException();
        }
    }
}
