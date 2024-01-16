<?php

namespace App\Controller;

use App\Entity\Recettes;
use App\Form\RecetteType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RecetteController extends AbstractController
{

    /**
     * affiche les recettes
     *
     * @param RecettesRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'app_recette', methods:(['GET']))]
    public function index(RecettesRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $repo->findAll();
        $recettes = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            10, /*limite par page*/
            ['sortDirectionParameterName' => 'dir']
        );
        return $this->render('recette/index.html.twig',[
            'recettes'=> $recettes
        ]);
    }

    /**
     * function create recette
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/new', name:'new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response{

        $recette = new Recettes();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Recette bien enregistré !!!'
            );

            return $this->redirectToRoute('app_recette');
        }

        return $this->render('recette/new.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * function update recette
     *
     * @param integer $id
     * @param Request $request
     * @param RecettesRepository $repo
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('recette/edit/{id}', name: 'edit', methods:(['GET', 'POST']))]
    public function edit(int $id,Request $request, RecettesRepository $repo, EntityManagerInterface $manager) : Response
    {
        $recette = $repo->findOneBy(["id" => $id]);
        $form = $this->createForm(RecetteType::class, $recette);
    
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'success',
                'Recette bien modifié !!!'
            );

            return $this->redirectToRoute('app_recette');
        }
        return $this->render('recette/edit.html.twig',[
            'form'=> $form->createView()
        ]);

    }

    /**
     * function delete recette
     *
     * @param integer $id
     * @param RecettesRepository $repo
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    #[Route('recette/delete/{id}', name:'delete', methods:(['GET']))]
    public function delete(int $id,RecettesRepository $repo, EntityManagerInterface $manager ): RedirectResponse
    {
        $recette = $repo->findOneBy(["id" => $id]);

        if(!$recette){
            $this->addFlash(
                'success',
                'Recette non trouvé !!!'
            );
            return $this->redirectToRoute('app_recette');
        }

        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            'Recette supprimé !!!'
        );
        return $this->redirectToRoute('app_recette');

    }
}
