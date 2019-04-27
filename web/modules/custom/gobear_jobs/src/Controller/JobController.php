<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Datetime\DrupalDateTime;

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

    $items = [];
    if ($jobs = json_decode($data, TRUE)) {
      //shuffle($jobs);
      /** @var \Drupal\Core\Datetime\DateFormatterInterface $date_formatter */
      $date_formatter = \Drupal::service('date.formatter');
      foreach ($jobs as $job) {
        //$created_at = DrupalDateTime::createFromFormat($job['created_at'], 'D M d H:i:s UTC Y');
        $created_at = new DrupalDateTime($job['created_at'], 'UTC');
        $items[] = [
          'id' => Xss::filter($job['id']),
          'title' => Xss::filter($job['title']),
          'url' => Xss::filter($job['url']),
          'company' => Xss::filter($job['company']),
          'company_url' => Xss::filter($job['company_url']),
          'location' => Xss::filter($job['location']),
          'type' => Xss::filter($job['type']),
          'created_at' => $date_formatter->formatTimeDiffSince($created_at->getTimestamp()),
          'description' => [
            '#markup' => Xss::filter($job['description']),
          ],
        ];
      }
    }
    return [
      '#theme' => 'gobear_job_listing',
      '#items' => $items,
    ];
  }

}
