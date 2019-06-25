<?php
declare(strict_types=1);

/**
 * most of parts is borrowed from zendframework/zend-code
 * https://github.com/zendframework/zend-code
 *
 * This source is aimed for hack to override zend-code.
 *
 * @license New BSD, code from Zend Framework
 * https://github.com/zendframework/zend-code/blob/master/LICENSE.md
 */

namespace PHPExtensionStubGenerator\ZendCode;

use PHPExtensionStubGenerator\DocBlockLoader;

class FunctionGenerator
{
    protected static function renderDocType(string $type) {
        $hasNull = strpos($type, "?") !== false;
        $type = str_replace("?", "", $type);
        if ($hasNull) {
            $type .= "|null";
        }
        return $type;
    }

    public static function generateDocBlockByPrototypeArray(array $prototype, DocBlockLoader $loader)
    {
        $out = [];

        if (($docBlock = $loader->fetchDocBlock($prototype['name']))) {
            foreach (explode("\n", $docBlock) as $line) {
                $out[] = $line;
            }
        }
        foreach ($prototype['arguments'] as $name => $argument) {
            $out[] = "@param ".self::renderDocType($argument['type'] ?: "")." \${$name}";
        }

        if (array_key_existS('return', $prototype) && $prototype !== NULL) {
            $out[] = "@return ".self::renderDocType($prototype['return']);
        }


        return "/**\n"
        .implode(array_map(function (string $line) {
            return " * {$line}";
        }, $out), "\n")
            . "\n */";
    }

    public static function generateByPrototypeArray(array $prototype)
    {
        $line = 'function' . ' ' . $prototype['name'] . '(';
        $args = [];
        foreach ($prototype['arguments'] as $name => $argument) {
            $type = ($argument['type'] && $argument['type'] !== 'resource' && $argument['type'] !== '?resource') ? "{$argument['type']} " : "";
            $argsLine = $type . ($argument['by_ref'] ? '&' : '') . '$' . $name;
            if (!$argument['required']) {
                $argsLine .= ' = ' . var_export($argument['default'], true);
            }
            $args[] = $argsLine;
        }
        $line .= implode(', ', $args);
        $line .= ')';
        if ($prototype['return'] !== 'mixed' && strpos($prototype['return'], "|") === false) {
            $line .= ": {$prototype['return']}";
        }
        $line .= " {}";
        return $line;
    }

}
