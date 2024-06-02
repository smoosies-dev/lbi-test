<?php

namespace App\DBAL\Types;

use App\Enum\Significance;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class SignificanceType extends Type
{
    const NAME = 'significance';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('principal', 'secondaire')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value !== null ? Significance::from($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Significance) {
            return $value->value;
        }

        return null;
    }

    public function getName()
    {
        return self::NAME;
    }
}



