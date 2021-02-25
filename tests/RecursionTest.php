<?php

namespace Kapusta\tests;

use Kapusta\View;

class RecursionView extends View
{

    public function __construct(
      public array $aRow
    ) {
    }

    public function render()
    {
        // @formatter:off ?>

        <id><?= $this->aRow[ 'id' ] ?></id>
        <?php foreach ($this->aRow[ 'children' ] ?? [] as $aChildRow) { ?>
            <?php $this->run(new RecursionView($aChildRow)) ?>
        <?php } ?>

        <?php // @formatter:off
    }
}


class RecursionTest extends TestCase
{
    public function testRecursion()
    {
        $oView = new RecursionView(
          [
            'id' => 1,
            'children' => [
              [
                'id' => 2,
                'children' => [
                  [
                    'id' => 4
                  ]
                ]
              ],
              [
                'id' => 3,
              ]
            ]
          ]
        );
        $this->assertEqualsStrings(
          '<id>1</id>
            <id>2</id>
                <id>4</id>
            <id>3</id>',
          $oView
        );
    }
}