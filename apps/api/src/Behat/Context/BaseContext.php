<?php declare(strict_types=1);

namespace DumpIt\Api\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext implements Context
{
    private Application $application;

    private UserContext $userContext;

    public function __construct(KernelInterface $kernel, UserContext $userContext)
    {
        $this->application = new Application($kernel);
        $this->userContext = $userContext;
    }

    /**
     * @BeforeScenario
     */
    public function resetScenario()
    {
        $this->loadFixtures();
    }

    /**
     * @BeforeStep
     */
    public function addHeaders(BeforeStepScope $step)
    {
        if (in_array($step->getStep()->getKeyword(), ['When', 'And'])) {
            $this->userContext->addHeaders();
        }
    }
    
    private function loadFixtures(): void
    {
        $input = new ArrayInput(['command' => 'do:fix:lo']);
        $input->setInteractive(false);

        $output = new BufferedOutput(Output::VERBOSITY_QUIET);

        $this->application->setAutoExit(false);

        $this->application->run($input, $output);
    }
}
