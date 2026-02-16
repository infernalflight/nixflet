<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'app_serie')]
final class SerieController extends AbstractController
{
    #[Route('/liste/{page}', name: '_liste', requirements: ['page' => '\d+'], methods: ['GET'])]
     public function list(SerieRepository $serieRepository, int $page = 1): Response
     {
         $limit = $this->getParameter('nb_limit_series');

         # restriction à page > 1
         $page = max($page, 1);
         $offset = ($page - 1) * $limit;

         $nbTotal = $serieRepository->count([]);
         $nbPagesMax = ceil($nbTotal / $limit);

         if ($page > $nbPagesMax) {
             throw $this->createNotFoundException('La page ' . $page . ' n\'existe pas');
         }

         $series = $serieRepository->findBy([], ['popularity' => 'DESC'], $limit, $offset);
         return $this->render('serie/liste.html.twig', [
             'series' => $series,
             'page' => $page,
             'nb_pages_max' => $nbPagesMax,
         ]);
     }

    #[Route('/liste/find_by/{page}', name: '_liste_find_by', requirements:['page' => '\d+'], methods: ['GET'])]
    public function listeFindby(SerieRepository $serieRepository, int $page = 1): Response
    {
        # appel aux parameters définis dans le fichier config/services.yaml
        $limit = $this->getParameter('nb_limit_series');

        # restriction à page > 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $criterias = [
            'status' => 'returning',
            //'genres' => 'Horror'
        ];

        $nbTotal = $serieRepository->count($criterias);
        $nbPagesMax = ceil($nbTotal / $limit);

        if ($page > $nbPagesMax) {
            throw $this->createNotFoundException('La page ' . $page . ' n\'existe pas');
        }

        # Méthode héritée qui utilise un tableau de critères binaires
        $series = $serieRepository->findBy($criterias, [
            'firstAirDate' => 'DESC',
            'dateCreated' => 'DESC'
        ], $limit, $offset);

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
            'page' => $page,
            'nb_pages_max' => $nbPagesMax,
        ]);
    }

    #[Route('/liste/find_custom/{page}', name: '_liste_find_custom', requirements:['page' => '\d+'], methods: ['GET'])]
    public function listeFindCustom(SerieRepository $serieRepository, int $page = 1): Response
    {
        $limit = $this->getParameter('nb_limit_series');

        # restriction à page > 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        # Méthode custom du Repository
        list($nbTotal, $series) = $serieRepository->findSeriesCustom($offset, $limit, 'returning', new \DateTime('1990-01-01'), 7.5);

        $nbPagesMax = ceil($nbTotal / $limit);

        if ($page > $nbPagesMax) {
            throw $this->createNotFoundException('La page ' . $page . ' n\'existe pas');
        }

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
            'page' => $page,
            'nb_pages_max' => $nbPagesMax,
        ]);
    }

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(Serie $serie): Response
    {
        return $this->render('serie/detail.html.twig', [
            'serie' => $serie,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $serie->setDateCreated(new \DateTime());
            $em->persist($serie);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle série a été enregistrée');
            return $this->redirectToRoute('app_serie_liste');
        }

        return $this->render('serie/edit.html.twig', [
            'serie_form' => $serieForm,
        ]);
    }
}
