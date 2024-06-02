<?php

namespace App\EventListener;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Movie;
use App\Service\ImdbApiService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MoviePosterUrlSubscriber implements EventSubscriberInterface
{
    private $imdbApiService;

    public function __construct(ImdbApiService $imdbApiService)
    {
        $this->imdbApiService = $imdbApiService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getMoviePosterUrl', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function getMoviePosterUrl(ViewEvent $event)
    {
        $movie = $event->getControllerResult();
        if ($movie instanceof Movie) {
            $this->imdbApiService->getMoviePosterUrl($movie);
        }
    }
}