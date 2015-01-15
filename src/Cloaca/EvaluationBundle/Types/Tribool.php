<?php
namespace Cloaca\EvaluationBundle\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class Tribool extends Type
{
	const TRIBOOL = 'tribool';

	const TRIBOOL_UNDETERMINED = 'undetermined';
	const TRIBOOL_NO = 'no';
	const TRIBOOL_YES = 'yes';

	public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return "ENUM('undetermined', 'no', 'yes') COMMENT '(DC2Type:tribool)'";
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		return $value;
	}

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if(!in_array($value, array(
			self::TRIBOOL_UNDETERMINED,
			self::TRIBOOL_NO,
			self::TRIBOOL_YES
		)))
			throw new \InvalidArgumentException("Invalid value " . $value);
		
		return $value;
	}

	public function getName()
	{
		return self::TRIBOOL;
	}
}
