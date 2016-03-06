<?php
class daily extends masterclass {
	
	use Master;

	function gettables() {
		return [
			'daily' => [
				'fields' => [
					'day' 	=> [ 'date', 'hidden' ],
					'text' 	=> [ 'text', 'html', ],
				],
			],
		];	
	}

	
	function add($day = null) { 
		$day = $this->id;
		if(empty($day)) $day = date('Y-m-d');
		$text = q()
			->select('text')
			->from('daily')
			->where(qEq('day', $day))
			->run(DBCELL);
		
		$data = [ 
			'day' => $day,
			'text' => $text,
		];	
		
		$this->title = fDate($day);
		$this->tpl = 'addedit';  
		return array('data' => $data, 'fields' =>$this->fields, 'options'=> $this->options);
	}
	
	
	/** Save element **/
    public function save() { 
		$this->parse = FALSE; 
		
		/* preprocess data */
		$data = $this->post['form'];
		$day = $data['day'];
		$f = $this->fields;
		foreach($data as $k => $v) {
			$data[$k] = sqlFormat($f[$k][1],$v);
		}
		
		/* Determines query type; 
		Update if element exists, insert if it`s a new element **/
		$res = q()
			->select('1')
			->from('daily')
			->where(qEq('day', $day))
			->run(DBCELL);	
		if($res) {
			$oQuery = q($this->cl)->qedit($data, qEq('day', $day));
		} else {
			$oQuery = q($this->cl)->qadd($data);	
		} 		
		/** Executing query, retrieving id, returning result **/	
		$oQuery->run();
		if($res < 1) {
			return json_encode(array('redirect' => BASE_URL . $this->cl . '/edit/' . $day));			
		}
		return json_encode(array('msg' => 'ok', 'day' => $day));
	}
	
	
	
	function import() {
		$url = G('importlinks_url') . '?do=get&stream=text';
		$data = json_decode(file_get_contents($url, true));
		$counter = 0;
		foreach($data as $key => $text) {
			$datetime = explode(' ', $key);
			$day = $datetime[0];
			$time = $datetime[1];
							
			$addtext = PHP_EOL . fTime($time) . PHP_EOL . $text . PHP_EOL;	
			
			$data = q()
				->select()
				->from('daily')
				->where(qEq('day', $day))
				->run(DBROW);
				
			if($data) {
				$data['text'] .= $addtext; inspect($data);
				q($this->cl)->qedit($data, qEq('day', $day))->run();
			} else {
				$data = [ 'day' => $day, 'text' => $addtext];
				q($this->cl)->qadd($data)->run();
			}
		}	
		file_get_contents(G('importlinks_url') . '?do=clean&stream=text');		
	}
	
	
	function admin() {
		//$this->import();
		
		$page 			= @$this->path[2];
		$oQuery 		= q($this->cl)->qlist('daily.*', $page, $this->perpage)->order('`day` DESC');
		$oCountQuery 	= q($this->cl)->qcount();
		$this->pageCount = ceil($oCountQuery->run() / $this->perpage);

		return $oQuery->run();
	}
}