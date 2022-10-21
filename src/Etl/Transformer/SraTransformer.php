<?php

namespace Gremy\Yaetl\Etl\Transformer;

use Exception;
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
        dump("Transforming CSV line...");
        $map = [3, 2, 11, 2, ];
        $result = [];
        $offset = 0;

        if (!in_array(substr($param, 0, 3), ['VEC', 'PRO'])) {
            throw new Exception("Something went wrong. Bad vehicle Record.");
        }

        foreach ($map as $length) {
            $result[] = trim(substr($param, $offset, $length));
            $offset += $length;
        }

        $result[] = substr($param, $offset);

        return $result;
    }

    public function filterProItems($param)
    {
        dump('Checking if the vehicle is PRO');
        return !str_starts_with($param, "PRO");
    }
}