<?php

namespace PHPSpec2\Formatter\Presenter\Differ;

class ArrayEngine implements EngineInterface
{
    public function supports($expected, $actual)
    {
        return is_array($expected) && is_array($actual);
    }

    public function compare($expected, $actual)
    {
        $diff = new \Diff($expected, $actual, array());

        $renderer = new \Diff_Renderer_Text_Unified;
        $text = $diff->render($renderer);

        $lines = array();
        foreach (explode("\n", $text) as $line) {
            if (0 === strpos($line, '-')) {
                $lines[] = sprintf('<diff-del>%s</diff-del>', $line);
            } elseif (0 === strpos($line, '+')) {
                $lines[] = sprintf('<diff-add>%s</diff-add>', $line);
            } else {
                $lines[] = $line;
            }
        }

        return sprintf("<code>\n%s</code>", implode("\n", $lines));
    }
}
