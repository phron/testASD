<?php

namespace App\EventSubscriber;

use App\Repository\OccasionRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $occasionRepository;
    private $router;

    public function __construct(
        OccasionRepository $occasionRepository,
        UrlGeneratorInterface $router
    ) {
        $this->occasionRepository = $occasionRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change occasion.beginAt by your start date property
        $occasions = $this->occasionRepository
            ->createQueryBuilder('occasion')
            ->where('occasion.startDate BETWEEN :start and :end OR occasion.endDate BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i'))
            ->setParameter('end', $end->format('Y-m-d H:i'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($occasions as $occasion) {
            // this create the events with your data (here occasion data) to fill calendar
            $occasionEvent = new Event(
                $occasion->getTitle(),
                $occasion->getStartDate(),
                $occasion->getEndDate(), // If the end date is null or not defined, a all day event is created.
                
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */
            

            $occasionEvent->setOptions([
                
                'backgroundColor' => $occasion->getCategory()->getBgColor(),
                'borderColor' => $occasion->getCategory()->getBdColor(),
                'textColor' => $occasion->getCategory()->getTextColor()
            ]);
            $occasionEvent->addOption(
                'url',
                $this->router->generate('app_occasion_show', [
                    'id' => $occasion->getId(),
                ])

            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($occasionEvent);
        }
    }
}