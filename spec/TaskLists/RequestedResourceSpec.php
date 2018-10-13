<?php

namespace spec\Scriptotek\Alma\TaskLists;

use Scriptotek\Alma\Bibs\Bib;
use Scriptotek\Alma\Client as AlmaClient;
use Scriptotek\Alma\Conf\Library;
use Scriptotek\Alma\TaskLists\RequestedResource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class RequestedResourceSpec extends ObjectBehavior
{
    public function let(AlmaClient $client, Library $library, Bib $bib)
    {
        $this->beConstructedWith($client, $library, 'SUPER_DESK', $bib);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestedResource::class);
    }
}
