<?php

namespace Drupal\facebook_video\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Queue\QueueWorkerInterface;
use Drupal\Core\Queue\SuspendQueueException;

/**
 * Class FacebookVideoProcess
 * @package Drupal\facebook_video\Plugin\QueueWorker
 *
 * @QueueWorker(
 *  id = 'facebook-video-processing',
 *  title = @Translation('Facebook Video Processing'),
 *  cron = {"time" = 10}
 * )
 */
class FacebookVideoProcess extends QueueWorkerBase implements QueueWorkerInterface {

    public function __construct(array $configuration, $plugin_id, $plugin_definition)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }

    public function processItem($data)
    {
        // TODO: Implement processItem() method.
        // Use DefaultFacebookFetchVideoPlugin to make request to node instance
    }
}