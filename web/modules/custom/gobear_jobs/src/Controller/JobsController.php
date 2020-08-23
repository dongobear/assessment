<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Class JobsController
 *
 * @package Drupal\gobear_jobs\Controller
 */
class JobsController extends ControllerBase
{

    public function index()
    {
        $content = file_get_contents('https://jobs.github.com/positions.json?location=new+york');
        $jobs = json_decode($content, true);
        if (!is_array($jobs)) {
            $jobs = [];
        }

        $items = [];
        /** @var DateFormatterInterface $date_formatter */
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

        return [
            '#theme' => 'jobs_listing',
            '#jobs' => $items,
        ];
    }

}
