<?php
namespace Cloaca\EvaluationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Cloaca\EvaluationBundle\Types\Verdictdegree;

class VerdictdegreeType extends AbstractType
{
	public function getDefaultOptions(array $options)
	{
		return array(
			'choices' => array(
				Verdictdegree::VERDICTDEGREE_A => 'A: De docentevaluatie is goed, en de OLC heeft daar verder geen commentaar op.',
				Verdictdegree::VERDICTDEGREE_B => 'B: De docentevaluatie is voldoende, maar de OLC heeft wel commentaar op verwerking feedback, verwerking verbeterplannen en nieuwe verbeterplannen.',
				Verdictdegree::VERDICTDEGREE_C => 'C: De docentevaluatie is onvoldoende.'
			),
		);
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'verdictdegree';
	}
}
?>
