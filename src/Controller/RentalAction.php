<?php


namespace App\Controller;


use App\Entity\Announcement;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RentalAction extends AbstractController
{
    /**
     * @Route("/rental", name="rental")
     * @param AnnouncementRepository $announcementRepository
     *
     * @return Response
     */
    public function __invoke(AnnouncementRepository $announcementRepository)
    {
        return $this->render('Acceuil/Rental.html.twig', [
            'announcements' => $announcementRepository->findByCategoryWithImage(Announcement::LOCATION)

        ]);
    }
}