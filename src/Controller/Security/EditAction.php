<?php


namespace App\Controller\Security;


use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EditAction extends AbstractController
{
    /**
     * @Route("/user/edit/user/{id}", name="edit_user")
     *
     * @param $id
     * @param UserRepository $Repository
     * @param RequestStack $stack
     *
     * @param FormFactoryInterface $factory
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     */
    public function __invoke($id , UserRepository $Repository, RequestStack $stack, FormFactoryInterface $factory, TokenStorageInterface $tokenStorage)
    {
        if($id != $tokenStorage->getToken()->getUser()->getId()){
            $this->addFlash('error', 'error: ad not find !');
            return $this->redirectToRoute('list_announcement');
        }
        $user = $Repository->find($id);
        $form = $factory->createBuilder(EditUserType::class, $user)
            ->getForm();
        $form->handleRequest($stack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $Repository->edit($user);
            $this->addFlash('success', 'edit successfully');
            return $this->redirectToRoute('list_announcement');
        }

        return $this->render('security/Edit.html.twig',[
            'form'=>$form->createView(),
            'id'=> $id
        ]);
    }
}