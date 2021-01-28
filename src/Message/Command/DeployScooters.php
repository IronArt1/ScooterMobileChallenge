<?php

namespace App\Message\Command;

/**
 * Class DeployScooters
 *
 * @package App\Message\Command
 */
class DeployScooters
{
    /**
     * A preferable number of scooters to deal with
     *
     * @var int
     */
    private $numberOfScooters;

    /**
     * DeployScooters constructor.
     *
     * @param int $numberOfScooters
     */
    public function __construct(int $numberOfScooters)
    {
        $this->numberOfScooters = $numberOfScooters;
    }

    /**
     * Gets a number of scooters
     *
     * @return int
     */
    public function getNumberOfScooters(): int
    {
        return $this->numberOfScooters;
    }
}
