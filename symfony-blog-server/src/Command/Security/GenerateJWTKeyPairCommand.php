<?php

namespace App\Command\Security;

use App\Config\Environment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateJWTKeyPairCommand extends Command
{
    protected static $defaultName = 'security:generate-key-pair';

    public function __construct(
        private Environment $environment
    ) {
        parent::__construct();
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        try {
            shell_exec($this->environment->path('openssl genrsa -out %s/config/jwt/private.pem 4096')); // Generates the jwt secret key for token verification
            shell_exec($this->environment->path('openssl rsa -in ./config/jwt/private.pem -out %s/config/jwt/public.pem -pubout -outform PEM')); // Generates the jwt public key signing

            $output->writeln('New key-pair was generated!');
        } catch (\Exception|\Throwable $e) {
            $output->writeln('Could not write keypair!: ' . $e->getMessage());
            return 0;
        }

        return 0;
    }
}