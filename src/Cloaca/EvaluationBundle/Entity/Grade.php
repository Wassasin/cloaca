<?php
namespace Cloaca\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Cloaca\EvaluationBundle\Entity\Course;

/**
 * @ORM\Entity
 * @ORM\Table(name="grade")
 */
class Grade
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	
	/**
	* @ORM\Column(type="decimal", precision=10, scale=1)
	*/
	protected $average;
	
	/**
	* @ORM\Column(type="decimal", precision=10, scale=1)
	*/
	protected $stddev;
	
	/**
	* @ORM\Column(type="integer")
	*/
	protected $number;

	/**
	* Get id
	*
	* @return integer 
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Set average
	*
	* @param float $average
	* @return Grade
	*/
	public function setAverage($average)
	{
		$this->average = $average;

		return $this;
	}

	/**
	* Get average
	*
	* @return float 
	*/
	public function getAverage()
	{
		return $this->average;
	}

	/**
	* Set stddev
	*
	* @param float $stddev
	* @return Grade
	*/
	public function setStddev($stddev)
	{
		$this->stddev = $stddev;

		return $this;
	}

	/**
	* Get stddev
	*
	* @return float 
	*/
	public function getStddev()
	{
		return $this->stddev;
	}

	/**
	* Set number
	*
	* @param float $number
	* @return Grade
	*/
	public function setNumber($number)
	{
		$this->number = $number;

		return $this;
	}

	/**
	* Get number
	*
	* @return float 
	*/
	public function getNumber()
	{
		return $this->number;
	}
}
