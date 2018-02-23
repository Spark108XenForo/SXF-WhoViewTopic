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
		
		$activityes = \XF::finder('XF:SessionActivity')->where([
			'view_state' => 'valid',
			'controller_name' => 'XF\Pub\Controller\Thread'
		])->fetch()->toArray();
		
		$visitor = \XF::visitor();
		
		$threadId = $params->thread_id;
		$userId = $visitor->user_id;
		
		$activityes = array_map(function($activity) use ($threadId, $userId)
		{
			$user = $activity->User;
			
			if ($threadId == $activity->params['thread_id'] && $user->user_id != $userId)
			{
				return $user;
			}
		}, $activityes);
		
		$activityes = array_diff($activityes, ['']);
		$activityes = array_values($activityes);
		
		if ($visitor->user_id)
		{
			$activityes[] = $visitor;
		}
		
		$action->setParams([
			'activityes' => $activityes
		]);
		
		return $action;
	}
}