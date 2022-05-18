<?php

namespace BuzzingPixel\Treasury\Service;

class FileCacheService
{
	/**
	 * Check file cache path
	 */
	private static function checkCachePath()
	{
		if (! is_dir(TREASURY_CACHE_PATH)) {
			mkdir(TREASURY_CACHE_PATH);
		}
	}

	/**
	 * Create an empty cache file
	 *
	 * @param string $ext Optional
	 */
	public static function createEmptyFile($ext = '')
	{
		// Check cache path
		self::checkCachePath();

		// Add a period to the file extension
		if ($ext) {
			$ext = ".{$ext}";
		}

		// Set the cache file name
		$cacheFile = TREASURY_CACHE_PATH . uniqid() . $ext;

		// Write the file to cache
		file_put_contents($cacheFile, '');

		// Return the file path/name
		return $cacheFile;
	}

	/**
	 * Cache a file
	 *
	 * @param string $file Path on disk or URL
	 * @param string $extension
	 * @return string Path to cached file
	 */
	public static function cacheByPath($file, $extension = false)
	{
		// Check cache path
		self::checkCachePath();

		// Get file info so we can get the extension
		$fileInfo = pathinfo($file);

		// Get the file contents
		$file = self::getFileContents($file);

		// If there is no file, end processing
		if (! $file) {
			return;
		}

		// If extension not passed in and file to cache has extension
		if (! $extension and isset ($fileInfo['extension'])) {
			$extension = $fileInfo['extension'];
		}

		// Set the cache file name
		if ($extension) {
			$cacheFile = TREASURY_CACHE_PATH . uniqid() . '.' . $extension;
		} else {
			$cacheFile = TREASURY_CACHE_PATH . uniqid();
		}

		// Write the file to cache
		file_put_contents($cacheFile, $file);

		// Return the file path/name
		return $cacheFile;
	}

	/**
	 * Get file
	 *
	 * @param string $file
	 */
	private static function getFileContents($file)
	{
		// File get contents context
		$context = stream_context_create(array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false
			)
		));

		// Try the file as is and return it if applicable
		$tryFile = $file;
		$fileContents = @file_get_contents($tryFile, false, $context);
		if ($fileContents) {
			return $fileContents;
		}

		// Get the site URL
		$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
		$protocol = $secure ? 'https://' : 'http://';
		$siteUrl = $protocol . $_SERVER['SERVER_NAME'];

		// Try the file by prepending the site URL
		$tryFile = $siteUrl . $file;
		$fileContents = @file_get_contents($tryFile, false, $context);
		if ($fileContents) {
			return $fileContents;
		}

		// Try the file by prepending the site url and a forward slash
		$tryFile = $siteUrl .'/' . $file;
		$fileContents = @file_get_contents($tryFile, false, $context);
		return $fileContents;
	}

	/**
	 * Clean up
	 */
	public static function cleanUp()
	{
		$cachePath = TREASURY_CACHE_PATH;
		array_map('unlink', glob("{$cachePath}*"));
	}
}
