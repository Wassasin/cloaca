<?php
namespace Cloaca\EvaluationBundle\Twig;

use Cloaca\EvaluationBundle\Types\Tribool;

class TriboolExtension extends \Twig_Extension
{
	public function getFilters()
	{
		return array(
		    new \Twig_SimpleFilter('tribool', array($this, 'triboolFilter'), array('is_safe' => array('html'))),
		);
	}

	public function triboolFilter($x, $mode='html', $bad='no')
	{
		static $strong_map = array(
			'na' => Tribool::TRIBOOL_UNDETERMINED,
			'no' => Tribool::TRIBOOL_NO,
			'yes' => Tribool::TRIBOOL_YES
		);
		
		$result = null;
		switch($x)
		{
		case Tribool::TRIBOOL_UNDETERMINED:
			$result = 'n.v.t.';
			break;
		case Tribool::TRIBOOL_NO:
			$result = 'nee';
			break;
		case Tribool::TRIBOOL_YES:
			$result = 'ja';
			break;
		}
		
		if($strong_map[$bad] == $x)
		{
			switch($mode)
			{
			case 'html':
				return '<strong>'.$result.'</strong>';
			case 'latex':
				return '\\textbf{'.$result.'}';
			}
		}
		else
		{
			return $result;
		}
	}

	public function getName()
	{
		return 'tribool_extension';
	}
}
?>
