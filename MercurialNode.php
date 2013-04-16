<?php

namespace ChubProduction\MercurialManager;

/**
 * MercurialNode
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class MercurialNode
{
	private $manager;
	private $hash;
	private $init = false;
	private $info;

	/**
	 * @param MercurialManager $manager
	 * @param string           $hash
	 */
	public function __construct(MercurialManager $manager, $hash)
	{
		$this->manager = $manager;
		$this->hash = $hash;
	}

	/**
	 * Init node with node information
	 */
	public function init()
	{
		if ($this->init) {
			return;
		}

		$infoKeys = array('author', 'branches', 'date|isodate', 'desc', 'rev', 'parents', 'tags');
		$delimiter = '<<<<' . $this->hash . '>>>>';

		$strInfos = '{' . implode('}' . $delimiter . '{', $infoKeys) . '}';
		$res = $this->manager->run('log -r ' . $this->hash . ' --template "' . $strInfos . '"');
		$infoValues = explode($delimiter, $res);
		$infoArray = array_combine($infoKeys, $infoValues);

		$this->info = $infoArray;

		$this->init = true;
	}

	/**
	 * @return array
	 */
	public function getTags()
	{
		$this->init();

		return explode("\n", trim($this->info['tags']));
	}

	/**
	 * @return array
	 */
	public function getParents()
	{
		$this->init();

		return explode("\n", trim($this->info['parents']));
	}

	/**
	 * @return string
	 */
	public function getBranch()
	{
		$this->init();

		return trim($this->info['branches']);
	}

	/**
	 * @return string
	 */
	public function getRev()
	{
		$this->init();

		return $this->info['rev'];
	}

	/**
	 * @return string
	 */
	public function getDesc()
	{
		$this->init();

		return $this->info['desc'];
	}

	/**
	 * @return \DateTime
	 */
	public function getDate()
	{
		$this->init();
		$date = new \DateTime($this->info['date|isodate']);

		return $date;
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		$this->init();

		return $this->info['author'];
	}
}
