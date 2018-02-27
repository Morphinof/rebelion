<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 22/12/2017
 * Time: 21:48
 */

namespace Rebelion\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class GenericWorkflowLogger implements EventSubscriberInterface
{
    /** @var LoggerInterface $logger */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onLeave(Event $event)
    {
        $this->logger->info(sprintf(
            '%s (id: "%s") performed transaction "%s" from "%s" to "%s"',
            get_class($event->getSubject()),
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()->getTos())
        ));
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.leave' => 'onLeave',
        );
    }
}