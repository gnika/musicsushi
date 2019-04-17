<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\MyType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class UsersController extends BaseAdminController
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * The method that is executed when the user performs an 'edit' action on the User entity.
     *
     * @return Response|RedirectResponse
     */
    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            // cast to integer instead of string to avoid sending empty responses for 'false'
            return new Response((int)$newValue);
        }

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('createUsersEditForm', array($entity, $fields));
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // hash the password before storing in db
            $encodedPassword = $this->passwordEncoder->encodePassword(
                $entity,
                $entity->getPassword()
            );
            $entity->setPassword($encodedPassword);

            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));

            $this->executeDynamicMethod('preUpdateUsersEntity', array($entity));
            $this->executeDynamicMethod('updateUsersEntity', array($entity));

            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        $parameters = array(
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );

        return $this->executeDynamicMethod('renderUsersTemplate', array('edit', $this->entity['templates']['edit'], $parameters));
    }

    /**
     * The method that is executed when the user performs a 'new' action on the User entity.
     *
     * @return Response|RedirectResponse
     */
    protected function newAction()
    {

//        $this->dispatch(EasyAdminEvents::PRE_NEW);
//
//        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');
//
//        $easyadmin = $this->request->attributes->get('easyadmin');
//        $easyadmin['item'] = $entity;
//        $this->request->attributes->set('easyadmin', $easyadmin);
//
//        $fields = $this->entity['new']['fields'];
//
//
//        $newForm = $this->executeDynamicMethod('createUsersNewForm', array($entity, $fields));
//
//        $newForm->handleRequest($this->request);
//        if ($newForm->isSubmitted() && $newForm->isValid()) {
//
//            // hash the password before storing in db
//            $encodedPassword = $this->passwordEncoder->encodePassword(
//                $entity,
//                $entity->getPassword()
//            );
//
//            $entity->setPassword($encodedPassword);
//            $entity->setRoles(["ROLE_ADMIN"]);
//
////            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));
//
////            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));
//            $this->executeDynamicMethod('persistUsersEntity', array($entity));
//
//            $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));
//
//            return $this->redirectToReferrer();
//        }
//
//        $this->dispatch(EasyAdminEvents::POST_NEW, array(
//            'entity_fields' => $fields,
//            'form' => $newForm,
//            'entity' => $entity,
//        ));
//
//        $parameters = array(
//            'form' => $newForm->createView(),
//            'entity_fields' => $fields,
//            'entity' => $entity,
//        );
//
//        return $this->executeDynamicMethod('renderUsersTemplate', array('new', $this->entity['templates']['new'], $parameters));
    }


    /**
     * @Route("/addFriends", name="app_form_friends")
     */
    public function addFriends(Request $request)
    {

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(MyType::class, $currentUser, [ 'emailUser' => $currentUser->getEmail() ] );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $em->persist($currentUser);
            $em->flush();
        }

        return $this->render('users/add.html.twig', array('form'=> $form->createView()));
    }
}