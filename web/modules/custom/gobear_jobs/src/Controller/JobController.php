<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\Xss;

/**
 * Class JobController.
 */
class JobController extends ControllerBase {

  /**
   * Returns jobs listing.
   *
   * @return array
   */
  public function build() {
    $data = file_get_contents('https://jobs.github.com/positions.json?location=new+york');

    $rows = [];
    if ($jobs = json_decode($data, TRUE)) {
      foreach ($jobs as $job) {
        $rows[] = [
          Xss::filter($job['title']),
          Xss::filter($job['company']),
          Xss::filter($job['location']),
          Xss::filter($job['type']),
          Xss::filter($job['created_at']),
          'description' => [
            'data' => [
              '#markup' => Xss::filter($job['description']),
            ],
          ]
        ];
      }
    }
    return [
      '#type' => 'table',
      '#header' => [
        $this->t('Title'),
        $this->t('Company'),
        $this->t('Location'),
        $this->t('Type'),
        $this->t('Created at'),
        $this->t('Description'),
      ],
      '#rows' => $rows,
    ];
  }

}
