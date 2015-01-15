<?php
namespace Cloaca\EvaluationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Cloaca\EvaluationBundle\Types\Actiondegree;

class ActiondegreeType extends AbstractType
{
	public function getDefaultOptions(array $options)
	{
		return array(
			'choices' => array(
				Actiondegree::ACTIONDEGREE_1 => '1: Nee, geen actie nodig.',
				Actiondegree::ACTIONDEGREE_2 => '2: Ja, actie nodig.'
			),
		);
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'actiondegree';
	}
}
?>
