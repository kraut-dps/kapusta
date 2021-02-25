<?php

namespace Kapusta\tests;

use Exception;
use Kapusta\View;

class BaseTest extends TestCase
{
    public function testSimple()
    {
        $oView = new class extends View {
            public function render()
            {
                ?>1<?php
            }
        };
        $this->assertEquals('1', $oView);
    }

    public function testVarFlush()
    {
        $oView = new class extends View {
            public function render()
            {
                // @formatter:off ?>
                <root>
                <?php $oSubView = $this->begin() ?>
                    <subview/>
                <?php $oSubView->end( true ) ?>
                </root>
                <?php
                // @formatter:on
            }
        };

        $this->assertEqualsStrings('<root><subview/></root>', $oView);
    }

    public function testVarSkipFlush()
    {
        $oView = new class extends View {
            public View $oSubView;

            public function render()
            {
                // @formatter:off ?>
                <root>
                <?php $this->oSubView = $this->begin() ?>
                    <subview/>
                <?php $this->oSubView->end() ?>
                </root>

                <?php
                // @formatter:on
            }
        };
        $this->assertEqualsStrings('<root></root>', $oView);
        $this->assertEqualsStrings('<subview/>', $oView->oSubView);
    }

    public function testVarNested()
    {
        $oView = new class extends View {
            public function render()
            {
                // @formatter:off ?>
                <root>
                <?php $oSubView = $this->begin() ?>
                    <level1>
                        <?php $oSubSubView = $this->begin() ?>
                        <level2/>
                        <?php $oSubSubView->end(true) ?>
                    </level1>
                <?php $oSubView->end(true) ?>
                </root>
                <?php
                // @formatter:on
            }
        };

        $this->assertEqualsStrings(
          '<root><level1><level2/></level1></root>',
          $oView
        );
    }

    public function testVarDouble()
    {
        $oView = new class extends View {
            public function render()
            {
                // @formatter:off ?>
                <?php $oSubView = $this->begin() ?>
                <level1/>
                <?php $oSubView->end() ?>
                <root>
                    <?= $oSubView ?>
                    <?= $oSubView ?>
                    <?= $oSubView ?>
                </root>
                <?php
                // @formatter:on
            }
        };

        $this->assertEqualsStrings(
          '<root><level1/><level1/><level1/></root>',
          $oView
        );
    }

    public function testBadNested()
    {
        $oView = new class extends View {
            public function render()
            {
                // @formatter:off ?>
                <root>
                    <?php $oSubView = $this->begin() ?>
                    <level1>
                        <?php $oSubSubView = $this->begin() ?>
                        <level2/>
                        <?php $oSubView->end(true) ?>
                    </level1>
                    <?php $oSubSubView->end(true) ?>
                </root>
                <?php
                // @formatter:on
            }
        };

        $this->expectException(Exception::class);
        echo $oView;
    }
}