<?php
	//define('LANGUAGE_EN',							1);
	define('LANGUAGE_CH',							1);
	if ( defined('LANGUAGE_EN')) {
		define('NT_QUERY_PROMPT',		'Querying,Please wait');
		define('NT_SERVER_CODE_LIST',	'Server Code List');
		define('NT_TITLE',				'Title');	
		define('NT_CONTENT',			'Content');
		define('NT_SUBMIT',				'Submit');
		define('NT_CANCEL',				'Cancel');
		define('NT_OPERATE',			'Operate');
		define('NT_MODIFY',				'Modify');
		define('NT_DELETE',				'Delete');
		define('NT_ADD',				'Add');
	} else if ( defined('LANGUAGE_CH')){ 
		define('NT_QUERY_PROMPT',		'正在查询，请稍候');
		define('NT_SERVER_CODE_LIST',	'区号');
		define('NT_TITLE',				'名称');
		define('NT_CONTENT',			'内容');
		define('NT_SUBMIT',				'确认增加');
		define('NT_CANCEL',				'取消');
		define('NT_OPERATE',			'操作');
		define('NT_MODIFY',				'修改');
		define('NT_DELETE',				'删除');
		define('NT_ADD',				'增加');
	}
?>
