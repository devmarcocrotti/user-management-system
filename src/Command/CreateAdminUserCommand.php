<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a new admin user',
    aliases: ['app:add-admin-user'],
    hidden: false
)]
class CreateAdminUserCommand extends Command
{
    protected UserPasswordHasherInterface $userPasswordHasher;
    protected EntityManagerInterface $entityManager;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@localhost.com');
        $admin->setPassword(
            $this->userPasswordHasher->hashPassword(
                $admin,
                "admin"
            )
        );
        $admin->setCreatedAt(new \DateTimeImmutable());
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setUuid(Uuid::v4());
        $this->entityManager->persist($admin);
        $this->entityManager->flush();


        $output->writeln('Admin user created');

        return Command::SUCCESS;
    }
}
