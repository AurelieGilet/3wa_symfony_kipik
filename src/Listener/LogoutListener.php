<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\LogoutException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LogoutListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        do {
            if ($exception instanceof LogoutException) {
                $this->handleLogoutException($event, $exception);
                return;
            }
        } while (null !== $exception = $exception->getPrevious());
    }

    private function handleLogoutException(ExceptionEvent $event, LogoutException $exception): void
    {
        // CSRF errors are normal so for those we just redirect page
        if ($exception->getMessage() !== 'Invalid CSRF token.') {
            return;
        }

        try {
            $event->setResponse(new RedirectResponse('/connexion'));
            $event->allowCustomResponseCode();
        } catch (\Exception $e) {
            $event->setThrowable($e);
        }
    }
}