<?php

namespace SXFWVT\XF\Pub\Controller;

use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class Thread extends XFCP_Thread
{
	public function actionIndex(ParameterBag $params)
	{
		$action = parent::actionIndex($params);
		
		$visitor = \XF::visitor();
		
		$activityes = \XF::finder('XF:SessionActivity')->where([
			'view_state' => 'valid',
			'controller_name' => 'XF\Pub\Controller\Thread',
			['user_id', '!=', $visitor->user_id],
			['user_id', '!=', 0]
		])->fetch()->toArray();
		
		$activityUsers = [];
		
		foreach ($activityes as $activity)
		{
			if ($params->thread_id == $activity->params['thread_id'])
			{
				$activityUsers[] = $activity->User;
			}
		}
		
		if ($visitor->user_id)
		{
			$activityUsers = array_merge([$visitor], $activityUsers);
		}
		
		$action->setParams([
			'activityUsers' => $activityUsers
		]);
		
		return $action;
	}
}