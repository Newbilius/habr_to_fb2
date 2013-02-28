<?php
/* phpMultiCurl
 * @url http://code.google.com/p/phpmulticurl/
 * @license MIT
 */
class phpMultiCurlException extends Exception
{}
class phpMultiCurl
{
	const CURLOPT_IGNORETIMEOUT = 'CURLOPT_IGNORETIMEOUT';

	protected $urls = array();
	protected $treads = array();
	protected $treadsData = array();
	protected $treadsFree = array();

	protected $curlOptions =
		   array(
			CURLOPT_HEADER => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			#417 error with lighttpd
			CURLOPT_HTTPHEADER => array('Expect:'),
			CURLINFO_HEADER_OUT => TRUE,
			#just set
			//self::CURLOPT_IGNORETIMEOUT => TRUE
			);

	protected $mcurl = NULL;
	protected $hash = 0;
	protected $numTreads = 25;

	protected $usleep = 50000;
	protected $select = '0.5';

	public function __construct()
	{
		$this->mcurl = curl_multi_init();
	}

	public function addUrl($Url, $onLoad, $onError, array $Data = array(), array $Options = array())
	{
		$hash = $this->hash();
		if (!is_callable($onLoad) || !is_callable($onError))
		{
			throw new phpMultiCurlException('Bad callback');
		}
		$this->urls[$hash] = array($Url, $onLoad, $onError, $Data, $Options);
		return $hash;
	}

	public function deleteUrl($Hash)
	{
	    if (isset($this->treads[$Hash]))
		{
			curl_close($this->treads[$Hash]);
			unset($this->treads[$Hash]);
		}
		if (isset($this->treadsData[$Hash]))
		{
			unset($this->treadsData[$Hash]);
		}
		unset($this->urls[$Hash]);
	}

	public function load()
	{
		while ($this->countUrls() > 0 || $this->countTreads() > 0)
		{
			$this->checkTreads();
			$running = null;
			do
			{
				do
				{
					usleep($this->usleep);
					$result = curl_multi_exec($this->mcurl, $running);
				}
				while ($result === CURLM_CALL_MULTI_PERFORM);
				$ready = curl_multi_select($this->mcurl, (float) $this->select);
				if($result == CURLM_OK)
				{
					while ($done = curl_multi_info_read($this->mcurl))
					{
						$hash = array_search($done['handle'], $this->treads);
						if ($done['result'] == 0)
						{
							$info = curl_getinfo($this->treads[$hash]);
							$content = curl_multi_getcontent($this->treads[$hash]);
							$header=substr($content,0,$info['header_size']);
							$content=substr($content,$info['header_size']);
							$info['response_header'] = $header;
							call_user_func($this->treadsData[$hash]['onLoad'], $content, $info, $this->treadsData[$hash]['data']);
							unset($header);
							unset($info);
							unset($content);
						}
						else
						{
							if ($done['result'] == 28 && isset($this->treadsData[$hash]['options'][self::CURLOPT_IGNORETIMEOUT]))
							{
								$this->treadsData[$hash]['options'][CURLOPT_CONNECTTIMEOUT] = 0;
								$this->treadsData[$hash]['options'][CURLOPT_TIMEOUT] = 2147483647;
								$this->addUrl($this->treadsData[$hash]['url'], $this->treadsData[$hash]['onLoad'], $this->treadsData[$hash]['onError'], $this->treadsData[$hash]['data'], $this->treadsData[$hash]['options']);
							}
							else
							{
								call_user_func($this->treadsData[$hash]['onError'], curl_error($this->treads[$hash]), $this->treadsData[$hash]['data']);
							}
						}
						$this->closeTread($hash);
					}
					$this->checkTreads();
				}
			}
			while ($running > 0  && $ready != -1);
		}
	}

	protected function openTread($Hash, $Url, $onLoad, $onError, $Data, $Options)
	{
		if ($this->countTreadsFree() == 0)
		{
			$curl = curl_init();
		}
		else
		{
			$curl = array_shift($this->treadsFree);
			if (is_resource($curl))
			{
				curl_setopt_array($curl, array());
			}
			else
			{
				$curl = curl_init();
			}
		}
		$Options = $this->array_merge_keys($Options, $this->curlOptions);
		$ops = array();
		foreach ($Options as $k=>$v)
		{
			if (is_int($k))
			$ops[$k] = $v;
		}
		curl_setopt($curl, CURLOPT_URL, $Url);
		curl_setopt_array($curl, $ops);
		$this->treads[$Hash] = $curl;
		$this->treadsData[$Hash]['url'] = $Url;
		$this->treadsData[$Hash]['onLoad'] = $onLoad;
		$this->treadsData[$Hash]['onError'] = $onError;
		$this->treadsData[$Hash]['data'] = $Data;
		$this->treadsData[$Hash]['options'] = $Options;
		curl_multi_add_handle($this->mcurl, $this->treads[$Hash]);
	}

	protected function closeTread($Hash)
	{
		curl_multi_remove_handle($this->mcurl, $this->treads[$Hash]);
		if ($this->numTreads > ($this->countTreadsFree() + $this->countTreads()))
		{
			$this->treadsFree[] = $this->treads[$Hash];
		}
		unset($this->treads[$Hash]);
		unset($this->treadsData[$Hash]);
	}

	protected function checkTreads()
	{
		while ($this->countTreads() < $this->getNumTreads() && $hash = key($this->urls))
		{
			$array = current($this->urls);
			$this->openTread($hash, $array[0], $array[1], $array[2], $array[3], $array[4]);
			unset($this->urls[$hash]);
			reset($this->urls);
		}
	}

	public function __destruct()
	{
		foreach ($this->treads as $k => $tread)
		{
			curl_close($tread);
			unset($this->treads[$k]);
		}
		foreach ($this->treadsFree as $k => $tread)
		{
			curl_close($tread);
			unset($this->treadsFree[$k]);
		}
		curl_multi_close($this->mcurl);
	}

	protected function array_merge_keys($a1, $a2)
	{
		foreach($a2 as $k=>$v)
		{
			if (is_array($v))
			{
				$a1[$k] = $this->array_merge_keys(array_key_exists($k, $a1) ? $a1[$k] : array(), $a2[$k]);
			}
			else
			{
				$a1[$k] = $v;
			}
		}
		return $a1;
	}

	protected function hash()
	{
		$this->hash++;
		return md5($this->hash);
	}

	//HACK for gzip see http://www.php.net/manual/en/function.gzuncompress.php#101643
	public function gzdecode($string)
	{
		return file_get_contents('compress.zlib://data:who/cares;base64,'. base64_encode($string));
	}

	public function setNumTreads($numTreads)
	{
		$this->numTreads = ((int) $numTreads > 0) ? (int) $numTreads : 1;
	}

	public function getNumTreads()
	{
		return $this->numTreads;
	}

	public function countTreads()
	{
		return count($this->treads);
	}

	public function countTreadsFree()
	{
		return count($this->treadsFree);
	}

	public function countUrls()
	{
		return count($this->urls);
	}

	public function setUsleep($int)
	{
		$this->usleep = ((int) $int > 0) ? (int) $int : 1;
	}

	public function getUsleep()
	{
		return $this->usleep;
	}

	public function setSelect($float)
	{
		$this->select = ((float) $float > 0) ? (float) $float : 1;
	}

	public function getSelect()
	{
		return $this->select;
	}
}

