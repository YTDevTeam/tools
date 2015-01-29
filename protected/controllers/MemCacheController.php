<?php
class MemCacheController extends Controller
{
	public function actionIndex()
	{
		$mem = Yii::app()->memcache->memCache;
		$items = $mem->getExtendedStats('items');
		$items=Util::getArray(current($items), 'items');
		$res = array();
		if(!is_array($items)) $items = array();
		foreach($items as $key=>$values){
			$number=$key;
			$str = $mem->getExtendedStats("cachedump",$number,0);
			$res = array_merge($res, array_keys(current($str)));
		}
		$list = array();
		foreach($res as $v)
		{
			$data = Yii::app()->memcache->memCache->get($v);
			if($data !== false)
			{
				$list[] = $v;
			}
		}
		sort($list);
		$this->render('index', array('list'=>$list));
	}

	public function actionView()
	{
		$name = Util::getArray($_GET, 'name');
		if($name)
		{
			$data = Yii::app()->memcache->memCache->get($name);
		}
		else
		{
			$data = false;
		}
		$this->render('view', array('data'=>$data));
	}
}