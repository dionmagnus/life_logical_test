<?php
namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ProductType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CatalogController extends AbstractFOSRestController {

    /**
     * @Route("/catalog", name="get_catalog", methods={"GET"})
     */
    public function getCatalog(Request $request) {        
        
        
        //sorting
        $sort = $request->query->get("sort");
        if (!in_array($sort, array("price", "orders", "balance")))
            $sort = "id";
        
        $sortBy = $request->query->get("sort_by");
        if (!in_array($sortBy, array("ASC", "DESC")))
            $sortBy = "ASC";

        
        //paging
        $page = (int) $request->query->get("page") ?? 1;
        if ($page < 1)
            $page = 1;


        $repository = $this->getDoctrine()->getRepository(Product::class);
        $title = $request->query->get("title");

        $data = ($title) ? $repository->findByTitle($title, $sort, $sortBy, $page - 1) 
        : $repository->findBy(array(), array($sort => $sortBy), 10, 10 * ($page - 1));

        $itemsCount = $repository->getItemsCount($title);
        $view = $this->view(array("items" => $data, "page" => $page, "per_page" => 10, "count" => $itemsCount), 200);
    
        return $view;
    }

    
    /**
     * @Route("/catalog", name="post_catalog", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function postCatalog(Request $request) {
        $defaultProduct = new Product();      
        $form = $this->createForm(ProductType::class, $defaultProduct);

        $productData = \json_decode($request->getContent(), true);           
        $form->submit($productData);
        //$form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = $form->getData();
            $newProduct->setOrders(0);
            
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($newProduct);
            $entityManager->flush();

            return $this->view($newProduct, 201);
        } else
            return $this->view($form);
    }

    /**
     * @Route("/catalog/{id<\d+>}", name="put_catalog", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function putCatalog($id, Request $request) {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);        
        
        if (!$product)
            throw $this->createNotFoundException("There is no such product");            

        $form = $this->createForm(ProductType::class, $product);

        $productData = \json_decode($request->getContent(), true);           
        $form->submit($productData);
        //$form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedProduct = $form->getData();

            //$product->setDescription($updatedProduct->setDescription());
            //$product->setBa

            $em = $this->getDoctrine()->getManager();
            //$em->persist($updatedProduct);
            $em->flush();


            return $this->view($product, 200);
        } else
            return $this->view($form);
    }

    /**
     * @Route("/catalog/{id<\d+>}", name="delete_catalog", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCatalog($id) {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $product = $repository->find($id);

        if (!$product)
            throw $this->createNotFoundException("There is no such product");

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->view($product, 200);
    }
}
