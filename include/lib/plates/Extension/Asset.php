<?php

namespace Template\Extension;

use Template\Engine;
use LogicException;

/**
 * Extension that adds the ability to create "cache busted" asset URLs.
 */
class Asset implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * Path to asset directory.
     * @var string
     */
    public $path;

    /**
     * Enables the filename method.
     * @var boolean
     */
    public $filenameMethod;

    /**
     * Create new Asset instance.
     * @param string  $path
     * @param boolean $filenameMethod
     */
    public function __construct($path, $filenameMethod = false)
    {
        $this->path = rtrim($path, '/');
        $this->filenameMethod = $filenameMethod;
    }

    /**
     * Register extension function.
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('asset', array($this, 'cachedAssetUrl'));
    }

    /**
     * Create "cache busted" asset URL.
     * @param  string $url
     * @return string
     */
    public function cachedAssetUrl($url, $cache = true)
    {
		$base_root = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']),array('off','no'))) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
		$base_url = $base_root; $parts = explode('/', $_SERVER['PHP_SELF']);
		$key = array_search('index.php', $parts);
		if ($key !== false) $base_url = $base_root.implode('/', array_slice($parts, 0, $key));	

        $filePath = $this->path . '/' .  ltrim($url, '/');

        if (!file_exists($filePath)) {
            throw new LogicException(
                'Unable to locate the asset "' . $url . '" in the "' . $this->path . '" directory.'
            );
        }

        $lastUpdated = filemtime($filePath);
        $pathInfo = pathinfo($url);

        if ($pathInfo['dirname'] === '.') {
            $directory = '';
        } elseif ($pathInfo['dirname'] === '/') {
            $directory = '/';
        } else {
            $directory = $pathInfo['dirname'] . '/';
        }

		if ($cache == true) {
			if ($this->filenameMethod) {
				return $base_url.'/'.$this->path . $directory . $pathInfo['filename'] . '.' . $lastUpdated . '.' . $pathInfo['extension'];
			} else {
				return $base_url.'/'.$this->path . $directory . $pathInfo['filename'] . '.' . $pathInfo['extension'] . '?v=' . $lastUpdated;
			}
		} else {
			return $base_url.'/'.$this->path . $directory . $pathInfo['filename'] . '.' . $pathInfo['extension'];
		}
    }
}
