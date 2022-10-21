<?php

namespace Gremy\Yaetl\Etl\Transformer;

use fab2s\NodalFlow\NodalFlowException;
use fab2s\YaEtl\Transformers\TransformerAbstract;

class StrToLowerTransformer extends TransformerAbstract
{
    /**
     * String to lower POC example
     *
     * @param array|null $param
     *
     * @return array
     */
    public function exec($param = null)
    {
        return array_map('strtolower', $param);
    }

}