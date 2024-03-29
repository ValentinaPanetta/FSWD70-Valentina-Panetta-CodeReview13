<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('AppBundle:Event')->findAll();

        return $this->render('event/index.html.twig', array(
            'events' => $events,
        ));
    }

    // SORT FUNCTIONS

    /**
    * @Route("/sortMusic", name="event_sort_by_music")
    */
    public function sortByMusicAction()
    {
        $emConn = $this->getDoctrine()->getManager()->getConnection();
        $sql = $emConn->prepare("SELECT * FROM `event` WHERE type = 'Music'");
        $sql->execute();
        $results = $sql->fetchAll();

        return $this->render('event/sort.html.twig', array('events'=>$results));
    }

    /**
    * @Route("/sortArt", name="event_sort_by_Art")
    */
    public function sortByArtAction()
    {
        $emConn = $this->getDoctrine()->getManager()->getConnection();
        $sql = $emConn->prepare("SELECT * FROM `event` WHERE type = 'Art'");
        $sql->execute();
        $results = $sql->fetchAll();

        return $this->render('event/sort.html.twig', array('events'=>$results));
    }

    /**
    * @Route("/sortFilm", name="event_sort_by_Film")
    */
    public function sortByFilmAction()
    {
        $emConn = $this->getDoctrine()->getManager()->getConnection();
        $sql = $emConn->prepare("SELECT * FROM `event` WHERE type = 'Film'");
        $sql->execute();
        $results = $sql->fetchAll();

        return $this->render('event/sort.html.twig', array('events'=>$results));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('AppBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_show', array('id' => $event->getId()));
        }

        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('AppBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/delete/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $event = $this->getDoctrine()->getManager()->getRepository("AppBundle:Event")->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
