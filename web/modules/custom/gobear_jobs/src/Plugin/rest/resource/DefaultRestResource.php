<?php

namespace Drupal\gobear_jobs\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "default_rest_resource",
 *   label = @Translation("Default rest resource"),
 *   uri_paths = {
 *     "canonical" = "/jobs"
 *   }
 * )
 */
class DefaultRestResource extends ResourceBase {

  const API_ENDPOINT = 'https://jobs.github.com/positions.json?location=new+york';

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('gobear_jobs');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * Responds to GET requests.
   *
   * @param string $payload
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($payload) {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $result = [];

    // Make sure the job listing contains the following info : Title, Company, Location, Type, Created at, Description
    $jobs = json_decode(file_get_contents(self::API_ENDPOINT));
    foreach ($jobs as $job) {
      if (empty($job->title) || empty($job->company) || empty($job->location) || empty($job->type) || empty($job->created_at) || empty($job->description)) {
        continue;
      }
      else {
        $result[] = (array) $job;
      }
    }

    $payload['success'] = $result;

    return new ResourceResponse($payload, 200);
  }

}