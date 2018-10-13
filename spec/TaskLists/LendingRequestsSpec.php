<?php

namespace spec\Scriptotek\Alma\TaskLists;

use Scriptotek\Alma\Client as AlmaClient;
use Scriptotek\Alma\Conf\Library;
use PhpSpec\ObjectBehavior;
use Scriptotek\Alma\TaskLists\ResourceSharingRequest;
use spec\Scriptotek\Alma\SpecHelper;

class LendingRequestsSpec extends ObjectBehavior
{
    function it_provides_filtering_options(AlmaClient $client, Library $library)
    {
        $library->code = 'SOME_LIBRARY';
        $this->beConstructedWith($client, $library, [
            'printed' => 'N',
            'status' => 'REQUEST_CREATED_LEND',
        ]);

        $client->getJSON('/task-lists/rs/lending-requests?printed=N&status=REQUEST_CREATED_LEND&library=SOME_LIBRARY')
            ->shouldBeCalled()
            ->willReturn(SpecHelper::getDummyData('lending_requests_created.json'));

        $result = $this->all();
        $result->shouldBeArray();
        $result->shouldHaveCount(3);
        $result[0]->shouldBeAnInstanceOf(ResourceSharingRequest::class);
    }
}