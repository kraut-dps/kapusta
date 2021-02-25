<?php

namespace Kapusta;

class View
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var View|null
     */
    protected $parent = null;

    /**
     * @var View|null
     */
    protected $slot = null;

    /**
     * @var int
     */
    protected int $obLevel;

    /**
     * @param View $view
     * @param bool $echo
     * @return View
     */
    public function run($view, $echo = true)
    {
        $view->setParent($this);
        if ($echo) {
            echo $view;
        }
        return $view;
    }

    /**
     * @template T
     * @param View|T|null $wrapper
     * @return View|T
     */
    public function begin($wrapper = null)
    {
        $view = new View();
        $view->setParent($this);

        if ($wrapper) {
            $wrapper->slot = $view;
        }

        $oContext = $wrapper ? $wrapper : $view;
        $oContext->obStart();
        return $oContext;
    }

    /**
     * @param bool $echo
     * @throws Exception
     */
    public function end($echo = false)
    {
        $oContext = $this->slot ?? $this;
        $oContext->content = $this->obGetClean();

        if ($echo) {
            $this->render();
        }
    }

    /**
     * @param callable $render
     * @return string
     * @throws Exception
     */
    public function obWrap(callable $render)
    {
        if (!isset($this->content)) {
            $this->obStart();
            $render();
            $this->content = $this->obGetClean();
        }
        return $this->content;
    }

    public function __toString()
    {
        return $this->obWrap(
          function () {
              $this->render();
          }
        );
    }

    protected function obStart()
    {
        ob_start();
        $this->obLevel = ob_get_level();
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function obGetClean()
    {
        if (ob_get_level() !== $this->obLevel) {
            // пройдемся по всем потомкам, найдем самый нижний уровень
            $iFloor = $this->obLevel;
            $oView = $this;
            while ($oView = $oView->getParent()) {
                $iFloor = $oView->getObLevel();
            }

            $obContents = [];
            while (ob_get_level() >= $iFloor) {
                $obContents[] = ob_end_clean();
            }

            $e = new Exception('bad nested ' . __CLASS__ . '::begin() and ' . __CLASS__ . '::end()');
            $e->setObContents($obContents);
            throw $e;
        }
        return ob_get_clean();
    }

    /**
     * @param View $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return View|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getObLevel()
    {
        return $this->obLevel;
    }

    /**
     * @return void
     */
    public function render()
    {
        echo $this->content;
    }
}
