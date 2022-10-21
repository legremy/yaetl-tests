<?php

namespace Gremy\Yaetl\Etl\Campain;

use Exception;
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
            $etl->setProgressMod(1);

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_START, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                if ($this->displayReport) {
                    dump('Flow n°' . $yaEtl->getId() . " starting...");
                    dump($yaEtl->getNodeMap());
                }
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_PROGRESS, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                $node = $event->getNode();
//                dump($node);
//                dump($yaEtl);
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_SUCCESS, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                if ($this->displayReport) {
                    dump($yaEtl->getStats()['report']);
                }
                dump('Flow n°' . $yaEtl->getId() . ' succeeded.');
            });

            try {
                $etl->exec();
            } catch (Exception $e) {
                echo 'oops';
            }

            dump("Flow status: " . $etl->getFlowStatus());
        }

        dump("Campaign n°" . $this->id . " executed");
    }
}