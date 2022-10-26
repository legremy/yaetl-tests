<?php

namespace Gremy\Yaetl\Etl\Campain;

use Exception;
use fab2s\NodalFlow\Events\FlowEvent;
use fab2s\NodalFlow\Events\FlowEventInterface;
use fab2s\NodalFlow\Flows\FlowRegistry;
use fab2s\NodalFlow\Flows\InterrupterInterface;
use fab2s\YaEtl\YaEtl;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EtlCampain
{
    private UuidInterface $id;
    private bool $displayFullReport = true;

    /**
     * @param YaEtl[] $etls
     */
    public function __construct(public array $etls)
    {
        $this->id = Uuid::uuid4();
        $this->flowRegistry = new FlowRegistry();

    }

    public function exec()
    {
        dump("Executing campain n°" . $this->id);
        $count = 0;

        foreach ($this->etls as $etl) {
            $count++;
            set_time_limit(50);
            $etl->setProgressMod(1);

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_START, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                if ($this->displayFullReport) {
                    dump('Flow n°' . $yaEtl->getId() . " starting...");
                    dump($yaEtl->getNodeMap());
                }
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_PROGRESS, function(FlowEventInterface $event) use($count){
                $yaEtl = $event->getFlow();
                $node = $event->getNode();
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_SUCCESS, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                dump($yaEtl->getStats()['report']);
                dump('Flow n°' . $yaEtl->getId() . ' succeeded.');
                $test = $this->flowRegistry->getFlow($yaEtl->getId());
                dump($test);
            });

            $etl->getDispatcher()->addListener(FlowEvent::FLOW_FAIL, function(FlowEventInterface $event) {
                $yaEtl = $event->getFlow();
                dump($yaEtl->getStats()['report']);
                dump('Flow n°' . $yaEtl->getId() . ' failed.');
                $test = $this->flowRegistry->getFlow($yaEtl->getId());
                dump($test);
            });

            try {
                $etl->exec();
            } catch (Exception $e) {
                echo 'An exception was caught : ' . $e->getMessage() . ' (' . $e->getPrevious()->getMessage() . ').';
            } finally {
                dump("Flow status: " . $etl->getFlowStatus());
            }

        }

        dump("Campaign n°" . $this->id . " executed");
    }
}