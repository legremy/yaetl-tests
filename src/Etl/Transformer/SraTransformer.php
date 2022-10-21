<?php

namespace Gremy\Yaetl\Etl\Transformer;

use fab2s\YaEtl\Transformers\TransformerAbstract;

class SraTransformer extends TransformerAbstract
{
    /**
     * Transforme le fichier plat sra en un jeu de données exportable
     *
     * @param string|null $param
     *
     * @return array
     */
    public function exec($param = null): array
    {
        $map = [3, 2, 11, 2, ];
        $result = [];
        $offset = 0;

        foreach ($map as $length) {
            $result[] = trim(substr($param, $offset, $length));
            $offset += $length;
        }

        $result[] = substr($param, $offset);

        return $result;
    }

    public static function filterProItems($param)
    {
        return !str_starts_with($param, "PRO");
    }
}