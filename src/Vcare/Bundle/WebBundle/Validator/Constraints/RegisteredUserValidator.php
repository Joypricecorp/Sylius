<?php

namespace Vcare\Bundle\WebBundle\Validator\Constraints;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RegisteredUserValidator extends ConstraintValidator
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(RepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param CustomerInterface $customer
     *
     * @param Constraint $constraint
     */
    public function validate($customer, Constraint $constraint)
    {
        $firstName = trim($customer->getFirstName());
        $lastName = trim($customer->getLastName());

        $existingCustomer = $this->customerRepository->findOneBy([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);

        if (null !== $existingCustomer && null !== $existingCustomer->getUser()) {
            $this->context
                ->buildViolation($constraint->message, [])
                ->atPath('form')
                ->addViolation()
            ;
        }
    }
}
