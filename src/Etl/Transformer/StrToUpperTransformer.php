<?php

namespace Gremy\Yaetl\Etl\Transformer;

use fab2s\NodalFlow\NodalFlowException;
use fab2s\YaEtl\Transformers\TransformerAbstract;

class StrToUpperTransformer extends TransformerAbstract
{
    /**
     * String to upper POC example
     *
     * @param array|null $param
     *
     * @return array
     */
    public function exec($param = null)
    {
        if (is_string($param)) {
            return strtoupper($param);
        }

        return array_map('strtoupper', $param);
    }
}