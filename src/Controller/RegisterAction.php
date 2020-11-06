<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RegisterAction extends AbstractController
{
    /**
     * @Route("/register", name="register")
     *
     * @param RequestStack $requestStack
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     * @param FormFactoryInterface $formFactory
     *
     * @return RedirectResponse|Response
     *
     * @throws TransportExceptionInterface
     */
    public function __invoke(RequestStack $requestStack, MailerInterface $mailer, UserRepository $userRepository, FormFactoryInterface $formFactory)
    {
        $user = new User();
        $users =$userRepository->findAll();
        $form = $formFactory->createBuilder(RegisterType::class, $user)
            ->getForm();

        $form->handleRequest($requestStack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            foreach ($users as $dbUser){
                if ($dbUser->getEmail() === $user->getEmail() ){
                    dump($dbUser, $user);
                    $this->addFlash('error','error : email not valid !');
                    return $this->redirectToRoute('register');
                }
            }

            $email = (new Email())
                ->from('luxo.noreply.robot@gmail.com')
                ->to($user->getEmail())
                ->subject('Confirmation create an account !')
                ->html($this->render('Email/Comfirmation.html.twig', [
                    'user' => $user
                ])->getContent());
            $mailer->send($email);

            $userRepository->register($user);
            return $this->redirectToRoute('login');
        }

        return $this->render('Acceuil/Register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}