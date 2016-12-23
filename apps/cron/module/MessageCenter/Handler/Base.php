<?php
/**
 * @namespace
 */
namespace MessageCenter\Handler;

use QueueCenter\Queue\HandlerCallbackInterface,
    Library\Traits\DIaware,
    Library\Traits\Observable,
    Library\Traits\Message;

/**
 * Class McMailNotification
 * @package MessageCenter\Handler
 */
abstract class Base implements HandlerCallbackInterface
{
    use DIaware,Observable,Message;
}