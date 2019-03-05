<?php
namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
	
    /**
     * @Route("/", name="app_default")
     */
    public function defaultpage()
    {
        $entityManager = $this->getDoctrine()->getManager();

//        $admin = new Users();
//        $admin->setEmail('moneo.house.atreides@gmail.com');
//        $admin->setPassword('$2a$04$HLtiCeZzwvoUzsMFzFBlb.TFpgKVRwEn6Z/6W/ygjLFai8S1lVTVS');
//        $admin->setRoles(array('ROLE_ADMIN'));
//        $entityManager->persist($admin);
//        $entityManager->flush();

        return $this->render('default/defaultpage.html.twig');
    }
    /**
     * @Route("/redirectlog", name="app_redirectlog")
     */
    public function redirectlog()
    {

        return $this->render('default/defaultpage.html.twig');
    }
}