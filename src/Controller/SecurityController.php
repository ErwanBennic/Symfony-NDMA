<?php

namespace App\Controller;

use App\Entity\User;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/api/login", name="api_login", methods={"OPTIONS", "POST"})
     */
    public function api_login(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        // last username entered by the user


        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $request->get('username')]);

        if ($user) {
            $password = $passwordEncoder->isPasswordValid($user, $request->get('password'));
            if ($password) {
                $response->setStatusCode(Response::HTTP_OK);
                return $response;
            }
        }
        $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        $response->setContent(json_encode(['error' => $password]));

        return $response;
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
