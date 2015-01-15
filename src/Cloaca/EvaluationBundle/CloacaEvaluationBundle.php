<?php

namespace Cloaca\EvaluationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class CloacaEvaluationBundle extends Bundle
{
	public function boot()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$platform = $em->getConnection()->getDatabasePlatform();
		
		if(!Type::hasType('tribool'))
		{
			Type::addType('tribool', 'Cloaca\EvaluationBundle\Types\Tribool');
			$platform->registerDoctrineTypeMapping('tribool', 'tribool');
		}

		if(!Type::hasType('actiondegree'))
		{
			Type::addType('actiondegree', 'Cloaca\EvaluationBundle\Types\Actiondegree');
			$platform->registerDoctrineTypeMapping('actiondegree', 'actiondegree');
		}

		if(!Type::hasType('verdictdegree'))
		{
			Type::addType('verdictdegree', 'Cloaca\EvaluationBundle\Types\Verdictdegree');
			$platform->registerDoctrineTypeMapping('verdictdegree', 'verdictdegree');
		}
		
		$platform->registerDoctrineTypeMapping('enum', 'string');
	}
}
