<?php

namespace App\Controller;
use App\Entity\Participants;
use App\Entity\Event;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantController extends AbstractController
{  
    #[Route("/event/{eventId}/participants/new", name:"add_participant")]
    public function addParticipant(
        int $eventId,
        Request $request,
        EntityManagerInterface $em
    ): Response{
        $event = $em->getRepository(Event::class)->find($eventId);
        if (!$event) {
            throw $this->createNotFoundException("L'événement avec l'ID {$eventId} n'existe pas.");
        }

        $participant = new Participants();
        $participant->setEvent($event);

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($participant);
            $em->flush();
            

            $this->addFlash('success', 'Participant ajouté avec succès !');
            return $this->redirectToRoute('view_event', ['id' => $eventId]);
        }

        return $this->render('participant/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }
}
