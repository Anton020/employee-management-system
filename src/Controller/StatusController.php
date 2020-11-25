<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use App\Service\Helper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_EMPLOYER")
 */
class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Status::class);
        return $this->render('status/index.html.twig', [
            'status' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/status/add", name="status_add")
     * @param Request $request
     * @param Helper $helper
     * @return Response
     */

    public function add(Request $request, Helper $helper)
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status->setCompany($helper->getCompany());

            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            $this->addFlash('success', 'Pomyślnie dodano status');
            return $this->redirectToRoute('status');
        }

        return $this->render('status/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
