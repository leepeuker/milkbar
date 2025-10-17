<?php declare(strict_types=1);

namespace Milkbar\Application\Command;

use Milkbar\Application\Repository\User;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'user:create',
    description: 'Create a new user.',
    aliases: ['user:create'],
    hidden: false,
)]
class UserCreate extends Command
{
    public function __construct(
        private readonly User $userRepository
    ) {
        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address for user')
            ->addArgument('password', InputArgument::REQUIRED, 'Password for user');
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        try {
            $this->userRepository->createUser($email, $password);
        } catch (Throwable $t) {
            $this->generateOutput($output, 'ERROR: Could not create new user.');

            return Command::FAILURE;
        }

        $this->generateOutput($output, 'SUCCESS: Created new user "' . $email . '".');

        return Command::SUCCESS;
    }
}
