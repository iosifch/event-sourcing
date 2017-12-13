<?php

namespace App\Repository;

use App\Entity\Customer;
use App\ValueObject\CustomerId;
use App\ValueObject\Email;
use Doctrine\ORM\EntityManagerInterface;

class CustomerCustomRepository
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function create(CustomerId $customerId, string $name, Email $email) : bool
    {
        $stmt = $this->em->getConnection()->prepare('
            INSERT INTO customer_custom_projection
              (`id`, `name`, `email`)
              VALUES (:id, :name, :email)
        ');

        return $stmt->execute([
            'id'    => $customerId->value(),
            'name'  => $name,
            'email' => $email->value()
        ]);
    }

    public function update(CustomerId $customerId, string $field, $value) : void
    {
        $stmt = $this->em->getConnection()->prepare(sprintf('
            UPDATE customer_custom_projection
            SET `%s` = :name
            WHERE `id` = :id
        ', $field));

        $stmt->execute([
            'id'    => $customerId->value(),
            'name' => $value
        ]);
    }

    public function emailExists(Email $email) : bool
    {
        $stmt = $this->em->getConnection()->prepare('
            SELECT COUNT(*)
            FROM customer_custom_projection
            WHERE `email` = :email
        ');
        $stmt->bindValue('email', $email->value());
        $stmt->execute();

        return (bool)$stmt->fetchColumn();
    }
}