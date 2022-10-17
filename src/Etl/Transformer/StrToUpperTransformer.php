<?php

namespace Gremy\Yaetl\Etl\Transformer;

use fab2s\NodalFlow\NodalFlowException;
use fab2s\YaEtl\Transformers\TransformerAbstract;

class StrToUpperTransformer extends TransformerAbstract
{
    /**
     * String to upper POC example
     *
     * @param string|null $record
     *
     * @return string
     * @throws NodalFlowException
     */
    public function exec($record = null)
    {
        return array_map('strtoupper', $record);
    }
}