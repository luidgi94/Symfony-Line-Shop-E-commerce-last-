<?php
    namespace App\Controller;
    use App\Entity\Search;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\HttpFoundation\Request;
    use App\Service\Cart\CartService;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Repository\GameRepository;
    class ListeDesJeuxController extends AbstractController
    {
      
        
        /**
        * @Route("/", name="liste_des_jeux")
        */
        public function SearchGame(Request $request,GameRepository $jeuxRepository, CartService $cartService)
        {
            // creates a Search object and initializes some data for this example
            $recherche = new Search();
            $recherche->setSearch('Far cry'); 
    
            $form = $this->createFormBuilder($recherche)
                ->add('search', TextType::class)
                ->add('send', SubmitType::class, ['label' => 'Rechercher'])
                ->getForm();
                dump(' formulaire chargé !!');
                ///////////////////////// VALIDATION FORMULAIRE ////////////////
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    // $form->getData() holds the submitted values
                    $recherche = $form->getData();
                    dump($form->getData()->getSearch()); 
                    $jeux = $jeuxRepository->trouverJeux($recherche->getSearch());
                    if (!$jeux) {
                        return $this->render('liste_des_jeux/index.html.twig',['form' => $form->createView(), 'itemsTotal' => $cartService->getTotalItem(),'total' => $cartService->getTotal(),'allgames' => $jeux, 'error' => ' Nous avons trouver aucun jeux correspondant à votre recherche!']);
                    }
                    return $this->render('liste_des_jeux/index.html.twig', ['allgames' => $jeux,'total' => $cartService->getTotal(), 'itemsTotal' => $cartService->getTotalItem(), 'form' => $form->createView()]);
                }
                else{
                    $jeux = $jeuxRepository->findAll();
                    if (!$jeux) {
                        throw $this->createNotFoundException(
                            ' Nous avons trouver aucun jeux !'
                        );
                    }
                    return $this->render('liste_des_jeux/index.html.twig', ['allgames' => $jeux,'total' => $cartService->getTotal(),'itemsTotal' => $cartService->getTotalItem(),  'form' => $form->createView()]);
    
                       
                }
         
        }
    
        //Ainsi, bien que cela ne soit pas toujours nécessaire, il est généralement judicieux de 
        //spécifier explicitement l' data_classoption en ajoutant ce qui suit à votre classe de type de formulaire:
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Search::class,
            ]);
        }
    
    }




