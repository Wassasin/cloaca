<?php
namespace Cloaca\EvaluationBundle\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Actiondegree extends Type
{
	const ACTIONDEGREE = 'actiondegree';

	const ACTIONDEGREE_1 = '1';
	const ACTIONDEGREE_2 = '2';

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return "ENUM('1', '2') COMMENT '(DC2Type:actiondegree)'";
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		return $value;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if(!in_array($value, array(
			self::ACTIONDEGREE_1,
			self::ACTIONDEGREE_2
		)))
			throw new \InvalidArgumentException("Invalid value " . $value);
		
		return $value;
	}

	public function getName()
	{
		return self::ACTIONDEGREE;
	}
}
