<?php

namespace App\Controller;

use App\Entity\EleveurSpa;
use App\Form\InscriptionESpaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionESpaController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @Route("/inscription_e_spa", name="inscription_e_spa_index")
     */
    public function index(): Response
    {
        return $this->render('inscription_e_spa/index.html.twig', [
            'controller_name' => 'InscriptionESpaController',
        ]);
    }

    /**
     * @Route("/inscription_e_spa/new", name="inscription_eleveur")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $inscriptionESpa = new EleveurSpa();
        $form = $this->createForm(InscriptionESpaType::class, $inscriptionESpa);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->hasher->hashPassword($inscriptionESpa, $inscriptionESpa->getPlainPassword());
            $inscriptionESpa->setPassword($hash);
            $em->persist($inscriptionESpa);
            $em->flush();

            return $this->redirectToRoute('about');
        }

        return $this->render('inscription_e_spa/registrationEleveur.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
