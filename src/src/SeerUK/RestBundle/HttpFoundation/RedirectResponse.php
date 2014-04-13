<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectcResponse
 */
class RedirectResponse extends Response
{
    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * Constructor
     *
     * @param string  $url     The URL to redirect to
     * @param integer $status  The status code (302 by default)
     * @param array   $headers The headers (Location is always set to the given URL)
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($url, $status = 302, $headers = array())
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        parent::__construct('', $status, $headers);

        $this->headers->set('Content-Type', 'application/json');
        $this->setTargetUrl($url);

        if (!$this->isRedirect()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code is not a redirect ("%s" given).', $status));
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function create($url = '', $status = 302, $headers = array())
    {
        return new static($url, $status, $headers);
    }

    /**
     * Returns the target URL.
     *
     * @return string
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * Sets the redirect target of this response.
     *
     * @param  string $url
     * @return RedirectResponse
     *
     * @throws \InvalidArgumentException
     */
    public function setTargetUrl($url)
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->targetUrl = $url;
        $this->headers->set('Location', $url);

        return $this;
    }
}
