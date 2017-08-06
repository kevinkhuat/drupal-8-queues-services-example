<?php

namespace Drupal\facebook_video\Utility;
use Drupal\Node\Entity\Node;
use GuzzleHttp\Exception\RequestException;

class FacebookVideoService
{
    // @param string - node type
    protected  $nodeType;

    // @param array - array of nodes
    protected $nodeList;

    /**
     * FacebookVideoService constructor.
     * @param $type string - node type
     */
    public function __construct($type)
    {
        // Set the node type we will query the database for
        $this->nodeType = $type;
        $this->nodeList = [];
    }

    /**
     * Fetch all nodes by type
     */
    public function fetchNodes()
    {
        // Ensures that we are making the database query once per request
        if (!empty($this->nodeList))
        {
            return $this->nodeList;
        }

        // Fetch all nids for this node type
        $nids = \Drupal::entityQuery('node')
            ->condition('type', $this->nodeType)
            ->execute();
        // Get the node object for each nid so that we can access the fields
        $nodes = Node::loadMultiple($nids);

        // Assign nodeList with the array of $nodes to prevent the query from getting called
        // again during this request
        $this->nodeList = $nodes;

        return $nodes;
    }

    /**
     * Make an http request to the node instance
     * @param $url
     */
    public function fetchJSONFromEndpoint($url)
    {
        try
        {
            // Attempt to make a request to the node instance
            $response = \Drupal::httpClient()->get($url);
            $data = (string) $response->getBody();
            return $data;
        }
        catch (RequestException $e)
        {
            // Http request failed
            watchdog_exception('facebook_video', $e);
            return false;
        }
    }

    /**
     * Save node
     * @param $nid
     * @param $views
     */
    public function saveNode($nid, $views)
    {
        // load node
        $node = Node::load($nid);
        // set new video views value
        $node->set('field_facebook_video_views', $views);
        // save node
        $node->save();
    }
}