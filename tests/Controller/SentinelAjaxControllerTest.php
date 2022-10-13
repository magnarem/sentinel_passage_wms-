<?php

namespace Drupal\sentinel_passage_wms\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the sentinel_passage_wms module.
 */
class SentinelAjaxControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "sentinel_passage_wms SentinelAjaxController's controller functionality",
      'description' => 'Test Unit for module sentinel_passage_wms and controller SentinelAjaxController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests sentinel_passage_wms functionality.
   */
  public function testSentinelAjaxController() {
    // Check that the basic functions of module sentinel_passage_wms.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
