<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Reader;

use Zend\Config\Exception;
use Zend\Config\Reader\JavaProperties;

/**
 * @group      Zend_Config
 */
class JavaPropertiesTest extends AbstractReaderTestCase
{
    public function setUp()
    {
        $this->reader = new JavaProperties();
    }

    /**
     * getTestAssetPath(): defined by AbstractReaderTestCase.
     *
     * @see    AbstractReaderTestCase::getTestAssetPath()
     * @return string
     */
    protected function getTestAssetPath($name)
    {
        return __DIR__ . '/TestAssets/JavaProperties/' . $name . '.properties';
    }

    public function testFromFile()
    {
        $arrayJavaProperties = $this->reader->fromFile($this->getTestAssetPath('include-target'));

        $this->assertNotEmpty($arrayJavaProperties);
        $this->assertEquals($arrayJavaProperties['single.line'], 'test');
        $this->assertEquals($arrayJavaProperties['multiple'], 'line test');
    }

    public function testIncludeAsElement()
    {
        $arrayJavaProperties = $this->reader->fromFile($this->getTestAssetPath('include-base'));

        $this->assertNotEmpty($arrayJavaProperties);
        $this->assertEquals($arrayJavaProperties['single.line'], 'test');
        $this->assertEquals($arrayJavaProperties['multiple'], 'line test');
    }

    public function testFromString()
    {
        $JavaProperties = <<<'ASSET'
#comment
!comment
single.line:test
multiple:line \
test
ASSET;

        $arrayJavaProperties = $this->reader->fromString($JavaProperties);

        $this->assertNotEmpty($arrayJavaProperties);
        $this->assertEquals($arrayJavaProperties['single.line'], 'test');
        $this->assertEquals($arrayJavaProperties['multiple'], 'line test');
    }

    public function testInvalidIncludeInString()
    {
        $JavaProperties = '@include:fail.properties';

        $expectedErrorMessage = 'Cannot process @include statement for a string';

        $this->expectException(Exception\RuntimeException::class);
        $this->expectExceptionMessage($expectedErrorMessage);
        $arrayJavaPropterties = $this->reader->fromString($JavaProperties);
    }
}
