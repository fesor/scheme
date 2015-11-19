<?php

/**
* The simple class to generate graphviz schemes
* Requires graphviz installation
*/
class Schceme
{
	
	// Here are stored nodes that needs 
	// special options
	private $nodes = array();
	// Here are stored all relations between nodes
	private $links = array();

	function __construct()
	{
		// some init settings
	}

	/**
	* This method runs command,
	* and sends result to browser
	*/	
	public function generateGraph($format = 'png')
	{	
		passthru($this->getCommand($format));
	}

	/**
	* This method creates and gets command
	* to generate graph
	*/	
	private function getCommand($format)
	{
		$command = 'echo "digraph G {'.$this->getGraphBody().'}" | dot -T'.$format;
		return $command;
	}

	/**
	* This method adds node to $this->nodes
	* options need to have this format('option'=>'value')
	*/	
	public function addNode($name, array $options)
	{
		$this->nodes[$name] = $options; 
	}

	/**
	* This method adds relation between two nodes
	* if nodes are not defined, graphviz will create them
	*/	
	public function addLink($from, $to)
	{
		$this->links[] = array('from' => $from, 'to' => $to);
	}

	/**
	* This method generates valid graphviz syntax
	* from arrays
	*/	
	private function getGraphBody()
	{
		$graphBody = '';

		foreach ($this->nodes as $node=>$options) {
			$graphBody = $graphBody.$node.'['.$this->parseNodeOptions($options).'];'.PHP_EOL;
		}

		foreach ($this->links as $link) {
			$graphBody = $graphBody.'\"'.$link['from'].'\"->\"'.$link['to'].'\";'.PHP_EOL;
		}
		return $graphBody;
	}

	/**
	* This method parses options to valid graphviz syntax
	*/	
	public function parseNodeOptions(array $options)
	{
		$nodeOptions = "";
		foreach ($options as $name => $value) {
			$nodeOptions = $nodeOptions.$name.'='.$value.',';
		}
		return rtrim($nodeOptions,',');

	}

}