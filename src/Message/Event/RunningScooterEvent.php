<?php

namespace App\Message\Event;

/**
 * Class RunningScooterEvent
 *
 * @package App\Message\Event
 */
class RunningScooterEvent
{
    /**
     * A scooter id's
     *
     * @var string
     */
    private $id;

    /**
     * * A scooter velocity's
     *
     * @var string
     */
    private $velocity;

    /**
     * RunningScooterEvent constructor.
     *
     * @param string $id
     * @param int $velocity
     */
    public function __construct(string $id, int $velocity)
    {
        $this->id = $id;
        $this->velocity = $velocity;
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
     * Gets a scooter velocity
     *
     * @return int
     */
    public function getScooterVelocity(): int
    {
        return $this->velocity;
    }
}
