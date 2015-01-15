<?php
namespace Cloaca\EvaluationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EvaluationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('summary', null, array('label' => 'Interne samenvatting. (wordt niet gepubliceerd)'));

		$builder->add('verdict', new VerdictdegreeType(), array('label' => 'Algemeen oordeel n.a.v. documentatie.'));
		$builder->add('verdict_justification', null, array('label' => 'Verantwoording t.a.v. oordeel documentatie. (wordt gepubliceerd)'));

		$builder->add('action', new ActiondegreeType(), array('label' => 'De cursus heeft op korte termijn actie nodig van de onderwijsdirectie.'));
		$builder->add('action_justification', null, array('label' => 'Verantwoording t.a.v. de vereiste actie door de onderwijsdirectie. (wordt gepubliceerd)'));

		$builder->add('processed_goals', new TriboolType(), array('label' => 'De actiepunten die vorig jaar zijn voorgenomen zijn afdoende verwerkt.'));
		$builder->add('processed_feedback', new TriboolType(), array('label' => 'De feedback uit de studentenevaluaties is afdoende verwerkt.'));
		$builder->add('students_ok', new TriboolType(), array('label' => 'De studenten geven aan in het algemeen tevreden te zijn over de cursus.'));
		$builder->add('procedural_ok', new TriboolType(), array('label' => 'Procedureel verliep de cursus goed. (tentaminering, roostering, evaluaties etc.)'));
		$builder->add('misc_justification', null, array('label' => 'Interne verantwoording overige punten. (wordt niet gepubliceerd)'));
	}

	public function getName()
	{
		return 'evaluation';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Cloaca\EvaluationBundle\Entity\Evaluation'
		));
	}
}
?>
