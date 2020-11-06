<?php


namespace App\Controller\Announcements;


use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListAction extends AbstractController
{
    /**
     * @Route("user/list", name="list_announcement")
     *
     * @param AnnouncementRepository $announcementRepository
     */
    public function __invoke(AnnouncementRepository $announcementRepository)
    {
        $lists = $announcementRepository->findByUser($this->getUser());

        return $this->render('Announcements/List.html.twig',[
           'lists' => $lists
        ]);
    }
}