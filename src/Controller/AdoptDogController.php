<?php

namespace App\Controller;

use App\Entity\Adoptant;
use App\Entity\Annonce;
use App\Entity\Contact;
use App\Form\AdoptantType;
use App\Form\ContactType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class AdoptDogController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    /**
     * @Route("/", name="home")
     */
    public function home(Request $request, PaginatorInterface $paginator, AnnonceRepository $annonceRepository): Response
    {
        $donnees = $annonceRepository->findAll();
        /*
                $annonces = $paginator->paginate(
                            $donnees,
                            $request->query->getInt('page', 1),
                            5
                        );
        */
        $annonces  = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('adopt_dog/home.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('adopt_dog/about.html.twig');
    }

    /**
     * @Route("/contact_us", name="contact")
     */
    public function contactus(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('adopt_dog/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
    * @Route("/helps", name="helps")
    */
    public function helps()
    {
        return $this->render('adopt_dog/help.html.twig');
    }

    /**
    * @Route("/other", name="other")
    */
    public function otherlinks()
    {
        return $this->render('adopt_dog/other.html.twig');
    }

    /**
     * @Route("/registration/adoptant", name="registrationAdoptant")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function registrationAdoptant(Request $request, EntityManagerInterface $em): Response
    {
        $adoptant = new Adoptant();

        $formAdoptant = $this->createForm(AdoptantType::class, $adoptant);
        $formAdoptant->handleRequest($request);

        if ($formAdoptant->isSubmitted() && $formAdoptant->isValid()) {
            $hash = $this->hasher->hashPassword($adoptant, $adoptant->getPlainPassword());
            $adoptant->setPassword($hash);
            $em->persist($adoptant);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('adopt_dog/registrationAdoptant.html.twig', [
            'formAdoptant' => $formAdoptant->createView()
        ]);
    }

    /**
     *@Route("/annonce/{id}", name="annonceById")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Annonce::class);

        $annonce = $repo->find($id);

        return $this->render('adopt_dog/annonceShow.html.twig', [
        'annonce' => $annonce]);
    }

    /**
     * @Route("/eleveurs", name="eleveurs")
     */
    public function eleveurs()
    {
        return $this->render('adopt_dog/eleveurs.html.twig');
    }

    /**
     * @Route("/spa", name="spa")
     */
    public function spa()
    {
        return $this->render('adopt_dog/spa.html.twig');
    }

    /**
    *@Route("/eleveur", name="eleveur")
     */
    public function showEleveurs()
    {
        return $this->render('adopt_dog/eleveurs.html.twig');
    }
}
