<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'app_serie')]
final class SerieController extends AbstractController
{
    #[Route('/test', name: '_test')]
    public function test(EntityManagerInterface $em): Response
    {
        $serie = new Serie();
        $serie->setName('Derrick')
            ->setOverview('Encore une enquête pour l\'inspecteur')
            ->setStatus('Ended')
            ->setGenres('série policière allemande')
            ->setFirstAirDate(new \DateTime('1974-10-20'))
            ->setLastAirDate(new \DateTime('1998-10-16'))
            ->setDateCreated(new \DateTime());

        $em->persist($serie);
        $em->flush();

        return new Response('Derrick est à la maison');
    }

    #[Route('/liste/{page}', name: '_liste', requirements:['page' => '\d+'], methods: ['GET'])]
    public function liste(SerieRepository $serieRepository, int $page = 1): Response
    {
        # appel aux parameters définis dans le fichier config/services.yaml
        $limit = $this->getParameter('nb_limit_series');

        # Méthode heritée du Repository
        //$series = $serieRepository->findAll();

        # restriction à page > 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $criterias = [
            'status' => 'returning',
            'genres' => 'Horror'
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

}
