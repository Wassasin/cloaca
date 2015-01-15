<?php
namespace Cloaca\EvaluationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Cloaca\EvaluationBundle\Types\Tribool;

class TriboolType extends AbstractType
{
	public function getDefaultOptions(array $options)
	{
		return array(
			'choices' => array(
				Tribool::TRIBOOL_UNDETERMINED => 'N.v.t.',
				Tribool::TRIBOOL_NO => 'Nee',
				Tribool::TRIBOOL_YES => 'Ja',
			),
		);
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'tribool';
	}
}
?>
