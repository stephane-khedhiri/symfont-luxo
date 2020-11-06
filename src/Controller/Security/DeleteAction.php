<?php


namespace App\Controller\Security;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteAction extends AbstractController
{
    /**
     * @Route("/user/delete/{id}", name="delete_user")
     *
     * @param $id
     * @param UserRepository $repository
     * @param TokenStorageInterface $tokenStorage
     * @param MailerInterface $mailer
     *
     * @return RedirectResponse
     *
     * @throws TransportExceptionInterface
     */
    public function __invoke($id, UserRepository $repository, TokenStorageInterface $tokenStorage, MailerInterface $mailer)
    {
        if($tokenStorage->getToken()->getUser()->getId() != $id){
            $this->addFlash('error','error: ad not find !');
            return $this->redirectToRoute('edit_user',[
               'id' => $tokenStorage->getToken()->getUser()->getId()
            ]);
        }
        $user = $repository->find($id);
        $repository->delete($user);
        $tokenStorage->setToken(null);

        $email = (new Email())
            ->from('luxo.noreply.robot@gmail.com')
            ->to($user->getEmail())
            ->subject('disactivate your account')
            ->html("We thank you for using our service see you soon");
        $mailer->send($email);
        return $this->redirectToRoute('/home');
    }
}