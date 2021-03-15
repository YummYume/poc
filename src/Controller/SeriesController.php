<?php

namespace App\Controller;

use App\Form\SeriesFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Series;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class SeriesController extends AbstractController
{
    /**
     * @Route("/series", name="series")
     */
    public function index(): Response
    {
        $id = $this->getUser()->getId();
        $series = $this->getDoctrine()->getRepository(Series::class)->findBy(
            array('user' => $id),
        );

        return $this->render('series/index.html.twig', [
            'series' => $series,
        ]);
    }

    /**
     * @Route("/series/add", name="addSeries")
     */
    public function create(Request $request): Response
    {
        $series = new Series();

        $form = $this->createForm(SeriesFormType::class, $series, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form['thumbnail']->getData()))
            {
                $file = $form['thumbnail']->getData();
                $file->move('thumbnails', $file->getClientOriginalName());
                $series->setThumbnail($file->getClientOriginalName());
            }
            else
            {
                $series->setThumbnail('default.jpg');
            }

            $series->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($series);
            $em->flush();

            return $this->redirectToRoute("series");
        }
        return $this->render('series/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/delete/{id}", name="deleteSeries")
    */
    public function delete(Series $series): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($series);
        $entityManager->flush();

        return $this->index();
    }
}
