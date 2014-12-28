<?php
/**
 * Description of Routes Entity
 *
 * @package		Application\Entity
 *
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity @ORM\Table(name="stop_routes") */
class StopRoutesEntity{

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $stop_id;

	/** @ORM\Column(type="integer") */
	private $route_id;

	/**
	 * Setter of the class.
	 * @param $key attribute name
	 * @param $val value of the attribute
	 * @return void
	 */
	public function __set($key, $val){
		$this->$key = $val;
	}

	/**
	 * Getter of the class.
	 * @param mixed $key attribute name
	 * @return mixed
	 */
	public function __get($key){
		return $this->$key;
	}

	/**
	 * Converts the attributes of this class to an associate array.
	 * @return array
	 */
	public function getObjectAsArray(){
		$array = array();

		$array['stop_id'] = isset($this->stop_id) ? $this->stop_id : NULL;
		$array['route_id'] = isset($this->route_id) ? $this->route_id : NULL;

		return $array;
	}

}
