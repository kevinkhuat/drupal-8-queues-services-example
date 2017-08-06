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
 *  id = "facebook-video-processing",
 *  title = @Translation("Facebook Video Processing"),
 *  cron = {"time" = 10}
 * )
 */
class FacebookVideoProcess extends QueueWorkerBase implements QueueWorkerInterface
{

    // @param string - node instance url
    public $facebookViewsUrl;

    public function __construct(array $configuration, $plugin_id, $plugin_definition)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);

        // test url
        $this->facebookViewsUrl = 'http://local.drupalfb.com';
    }

    /**
     * Process queue items
     * @param mixed $data
     */
    public function processItem($data)
    {
        try
        {

            // Put array elements in their own variables
            $nid = $data[0];
            $pageId = $data[1];
            $videoId = $data[2];

            // add GET parameters
            $requestUrl = $this->facebookViewsUrl . '?fb_page_id=' . $pageId . '&fb_video_id=' . $videoId;

            // Invoke facebook_video.default service
            $facebookVideoService = \Drupal::service('facebook_video.default');

            // make an http request to node instance
            $jsonResponse = $facebookVideoService->fetchJSONFromEndpoint($requestUrl);

            // Only update node if the response is not empty
            if (!empty($jsonResponse)) {
                $jsonDecode = \GuzzleHttp\json_decode($jsonResponse);
                $views = $jsonDecode->views;
                // Save node
                $facebookVideoService->saveNode($nid, $views);
            }
        }
        catch (SuspendQueueException $e)
        {
            // send this back to the queue
        }
    }
}