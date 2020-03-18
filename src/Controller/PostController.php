<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
        ]);
    }
    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){

         $post = new Post();
         $form = $this->createForm(PostType::class, $post);

         $form->handleRequest($request);
         if ($form->isSubmitted()){
             $em = $this->getDoctrine()->getManager();
             /** @var UploadedFile $file */
             $file =  $request->files->get('post')['image'];
             if($file) {
                $filename=md5(uniqid()) . '.' . $file->guessClientExtension();

                $file->move(
                    $this->getParameter('uploads_dir'),
                    // get target directory
                    $filename
                );
                $post->setImage($filename);
             }
             $em->persist($post);
             $em->flush();

             return $this->redirect($this->generateUrl('post.index'));
         }


        return $this->render('post/create.html.twig', [
            'form' => $form ->createView()
        ]);

    }

    /**
     * @Route("/show/{id}", name="show")
     * @param Post $post
     * @return Response
     */
    public function show (Post $post ){

        //$post = $postRepository->findPostWithCategory($id);
        return $this->render('post/show.html.twig', [

            'post' => $post,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete")
     * @param Post $post
     * @return Response
     */
    public function delete(Post $post){
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        $this->addFlash('success','post removed');
        //redirect the user after deleting the post
        return $this->redirect($this->generateUrl('post.index'));
    }
}












































































































































































































































































































































