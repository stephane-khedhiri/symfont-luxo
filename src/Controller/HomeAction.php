<?php


namespace App\Controller;


use App\Entity\Announcement;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeAction extends AbstractController
{

    /**
     * @Route("/")
     * @Route(path ="/home", name="home")
     * @param AnnouncementRepository $announcementRepository
     *
     * @return Response
     */
    public function __invoke(AnnouncementRepository $announcementRepository)
    {
        return $this->render('Acceuil/Home.html.twig',[
            'announcements' => [
                'location' => $announcementRepository->findByCategoryWithImage(Announcement::LOCATION),
                'achat'=> $announcementRepository->findByCategoryWithImage(Announcement::ACHAT),
            ],
        ]);
    }

}