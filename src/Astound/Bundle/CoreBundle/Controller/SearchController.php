<?php

/*
 *  Override of Sylius class by the same name
 */

namespace Astound\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function showResultAction(Request $request)
    {

        $params = $this->get('request')->query->all();
        
        if( empty($params['uniSearchTerm']) ){
            $this->getRequest()->getSession()->getFlashBag()->add('warning', "No search terms recieved.");
            return $this->directToReferer();
        }
        if( is_numeric($params['uniSearchTerm']) ){
            // Phone or Order Number
            if( strlen($params['uniSearchTerm']) == 10 ){
                // phone number lookup
                // TODO Impliment Phone Numbers!
            } else {
                // order ID lookup
                $orderNumber = str_pad($params['uniSearchTerm'],9,"0",STR_PAD_LEFT);

                $order = $this->get('sylius.repository.order')->findByNumber($orderNumber);
                if( count($order) == 1 ){
                    return  $this->redirect($this->generateUrl('sylius_backend_order_show', array(
                        'id' => $order[0]->getId() 
                    )));
                } else {
                    return  $this->redirect($this->generateUrl('sylius_backend_order_index', array(
                        'criteria[number]' => $orderNumber,
                        'sorting[createdAt]' => 'desc' 
                    ))); 
                }
            }
        } else {
            // Lastname lookup
            $lastName = $params['uniSearchTerm'];

            $user = $this->get('sylius.repository.user')->findBylast_name($lastName);
            if( count($user) == 1 ){
                return  $this->redirect($this->generateUrl('sylius_backend_user_show', array(
                    'id' => $user[0]->getId() 
                )));           
            } else {
                 return  $this->redirect($this->generateUrl('sylius_backend_user_index', array(
                    'criteria[query]' => $lastName,
                    'sorting[createdAt]'=> 'desc' 
                )));                
            }
        }
    }

    public function directToReferer()
    {
        // $ruta = $this->getRefererRoute();

        // $locale = $request->get('_locale');
        // $url = $this->get('router')->generate($ruta, array('_locale' => $locale));

        // return $this->redirect($url);
        return $this->redirect($this->getRefererRoute());
    }

    public function getRefererRoute()
    {
        $request = $this->getRequest();

        //look for the referer route
        $referer = $request->headers->get('referer');
        // die("Referer: ".var_dump($referer));
        // $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
        // $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

        // $matcher = $this->get('router')->getMatcher();
        // $parameters = $matcher->match($lastPath);
        // $route = $parameters['_route'];

        // return $route;
        return $referer;
    }
}
