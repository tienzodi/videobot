<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VideosSuggestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VideosSuggestsTable Test Case
 */
class VideosSuggestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VideosSuggestsTable
     */
    public $VideosSuggests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.videos_suggests',
        'app.video_suggests',
        'app.videos',
        'app.fb_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('VideosSuggests') ? [] : ['className' => 'App\Model\Table\VideosSuggestsTable'];
        $this->VideosSuggests = TableRegistry::get('VideosSuggests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VideosSuggests);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
