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
	// Path to graphviz/bin/dot on your machine
	private $path;

	/*
	*	@var path Path to graphviz
	*   leave it empty if graphviz is in your path
	*/
	function __construct($path = 'dot')
	{
		$this->path = $path;
	}

	/**
	* This method runs command,
	* and sends result to browser
	*/	
	public function generateGraph($dotFileName, $format = 'png')
	{	
		$image = passthru($this->getCommand($dotFileName, $format), $error);
		if($error){
			throw new Exception("Error executing command");
		}
		return $image;
	}

	/**
	* This method runs command,
	* and saves result to file
	*/	
	public function saveGraph($dotFileName, $outputFileName, $format = 'png')
	{	
		exec($this->getCommand($dotFileName, $format,  $outputFileName), $output, $result);
		return $result;
	}

	/**
	* This method creates and gets command
	* to generate graph
	*/	
	public function getCommand($dotFileName, $format, $outputFileName = null)
	{
		$outputFileName = (empty($outputFileName))?'':'-o'.$outputFileName;
		$command = '"'.$this->path.'" '.$dotFileName.' -T'.$format.' '.$outputFileName;
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
			$graphBody = $graphBody.'"'.$link['from'].'"->"'.$link['to'].'";'.PHP_EOL;
		}
		return $graphBody;
	}

	/*
	* Gets full contents of graph file,
	* which can be executed by graphviz
	*/
	public function getGraph($name = 'G'){
		$graph  = 'digraph '.$name.' { ';
		$graph .= $this->getGraphBody();
		$graph .= ' }';
		return $graph;
	}

	/*
	* Writes dot code to file
	*/
	public function writeDotFile($filename){
		
		$file = fopen($filename, 'w');
		if(!$file){
			throw new Exception("Can't open file");			
		}

		$success = fwrite($file, $this->getGraph());

		if(!$success){
			throw new Exception("Can't write file ".$filename);
		}

		return $success;
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