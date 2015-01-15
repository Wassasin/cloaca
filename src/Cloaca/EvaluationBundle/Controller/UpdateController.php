<?php
namespace Cloaca\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Cloaca\EvaluationBundle\Entity\Grade;
use Cloaca\EvaluationBundle\Entity\Course;
use Cloaca\EvaluationBundle\Entity\Evaluation;
use Cloaca\EvaluationBundle\Form\Type\EvaluationType;

function filter($arr, $f)
{
	$result = array();
	
	foreach($arr as $value)
		if($f($value))
			$result[] = $value;
	
	return $result;
}

function map($arr, $f)
{
	$result = array();
	
	foreach($arr as $value)
		$result[] = $f($value);
	
	return $result;
}

function pickOne($arr)
{
	foreach($arr as $value)
		return $value;
	
	return null;
}

function findOne($arr, $f)
{
	foreach($arr as $value)
		if($f($value))
			return $value;
	
	return null;
}

function filterNonPath($arr)
{
	return filter($arr, function($x) {
		return($x != '.' && $x != '..');
	});
}

function recursedir($path, $prefix = '')
{
	$result = array();
	
	foreach(filterNonPath(scandir($path.'/'.$prefix)) as $file)
	{
		$file = $prefix . $file;
		$result[] = $file;

		if(is_dir($path.'/'.$file))
			$result = array_merge($result, recursedir($path, $file.'/'));
	}
	
	return $result;
}

class UpdateController extends Controller
{
	var $root_dir;
	var $web_dir;
	var $data_dir;
	var $new_dir;
	var $old_dir;

	public function __construct()
	{
		$this->root_dir = $this->get('kernel')->getRootDir() . '/../';
		$this->web_dir = $this->root_dir.'web/';
		
		$this->data_dir = 'data/';
		$this->new_dir = $this->data_dir.'new/';
		$this->old_dir = $this->data_dir.'old/';
	}

	private function fetchGrades()
	{
		$grades = array();
		foreach(explode("\n", file_get_contents($this->data_dir.'grades.csv')) as $line)
		{
			if($line == '')
				continue;
			
			$grade = str_getcsv($line);
			$grades[$grade[0]] = $grade;
		}
		
		return $grades;
	}

	private function fetchMapping()
	{
		return array(); // TODO stub
	}
	
	private function fetchNewCourses()
	{
		return array('IBC022'); // TODO stub
	}
	
	private function fetchEvals($mapping, $new_courses)
	{
		$mappingf = function($x) use ($mapping)
		{
			foreach($mapping as $key => $value)
				if($x == $key)
					return $value;
			
			return $x;
		};

		$evals = map(filterNonPath(scandir($this->web_dir.$this->new_dir)), function($dir) use ($mappingf, $new_courses) {
			$files = recursedir($this->web_dir.$this->new_dir.$dir);
			$old_files = recursedir($this->old_dir);
			
			$paths = map($files, function($x) use ($dir) { return $this->new_dir.$dir.'/'.$x; });
			$old_paths = map($old_files, function($x) { return $this->old_dir.$x; });

			list($code, $name) = findOne(
				map($files, function($x) {
					if(!preg_match('%Studentenevaluaties/.*_(?:NWI-)?([^\\_^-]+)_(.+)_([0-9]+)_(.+)\\.xls%', $x, $matches))
						return NULL;
					
					return array($matches[1], $matches[4]);
				}),
				function($x) { return($x != NULL); }
			);

			$old_code = $mappingf($code);

			if(FALSE === preg_match('%([A-Z]+)-(.+)%', $dir, $code_matches))
				throw new Exception("Directory not in format NWI-ABCXXX");

			return array(
				'dir' => $dir,
				'code' => $code_matches[2],
				'name' => $name,
				'is_new' => in_array($code, $new_courses)
			);
		});
		
		return $evals;
	}
	
	private function pushData($evals, $grades)
	{
		foreach($evals as $course)
		{
			$courseObj = $em->getRepository('Cloaca\EvaluationBundle\Entity\Course')
				->findOneByCode($course['code']);
			
			if(!$courseObj)
			{
				$courseObj = new Course();
				$em->persist($courseObj);
			}
			
			$courseObj->setCode($course['code']);
			$courseObj->setDirectory($course['dir']);
			$courseObj->setName($course['name']);
			$courseObj->setIsNew($course['is_new']);
			
			if(array_key_exists($course['code'], $grades))
			{
				if(!array_key_exists($course['code'], $grades))
					throw new Exception('No grades for course '.$course['code']);
				
				$grade = $grades[$course['code']];
				
				{
					$gradeObj = new Grade();
					$gradeObj->setAverage($grade[1]);
					$gradeObj->setStddev($grade[2]);
					$gradeObj->setNumber($grade[3]);
				
					$em->persist($gradeObj);
					$courseObj->setNewGrade($gradeObj);
				}
				
				{
					$gradeObj = new Grade();
					$gradeObj->setAverage($grade[4]);
					$gradeObj->setStddev($grade[5]);
					$gradeObj->setNumber($grade[6]);
				
					$em->persist($gradeObj);
					$courseObj->setNewGradeTeacher($gradeObj);
				}
				
				if((int)$grade[9] > 0)
				{
					$gradeObj = new Grade();
					$gradeObj->setAverage($grade[7]);
					$gradeObj->setStddev($grade[8]);
					$gradeObj->setNumber($grade[9]);
				
					$em->persist($gradeObj);
					$courseObj->setOldGrade($gradeObj);
				}
			}
			
			$em->persist($courseObj);
			
			$em->flush();
		}
	}

	public function updateAction()
	{
		$em = $this->getDoctrine()->getManager();
	
		$mapping = $this->fetchMapping();
		$new_courses = $this->fetchNewCourses();
		
		$evals = $this->fetchEvals($mapping, $new_courses);

		$grades = $this->fetchGrades();
		
		$this->pushData($evals, $grades);

		return $this->render('CloacaEvaluationBundle:Default:update.html.twig', array());
	}
}
