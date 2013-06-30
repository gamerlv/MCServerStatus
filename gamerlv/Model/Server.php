<?php
use RedBean_Facade as R;


/**
* 
*/
class Model_Server extends RedBean_SimpleModel
{
	public function getAddress()
	{
		return $this->address . ':' . $this->port;
	}
}