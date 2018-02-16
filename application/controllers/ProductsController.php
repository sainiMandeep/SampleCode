<?php 
class ProductsController extends Zend_Controller_Action
{
    public function init() 
    {
        if (!Application_Model_User::isVendor()) {
            $this->_redirect('/');
        }               
    }

    public function indexAction()
    {   
        $this->view->current_page = array(
            'menu' => 'products', 
            'label' => 'Recovery Products', 
            'subtitle' => 'Manage Recovery Products Processing Cost'
        );
        $this->view->no_breadcrumb = true; 
    }

    public function listAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();

        if ($request->isGet()) {
            $productsModel = new Application_Model_Products();
            $products = $productsModel->findAll();           
            $this->sendResponse(200, $products);
            return; 
        }
        
    }
    public function saveAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true); 
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;        
        } 
        
        $product = $request->getPost();

        if (isset($product['id'])) {
            if (!isset($product['field'])) {
                $this->sendError(400, 'Field paramater must be set');
                return;
            }          

            if (!isset($product['value'])) {
                $this->sendError(400, 'Value paramater must be set');
                return;
            }             
        }

        $validator = new Zend_Validate_Float();;
        $validator->setMessage('Please check entered processing cost.');
        if (!$validator->isValid($product['value'])) {
            $messages = $validator->getMessages();
            foreach ($messages as $key => $value) {
                $message = $value;
            }
            $this->sendError(409, $message);
            return false;
        }
        $modelProducts = new Application_Model_Products();
        $currentProduct = $modelProducts::productById($product['id']);
        $processing_cost = $currentProduct['processing_cost'];

        if($processing_cost != $product['value']) {
            try {

                $key = $modelProducts->save($product);
                if (!$key) {
                    $this->sendError(500, 'Failed to save item processing cost.');
                    return;
                }
                $response = array(
                    'status' =>'success',
                    'message' => 'Item processing cost is saved successfully.'
                );                
                $this->sendResponse(201, $response);
                $savedProduct = Application_Model_Products::productById($product['id']);
                $savedProduct['costBeforeChange'] = $processing_cost;               
                Ontraq_Mail::sendMwsPriceChange($savedProduct);    

            } catch (Exception $e) {
                $this->_forward('error','error');
                return;
            }    
        }
    }   
    
    private function sendError($responseCode, $errorMessage) 
    {
        $response = $this->getResponse();
        $response->setHttpResponseCode($responseCode);
        $response->setHeader('Content-Type', 'application/json');
        $response->appendBody(json_encode($errorMessage, JSON_PRETTY_PRINT));        
    }

    private function sendResponse($responseCode, $responseObject = null) 
    {
        $response = $this->getResponse();
        $response->setHttpResponseCode($responseCode);
        $response->setHeader('Content-Type', 'application/json');
        if ($responseObject !== null) {
          $response->appendBody(json_encode($responseObject, JSON_PRETTY_PRINT));
        }
    }
}