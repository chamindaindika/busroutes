<?php
/**
 * Description of Routes Entity
 *
 * @package		Application\Entity
 *
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity @ORM\Table(name="stops") */
class StopsEntity{

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** @ORM\Column(type="string") */
	private $stop;

	/** @ORM\Column(type="string") */
	private $type;

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

		$array['id'] = isset($this->id) ? $this->id : NULL;
		$array['stop'] = isset($this->stop) ? $this->stop : NULL;
		$array['type'] = isset($this->type) ? $this->type : NULL;

		return $array;
	}

}
