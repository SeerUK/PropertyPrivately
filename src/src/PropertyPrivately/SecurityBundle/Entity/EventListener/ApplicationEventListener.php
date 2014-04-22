<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use PropertyPrivately\SecurityBundle\Entity\Application;
use PropertyPrivately\SecurityBundle\Utils\TokenGenerator;

/**
 * Application Event Listener
 */
class ApplicationEventListener
{
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * Constructor
     *
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @inheritDoc
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $application = $event->getEntity();

        if ( ! $application instanceof Application) {
            return;
        }

        $this->updateApplication($application);
    }

    /**
     * Update an Application entity
     *
     * @param Application $application
     */
    private function updateApplication(Application $application)
    {
        $application->setToken($this->tokenGenerator->generate());
    }
}
