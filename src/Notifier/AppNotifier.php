<?php

namespace App\Notifier;

use App\Entity\Movie;
use App\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class AppNotifier
{
    public function __construct(
        protected readonly NotifierInterface $notifier,
        /** @var NotificationFactoryInterface[] $factories */
        #[TaggedLocator('app.notification_factory', defaultIndexMethod: 'getIndex')]
        protected ContainerInterface $factories
    )
    {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function sendMovieNotification(Movie $movie/*, User $user*/): void
    {
        $user = $this->getUser();
        $msg = sprintf("New movie dropped! %s", $movie->getTitle());

        $notification = $this->factories->get($user->getPreferredChannel())->createNotification($msg);
        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }

    private function getUser(): object
    {
        return new class {
            public function getEmail(): string
            {
                return 'me@me.com';
            }

            public function getPreferredChannel(): string
            {
                return 'slack';
            }
        };
    }
}
