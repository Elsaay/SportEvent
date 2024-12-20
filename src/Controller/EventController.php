<?php

namespace App\Controller;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\DistanceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'list_events')]
    public function listEvents(EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findAll();
        return $this->render('event/list.html.twig', [
            'event' => $event,
        ]);
    }
 
    #[Route('/event/{id}', name:'view_event', requirements:['id'=>'\d+'])] 
    public function viewEvent(int $id, EventRepository $eventRepository, Request $request, DistanceCalculator $distanceCalculator): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException("L'événement avec l'ID {$id} n'existe pas.");
        }

        $distance = null;
        if ($request->isMethod('POST')) {
            $userLat = $request->request->get('latitude');
            $userLon = $request->request->get('longitude');

            if ($userLat !== null && $userLon !== null) {
                $distance = $distanceCalculator->calculateDistance(
                    (float)$userLat,
                    (float)$userLon,
                    (float)$event->getLocationX(),
                    (float)$event->getLocationY()
                );
            }
        }

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'distance' => $distance,
        ]);
    }
    
}
