<?php

namespace App\Controller;


use App\Entity\Music;
use App\Form\MusicType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{
    /**
     * @Route("/formmusic", name="app_form_music")
     */
    public function formmusic(Request $request)
    {
        $music = new Music();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $file = $music->getFile();
            if( $file->guessExtension() == 'mpga' )
                $ext = 'mp3';
            else
                $ext = $file->guessExtension();
            $fileName = $this->generateUniqueFileName().'.'.$ext;

            try {
                $file->move(
                    $this->getParameter('app.path.music_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $em = $this->getDoctrine()->getManager();
            $music->setFile($fileName);
            $music->setUser($currentUser);
            $em->persist($music);
            $em->flush();

            return $this->redirectToRoute('app_list_music', array(
                'message' => 'success',
            ));
        }
        return $this->render('music/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/listmusic", name="app_list_music")
     */
    public function listmusic(Request $request)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $musics = $em = $this->getDoctrine()->getRepository('App\Entity\Music')->findBy(array('User' => $currentUser));
       if( isset($_GET['message']) )
           $msg = $_GET['message'];
       else
           $msg = '';

        return $this->render('music/musics.html.twig', array('musics'=>$musics,'message'=>$msg));
    }

    /**
     * @Route("/musicdetail/{id}", name="app_music_detail")
     */
    public function musicdetail(Request $request, $id)
    {
        $music = $em = $this->getDoctrine()->getRepository('App\Entity\Music')->find($id);

        return $this->render('music/music.html.twig', array('music'=>$music));
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}