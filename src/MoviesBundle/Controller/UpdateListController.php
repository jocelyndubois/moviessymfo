<?php

namespace MoviesBundle\Controller;

use MoviesBundle\Entity\MovieUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Finder;
use JMS\SecurityExtraBundle\Annotation\Secure;

class UpdateListController extends Controller
{
    /**
     * @Route("/{_locale}/upload/movies", name="_uploadMovies", defaults={"_locale" = "fr"}, requirements={"_locale" = "|en|fr"})
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function selectFolderAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('folder', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            //Get info from file
            $file = $form['folder']->getData();

            //TODO: Rajouter sécurité sur le nom, l'extension et le contenu

            $contents = file_get_contents($file->getPathname());
            $contents = str_replace(".avi", "", $contents);
            $contents = str_replace(".AVI", "", $contents);
            $contents = str_replace(".mkv", "", $contents);
            $contents = str_replace(".MKV", "", $contents);
            $movies = explode("\n", $contents);
            $movies = array_map('trim', $movies);
            array_pop($movies);

            $moviesList = $this->importMoviesFromFolder($movies);

            return $this->render(
                'MoviesBundle:UpdateList:importMoviesFromFolder.html.twig',
                array('moviesList' => $moviesList)
            );
//            return $this->redirect($this->generateUrl('_importMovies', array('movies' => $movies)));
        }

        return array(
            'form' => $form->createView()
        );
    }

    public function importMoviesFromFolder($movies)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        foreach ($movies as $filename) {
            $movie = $this->get('api_service')->searchformovieAction($filename);
            if ($movie) {
                if (!$user->hasMovie($movie)) {
                    $movieUser = new MovieUser();
                    $movieUser->setMovie($movie);
                    $movieUser->setUser($user);
                    $movieUser->setLocalName(utf8_decode($filename));
                    $movie->addUser($movieUser);
                    $user->addMovie($movieUser);

                    $em->persist($movieUser);
                    $em->persist($movie);
                    $em->persist($user);
                    $em->flush();
                }
                $moviesList[] = $movie;
            } else {
                $moviesErrorsList[] = $filename;
            }
        }
        if (!empty($moviesList)) {
            $em->persist($user);
            $em->flush();
        }

        if (!empty($moviesErrorsList)) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->generateStringForFlashMessage($moviesErrorsList)
            );
        }

        return $moviesList;
    }

    private function generateStringForFlashMessage($list)
    {
        $result = $this->get('translator')->trans("Les films suivants n'ont pas étés trouvés, essayez de les renommer puis refaites un import.");
        $result = "<ul>";
        foreach ($list as $movie) {
            $result .= "<li>$movie</li>";
        }
        $result .= "</ul>";

        return $result;
    }
}
