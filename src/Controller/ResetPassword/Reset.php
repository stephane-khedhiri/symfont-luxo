<?php


namespace App\Controller\ResetPassword;


use App\Form\ChangePasswordFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Reset extends AbstractController
{
    /**
     * @Route("/reset/{token}/{id}", name="reset_password")
     *
     * @param $token
     * @param $id
     * @param UserRepository $repository
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $stack
     *
     * @return RedirectResponse|Response
     */
    public function __invoke($token, $id, UserRepository $repository, FormFactoryInterface $formFactory, RequestStack $stack)
    {


        if(!$user = $repository->findOneBy(['id'=>$id])){
            $this->addFlash('error', 'error: not found');
        }
        if($user->getToken() != $token){
            $this->addFlash('error', 'error: not found');
        }
        $form =$formFactory->createBuilder(ChangePasswordFormType::class)
            ->getForm();

        $form->handleRequest($stack->getCurrentRequest());

        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword($form->get('plainPassword')->getData());
            $user->setToken(null);
            $repository->edit($user);
            return $this->redirectToRoute('login');
        }

        return $this->render('Reinitialisations/reset.html.twig', [
            'form' => $form->createView()
        ]);

    }
}