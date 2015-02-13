<?php
namespace Cloaca\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Cloaca\EvaluationBundle\Entity\Grade;
use Cloaca\EvaluationBundle\Entity\Course;
use Cloaca\EvaluationBundle\Entity\Evaluation;
use Cloaca\EvaluationBundle\Form\Type\EvaluationType;

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
		$postfix = $evaluation->getEnglish() ? '_en' : '';

		return $this->render('CloacaEvaluationBundle:Default:evaluation_preview'.$postfix.'.html.twig', array(
			'course' => $course,
			'evaluation' => $evaluation,
			'conclusion' => $conclusion
		));
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
			$postfix = $evaluation->getEnglish() ? '_en' : '';
	
			file_put_contents($generated_dir.$course->getCode().'.tex', $this->render('CloacaEvaluationBundle:Default:evaluation'.$postfix.'.tex.twig', array(
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
