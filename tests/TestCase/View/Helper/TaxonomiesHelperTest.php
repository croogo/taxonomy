<?php

namespace Croogo\Taxonomy\Test\TestCase\View\Helper;

use Cake\Controller\Controller;
use Croogo\TestSuite\CroogoTestCase;
use Taxonomy\View\Helper\TaxonomiesHelper;

class TheTaxonomyTestController extends Controller
{

    public $uses = null;
}

class TaxonomiesHelperTest extends CroogoTestCase
{

/**
 * setUp
 */
    public function setUp()
    {
        parent::setUp();
        $this->ComponentRegistry = new ComponentRegistry();

        $request = $this->createMock('Request');
        $response = $this->createMock('Response');
        $this->View = new View(new TheTaxonomyTestController($request, $response));
        $this->Taxonomies = new TaxonomiesHelper($this->View);
    }

/**
 * tearDown
 */
    public function tearDown()
    {
        unset($this->View);
        unset($this->Taxonomies);
    }

/**
 * Test [vocabulary] shortcode
 */
    public function testVocabularyShortcode()
    {
        $content = '[vocabulary:categories type="blog"]';
        $this->View->viewVars['vocabulariesForLayout']['categories'] = [
            'Vocabulary' => [
                'id' => 1,
                'title' => 'Categories',
                'alias' => 'categories',
            ],
            'threaded' => [],
        ];
        Croogo::dispatchEvent('Helper.Layout.beforeFilter', $this->View, ['content' => &$content]);
        $this->assertContains('vocabulary-1', $content);
        $this->assertContains('class="vocabulary"', $content);
    }
}
