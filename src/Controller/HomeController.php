<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\CommandProduct;
use App\Entity\Product;
use App\Form\CommandType;
use App\Form\ProductType;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ManagerRegistry $managerRegistry, ProductRepository $productRepository, Session $session, CommandRepository $commandRepository): Response
    {

        $add = new Product();

        $form = $this->createForm(ProductType::class, $add);
        $form->handleRequest($request);
        $submitted = $form->isSubmitted();
        $valid = $submitted && $form->isValid();

        if ($valid) {
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($add);
            $entityManager->flush();

            $this->addFlash("success", "Nouveau produit ajouter avec success");
            return $this->redirectToRoute('app_home');
        }

        $panierWithData = [];

        foreach ($session->get('panier', []) as $id => $quantity)
        {
            $panierWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach ($panierWithData as $item)
        {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        $command = new Command();

        $formCommand = $this->createForm(CommandType::class, $command);
        $formCommand->handleRequest($request);
        $submitted = $formCommand->isSubmitted();
        $valid = $submitted && $formCommand->isValid();

        $tva = round($total * 20) / 100;

        $ttc = $total + $tva;
        if ($valid) {

            $command->setCreatedAt(new \DateTimeImmutable('now'));
            $command->setNumeroCommand("R".mt_rand(1500, 10000000));
            $command->setTotal($ttc);
            $command->setTva($tva);
            $command->setHt($total);
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($command);

            foreach ($panierWithData as $product)
            {
                $extra = new CommandProduct();
                $extra->setCommand($command);
                $extra->setProduct($product['product']);
                $extra->setQuantity($product['quantity']);
                $extra->setPrice( $product['product']->getPrice() * $product['quantity']);
                $entityManager->persist($extra);


                if ($product['quantity'] >= 1)
                {
                    $session->remove('panier', []);
                }
            }
            $entityManager->flush();

            $this->addFlash("success", "Nouveau produit ajouter avec success");
            return $this->redirectToRoute('app_home');
        }



        return $this->renderForm('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'add_product' => $form,
            'product' => $productRepository->findAll(),
            'items' => $panierWithData,
            'total' => $total,
            'tva' => $tva,
            'ttc' => $ttc,
            'commands' => $commandRepository->findAll(),
            'form_command' => $formCommand
        ]);
    }

    /**
     * Ajouter un extra au panier pour une reservation d'une salle de réunion
     *
     * @Route("/product-add-{id}", name="cart_add")
     */
    public function addExtrasStandard1($id, Session $session): Response
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id]))
        {
            $panier[$id]++;
        } else
        {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        $this->addFlash("success", "Article ajouter à votre panier");
        return $this->redirectToRoute('app_home' );
    }
}
