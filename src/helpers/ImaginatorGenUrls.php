<?php

use Omatech\Imaginator\Repositories\Imaginator;

function imaginatorGenUrls($id, $alt = '', $formats = [], $options = [], $sets = [])
{
    $imaginator = new Imaginator();

    $html = '';
    $urls = '';

    foreach ($formats as $format) {
        foreach ($sets as $index => $set) {
            if (!empty($set['srcset'])) {
                if (gettype($set['srcset']) == 'array') {
                    $srcset = $set['srcset'];
                } elseif (gettype($set['srcset']) === 'integer') {
                    $srcset = [$set['srcset'] => $set['srcset']];
                } else {
                    $srcset = [];
                }
            } else {
                $srcset = [];
            }

            $html .= "<source";

            $urls = $imaginator->generateUrls([
                'hash'    => $id,
                'srcset'  => $srcset ?? [],
                'media'   => $set['media'] ?? '',
                'sizes'   => $set['sizes'] ?? '',
                'options' => $options ?? [],
                'format'  => $format
            ]);

            if (!empty($set['media'])) {
                $html .= " media='{$set['media']}'";
            }

            if (!empty($set['sizes'])) {
                $html .= " size='{$set['sizes']}'";
            }

            $html .= " srcset='{$urls['srcset']}' type='image/{$urls['format']}'>\n";
        }
    }

    $html .= "<img src='{$imaginator->generateUrls(['hash' => $id, 'options'=> $options])['base']}' alt='{$alt}'>";
    
    return $html;
}
