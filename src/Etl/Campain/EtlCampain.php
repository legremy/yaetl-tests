<?php

namespace Gremy\Yaetl\Etl\Campain;

use fab2s\NodalFlow\Events\FlowEvent;
use fab2s\NodalFlow\Events\FlowEventInterface;
use fab2s\YaEtl\YaEtl;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EtlCampain
{
    private UuidInterface $id;
    private bool $displayReport = true;

    /**
     * @param YaEtl[] $etls
     */
    public function __construct(public array $etls)
    {
        $this->id = Uuid::uuid4();
    }

    public function exec()
    {
        dump("Executing campain n°" . $this->id);

        foreach ($this->etls as $etl) {

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_START, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                if ($this->displayReport) {
                    dump('Flow n°' . $yaEtl->getId() . " starting...");
                    dump($yaEtl->getNodeMap());
                }
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_SUCCESS, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                if ($this->displayReport) {
                    dump($yaEtl->getStats()['report']);
                }
                dump('Flow n°' . $yaEtl->getId() . ' succeeded.');
            });

            $etl->exec();
        }

        dump("Campaign n°" . $this->id . " executed");
    }
}