<? include_once $_SERVER["DOCUMENT_ROOT"] . "/common/classes/comm/Common.php" ; ?>
<?
if(!class_exists("FrontBase")){
	class FrontBase extends Common{

		function __construct($req) 
		{
			parent::__construct($req);
		}
		
		function wrapParam()
		{
			$this->req['page']	= ($this->req['page'] == "") ? 1 : $this->req['page'] ;
		}
		
		
	}	
}
?>