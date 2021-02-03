<?php

namespace App\Message\Event;

/**
 * Class ChangeScooterStatusEvent
 *
 * @package App\Message\Event
 */
class ChangeScooterStatusEvent
{
    /**
     * A scooter id's
     *
     * @var string
     */
    private $id;

    /**
     * * A scooter status's
     *
     * @var bool
     */
    private $status;

    /**
     * ChangeScooterStatusEvent constructor.
     *
     * @param string $id
     * @param bool $status
     */
    public function __construct(string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Gets a scooter id
     *
     * @return string
     */
    public function getScooterId(): string
    {
        return $this->id;
    }

    /**
     * Gets a scooter status
     *
     * @return bool
     */
    public function getScooterStatus(): bool
    {
        return $this->status;
    }
}
