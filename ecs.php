<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLineConstructorParamFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests/Behat', __DIR__ . '/ecs.php']);

    $ecsConfig->import('vendor/sylius-labs/coding-standard/ecs.php');
    $ecsConfig->rule(StandaloneLineConstructorParamFixer::class);
    $ecsConfig->rule(LineLengthFixer::class);

    $ecsConfig->skip([
        __DIR__ . '/tests/Application/var/',
        VisibilityRequiredFixer::class => ['*Spec.php'],
    ]);
};
