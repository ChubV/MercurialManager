<?php

namespace ChubProduction\MercurialManager;

use Symfony\Component\Process\Process;

/**
 * Class to handle mercurial repository information
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class MercurialManager
{
	/** @var string $path Path to repo or null if current directory is one */
	private $path = null;

	/**
	 * @param string $path
	 *
	 * @return MercurialManager
	 */
	public function setPath($path)
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Get node by hash (tip is default)
	 *
	 * @param string $hash
	 *
	 * @return MercurialNode
	 */
	public function getNode($hash = 'tip')
	{
		return new MercurialNode($this, $hash);
	}

	/**
	 * Return hooked node. When used in hook.
	 *
	 * @return MercurialNode
	 */
	public function getHookNode()
	{
		return $this->getNode(getenv('HG_NODE'));
	}

	/**
	 * Get list of node hashes
	 *
	 * @return array
	 */
	public function getNodeList()
	{
		$out = $this->run('log --template "{node}\n"');

		return explode("\n", $out);
	}

	/**
	 * Run command wrapper
	 * @param $cmd
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	public function run($cmd)
	{
		if ($this->path) {
			$cmd = '--cwd="' . $this->path . '" ' . $cmd;
		}

		$cmd = 'hg ' . $cmd;

		$process = new Process($cmd);
		$process->run();

		if (!$process->isSuccessful()) {
			throw new \RuntimeException($process->getErrorOutput());
		}

		return trim($process->getOutput());
	}
}
