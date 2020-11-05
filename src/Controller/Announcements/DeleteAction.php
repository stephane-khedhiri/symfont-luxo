<?php


namespace App\Controller\Announcements;


use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class DeleteAction extends AbstractController
{

    /**
     * @Route("/user/delete/announcement/{id}", name="delete_announcement",methods="DELETE")
     *
     * @param $id
     * @param AnnouncementRepository $announcementRepository
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke($id, AnnouncementRepository $announcementRepository , Request $request)
    {
        if(!$announcement = $announcementRepository->findOneBy(['id' => $id])){
            $this->addFlash('error', 'error: error: ad not find !');
        }
        if($announcement->getPostedBy() === $this->getUser()){
            if($this->isCsrfTokenValid('delete'.$announcement->getId(), $request->get('_token'))){
                $announcementRepository->delete($announcement);
                $this->addFlash('success', 'Delete successfully');
                return $this->redirectToRoute('list_announcement');
            }

        }
            return $this->redirectToRoute('list_announcement');
    }
}