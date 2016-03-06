<?php class links extends masterclass {

	use Master;
	
	function extend() {
		$this->description = 'Module for saving links';
		
		$this->options = [
			'tags'	=> $this->getLinkTags(),
		];
	}
	
	
	function getLinkTags() {
		$ret = array();
		$tmp = explode(',', G('importlinks_tags'));
		foreach($tmp as $tag) {
			$ret[$tag] = $tag;
		}	
		return $ret;
	}
	
	
	function gettables() {
		return 
		[
			'links' => [
				'fields' => [					
					'url'			=>	[ 'string', 'text', 'search' => TRUE ],					
					'title'			=>  [ 'string', 'text', 'search' => TRUE ],	
					'tags'			=>	[ 'string',	'checkboxes' ],
					'time'			=>	[ 'date', 'date' ],			
				],
			],
			
			'link_tags' => [
				'fields' => [
					'link_id' => ['int'],
					'tag' => ['string'],
				],
				'fk' => [
					'link_id' => 'links(id)'
				]
			]
		];		
	}
	
	
	function items() {		
		/** importing links first; uncomment this if you dont want to **/
		$this->importLinks();
		$this->parse = true;
		/** end of link import **/
		$page 			= @$this->path[2];
		$oQuery 		= q($this->cl)->qlist('links.*', $page, $this->perpage)->order('`time` DESC');
		$oCountQuery 	= q($this->cl)->qcount();
		/* tag filtering */
		$tag = urldecode(@$this->path[3]);
		if($tag) {
			$oQuery->join('link_tags', "lt.link_id = links.id AND lt.tag='$tag'", 'lt')->group('links.id');
			$oCountQuery->join('link_tags', "lt.link_id = links.id AND lt.tag='$tag'", 'lt')->group('links.id');
		}
		
		$this->pageCount = ceil($oCountQuery->run() / $this->perpage);

		return $oQuery->run();
	}	
	
	function save() {
		$this->post['form']['tags'] = implode(',', $this->post['form']['tags']);
		parent :: save();
		
		/** deleting tags for link **/
		q()->delete()->from('link_tags')->where(qEq('link_id',$this->id))->run();
		/** adding tags for link **/
		$tags = explode(',', $this->post['form']['tags']);
		foreach($tags as $tag) {
			$data = array( 'link_id' => $this->id, 'tag' => $tag);
			q('link_tags')->qadd($data)->run();
		}
	}	
	
	function saveByUrl($url) {
		/* checking if element exists */
		$res = q()
			->select('1')
			->from('links')
			->where(qEq('url',$url))
			->run(DBCELL);
		if($res) {
			return json_encode(array('error' => 'URL already exists'));
		}
		
		/* retrieving content */
		$content = file_get_contents($url);
		preg_match("/<title>(.*)<\/title>/i", $content, $matches);	
		$data = array(
			'url'	=> $url,
			'title'	=> $matches[1],
		);		
		q('links')
			->qadd($data)
			->run();
				
		return json_encode(array('msg' => 'Saved successfuly'));

	}	
	
	function importLinks() {
		$this->parse = false;
		$data = json_decode(file_get_contents(G('importlinks_url') . '?do=get&stream=' . G('importlinks_stream')), true);
		$counter = 0;
		foreach($data as $row) {
			/* checking if url is already added */
			$res = q()
				->select('1')
				->from('links')
				->where(qEq('url', $row['url']))
				->run(DBCELL);
			if($res) continue;
			
			/* if not, then we add */	
			q('links')->qadd($row)->run();
			$counter++;	
		}
		
		file_get_contents(G('importlinks_url') . '?do=clean&stream=' . G('importlinks_stream'));
		
		return json_encode(array('msg' => $counter . ' links imported'));
	}
	
	function edittags() {
		$this->parse = false;
		$this->ajax = true;
		
		$tagsId = q()
			->select('id')
			->from('system')
			->where(qEq('name','importlinks_tags'))
			->run(DBCELL);

		$M = M('system');
		$M->data = $M->edit($tagsId);
		$M->ajax = true;
		return $M->parse();
	}
	
	function install() {
		parent::install();		
		G('importlinks_url', HOST . 'save/');
		G('importlinks_stream', 'my');	
		G('importlinks_tags', 'tag1,tag2');	
	}
	
	function uninstall() {
		parent::uninstall();	
		delG('importlinks_url');
		delG('importlinks_stream');
		delG('importlinks_tags');
	}
	
	
}