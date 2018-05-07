<?php

namespace Evp\Component\Money;

class Serializer
{
    /**
     * Gets metadata directory path
     *
     * @return string
     */
    static public function getMetadataPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'serializer';
    }

    /**
     * Gets metadata namespace prefix
     *
     * @return string
     */
    static public function getNamespacePrefix()
    {
        return 'Evp\Component\Money';
    }
}
