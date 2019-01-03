<?php
/**
 * ExportFromViewTest.php.
 *
 * @author: Amando Vledder <amando@acfbentveld.nl>
 */

namespace ACFBentveld\XML\Tests\Features\Export;

use ACFBentveld\XML\Tests\TestCase;
use ACFBentveld\XML\XML;
use Illuminate\Support\Facades\View;

class ExportFromViewTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        View::addLocation(__DIR__ . '/views');
    }


    /**
     * Test xml export from a view
     */
    public function test_exports_from_view()
    {
        $data = [
            'files' => [
                [
                    'name' => 'file1',
                    'type' => 'pdf'
                ],
                [
                    'name' => 'file2',
                    'type' => 'png'
                ],
                [
                    'name' => 'file3',
                    'type' => 'xml'
                ],
            ]
        ];

        $xml = XML::exportView('files', $data)
            ->setRootTag("files")
            ->version("1.0")
            ->encoding("UTF-8")
            ->toString();
        $this->assertMatchesXmlSnapshot($xml);
    }


    /**
     * Test xml from a view without a generated root tag
     */
    public function test_exports_from_view_without_root()
    {
        $data = [
            'files' => [
                [
                    'name' => 'file1',
                    'type' => 'pdf'
                ],
                [
                    'name' => 'file2',
                    'type' => 'png'
                ],
                [
                    'name' => 'file3',
                    'type' => 'xml'
                ],
            ]
        ];

        $xml = XML::exportView('no-root', $data)
            ->disableRootTag()
            ->version("1.0")
            ->encoding("UTF-8")
            ->toString();
        $this->assertMatchesXmlSnapshot($xml);
    }
}