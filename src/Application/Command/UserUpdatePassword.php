<?php declare(strict_types=1);

namespace Milkbar\Application\Command;

use Milkbar\Application\Repository\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'user:update:password',
    description: 'Update password of user.',
    aliases: ['user:update:password'],
    hidden: false,
)]
class UserUpdatePassword extends Command
{
    public function __construct(
        private readonly User $userRepository
    ) {
        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email address of user')
            ->addArgument('password', InputArgument::REQUIRED, 'New password for user');
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        try {
            $this->userRepository->updatePassword($email, $password);
        } catch (Throwable $t) {
            $this->generateOutput($output, 'ERROR: Could not update user password.');

            return Command::FAILURE;
        }

        $this->generateOutput($output, 'SUCCESS: Updated password for user "' . $email . '".');

        return Command::SUCCESS;
    }
}
