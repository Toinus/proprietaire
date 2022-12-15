<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="app_categories")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        //On va chercher les catégories dans la BDD avec un repository
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll(); //select * transformé en liste de Categorie

        return $this->render('categories/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/categorie/ajouter", name="app_categories_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //création du formulaire
        //Création d'une catégorie vide en 1er
        $categorie = new Categorie();


        //Ensuite je crée le formulaire
        $form =$this->createForm(CategorieType::class, $categorie);


        //On s'occupe du retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //objet catégorie rempli
            $em=$doctrine->getManager();

            //on mets la catégorie dans la table
            $em->persist($categorie);

            //on génère l'appel SQL
            $em->flush();

            //retour l'accueil
            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/ajouter.html.twig",[
           "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/categorie/modifier/{id}", name="app_categories_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response{
        //création du formulaire sur le même principe que dans ajouter mais avec une catégorie existante
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //On gère le fait que l'id n'existe pas
        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        //si arrivé ici alors elle existe en BDD
        //Avec je crée le formulaire
        $form=$this->createForm(CategorieType::class, $categorie);

        //retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            //l'objet catégorie est rempli on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();

            //on mets la catégorie dans la table
            $em->persist($categorie);

            //on génère l'appel SQL
            $em->flush();

            //Retour accueil
            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/modifier.html.twig",[
            "categorie"=>$categorie,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/categorie/supprimer/{id}", name="app_categories_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response{
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //je vais gérer le fait que l'id n'existe pas
        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        //Si j'arrive là c'est qu'elle existe en BDD
        //à partir de ça je crée le formulaire
        $form=$this->createForm(CategorieSupprimerType::class, $categorie);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            //l'objet catégorie rempli, on utilise l'entity manager de doctrine
            $em=$doctrine->getManager();

            //Supprimer la catégorie
            $em->remove($categorie);

            //on génère l'appel SQL (update ici)
            $em->flush();

            //retour accueil
            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/supprimer.html.twig",[
            "categorie"=>$categorie,
            "formulaire"=>$form->createView()
        ]);
    }
}
