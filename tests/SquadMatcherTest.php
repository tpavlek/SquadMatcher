<?php

class SquadMatcherTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function it_can_match_into_squads()
    {
        $matcher = new \App\SquadMatcher();

        $matcher->addPlayer('Player1');
        $matcher->addPlayer('Player2');
        $matcher->addPlayer('Player3');
        $matcher->addPlayer('Player4');
        $matcher->addPlayer('Player5');
        $matcher->addPlayer('Player6');
        $matcher->addPlayer('Player7');
        $matcher->addPlayer('Player8');

        foreach ($matcher->computeSquads() as $squad) {
            $this->assertInstanceOf(\App\Squad::class, $squad);
            $this->assertEquals(4, $squad->count());
        }
    }

    /**
     * @test
     */
    public function it_works_with_odd_sized_squads()
    {
        $matcher = new \App\SquadMatcher();

        $matcher->addPlayer('Player1');
        $matcher->addPlayer('Player2');
        $matcher->addPlayer('Player3');
        $matcher->addPlayer('Player4');
        $matcher->addPlayer('Player5');

        $squads = $matcher->computeSquads();

        $this->assertEquals(3, $squads->first()->count());
        $this->assertEquals(2, $squads->last()->count());
    }
}
