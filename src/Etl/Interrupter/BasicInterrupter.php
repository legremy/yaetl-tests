<?php

namespace Gremy\Yaetl\Etl\Interrupter;

use fab2s\NodalFlow\Flows\InterrupterInterface;
use fab2s\NodalFlow\Nodes\InterruptNodeAbstract;

class BasicInterrupter extends InterruptNodeAbstract
{


    /**
     * @param mixed $param
     *
     * @return InterrupterInterface|null|bool `null` do do nothing, eg let the Flow proceed untouched
     *                                        `true` to trigger a continue on the carrier Flow (not ancestors)
     *                                        `false` to trigger a break on the carrier Flow (not ancestors)
     *                                        `InterrupterInterface` to trigger an interrupt with a target (which may be one ancestor)
     */
    public function interrupt($param)
    {
        return true;
    }
}