<?php
namespace Cloaca\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Cloaca\EvaluationBundle\Entity\Evaluation;
use Cloaca\EvaluationBundle\Entity\Grade;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $code;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $directory;
	
	/**
	* @ORM\Column(type="string", nullable=true)
	*/
	protected $name;

	/**
	* @ORM\OneToOne(targetEntity="Evaluation", mappedBy="course", orphanRemoval=true)
	**/
	protected $evaluation;
	
	/**
	* @ORM\Column(type="boolean")
	*/
	protected $is_new;
	
	/**
	* @ORM\OneToOne(targetEntity="Grade", orphanRemoval=true)
	*/
	protected $new_grade;
	
	/**
	* @ORM\OneToOne(targetEntity="Grade", orphanRemoval=true)
	*/
	protected $new_grade_teacher;
	
	/**
	* @ORM\OneToOne(targetEntity="Grade", orphanRemoval=true)
	*/
	protected $old_grade;

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
	* Set code
	*
	* @param string $code
	* @return Course
	*/
	public function setCode($code)
	{
		$this->code = $code;

		return $this;
	}

	/**
	* Get code
	*
	* @return string 
	*/
	public function getCode()
	{
		return $this->code;
	}
	
	/**
	* Set directory
	*
	* @param string $directory
	* @return Course
	*/
	public function setDirectory($directory)
	{
		$this->directory = $directory;

		return $this;
	}

	/**
	* Get directory
	*
	* @return string 
	*/
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	* Set name
	*
	* @param string $name
	* @return Course
	*/
	public function setName($name)
	{
		$this->name = $name;
		
		return $this;
	}

	/**
	* Get name
	*
	* @return string 
	*/
	public function getName()
	{
		return $this->name;
	}

	/**
	* Set evaluation
	*
	* @param \Cloaca\EvaluationBundle\Entity\Evaluation $evaluation
	* @return Course
	*/
	public function setEvaluation(\Cloaca\EvaluationBundle\Entity\Evaluation $evaluation = null)
	{
		$this->evaluation = $evaluation;

		return $this;
	}

	/**
	* Get evaluation
	*
	* @return \Cloaca\EvaluationBundle\Entity\Evaluation 
	*/
	public function getEvaluation()
	{
		return $this->evaluation;
	}

	/**
	* Get old_teacher_eval
	*
	* @return string 
	*/
	public function getOldTeacherEvals()
	{
        $basedir = __DIR__.'/../../../../web/';
	    $dir = 'data/old/'.$this->getDirectory().'/Docentenevaluatie/';
	    $truedir = $basedir.$dir;
	    
	    if(!file_exists($truedir))
	        return array();
	    
	    $result = array();
	    foreach(scandir($truedir) as $file)
	        if($file != '.' && $file != '..')
	            $result[] = $dir.$file;
	            
	    return $result;
	}

	/**
	* Get list of new_teacher_evals
	*
	* @return array 
	*/
	public function getNewTeacherEvals()
	{
	    $basedir = __DIR__.'/../../../../web/';
	    $dir = 'data/new/'.$this->getDirectory().'/Docentenevaluatie/';
	    $truedir = $basedir.$dir;
	    
	    if(!file_exists($truedir))
	        return array();
	    
	    $result = array();
	    foreach(scandir($truedir) as $file)
	        if($file != '.' && $file != '..')
	            $result[] = $dir.$file;
	            
	    return $result;
	}

	/**
	* Get new_student_eval
	*
	* @return string 
	*/
	public function getNewStudentEvals()
	{
	    $basedir = __DIR__.'/../../../../web/';
	    $dir = 'data/new/'.$this->getDirectory().'/Studentenevaluaties/';
	    $truedir = $basedir.$dir;
	    
	    if(!file_exists($truedir))
	        return array();
	    
	    $result = array();
	    foreach(scandir($truedir) as $file)
	        if($file != '.' && $file != '..')
	            $result[] = $dir.$file;
	            
	    return $result;
	}

	/**
	* Set is_new
	*
	* @param boolean $isNew
	* @return Course
	*/
	public function setIsNew($isNew)
	{
		$this->is_new = $isNew;

		return $this;
	}

	/**
	* Get is_new
	*
	* @return boolean 
	*/
	public function getIsNew()
	{
		return $this->is_new;
	}

    /**
     * Set new_grade
     *
     * @param \Cloaca\EvaluationBundle\Entity\Grade $newGrade
     * @return Course
     */
    public function setNewGrade(\Cloaca\EvaluationBundle\Entity\Grade $newGrade = null)
    {
        $this->new_grade = $newGrade;
    
        return $this;
    }

    /**
     * Get new_grade
     *
     * @return \Cloaca\EvaluationBundle\Entity\Grade 
     */
    public function getNewGrade()
    {
        return $this->new_grade;
    }

    /**
     * Set new_grade_teacher
     *
     * @param \Cloaca\EvaluationBundle\Entity\Grade $newGradeTeacher
     * @return Course
     */
    public function setNewGradeTeacher(\Cloaca\EvaluationBundle\Entity\Grade $newGradeTeacher = null)
    {
        $this->new_grade_teacher = $newGradeTeacher;
    
        return $this;
    }

    /**
     * Get new_grade_teacher
     *
     * @return \Cloaca\EvaluationBundle\Entity\Grade 
     */
    public function getNewGradeTeacher()
    {
        return $this->new_grade_teacher;
    }

    /**
     * Set old_grade
     *
     * @param \Cloaca\EvaluationBundle\Entity\Grade $oldGrade
     * @return Course
     */
    public function setOldGrade(\Cloaca\EvaluationBundle\Entity\Grade $oldGrade = null)
    {
        $this->old_grade = $oldGrade;
    
        return $this;
    }

    /**
     * Get old_grade
     *
     * @return \Cloaca\EvaluationBundle\Entity\Grade 
     */
    public function getOldGrade()
    {
        return $this->old_grade;
    }
}
