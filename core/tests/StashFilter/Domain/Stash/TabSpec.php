<?php

namespace tests\DumpIt\StashFilter\Domain\Stash;

use DumpIt\StashFilter\Domain\Stash\Tab;
use DumpIt\StashFilter\Domain\Stash\TabId;
use PhpSpec\ObjectBehavior;

class TabSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(TabId::from('1'), 'Tab name', null);
    }

    function it_updates_its_last_sync()
    {
        $this->lastSync()->shouldReturn(null);

        $this->refreshSync();

        $this->lastSync()->shouldNotReturn(null);
    }
}
