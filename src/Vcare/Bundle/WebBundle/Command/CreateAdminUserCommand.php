<?php

namespace Vcare\Bundle\WebBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\AdminUserInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateAdminUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('vcare:admin-user:create')
            ->setDescription('Creates a new admin user account.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'Email'),
                new InputArgument('password', InputArgument::REQUIRED, 'Password'),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = $this->createUser($email, $password, ['ROLE_ADMINISTRATION_ACCESS']);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('email')) {
            $email = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please enter an email:',
                function ($username) {
                    if (empty($username)) {
                        throw new \Exception('Email can not be empty');
                    }
                    return $username;
                }
            );
            $input->setArgument('email', $email);
        }

        if (!$input->getArgument('password')) {
            $password = $this->getHelper('dialog')->askHiddenResponseAndValidate(
                $output,
                'Please choose a password:',
                function ($password) {
                    if (empty($password)) {
                        throw new \Exception('Password can not be empty');
                    }
                    return $password;
                }
            );

            $input->setArgument('password', $password);
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @param array $securityRoles
     *
     * @return AdminUserInterface
     */
    protected function createUser($email, $password, array $securityRoles = ['ROLE_ADMINISTRATION_ACCESS'])
    {
        $canonicalizer = $this->getContainer()->get('sylius.user.canonicalizer');

        /** @var AdminUserInterface $user */
        $user = $this->getUserFactory()->createNew();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setUsernameCanonical($canonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($canonicalizer->canonicalize($user->getEmail()));
        $user->setPlainPassword($password);
        $user->setRoles($securityRoles);
        $user->setLocaleCode($this->getContainer()->getParameter('locale'));
        $user->enable();

        $this->getContainer()->get('sylius.user.password_updater')->updatePassword($user);

        return $user;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return FactoryInterface
     */
    protected function getUserFactory()
    {
        return $this->getContainer()->get('sylius.factory.admin_user');
    }
}
