<?php

declare(strict_types=1);

namespace ThreeBRS\SyliusContactFormPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ThreeBRSSyliusContactFormPlugin extends Bundle
{
    use SyliusPluginTrait;

    /**
     * Affects loading of files from the bundle's config directory and resolving real path from logic path, @see \Symfony\Component\HttpKernel\Kernel::locateResource
     * and @see \Symfony\Bundle\TwigBundle\TemplateIterator::getIterator
     */
     public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
