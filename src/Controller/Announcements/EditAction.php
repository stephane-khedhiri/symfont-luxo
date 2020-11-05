<?php


namespace App\Controller\Announcements;


use App\Entity\Image;
use App\Form\AddImageType;
use App\Form\EditAnnouncementType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\String\Slugger\AsciiSlugger;

class EditAction extends AbstractController
{
    /**
     * @Route("/user/announcement/edit/{id}", name="edit_announcement")
     *
     * @param $id
     * @param FormFactoryInterface $formFactory
     * @param AnnouncementRepository $announcementRepository
     * @param RequestStack $request
     *
     * @return Response
     */
    public function __invoke($id, FormFactoryInterface $formFactory, AnnouncementRepository $announcementRepository, RequestStack $request)
    {
        if (!$announcement = $announcementRepository->findOneBy(['id' => $id])) {
            $this->addFlash('error', 'error: ad not find !');
            return $this->redirectToRoute('list_announcement');
        }
        if ($announcement->getPostedBy() !== $this->getUser()) {
            $this->addFlash('error', 'error: ad not find !');
            return $this->redirectToRoute('list_announcement');
        }
        $form = $formFactory->createBuilder(EditAnnouncementType::class, $announcement)
            ->getForm();
        $form->handleRequest($request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            $announcementEdit = $form->getData();
            $announcementEdit->getImages()->map(function (Image $image) use ($announcement) {
                if ($file = $image->getFile()) {
                    $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = (new AsciiSlugger())->slug($originalFileName);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                    try {
                        $publicPath = 'media/' . $announcement->getTypeName();
                        $path = realpath(__DIR__ . '/../../../public/') . '/' . $publicPath;
                        @mkdir($path, 0777, true);

                        $file->move($path, $newFilename);

                    } catch (FileException $e) {
                        throw $e;
                    }
                    $image->setPath($publicPath . '/' . $newFilename)->setName($originalFileName);
                    return $image;
                }
            });
            $announcementRepository->persist($announcementEdit);
            $this->addFlash('success', 'edit successfully');
            return $this->redirectToRoute('list_announcement');
        }


        return $this->render('Announcements/Edit.html.twig', [
            'form' => $form->createView(),
            'announcement' => $announcement

        ]);
    }
}