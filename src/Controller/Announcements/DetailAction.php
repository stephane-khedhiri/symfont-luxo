<?php


namespace App\Controller\Announcements;


use App\Form\ContactType;
use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormErrorIterator;

class DetailAction extends AbstractController
{
    /**
     * @Route("/announcement/detail/{id}", name="detail_announcement")
     *
     * @param $id
     * @param AnnouncementRepository $announcementRepository
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $stack
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function __invoke($id, AnnouncementRepository $announcementRepository, FormFactoryInterface $formFactory, RequestStack $stack, MailerInterface $mailer)
    {


        if (!$announcement = $announcementRepository->findByAnnouncementWithImage($id)){
            return $this->redirectToRoute('home');
        }
        $owner = $announcement->getPostedBy();

        $form = $formFactory->createBuilder(ContactType::class)
            ->getForm();
        $form->handleRequest($stack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            $email = (new Email())
                ->from('luxo.noreply.robot@gmail.com')
                ->to($owner->getEmail())
                ->subject('an individual is interested in your ad')
                ->html($this->render('Email/Contact.html.twig', [
                    'contact'=> $contact,
                    'owner' => $owner,
                    'announcement' => $announcement
                    ])->getContent()
                );
            $mailer->send($email);
            $this->addFlash('success', 'send successfully');
            return $this->redirectToRoute('detail_announcement', [
                'id' => $announcement->getId()
            ]);

        }


        return $this->render('Announcements/Detail.html.twig',[
            'announcement' => $announcement,
            'form' => $form->createView(),

        ]);
    }
}