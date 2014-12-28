<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class RouteController extends AbstractRestfulController{

	/*protected $collectionOptions = array('GET', 'POST');
	protected $resourceOptions = array('GET', 'PUT', 'DELETE');

	protected function _getOptions(){
		if($this->params()->fromRoute('id', false)){
			return $this->resourceOptions;
		}

		return $this->collectionOptions;
	}

	public function options(){
		$response = $this->getResponse();
		$response->getHeaders()->addHeaderLine('Allow', implode(',', $this->_getOptions()));

		return $response;
	}

	public function setEventManager(EventManagerInterface $events){
		$this->events = $events;
		$events->attach('dispatch', array($this, 'checkOptions'), 10);

	}

	public function checkOptions($e){
		if(in_array($e->getRequest()->getMethod(), $this->_getOptions())){
			return;
		}

		$response = $this->getResponse();
		$response->setStatusCode(405);
		return $response;
	}*/

	public function getResponseWithHeader(){
		$response = $this->getResponse();
		$response->getHeaders()
			->addHeaderLine('Access-Control-Allow-Origin', '*')
			->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');
         
        return $response;
    }

	public function get($origin, $destination){
		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$route = $em->getRepository('Application\Entity\RoutesEntity')
			->findOneBy(array('id' => 1));
var_dump($route);exit();
		return new JsonModel(array('status' => true, 'msg' => 'GET Single Item', 'data' => $route));
    }

	public function getList(){
		$origin = $this->params()->fromRoute('origin');
		$destination = $this->params()->fromRoute('destination');

		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$query = $em->createQuery("SELECT sr.route_id FROM Application\Entity\StopRoutesEntity sr WHERE sr.stop_id = (SELECT s.id FROM Application\Entity\StopsEntity s WHERE s.stop ='$origin')");
		$originRoutes = $query->execute();
		$originRouteIds = array();
		foreach($originRoutes as $r){
			$originRouteIds[] = $r['route_id'];
		}
		
		$query = $em->createQuery("SELECT sr.route_id FROM Application\Entity\StopRoutesEntity sr WHERE sr.stop_id = (SELECT s.id FROM Application\Entity\StopsEntity s WHERE s.stop ='$destination')");
		$destinRoutes = $query->execute();
		$destinRouteIds = array();
		foreach($destinRoutes as $r){
			$destinRouteIds[] = $r['route_id'];
		}

		$commonRoutes = array_intersect($originRouteIds, $destinRouteIds);

		//select sr.route_id from stops s, stop_routes sr where s.id = sr.stop_id && s.stop="moratuwa" && sr.route_id IN (select sr.route_id from stops s, stop_routes sr where s.id = sr.stop_id && s.stop="ratmalana")
		/*$query = $em->createQuery("SELECT sr.route_id FROM Application\Entity\StopRoutesEntity sr, Application\Entity\StopsEntity s WHERE s.id = sr.stop_id && s.stop = '$origin' && sr.route_id IN (SELECT sr.route_id FROM Application\Entity\StopRoutesEntity sr, Application\Entity\StopsEntity s WHERE s.id = sr.stop_id && s.stop = '$destination')");
		$routes = $query->execute();
		$routes = array();
		foreach($routes as $r){
			$commonRoutes[] = $r['route_id'];
		}*/

		$routeList = array();
		if(count($commonRoutes) > 0){
			foreach($commonRoutes as $cRoute){
				$query = $em->createQuery("SELECT r.routeOrigin, r.routeDestination, r.stops FROM Application\Entity\RoutesEntity r WHERE r.routeID = '$cRoute'");
				$c = $query->execute();
				$routeList[] = array('route' => $cRoute, 'origin' => $c[0]['routeOrigin'], 'destination' => $c[0]['routeDestination']);
			}
			$data = array('type' => 'SINGLE-ROUTE', 'routes' => $routeList);
		}else{
			foreach($originRouteIds as $oRoute){
				$query = $em->createQuery("SELECT r.routeOrigin, r.routeDestination, r.stops FROM Application\Entity\RoutesEntity r WHERE r.routeID = '$oRoute'");
				$oStops = $query->execute();
				$oStopsArray = explode(',', $oStops[0]['stops']);

				foreach($destinRouteIds as $dRoute){
					$query = $em->createQuery("SELECT r.routeOrigin, r.routeDestination, r.stops FROM Application\Entity\RoutesEntity r WHERE r.routeID = '$dRoute'");
					$dStops = $query->execute();
					$dStopsArray = explode(',', $dStops[0]['stops']);

					$commonStops = array_intersect($oStopsArray, $dStopsArray);
					if(count($commonStops) > 0){
						$stops = array();
						foreach($commonStops as $commonStop){
							$query = $em->createQuery("SELECT s.stop FROM Application\Entity\StopsEntity s WHERE s.id = '$commonStop'");
							$stopRow = $query->execute();
							$stops[] = $stopRow[0]['stop'];
						}
						$routeList[] = array('first' => $oRoute, 'firstOrigin' => $oStops[0]['routeOrigin'], 'firstDestination' => $oStops[0]['routeDestination'], 'interchange' => $stops, 'last' => $dRoute, 'lastOrigin' => $dStops[0]['routeOrigin'], 'lastDestination' => $dStops[0]['routeDestination']);
					}
				}
			}

			$data = array('type' => 'MULTIPLE-ROUTE', 'routes' => $routeList);
		}

		return new JsonModel(array('status' => true, 'msg' => 'GET multiple items', 'data' => $data));
    }

	public function create($data){
		$response = $this->getResponse();
		$response->setStatusCode(201);

		return new JsonModel(array('status' => true, 'msg' => 'POST it worked!'));
	}

	public function delete($id){

	}

	public function deleteList(){

	}

	public function update($id, $data){

	}

	public function replaceList($data){

	}

	public function indexAction(){
		return new JsonModel(array('Welcome to bus routes API!'));
	}

}
