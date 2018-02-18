<?php

namespace PHPExtensionStubGenerator;

class DocBlockLoader
{
    /**
     * @var array - map[fxn name]{doc: ''}
     */
    private $docBlock = [];

    /**
     * DocBlockLoader constructor.
     * @param array $docBlock
     */
    public function __construct(array $docBlock = [])
    {
        $this->docBlock = $docBlock;
    }

    /**
     * @param string $function
     * @return null|string
     */
    public function fetchDocBlock(string $function): ?string
    {
        if (!array_key_exists($function, $this->docBlock)) {
            return null;
        }

        return $this->docBlock[$function]->doc;
    }
}
