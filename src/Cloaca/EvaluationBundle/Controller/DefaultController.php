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

class DefaultController extends Controller
{
	public function evaluationAction($course_id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
	
		$course = $this->getDoctrine()
			->getRepository('Cloaca\EvaluationBundle\Entity\Course')
			->find($course_id);

		if(!$course)
			throw $this->createNotFoundException(
				'No course found for id '.$course_id
			);
			
		$evaluation = $this->getDoctrine()
			->getRepository('Cloaca\EvaluationBundle\Entity\Evaluation')
			->findOneByCourse($course_id);
	
		if(!$evaluation)
			$evaluation = new Evaluation($course);
		
		$form = $this->createForm(new EvaluationType(), $evaluation);
		
		if($request->isMethod('POST'))
		{
			$form->bind($request);
		
			if($form->isValid())
			{
				$em->persist($evaluation);
				$em->flush();

				return $this->redirect($this->generateUrl(
					'cloaca_evaluation_evaluation_success',
					array('course_id' => $course_id)
				));
			}
		}
		
		return $this->render('CloacaEvaluationBundle:Default:evaluation.html.twig', array(
			'form' => $form->createView(),
			'course' => $course
		));
	}

	public function indexAction()
	{
		$courses = $this->getDoctrine()
			->getRepository('Cloaca\EvaluationBundle\Entity\Course')
			->findAll();
	
		return $this->render('CloacaEvaluationBundle:Default:index.html.twig', array('courses' => $courses));
	}
	
	public function evaluationSuccessAction($course_id)
	{
		return $this->render('CloacaEvaluationBundle:Default:evaluation_success.html.twig', array('course_id' => $course_id));
	}
	
	public function evaluationPreviewAction($course_id)
	{
		$em = $this->getDoctrine()->getManager();
	
		$course = $this->getDoctrine()
			->getRepository('Cloaca\EvaluationBundle\Entity\Course')
			->find($course_id);
		
		$evaluation = $course->getEvaluation();
		$conclusion = $evaluation->getConclusion();
	
		return $this->render('CloacaEvaluationBundle:Default:evaluation_preview.html.twig', array(
			'course' => $course,
			'evaluation' => $evaluation,
			'conclusion' => $conclusion
		));
	}
	
	public function updateAction()
	{
		$em = $this->getDoctrine()->getManager();
	
		$root_dir = $this->get('kernel')->getRootDir() . '/../';
		$web_dir = $root_dir.'web/';
		
		$data_dir = 'data/';
		$new_dir = $data_dir.'new/';
		$old_dir = $data_dir.'old/';

		$mapping = array();
		$new_courses = array('IBC022');
		
		$mappingf = function($x) use ($mapping)
		{
			foreach($mapping as $key => $value)
				if($x == $key)
					return $value;
			
			return $x;
		};

		$evals = map(filterNonPath(scandir($web_dir.$new_dir)), function($dir) use ($web_dir, $new_dir, $old_dir, $mappingf, $new_courses) {
			$files = recursedir($web_dir.$new_dir.$dir);
			$old_files = recursedir($old_dir);
			
			$paths = map($files, function($x) use ($new_dir, $dir) { return $new_dir.$dir.'/'.$x; });
			$old_paths = map($old_files, function($x) use ($old_dir) { return $old_dir.$x; });

			list($code, $name) = findOne(
				map($files, function($x) {
					if(!preg_match('%Studentenevaluaties/.*_(?:NWI-)?([^\\_^-]+)_(.+)_([0-9]+)_(.+)\\.xls%', $x, $matches))
						return NULL;
					
					return array($matches[1], $matches[4]);
				}),
				function($x) { return($x != NULL); }
			);

			var_dump($files);
			
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
		
		$evals_old = $evals;
		
		//$evals = filter($evals, function($x) use ($courses) { return(in_array($x['dir'], $courses)); });
		
		$evals_keys = map($evals, function($x) { return($x['dir']); });
		//var_dump(filter($evals_old, function($x) use ($evals_keys) { return(!in_array($x['code'], $evals_keys)); }));
		
		//$evals = filter($evals, function($x) { return($x['docenten_eval'] != NULL); });
		
		$grades = array();
		foreach(explode("\n", file_get_contents($data_dir.'grades.csv')) as $line)
		{
			if($line == '')
				continue;
			
			$grade = str_getcsv($line);
			$grades[$grade[0]] = $grade;
		}
		
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

		return $this->render('CloacaEvaluationBundle:Default:update.html.twig', array());
	}
	
	public function generateAction()
	{
		$em = $this->getDoctrine()->getManager();
	
		$root_dir = $this->get('kernel')->getRootDir() . '/../';
		$generated_dir = $root_dir.'web/data/generated/';
	
		$evaluations = $this->getDoctrine()
			->getRepository('Cloaca\EvaluationBundle\Entity\Evaluation')
			->findAll();
		
		foreach($evaluations as $evaluation)
		{
			$course = $evaluation->getCourse();
			$conclusion = $evaluation->getConclusion();
	
			file_put_contents($generated_dir.$course->getCode().'.tex', $this->render('CloacaEvaluationBundle:Default:evaluation.tex.twig', array(
				'course' => $course,
				'evaluation' => $evaluation,
				'conclusion' => $conclusion,
				'internal' => false
			))->getContent());
		}
		
		return $this->render('CloacaEvaluationBundle:Default:generate.html.twig', array(
			'evaluationsCount' => count($evaluations),
			'evaluations' => $evaluations
		));
	}
}
