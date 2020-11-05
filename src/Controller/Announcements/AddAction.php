<?php


namespace App\Controller\Announcements;


use App\Entity\Announcement;
use App\Entity\Image;
use App\Form\AddAnnouncementType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AddAction extends AbstractController
{
    /**
     * @Route("/user/announcement/add", name="add_announcement")
     *
     * @param RequestStack $requestStack
     * @param AnnouncementRepository $announcementRepository
     *
     * @param FormFactoryInterface $formFactory
     * @return RedirectResponse|Response
     */
    public function __invoke(RequestStack $requestStack, AnnouncementRepository $announcementRepository, FormFactoryInterface $formFactory)
    {
        $announcement = new Announcement();

        $form = $formFactory->createBuilder(AddAnnouncementType::class, $announcement)
            ->getForm()
        ;

        $form->handleRequest($requestStack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){
            $announcement = $form->getData();
            $announcement->setPostedBy($this->getUser());
            $announcement->getImages()->map(function (Image $image) use ($announcement){
               if($file = $image->getFile()){
                   $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                   $safeFilename = (new AsciiSlugger())->slug($originalFileName);
                   $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                   try {
                       $publicPath = 'media/' . $announcement->getTypeName();
                       $path = realpath(__DIR__ . '/../../../public/').'/'.$publicPath;
                       @mkdir($path,0777,true);

                       $file->move($path, $newFilename);

                   } catch (FileException $e) {
                       throw $e;
                   }
                   $image->setPath($publicPath.'/'.$newFilename)->setName($originalFileName);
                   return $image;
               }
            });
            $announcementRepository->persist($announcement);
            $this->addFlash('success', 'add successfully');
            return $this->redirectToRoute('list_announcement');


        }

        return $this->render('Announcements/Add.html.twig',[
            'form' => $form->createView()
        ]);


    }
}