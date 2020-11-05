<?php


namespace App\Controller\ResetPassword;


use App\Entity\User;
use App\Form\ResetPasswordRequestFormType;
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

class CheckEmail extends AbstractController
{
    /**
     * @Route("/check/email", name="check_email")
     *
     * @param FormFactoryInterface $formFactory
     * @param UserRepository $repository
     * @param RequestStack $stack
     * @param MailerInterface $mailer
     * @return RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function __invoke(FormFactoryInterface $formFactory, UserRepository $repository, RequestStack $stack, MailerInterface $mailer)
    {
        $user = new User();
        $form = $formFactory->createBuilder(ResetPasswordRequestFormType::class, $user)
            ->getForm();
        $form->handleRequest($stack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {



            if (!$user = $repository->findOneBy(['email'=>$form->getData()->getEmail()])) {
                $this->addFlash('reset_password_error', 'email not exist');
            } else {
                $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
                $user->setToken($token);
                $repository->edit($user);

                $mail = (new Email())
                    ->from('luxo.noreply.robot@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Luxo account reset !')
                    ->html($this->render('Email/email.html.twig', [
                        'user' => $user
                    ])->getContent());
                $mailer->send($mail);
                return $this->render('Reinitialisations/check_email.html.twig');
            }
        }
        return $this->render('Reinitialisations/request.html.twig',[
           'form' => $form->createView()
        ]);
    }
}