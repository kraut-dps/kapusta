<?php

namespace Kapusta\tests;

use Kapusta\View;

class Layout extends View
{
    public function __construct(
      public string $sTitle
    ) {
    }


    public function render()
    {
        // @formatter:off ?>
        <html>
        <head>
            <title><?= $this->sTitle ?></title>
        </head>
        <body><?= $this->slot ?></body>
        </html>
        <?php
        // @formatter:on
    }
}

class Page extends View
{

    public function render()
    {
        // @formatter:off ?>
        <?php $oLayout = $this->begin(new Layout('title1')) ?>
            <?php $oLayout->sTitle = 'title2'; ?>
            123
        <?php $oLayout->end(true) ?>
        <?php
        // @formatter:on
    }
}


class SlotTest extends TestCase
{
    public function testBase()
    {
        $oPage = new Page();
        $this->assertEqualsStrings(
          '<html>
        <head>
            <title>title2</title>
        </head>
        <body>123</body>
        </html>',
          $oPage
        );
    }
}