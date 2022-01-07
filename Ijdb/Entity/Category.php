<?php
namespace Ijdb\Entity;

class Category {
    public $id;
    public $name;

    public function __construct(private \Ninja\DatabaseTable $jokesTable, private \Ninja\DatabaseTable $jokeCategoriesTable) {
    }

    public function getJokes() {
      $jokeCategories = $this->jokeCategoriesTable->find('categoryId', $this->id);

      $jokes = [];

      foreach ($jokeCategories as $jokeCategory) {
        $joke =  $this->jokesTable->find('id', $jokeCategory->jokeId)[0];
        if ($joke) {
          $jokes[] = $joke;
        }           
      }

      usort($jokes, [$this, 'sortJokes']);

      return $jokes;
    }

    private function sortJokes($a, $b) {
      $aDate = new \DateTime($a->jokedate);
      $bDate = new \DateTime($b->jokedate);

      if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
        return 0;
      }

      return $aDate->getTimestamp() > $bDate->getTimestamp() ? -1 : 1;
    }
}