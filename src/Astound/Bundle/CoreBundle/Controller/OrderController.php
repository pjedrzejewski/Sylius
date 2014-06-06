<?php

/*
 *  Override of Sylius class by the same name
 */

namespace Astound\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class OrderController extends Controller
{

    private function getFormFactory()
    {
        return $this->container->get('form.factory');
    }

    public function newOrderAction(Request $request)
    {

        $templating = $this->container->get('templating');
      
        $form = $this->getFormFactory()->
        createNamed('criteria', 'sylius_product_filter', $request->query->get('criteria'));

        $content = $templating->render(
            'AstoundWebBundle:Backend/Order:create.html.twig',
            array('form' => $form->createView() )
        );
        
        return new Response($content);
    }   

    public function showAddButtonAction(Request $request)
    {

        $params = $this->get('request')->query->all();

        if( empty($params['name']) and empty($params['sku']) ){
            $arr = array( 
                'fail' => "TRUE", 
                'message' => "No search terms entered.  Enter the name or sku of product to add it to this order." );
           
        } else {
            $repository = $this->container->get('sylius.repository.product');
            $products = $repository->findNameOrSku($params);

            //die(var_dump($products));

            if( $products == array() ){
                $name = (empty($params['name'])) ? "any" : $params['name'];
                $sku = (empty($params['sku'])) ? "any" : $params['sku'];
                $arr = array( 
                    'fail' => "TRUE", 
                    'message' => "No products found with partial matching of name: ".$name." and sku: ".$sku );
            } else {
                foreach( $products as $key => $product ){
                    $arr[] = array(
                        'productID'     => $product->getId(),
                        'sku'           => $product->getSku() 
                    );
                }
            }
        }
        $response = new Response(json_encode($arr));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
