<?php
class class_cache
{
	protected $m_class_session;
	protected $m_cache_factory;
	
	
	
	function __construct($class_session,$class_config)
	{
		$this->m_class_session = $class_session;
		$this->m_cache_factory = new class_modelFactory($class_config->getCacheModel(),$class_query);
	}
	
	function getCacheID($id)
	{
		return md5($id);
	}
	
	function cacheQuery($table,$query,$result)
	{
		$cache->['ID'] = $this->getCacheID($query);
		$cache->['TID'] =$table;
		$cache->['RESULT'] = $result;
		
		return $this->m_class_session->save($cache);
		
	}
	function getQueryCache($table,$query)
	{
		if($this->m_class_session->load($cache,$this->getCacheID($query)))
		{
			return $cache->['RESULT'];
		}
		return false;
		
		
	}
	function cleanQueryCache($table)
	{
		$fmt = 'where TID="%s"';
		$where = sprintf($fmt,$table);
		return $this->m_class_session->delete($cache,$where);
	}
	function cacheRequest($user,$request,$response)
	{
		
	}
	function getRequestCache($user,$request)
	{
		
	}
	function cleanRequestCache()
	{
		
	}
}
?>