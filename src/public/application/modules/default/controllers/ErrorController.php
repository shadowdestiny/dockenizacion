<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {	
	//$draw_id=111;
	//$product_id=1;
	
	//$obj_s = new Default_Model_Stats();
	//$obj_s->setTicketstats("add_to_cart",1,$draw_id,$product_id);
	//exit;

	/*
	$cart_id=11;
	$post_id=1413280204;
	$obj_lc = new Logic_Cart();
	$obj_lc->delItemGroupFromCart($cart_id,$post_id);
	exit;
	*/
        $errors = $this->_getParam('error_handler');
		
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';			
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

echo $this->view->message;
echo $priority;
echo $errors->exception        ;
exit;
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
		
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
		if ( Zend_Registry::isRegistered  ("Zend_Log") )
		{
			return  Zend_Registry::get ("Zend_Log");
		} else {
			return false;
		}
        
    }

	public function noaccessAction()
	{

	}

}

