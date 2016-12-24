<?php

namespace NotificationChannels\Ideamart\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function invalidMessageObject($response){

    }
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Descriptive error message.");
    }

    public static function invalidReceiver()
    {
    }
}
