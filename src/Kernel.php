<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Gedmo\Timestampable\TimestampableListener;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        if ($this->container->has('doctrine')) {
            $om = $this->container->get('doctrine')->getManager();
            $timestampableListener = new TimestampableListener();
            $om->getEventManager()->addEventSubscriber($timestampableListener);
        }
    }
}
