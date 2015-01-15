<?php
namespace Cloaca\EvaluationBundle\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Verdictdegree extends Type
{
	const VERDICTDEGREE = 'verdictdegree';

	const VERDICTDEGREE_A = 'A';
	const VERDICTDEGREE_B = 'B';
	const VERDICTDEGREE_C = 'C';

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return "ENUM('A', 'B', 'C') COMMENT '(DC2Type:verdictdegree)'";
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		return $value;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if(!in_array($value, array(
			self::VERDICTDEGREE_A,
			self::VERDICTDEGREE_B,
			self::VERDICTDEGREE_C
		)))
			throw new \InvalidArgumentException("Invalid value " . $value);
		
		return $value;
	}

	public function getName()
	{
		return self::VERDICTDEGREE;
	}
}
