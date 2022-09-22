<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(Request $request): Response
    {
        if($request->headers->has('Authorization')){
            $token=$request->headers->get('Authorization');
            $token=str_replace("Bearer","",$token);
            $token=str_replace(" ","",$token);

        }
        else{
            $token="";
        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DashboardController.php',
            "token"=>$token,
            "user"=>$this->getUser()
        ]);
    }
    /**
     * @Route("/post",name="add_post",methods={"POST"})
     */
    public function PostList(Request $request)
    {
        if($request->getContent()==null)
        {
            return $this->json(
                [
                    'message' => 'vous douvez choisir le title et le contenu de post',
                ],
                Response::HTTP_BAD_REQUEST

            ); 
        }
        $request2=json_decode($request->getContent());
        $title=$request2->title;
        $content=$request2->content;
        $user=$this->getUser();
        if($title==null || $content==null)
        {
            return $this->json(
                [
                    'message' => 'vous douvez choisir le title et le contenu de post',
                ],
                Response::HTTP_BAD_REQUEST

            );
        }
        else{
            $post=new Post();
            $post->setTitle($title);
            $post->setContent($content);
            $post->setUsercreated($user->getUserIdentifier());
            $em=$this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->json(
                [
                    'message' => 'votre post est ajouter avec success',
                    'data'=>$post
                ],
                Response::HTTP_CREATED

            );

        }      
    }
    /**
     * @Route("/posts",name="list_posts",methods={"GET"})
     */
    public function GetPosts(Request $request)
    {
        return $this->json(
            [
                'data'=>$this->getDoctrine()->getRepository(Post::class)->findAll()
            ],
            Response::HTTP_OK
        );
    }
    /**
     * @Route("/post/{id}",name="put_post",methods={"PUT"})
     */
    public function ModifyList(Request $request,$id,PostRepository $postrep)
    {
        if($request->getContent()==null)
        {
            return $this->json(
                [
                    'message' => 'vous douvez choisir le title et le contenu de post',
                ],
                Response::HTTP_BAD_REQUEST

            ); 
        }
        $request2=json_decode($request->getContent());
        $id=$request->get('id');
        $title=$request2->title;
        $content=$request2->content;
        $user=$this->getUser();
        if($title==null || $content==null)
        {
            return $this->json(
                [
                    'message' => 'vous douvez choisir le title et le contenu de post',
                ],
                Response::HTTP_BAD_REQUEST

            );
        }
        else{
            $post=$postrep->findOneById($id);
            if($post!=null)
            {
                $post->setTitle($title);
                $post->setContent($content);
                $post->setUsercreated($user->getUserIdentifier());
                $em=$this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                return $this->json(
                    [
                        'message' => 'votre post est modifier avec success',
                        'data'=>$post
                    ],
                    Response::HTTP_ACCEPTED
    
                );
            }
            else{
                return $this->json(
                    [
                        'message' => 'post not found',
                    ],
                    Response::HTTP_BAD_REQUEST
    
                );
            }
            

        }      
    }

    /**
     * @Route("/post/{id}",name="delete_post",methods={"DELETE"})
     */
    public function DeletePost(Request $request,$id,PostRepository $postRepository)
    {
        $id=$request->get('id');
        $post=$postRepository->findOneById($id);
        if($post!=null)
        {

            $em=$this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            return $this->json(
                [
                    'message' => 'votre post est supprimer avec success',
                ],
                Response::HTTP_OK

            );
        }
        else{
            return $this->json(
                [
                    'message' => 'post not found',
                ],
                Response::HTTP_BAD_REQUEST

            );
        }

    }

} 
