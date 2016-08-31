<?php 


require('AlboCittaDellaSaluteEntry.php');

class AlboCittaDellaSaluteParser implements Iterator{
	private $rows;
	private $index;

	/**
	 * Parse the entries of the Albo from the rows of the table in the Albo Pretorio page.
	 */
	public function __construct($page) 
	{
		$this->rows=$page;
		$this->index=1;
	}

	public function current(){
		return new AlboCittaDellaSaluteEntry($this->rows->atto->atti[$this->index]);
	}
	
	
	public function key (){
		return $this->index;
	}
	
	public function next(){
		++$this->index;
	}
	
	public function rewind(){
		$this->index=1;
	}
	
	public function valid(){
		return $this->rows->atto->length>1 && $this->index<($this->rows->atto->length);
	}
}
?>