<?php

namespace MoviesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;

class UpdateListController extends Controller
{
    /**
     * @Route("/selectFolder")
     * @Template()
     */
    public function selectFolderAction()
    {
        $form = $this->createFormBuilder()
            ->add('folder', 'file')
            ->getForm();

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/import/movies/from/folder")
     * @Template()
     */
    public function importMoviesFromFolderAction()
    {
        $folder = 'F:\Video\Films';

        $filter = function (\SplFileInfo $file)
        {
            if (!in_array($file->getExtension(), array('avi', 'mkv'))) {
                return false;
            }
        };

        $finder = new Finder();
        $finder->files()->in($folder)->filter($filter);


        $moviesList = array();
        $moviesErrorsList = array();
        foreach ($finder as $file) {
            //TODO: improve that!
            $filename = explode('.', $file->getFilename());
            $filename = $filename[0];

            $movie = $this->get('api_service')->searchformovieAction($filename);
            if ($movie) {
                $moviesList[] = $movie;
            } else {
                $moviesErrorsList[] = $filename;
            }
        }

        if (!empty($moviesErrorsList)) {
            $this->get('session')->getFlashBag()->add(
                'error',
                $this->generateStringForFlashMessage($moviesErrorsList)
            );
        }

        return array(
            'moviesList' => $moviesList,
            'moviesErrorsList' => $moviesErrorsList
        );
    }

    private function generateStringForFlashMessage($list)
    {
        $result = "Les films suivants n'ont pas étés trouvés, essayez de les renommer puis refaites un import.
        <ul>";
        foreach ($list as $movie) {
            $result .= "<li>$movie</li>";
        }
        $result .= "</ul>";

        return $result;
    }
}
