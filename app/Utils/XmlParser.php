<?php

namespace App\Utils;

use Generator;
use XMLReader;

/**
 * Parse xml file with books
 *
 * example structure
 * <?xml version="1.0"?>
 * <xml>
 *     <books>
 *         <book isbn="354-2-50-082770-8" title="Dolores voluptatum et voluptatibus architecto. Esse sapiente magni consectetur consequatur. Vel omnis ea dicta aut qui. Omnis fugit nam ex quaerat.">
 *             <image>https://dummyimage.com/2831x679</image>
 *             <description>It sounded an excellent opportunity for making her escape; so she set to work very diligently to write out a history of the hall; but, alas! either the locks were too large, or the key was lying.</description>
 *         </book>
 *     </books>
 * </xml>
 */
class XmlParser
{
    private $filepath;

    /**
     * @param string $filepath
     */
    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Read xml file
     *
     * @return Generator
     */
    public function read(): Generator
    {
        $reader = new XMLReader();
        try {
            $reader->open($this->filepath);
            $book = [];
            while ($reader->read()) {
                if ($reader->nodeType === XMLReader::END_ELEMENT) {
                    if ($reader->name === 'book') {
                        yield $book;
                    }
                    continue;
                }
                switch ($reader->name) {
                    case 'book':
                        $book = [
                            'isbn' => $reader->getAttribute('isbn'),
                            'title' => $reader->getAttribute('title'),
                        ];
                        break;
                    case 'image':
                        $book['image'] = $reader->readString();
                        break;
                    case 'description':
                        $book['description'] = $reader->readString();
                        break;
                }
            }
        } finally {
            $reader->close();
        }
    }

    /**
     * @param string $path
     * @return self
     */
    public static function createFromPath(string $path): self
    {
        return new self($path);
    }
}
