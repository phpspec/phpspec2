<?php

namespace PHPSpec2\Initializer;

use PHPSpec2\SpecificationInterface;
use PHPSpec2\Loader\Node\Example;
use PHPSpec2\Prophet\CollaboratorsCollection;
use PHPSpec2\Prophet\MockProphet;
use PHPSpec2\Mocker\MockerInterface;
use PHPSpec2\Wrapper\ArgumentsUnwrapper;

class ArgumentsProphetsInitializer implements ExampleInitializerInterface
{
    private $parametersReader;
    private $mocker;
    private $unwrapper;

    public function __construct(FunctionParametersReader $parametersReader,
                                MockerInterface $mocker, ArgumentsUnwrapper $unwrapper)
    {
        $this->parametersReader = $parametersReader;
        $this->mocker           = $mocker;
        $this->unwrapper        = $unwrapper;
    }

    public function getPriority()
    {
        return 0;
    }

    public function supports(SpecificationInterface $specification, Example $example)
    {
        return true;
    }

    public function initialize(SpecificationInterface $specification, Example $example,
                               CollaboratorsCollection $collaborators)
    {
        foreach ($this->parametersReader->getParameters($example) as $name => $type) {
            $subject = $type ? $this->mocker->mock($type) : $type;
            $prophet = new MockProphet($subject, $this->mocker, $this->unwrapper);
            $collaborators->set($name, $prophet);
        }
    }
}
