<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SyliusContactFormPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;

final class DebugContext implements Context, MinkAwareContext
{
    private ?Mink $mink = null;

    public function setMink(Mink $mink): void
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters): void
    {
    }

    /**
     * @AfterStep
     */
    public function reportFailureDetail(AfterStepScope $afterStepScope): void
    {
        if (!$afterStepScope->getTestResult()->isPassed()) {
            throw new \RuntimeException(
                sprintf(
                    'Test failed:%s---%s%s%s---%s',
                    \PHP_EOL,
                    \PHP_EOL,
                    $this->getVisitedPageContent($afterStepScope),
                    \PHP_EOL,
                    \PHP_EOL,
                ),
            );
        }
    }

    private function getVisitedPageContent(AfterStepScope $afterStepScope): string
    {
        try {
            return $this->getMink()->getSession()->getPage()->getHtml();
        } catch (\Exception $exception) {
            return $afterStepScope->getStep()->getText();
        }
    }

    private function getMink(): Mink
    {
        assert($this->mink instanceof Mink);

        return $this->mink;
    }
}
