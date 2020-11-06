<?php


namespace App\Controller;


use App\Entity\Announcement;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseAction extends AbstractController
{
    /**
     * @Route(path ="/purchase", name="purchase")
     * @param AnnouncementRepository $announcementRepository
     *
     * @return Response
     */
    public function __invoke(AnnouncementRepository $announcementRepository)
    {
        return $this->render('Acceuil/Purchase.html.twig', [
            'announcements' => $announcementRepository->findByCategoryWithImage(Announcement::ACHAT),
        ]);
    }
}