<?php

namespace NotificationChannels\Ideamart;

use Exception;
use Illuminate\Notifications\Events\NotificationFailed;
use Joomtriggers\Ideamart\SMS\Handler;
use NotificationChannels\Ideamart\Exceptions\CouldNotSendNotification;
use NotificationChannels\Ideamart\Events\MessageWasSent;
use NotificationChannels\Ideamart\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class IdeamartChannel
{
    protected $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
        // Initialisation code here
    }

    public function send($notifiable, Notification $notification)
    {
        try {
            $to = $this->getTo($notifiable);
            $message = $notification->toIdeamart($notifiable);
            if (is_string($message)) {
                $message = new IdeamartMessage($message);
            }
            if (!$message instanceof IdeamartMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->handler->addSubscriber($to)->setMessage($message)->send();
        } catch (Exception $exception) {
            $this->events->fire(
                new NotificationFailed($notifiable, $notification, 'ideamart', ['message' => $exception->getMessage()])
            );
        }
    }

    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('ideamart')) {
            return $notifiable->routeNotificationFor('ideamart');
        }
        if (isset($notifiable->ideamart_number)) {
            return $notifiable->ideamart_number;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }
}
