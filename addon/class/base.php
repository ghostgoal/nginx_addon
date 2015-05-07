<?
class class_base
{
	function toJSON()
	{
		return json_encode($this);
	}
	
	function fromJSON($json)
	{
		$data = json_decode($json,true);
		if(is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->$k = $v;
			}
			
			return true;
		}
		
		return false;
	}
}

?>