<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController class.
 */
class JobsController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    
    $json_content = file_get_contents('https://jobs.github.com/positions.json?location=new+york');
   
    $listJobs = json_decode($json_content, true);
    
    return [
      '#theme' => 'my_template',
      '#listJobs' => $listJobs,
    ];
  }

}