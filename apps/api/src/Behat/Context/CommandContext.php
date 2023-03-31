<?php declare(strict_types=1);

namespace DumpIt\Api\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandContext implements Context
{
    private Application $application;

    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
    }

    /**
     * @Given I execute the command :command
     */
    public function iAmLoggedAs(string $command)
    {
        $input = new ArrayInput(['command' => $command]);

        $output = new BufferedOutput(Output::VERBOSITY_QUIET);

        $this->application->setAutoExit(false);

        $this->application->run($input, $output);
    }
}
