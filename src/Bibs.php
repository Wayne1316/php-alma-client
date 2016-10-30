<?php

namespace Scriptotek\Alma;

use Scriptotek\Alma\Models\Bib;

class Bibs extends ResourceList
{
    protected $resourceName = Bib::class;

    public function fromBarcode($barcode)
    {
        $destinationUrl = $this->client->getRedirectLocation('/items', ['item_barcode' => $barcode]);

        // Example: https://api-eu.hosted.exlibrisgroup.com/almaws/v1/bibs/999211285764702204/holdings/22156746440002204/items/23156746430002204
        if (!is_null($destinationUrl) && preg_match('$bibs/([0-9]+)/holdings/([0-9]+)/items/([0-9]+)$', $destinationUrl, $matches)) {
            $mmsId = $matches[1];

            return $this->get($mmsId);
        }
    }

    public function fromIsbn($isbn)
    {
        $record = $this->client->sru->first('alma.isbn="' . $isbn . '"');
        if (is_null($record)) {
            return;
        }

        $mmsId = $record->data->text('//marc:controlfield[@tag="001"]');
        if (empty($mmsId)) {
            $mmsId = $record->data->text('//controlfield[@tag="001"]');
            if (empty($mmsId)) {
                throw new \RuntimeError('SRU returned a record, but it didn\'t contain valid MARC21!');
            }
        }

        return $this->get($mmsId);
    }

    public function search($cql, $batchSize = 10)
    {
        foreach ($this->client->sru->all($cql, $batchSize) as $sruRecord) {
            yield Bib::fromSruRecord($sruRecord, $this->client);
        }
    }
}
