<?php

namespace Nonda\Shopify;

class ShopifyIterator  implements \Iterator
{
    private $client;

    private $limit = 150;

    private $entity;

    private $args;

    private $parseResponse;

    private $ttl;

    private $flush;

    public function __construct(ShopifyClient $client, $entity, callable $parseResponse, $args = [], $ttl = 86400, $flush = false)
    {
        $this->client = $client;
        $this->entity = $entity;
        $this->args = $args;
        $this->pageData = [];
        $this->parseResponse = $parseResponse;
        $this->ttl = $ttl;
        $this->flush = $flush;

        if (!empty($args['limit'])) {
            $this->limit = $args['limit'];
        }
    }

    private $currentPage;

    private $pageData;

    private $lastResponse;

    private $currentPosition;

    private $nextPageUrl;

    private $booted = false;

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (!$this->booted) {
            $this->rewind();
        }

        return $this->pageData[$this->currentPosition];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if (!$this->booted) {
            $this->rewind();
        }

        $this->currentPosition++;

        if (count($this->pageData) === $this->currentPosition) {
            $this->nextPage();
        }
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if (!$this->booted) {
            $this->rewind();
        }

        return ($this->currentPage - 1) * $this->limit + $this->currentPosition;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (!$this->booted) {
            $this->rewind();
        }

        if ($this->nextPageUrl) {
            return true;
        }

        return isset($this->pageData[$this->currentPosition]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $query = http_build_query($this->args);
        $endpoint = '/admin/api/'.ShopifyClient::API_VERSION.'/'.$this->entity.'.json?'.$query;
        $this->prepareCacheResponse($endpoint);
        $this->currentPage = 1;
        $this->currentPosition = 0;
        $this->booted = true;
    }

    private function setResponseData($data, $header)
    {
        $this->lastResponse = $data;
        $parser = $this->parseResponse;
        $this->pageData = $parser($data);
        $this->nextPageUrl = null;

        preg_match("/<https:\/\/.*\/admin\/(.*)>; rel=\"next\"/", $header, $next);

        if (!empty($next[1])) {
            $this->nextPageUrl = $next[1];
        }

        return $data;
    }

    private function prepareCacheResponse($endpoint)
    {
        $cached = null;

        if (is_callable($this->getCacheCallback) && is_callable($this->setCacheCallback) && !$this->flush) {
            $cached = call_user_func($this->getCacheCallback, $this, $endpoint);
        }

        if (!empty($cached) && ($cached = json_decode($cached, true)) && !empty($cached['data'])) {
            $data = $this->setResponseData($cached['data'], empty($cached['next_link']) ? '' : $cached['next_link']);
        } else {
            $response = $this->client->getRaw($endpoint);
            $body = $response->getBody()->getContents();
            $nextLink = $response->getHeaderLine('Link') ?: '';
            $data = $this->setResponseData($body, $nextLink);

            if (is_callable($this->setCacheCallback)) {
                call_user_func(
                    $this->setCacheCallback,
                    $this,
                    $endpoint,
                    json_encode([
                        'data' => $body,
                        'next_link' => $nextLink,
                    ]), false); // api url, remove cache
            }
        }

        return $data;
    }

    private function nextPage()
    {
        if (!$this->nextPageUrl) {
            return false;
        }

        $this->prepareCacheResponse('/admin/'.$this->nextPageUrl);
        $this->currentPage++;
        $this->currentPosition = 0;

        return true;
    }

    private $getCacheCallback;
    private $setCacheCallback;

    /**
     * @param callable $getCacheCallback
     *
     * @return self
     */
    public function getCache(callable $getCacheCallback)
    {
        $this->getCacheCallback = $getCacheCallback;
        return $this;
    }

    /**
     * @param callable $setCacheCallback
     *
     * @return self
     */
    public function setCache(callable $setCacheCallback)
    {
        $this->setCacheCallback = $setCacheCallback;
        return $this;
    }

    public function getTtl()
    {
        return $this->ttl;
    }
}