<?php

namespace Spliced\Lodestone\Response;

use Spliced\Lodestone\Model\Character;

class CharacterResponse {

    protected $character = null;

    public function __construct(array $results = array())
    {
        foreach ($results as $result) {
            if (!$result instanceof CharacterSearchResult) {
                throw new \InvalidArgumentException('CharacterSearchResponse takes an array of CharacterSearchResult');
            }
        }
    }

    public function setCharacter(Character $character)
    {
        $this->character = $character;
        return $this;
    }

    public function getResults()
    {
        return $this->character;
    }


} 