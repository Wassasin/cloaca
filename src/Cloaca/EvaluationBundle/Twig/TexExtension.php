<?php
namespace Cloaca\EvaluationBundle\Twig;

use Cloaca\EvaluationBundle\Types\Tribool;

class TexExtension extends \Twig_Extension
{
	public function getFilters()
	{
		return array(
		    new \Twig_SimpleFilter('tex', array($this, 'texFilter'), array('is_safe' => array('html', 'tex'))),
		);
	}

	public function texFilter($str)
	{
		return str_replace('&', '\\&', $str);
	}

	public function getName()
	{
		return 'tex_extension';
	}
}
?>
