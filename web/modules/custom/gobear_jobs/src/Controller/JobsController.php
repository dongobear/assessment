<?php

namespace Drupal\gobear_jobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;

/**
 * Class JobsController
 *
 * @package Drupal\gobear_jobs\Controller
 */
class JobsController extends ControllerBase
{

    /**
     * @return array
     */
    public function index()
    {
        $content = file_get_contents('https://jobs.github.com/positions.json?location=new+york');
        $json = json_decode($content, true);
        if (!is_array($json)) {
            $json = [];
        }

        $rows = [];
        foreach ($json as $item) {
            $rows[] = [
                Markup::create('<strong>'.$item['title'].'</strong>'),
                $item['company'],
                $item['location'],
                $item['type'],
                $item['created_at'],
                Markup::create($item['description'])
            ];
        }

        $header = [
            'title' => t('Title'),
            'company' => t('Company'),
            'location' => t('Location'),
            'type' => t('Type'),
            'created_at' => t('Created at'),
            'description' => t('Description'),
        ];
        $build['table'] = [
            '#type' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#empty' => t('No content has been found.'),
        ];

        return [
            '#type' => '#markup',
            '#markup' => render($build)
        ];
    }

}
