<?php

class Hooks_poptastic extends Hooks
{

	public function _render__after($data)
	{
		$url = URL::getCurrent();
		$page = Content::get($url);

		if($url != '/' AND count($page) > 0)
		{
			$ip = Request::getIP();
			$agent = Request::getUserAgent();
			$is_unique = FALSE;

			// Check to see if visit is unique based on cache.
			if($this->cache->exists('rawdata'))
			{
				$rawdata = json_decode($this->cache->get('rawdata'), TRUE);

				// Check to see if this page has been visited before... ever.
				if(array_key_exists($url, $rawdata))
				{
					// Check to see if this IP/browser combination has ever visited this page before (records only unique visits based on this criteria).
					if(array_key_exists($ip, $rawdata[$url]) === FALSE OR array_search($agent, $rawdata[$url][$ip]) === FALSE)
					{
						$rawdata[$url][$ip][] = $agent;
						$is_unique = TRUE;
					}
				}
				else
				{
					$rawdata[$url][$ip][] = $agent;
					$is_unique = TRUE;
				}
			}
			else
			{
				$rawdata[$url][$ip][] = $agent;
				$is_unique = TRUE;
			}

			$this->cache->put('rawdata', json_encode($rawdata));

			// Increment counter variable in content file if visit is unique.
			if($is_unique)
			{
				$file = $page['_file'];
				$rawfile = File::get($file);
				$yaml = Parse::frontMatter($rawfile);

				$hits = array_get($yaml['data'], 'poptastic', 0);
				$hits++;

				$yaml['data']['poptastic'] = $hits;

				$contents = File::buildContent($yaml['data'], $yaml['content']);

				File::put($file, $contents);
			}
		}

		return $data;
	}

}