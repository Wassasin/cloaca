<?php
namespace Cloaca\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Cloaca\EvaluationBundle\Types\Tribool;
use Cloaca\EvaluationBundle\Types\Actiondegree;
use Cloaca\EvaluationBundle\Types\Verdictdegree;

/**
 * @ORM\Entity
 * @ORM\Table(name="evaluation")
 */
class Evaluation
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	
	/**
	* @ORM\OneToOne(targetEntity="Course", inversedBy="evaluation", orphanRemoval=true)
	*/
	protected $course;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $summary;
	
	/**
	* @ORM\Column(type="actiondegree")
	*/
	protected $action;
	
	/**
	* @ORM\Column(type="text", nullable=true)
	*/
	protected $action_justification;
	
	/**
	* @ORM\Column(type="verdictdegree")
	*/
	protected $verdict;
	
	/**
	* @ORM\Column(type="text", nullable=true)
	*/
	protected $verdict_justification;
	
	/**
	* @ORM\Column(type="tribool")
	*/
	protected $processed_goals;
	
	/**
	* @ORM\Column(type="tribool")
	*/
	protected $processed_feedback;
	
	/**
	* @ORM\Column(type="tribool")
	*/
	protected $students_ok;
	
	/**
	* @ORM\Column(type="tribool")
	*/
	protected $procedural_ok;
	
	/**
	* @ORM\Column(type="text", nullable=true)
	*/
	protected $misc_justification; // Do not print this in public version

	public function __construct($course)
	{
		$this->course = $course;
	}

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
	* Set processed_goals
	*
	* @param tribool $processedGoals
	* @return Evaluation
	*/
	public function setProcessedGoals($processedGoals)
	{
		$this->processed_goals = $processedGoals;

		return $this;
	}

	/**
	* Get processed_goals
	*
	* @return tribool 
	*/
	public function getProcessedGoals()
	{
		return $this->processed_goals;
	}

	/**
	* Set processed_feedback
	*
	* @param tribool $processedFeedback
	* @return Evaluation
	*/
	public function setProcessedFeedback($processedFeedback)
	{
		$this->processed_feedback = $processedFeedback;

		return $this;
	}

	/**
	* Get processed_feedback
	*
	* @return tribool 
	*/
	public function getProcessedFeedback()
	{
		return $this->processed_feedback;
	}

	/**
	* Set misc_justification
	*
	* @param string $misc_justification
	* @return Evaluation
	*/
	public function setMiscJustification($misc_justification)
	{
		$this->misc_justification = $misc_justification;

		return $this;
	}

	/**
	* Get misc_justification
	*
	* @return string 
	*/
	public function getMiscJustification()
	{
		return $this->misc_justification;
	}

	/**
	* Set course
	*
	* @param \Cloaca\EvaluationBundle\Entity\Course $course
	* @return Evaluation
	*/
	public function setCourse(\Cloaca\EvaluationBundle\Entity\Course $course = null)
	{
		$this->course = $course;

		return $this;
	}

	/**
	* Get course
	*
	* @return \Cloaca\EvaluationBundle\Entity\Course 
	*/
	public function getCourse()
	{
		return $this->course;
	}

	/**
	* Set summary
	*
	* @param string $summary
	* @return Evaluation
	*/
	public function setSummary($summary)
	{
		$this->summary = $summary;

		return $this;
	}

	/**
	* Get summary
	*
	* @return string 
	*/
	public function getSummary()
	{
		return $this->summary;
	}

	/**
	* Set students_ok
	*
	* @param tribool $studentsOk
	* @return Evaluation
	*/
	public function setStudentsOk($studentsOk)
	{
		$this->students_ok = $studentsOk;

		return $this;
	}

	/**
	* Get students_ok
	*
	* @return tribool 
	*/
	public function getStudentsOk()
	{
		return $this->students_ok;
	}

	/**
	* Set procedural_ok
	*
	* @param tribool $proceduralOk
	* @return Evaluation
	*/
	public function setProceduralOk($proceduralOk)
	{
		$this->procedural_ok = $proceduralOk;

		return $this;
	}

	/**
	* Get procedural_ok
	*
	* @return tribool 
	*/
	public function getProceduralOk()
	{
		return $this->procedural_ok;
	}

	/**
	* Set action
	*
	* @param actiondegree $action
	* @return Evaluation
	*/
	public function setAction($action)
	{
		$this->action = $action;

		return $this;
	}

	/**
	* Get action
	*
	* @return actiondegree 
	*/
	public function getAction()
	{
		return $this->action;
	}

	/**
	* Set action_justification
	*
	* @param string $action_justification
	* @return Evaluation
	*/
	public function setActionJustification($action_justification)
	{
		$this->action_justification = $action_justification;

		return $this;
	}

	/**
	* Get action_justification
	*
	* @return string 
	*/
	public function getActionJustification()
	{
		return $this->action_justification;
	}
	
	/**
	* Set verdict
	*
	* @param verdictdegree $verdict
	* @return Evaluation
	*/
	public function setVerdict($verdict)
	{
		$this->verdict = $verdict;

		return $this;
	}

	/**
	* Get verdict
	*
	* @return verdictdegree 
	*/
	public function getVerdict()
	{
		return $this->verdict;
	}

	/**
	* Set verdict_justification
	*
	* @param string $verdict_justification
	* @return Evaluation
	*/
	public function setVerdictJustification($verdict_justification)
	{
		$this->verdict_justification = $verdict_justification;

		return $this;
	}

	/**
	* Get verdict_justification
	*
	* @return string 
	*/
	public function getVerdictJustification()
	{
		return $this->verdict_justification;
	}
	
	public function getConclusion()
	{
		$has_all_files = (
			count($this->course->getOldTeacherEvals()) > 0 &&
			count($this->course->getNewTeacherEvals()) > 0 &&
			count($this->course->getNewStudentEvals()) > 0
		);

		$rating = $this->getVerdict() . $this->getAction();
		
		$dossier_acceptable = !(
			$this->processed_goals == Tribool::TRIBOOL_NO ||
			$this->processed_feedback == Tribool::TRIBOOL_NO
		);
		
		$owdir_scrutiny_advised = (
			$this->processed_goals == Tribool::TRIBOOL_NO ||
			$this->processed_feedback == Tribool::TRIBOOL_NO ||
			$this->students_ok == Tribool::TRIBOOL_NO ||
			$this->procedural_ok == Tribool::TRIBOOL_NO
		);
		
		return array(
			'has_all_files' => $has_all_files,
			'dossier_acceptable' => $dossier_acceptable,
			'owdir_scrutiny_advised' => $owdir_scrutiny_advised,
			'rating' => $rating
		);
	}
}
