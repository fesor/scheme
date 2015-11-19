<?php

/**
* The simple class to generate graphviz schemes
* Requires graphviz installation
*/
class Schceme
{
	private $format;
	private $command;
	private $nodes = array();
	private $links = array();

	function __construct()
	{

	}

	public function generateGraph($format = 'png')
	{	
		passthru($this->getCommand($format));
	}

	public function getCommand($format)
	{
		$command = 'echo "digraph G {'.$this->getGraphBody().'}" | dot -T'.$format;
		return $command;
	}

	public function addNode($name, array $options)
	{
		$this->nodes[$name] = $options; 
	}

	public function addLink($from, $to)
	{
		$this->links[] = array('from' => $from, 'to' => $to);
	}

	public function getGraphBody()
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

	public function parseNodeOptions(array $options)
	{
		$nodeOptions = "";
		foreach ($options as $name => $value) {
			$nodeOptions = $nodeOptions.$name.'='.$value.',';
		}
		return rtrim($nodeOptions,',');

	}

}