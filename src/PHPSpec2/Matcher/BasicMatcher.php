<?php

namespace PHPSpec2\Matcher;

abstract class BasicMatcher implements MatcherInterface
{
    final public function positiveMatch($name, $subject, array $arguments)
    {
        if (false === $this->matches($name, $subject, $arguments)) {
            throw $this->getFailureException($name, $subject, $arguments);
        }

        return $subject;
    }

    final public function negativeMatch($name, $subject, array $arguments)
    {
        if (true === $this->matches($name, $subject, $arguments)) {
            throw $this->getNegativeFailureException($name, $subject, $arguments);
        }

        return $subject;
    }

    abstract protected function matches($name, $subject, array $arguments);
    abstract protected function getFailureException($name, $subject, array $arguments);
    abstract protected function getNegativeFailureException($name, $subject, array $arguments);
}
