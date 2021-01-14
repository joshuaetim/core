<?php

declare(strict_types=1);

// sandbox for testing the PSR-0 Implementation
// https://www.php-fig.org/psr/psr-0/

$classNamePSRStandard = '\App\NiceBoy\Call_Name';

function autoload($className): array 
{
    $originalName = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if($lastSlashPosition = strrpos($originalName, '\\')){
        $namespace = substr($originalName, 0, $lastSlashPosition);
        $className = substr($originalName, $lastSlashPosition + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    return [
        'originalName' => $originalName,
        'className' => $className,
        'namespace' => $namespace,
        'fileName' => $fileName,
    ];
}

print_r(autoload($classNamePSRStandard));