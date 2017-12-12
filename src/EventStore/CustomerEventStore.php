<?php

namespace App\EventStore;

use App\Event\CustomerEventInterface;
use App\ValueObject\CustomerId;
use Doctrine\ORM\EntityManagerInterface;

final class CustomerEventStore
{
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function insert(CustomerEventInterface $event) : void
    {
        $conn = $this->em->getConnection();

        $sql = '
            INSERT INTO customer_event (`customer_id`, `event_name`, `data`)
            VALUES(:customer_id, :event_name, :data)
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'customer_id'   => $event->customerId()->value(),
            'event_name'    => $event::NAME,
            'data'          => serialize($event)
        ]);
    }

    /**
     * @param CustomerId $customerId
     * @return CustomerEventInterface[]
     * @throws \Exception
     */
    public function fetchAllById(CustomerId $customerId) : array
    {
        $stmt = $this->em->getConnection()->executeQuery('
            SELECT *
            FROM customer_event
            WHERE customer_id = :customer_id
        ');

        $stmt->execute(['customer_id' => $customerId->value()]);
        $events = $stmt->fetchAll();

        return array_map(function($event) {
            return unserialize($event['data']);
        }, $events);
    }
}