<?php

namespace App\DBAL\Platforms;

use Doctrine\DBAL\Platforms\MySQLPlatform as BaseMySQLPlatform;

class CustomMySQLPlatform extends BaseMySQLPlatform
{
    protected function initializeDoctrineTypeMappings(): void
    {
        parent::initializeDoctrineTypeMappings();
        $this->doctrineTypeMapping['enum'] = 'string';
    }
}

