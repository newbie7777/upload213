<?php
require_once (DIR_SYSTEM . "library/tmdimportexport/PHPExcel.php"); 

class ControllerVendorShipping extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('vendor/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/shipping');

		$this->getList();
	}

	public function add() {
		$this->load->language('vendor/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/shipping');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_vendor_shipping->addShipping($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->session->data['token'])){
				$tokenchage = 'token=' . $this->session->data['token'];
			} else {
				$tokenchage = 'user_token=' . $this->session->data['user_token'];
			}

			$this->response->redirect($this->url->link('vendor/shipping', $tokenchage . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('vendor/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/shipping');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $shipping_id) {
				$this->model_vendor_shipping->deleteShipping($shipping_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if(isset($this->session->data['token'])){
				$tokenchage = 'token=' . $this->session->data['token'];
			} else {
				$tokenchage = 'user_token=' . $this->session->data['user_token'];
			}

			$this->response->redirect($this->url->link('vendor/shipping', $tokenchage  . $url, true));
		}

		$this->getList();
	}
		
	protected function getList() {

		if(isset($this->session->data['token'])){
			$tokenchage = 'token=' . $this->session->data['token'];
		} else {
			$tokenchage = 'user_token=' . $this->session->data['user_token'];
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['filter_store_name'])) {
			$filter_store_name = $this->request->get['filter_store_name'];
		} else {
			$filter_store_name = '';
		}
		/* 11 02 2020 */
		if (isset($this->request->get['filter_vendor'])) {
			$filter_vendor = $this->request->get['filter_vendor'];
		} else {
			$filter_vendor = '';
		}
		/* 11 02 2020 */

		if (isset($this->request->get['filter_country'])) {
			$filter_country = $this->request->get['filter_country'];
		} else {
			$filter_country = '';
		}

		if (isset($this->request->get['filter_zipfrom'])) {
			$filter_zipfrom = $this->request->get['filter_zipfrom'];
		} else {
			$filter_zipfrom = '';
		}

		if (isset($this->request->get['filter_zipto'])) {
			$filter_zipto = $this->request->get['filter_zipto'];
		} else {
			$filter_zipto = '';
		}

		if (isset($this->request->get['filter_weightto'])) {
			$filter_weightto = $this->request->get['filter_weightto'];
		} else {
			$filter_weightto = '';
		}

		if (isset($this->request->get['filter_weightfrom'])) {
			$filter_weightfrom = $this->request->get['filter_weightfrom'];
		} else {
			$filter_weightfrom = '';
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'shipping_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_store_name'])) {
			$url .= '&filter_store_name=' . $this->request->get['filter_store_name'];
		}
		/* 11 02 2020 */
		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		/* 11 02 2020 */
			
		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}

		if (isset($this->request->get['filter_zipfrom'])) {
			$url .= '&filter_zipfrom=' . $this->request->get['filter_zipfrom'];
		}

		if (isset($this->request->get['filter_zipto'])) {
			$url .= '&filter_zipto=' . $this->request->get['filter_zipto'];
		}

		if (isset($this->request->get['filter_weightto'])) {
			$url .= '&filter_weightto=' . $this->request->get['filter_weightto'];
		}

		if (isset($this->request->get['filter_weightfrom'])) {
			$url .= '&filter_weightfrom=' . $this->request->get['filter_weightfrom'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenchage, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/shipping', $tokenchage . $url, true)
		);

		$data['add'] = $this->url->link('vendor/shipping/add', $tokenchage . $url, true);
		$data['delete'] = $this->url->link('vendor/shipping/delete', $tokenchage . $url, true);

		$data['shippings'] = array();

		$filter_data = array(
			'filter_store_name' => $filter_store_name,
			/* 11 02 2020 */
			'filter_vendor'     => $filter_vendor,
			/* 11 02 2020 */
			'filter_country'    => $filter_country,
			'filter_zipto'      => $filter_zipto,
			'filter_zipfrom'    => $filter_zipfrom,
			'filter_weightto'   => $filter_weightto,
			'filter_weightfrom' => $filter_weightfrom,
			'filter_price'      => $filter_price,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
				
		$review_total = $this->model_vendor_shipping->getTotalShippping($filter_data);
		$results = $this->model_vendor_shipping->getShippings($filter_data);
	
		foreach ($results as $result) {
			$this->load->model('localisation/country');
			$country_info = $this->model_localisation_country->getCountry($result['country_id']);
			if(isset($country_info['name'])){
				$country = $country_info['name'];
			} else {
				$country = '';
			}

			$vendor_info = $this->model_vendor_shipping->getVendorDescription($result['vendor_id']);
			if(isset($vendor_info['name'])){
				$store_name = $vendor_info['name'];
			} else {
				$store_name = '';
			}

			$data['shippings'][] = array(
				'shipping_id'   => $result['shipping_id'],
				'country_id'   	=> $country,
				'zip_from'   	=> $result['zip_from'],
				
				'weight_from'   => $result['weight_from'],
				'weight_to'   	=> $result['weight_to'],
				'price'   		=> $this->currency->format( $result['price'], $this->config->get('config_currency')),
				'store_name'   	=> $store_name,
			);
		}

		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();	

		$data['heading_title'] 	= $this->language->get('heading_title');
		//$data['token'] = $this->session->data['token'];
		$data['text_list'] 		= $this->language->get('text_list');
		$data['text_no_results']= $this->language->get('text_no_results');
		$data['text_confirm'] 	= $this->language->get('text_confirm');
		$data['text_enable'] 	= $this->language->get('text_enable');
		$data['text_disable'] 	= $this->language->get('text_disable');
		$data['text_select'] 	= $this->language->get('text_select');
		$data['text_none'] 		= $this->language->get('text_none');

		$data['column_store'] 		= $this->language->get('column_store');
		$data['column_sellerstore'] = $this->language->get('column_sellerstore');
		$data['column_country'] 	= $this->language->get('column_country');
		$data['column_zipfrom'] 	= $this->language->get('column_zipfrom');
		$data['column_zipto'] 		= $this->language->get('column_zipto');
		$data['column_weightfrom'] 	= $this->language->get('column_weightfrom');	
		$data['column_weightto'] 	= $this->language->get('column_weightto');	
		$data['column_price'] 		= $this->language->get('column_price');	
		$data['column_action'] 		= $this->language->get('column_action');	

		
		$data['button_add'] 	= $this->language->get('button_add');
		$data['button_bulkupload'] 	= $this->language->get('button_bulkupload');
		$data['button_delete'] 	= $this->language->get('button_delete');
		$data['button_filter'] 	= $this->language->get('button_filter');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		
		if (isset($this->request->get['filter_store_name'])) {
			$url .= '&filter_store_name=' . $this->request->get['filter_store_name'];
		}
		/* 11 02 2020 */
		if (isset($this->request->get['filter_vendor'])) {
			$url .= '&filter_vendor=' . $this->request->get['filter_vendor'];
		}
		/* 11 02 2020 */
		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}

		if (isset($this->request->get['filter_zipfrom'])) {
			$url .= '&filter_zipfrom=' . $this->request->get['filter_zipfrom'];
		}

		if (isset($this->request->get['filter_zipto'])) {
			$url .= '&filter_zipto=' . $this->request->get['filter_zipto'];
		}

		if (isset($this->request->get['filter_weightto'])) {
			$url .= '&filter_weightto=' . $this->request->get['filter_weightto'];
		}

		if (isset($this->request->get['filter_weightfrom'])) {
			$url .= '&filter_weightfrom=' . $this->request->get['filter_weightfrom'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_store_name']   = $this->url->link('vendor/shipping', $tokenchage . '&sort=store_name' . $url, true);
		$data['sort_country'] = $this->url->link('vendor/shipping', $tokenchage . '&sort=country' . $url, true);
		$data['sort_zipto']   = $this->url->link('vendor/shipping', $tokenchage . '&sort=zipto' . $url, true);
		$data['sort_zipfrom']     = $this->url->link('vendor/shipping', $tokenchage . '&sort=zipfrom' . $url, true);
		$data['sort_weightfrom']     = $this->url->link('vendor/shipping', $tokenchage . '&sort=weightfrom' . $url, true);
		$data['sort_weightto']     = $this->url->link('vendor/shipping', $tokenchage . '&sort=weightto' . $url, true);
		$data['sort_price']     = $this->url->link('vendor/shipping', $tokenchage . '&sort=price' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('vendor/shipping', $tokenchage . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));
			
		$data['bulkshipping'] = $this->url->link('vendor/shipping/bulkshipping', $tokenchage . $url, true);

		
		
		/* 11 02 2020 */	
		$data['filter_store_name'] 	   = $filter_store_name;
		$data['filter_vendor'] 	   = $filter_vendor;
		/* 11 02 2020 */
		$data['filter_country']    = $filter_country;
		$data['filter_zipto'] 	   = $filter_zipto;
		$data['filter_zipfrom']    = $filter_zipfrom;
		$data['filter_weightfrom'] = $filter_weightfrom;
		$data['filter_weightto']   = $filter_weightto;
		$data['filter_price'] 	   = $filter_price;
		$data['sort'] 			   = $sort;
		$data['order'] 			   = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/shipping_list', $data));
	}

	protected function getForm() {

		if(isset($this->session->data['token'])){
			$tokenchage = 'token=' . $this->session->data['token'];
		} else {
			$tokenchage = 'user_token=' . $this->session->data['user_token'];
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] 	= !isset($this->request->get['shipping_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] 	= $this->language->get('text_enabled');
		$data['text_disabled'] 	= $this->language->get('text_disabled');
		$data['text_default'] 	= $this->language->get('text_default');
		$data['text_percent'] 	= $this->language->get('text_percent');
		$data['text_amount'] 	= $this->language->get('text_amount');
		$data['text_select'] 	= $this->language->get('text_select');
		$data['text_none'] 		= $this->language->get('text_none');
		$data['text_enable'] 	  = $this->language->get('text_enable');
		$data['text_disable'] 	  = $this->language->get('text_disable');		
		$data['entry_storename']  = $this->language->get('entry_storename');
		$data['entry_country'] 	  = $this->language->get('entry_country');
		$data['entry_zipfrom'] 	  = $this->language->get('entry_zipfrom');
		$data['entry_zipto'] 	  = $this->language->get('entry_zipto');
		$data['entry_weightfrom'] = $this->language->get('entry_weightfrom');
		$data['entry_weightto']   = $this->language->get('entry_weightto');
		$data['entry_price'] 	  = $this->language->get('entry_price');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['vendor_id'])) {
			$data['error_vendor_id'] = $this->error['vendor_id'];
		} else {
			$data['error_vendor_id'] = '';
		}
		
		if (isset($this->error['country_id'])) {
			$data['error_country'] = $this->error['country_id'];
		} else {
			$data['error_country'] = '';
		}
		
		
		
		if (isset($this->error['zip_from'])) {
			$data['error_zip_from'] = $this->error['zip_from'];
		} else {
			$data['error_zip_from'] = '';
		}

		if (isset($this->error['weight_from'])) {
			$data['error_weight_from'] = $this->error['weight_from'];
		} else {
			$data['error_weight_from'] = '';
		}

		if (isset($this->error['weight_to'])) {
			$data['error_weight_to'] = $this->error['weight_to'];
		} else {
			$data['error_weight_to'] = '';
		}

		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenchage, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/shipping', $tokenchage . $url, true)
		);

		if (!isset($this->request->get['shipping_id'])) {
			$data['action'] = $this->url->link('vendor/shipping/add', $tokenchage . $url, true);
		} else {
			$data['action'] = $this->url->link('vendor/shipping/edit', $tokenchage . '&shipping_id=' . $this->request->get['shipping_id'] . $url, true);
		}

		//$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();	
		
		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = (int)$this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}
		
		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = (int)$this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = (int)$this->request->post['name'];
		} else{
			$data['name'] = '';
		}

		if (isset($this->request->post['vendor_id'])) {
			$data['vendor_id'] = (int)$this->request->post['vendor_id'];
		} else{
			$data['vendor_id'] = '';
		}

		$data['cancel'] = $this->url->link('vendor/shipping', $tokenchage . $url, true);
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/shipping_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'vendor/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!empty($this->request->post['vendor_id'] == '')) {
			$this->error['vendor_id'] = $this->language->get('error_store');
		}

		if ($this->request->post['country_id'] == '') {
			$this->error['country_id'] = $this->language->get('error_country');
		}

		if ((utf8_strlen($this->request->post['zip_from']) < 1) || (utf8_strlen($this->request->post['zip_from']) > 64)) {
			$this->error['zip_from'] = $this->language->get('error_zip_from');
		}

		

		if ((utf8_strlen($this->request->post['weight_to']) < 1) || (utf8_strlen($this->request->post['weight_to']) > 64)) {
			$this->error['weight_to'] = $this->language->get('error_weight_to');
		}

		if ((utf8_strlen($this->request->post['weight_from']) < 1) || (utf8_strlen($this->request->post['weight_from']) > 64)) {
			$this->error['weight_from'] = $this->language->get('error_weight_from');
		}

		if ((utf8_strlen($this->request->post['price']) < 1) || (utf8_strlen($this->request->post['price']) > 64)) {
			$this->error['price'] = $this->language->get('error_price');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'vendor/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}
	
	public function autocomplete(){
		
		if (isset($this->request->get['filter_store_name'])) {
			$filter_store_name = $this->request->get['filter_store_name'];
		} else {
			$filter_store_name = '';
		}
	
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$this->load->model('vendor/vendor');
			
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		'filter_store_name' => $filter_store_name,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
		$this->load->model('vendor/shipping');
		$accounts = $this->model_vendor_shipping->getVendorStoreDescription($filter_data);
		
		foreach ($accounts as $account) {

		$json[] = array(
		'vendor_id'  => $account['vendor_id'],
		'name'   => strip_tags(html_entity_decode($account['name'], ENT_QUOTES, 'UTF-8'))
		);
		}
		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function shippingdelete(){
		$json = array();
		$this->load->model('vendor/shipping');
		$this->load->language('vendor/shipping');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$this->model_vendor_shipping->deleteShipping($this->request->get['shipping_id']);
			$json['success'] = $this->language->get('text_delete');
			
		}					
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function bulkshipping(){
		if(isset($this->session->data['token'])){
			$tokenchage = 'token=' . $this->session->data['token'];
		} else {
			$tokenchage = 'user_token=' . $this->session->data['user_token'];
		}


		$this->load->language('vendor/shipping');

		$this->document->setTitle($this->language->get('heading_bulktitle'));

		$this->load->model('vendor/shipping');

		$data['heading_bulktitle']  = $this->language->get('heading_bulktitle');
		$data['text_bulklist']= $this->language->get('text_bulklist');
		$data['text_select']= $this->language->get('text_select');

		$data['entry_export']= $this->language->get('entry_export');
		$data['entry_upload'] 	= $this->language->get('entry_upload');
		$data['entry_seller'] 	= $this->language->get('entry_seller');
		$data['entry_time_allowed'] 	= $this->language->get('entry_time_allowed');
		$data['entry_file_size'] = $this->language->get('entry_file_size');
		$data['button_export'] = $this->language->get('button_export');
		$data['button_upload'] 	= $this->language->get('button_upload');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $tokenchage,	true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/shipping', $tokenchage . $url, true)
		);

		$data['export'] = $this->url->link('vendor/shipping/export', $tokenchage . $url,true );
		
		$data['import'] = $this->url->link('vendor/shipping/import', $tokenchage . $url,true );

		//$data['token'] = $this->session->data['token'];

		$this->load->model('vendor/shipping');
		$data['sellers'] = $this->model_vendor_shipping->getVendorStoreDescription($data);	

		$data['vendor_id'] = '';

		$data['timeallowed'] = ini_get('max_execution_time');
		
		$data['filesize'] = ini_get("upload_max_filesize");

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('vendor/bulkshipping', $data));
	}

	public function export() {
		if(isset($this->session->data['token'])){
			$tokenchage = 'token=' . $this->session->data['token'];
		} else {
			$tokenchage = 'user_token=' . $this->session->data['user_token'];
		}

		$this->load->language('vendor/shipping');
		$this->load->model('vendor/shipping');
		
			$data['produts']=array();
			$filter_data = array();

	
		 if(isset($this->request->post['vendor_id'])){
		 	$vendor_id = $this->request->post['vendor_id'];
		 } else{
		 	$vendor_id ='';
		 }

		
		$results = $this->model_vendor_shipping->getShipping($vendor_id);
	
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator("Category Product");
		$objPHPExcel->getProperties()->setLastModifiedBy("Category Product");
		$objPHPExcel->getProperties()->setTitle("Office Excel");
		$objPHPExcel->getProperties()->setSubject("Office Excel");
		$objPHPExcel->getProperties()->setDescription("Office Excel");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$i=1;
		
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, 'Store Name');
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, 'Shipping Country');
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, 'Zipcode From');
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, 'Weight From');
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, 'Weight To');
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, 'Price');
		

		foreach ($results as $result) {
		$i++;
			$this->load->model('localisation/country');
			$country_info = $this->model_localisation_country->getCountry($result['country_id']);
			if(isset($country_info)){
				$country = $country_info['name'];
			} else {
				$country = '';
			}

			$this->load->model('vendor/shipping');
			$vendor_info = $this->model_vendor_shipping->getVendorDescription($result['vendor_id']);
			if(isset($vendor_info['name'])){
				$store_name = $vendor_info['name'];
			} else {
				$store_name = '';
			}

			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $store_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$i, $country);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $result['zip_from']);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $result['weight_from']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $result['weight_to']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $result['price']);
		}
		
		/* color setup */
		$al='G';
		
		for($col = 'A'; $col != $al; $col++) {
		   $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
		}
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);	
		$objPHPExcel->getActiveSheet()
		->getStyle('A1:'.$al.'1')
		->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		->getStartColor()
		->setARGB('02057D');
		
		$styleArray = array(
			'font'  => array(
			'bold'  => true,
			'color' => array('rgb' => 'FFFFFF'),
			'size'  => 9,
			'name'  => 'Verdana'
		));
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$al.'1')->applyFromArray($styleArray);

		/* color setup */
		
		$excel='Excel5';	
		$filename = 'shipping.xls';
		$objPHPExcel->getActiveSheet()->setTitle('Shipping Report');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $excel);
		$objWriter->save($filename );
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		$objWriter->save('php://output');
		unlink($filename);
	}

	public function import() {
		if(isset($this->session->data['token'])){
			$tokenchage = 'token=' . $this->session->data['token'];
		} else {
			$tokenchage = 'user_token=' . $this->session->data['user_token'];
		}

		$this->load->language('vendor/shipping');
		$this->load->model('vendor/shipping');

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'vendor/shipping')) {
			
				
				if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
					$content = file_get_contents($this->request->files['import']['tmp_name']);
				} else {
					$content = false;
				}

				if ($content) {
		////////////////////////// Started Import work  //////////////
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($this->request->files['import']['tmp_name']);
				} catch(Exception $e) {
					die('Error loading file "'.pathinfo($this->path.$files,PATHINFO_BASENAME).'": '.$e->getMessage());
				}
		/*	@ get a file data into $sheetDatas variable */
				$sheetDatas = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

				//$results = $this->model_vendor_shipping->getShipping($vendor_id);

		/*	@ $i variable for getting data. in first iteration of loop we get size and color name of product */
				$i=0;
		/*
		@ arranging the data according to our need
		*/
				foreach($sheetDatas as $sheetData){
					if($i!=0) {

						$vendor  = $sheetData['A'];
						$vendor_id = 0;
						if(!empty($vendor))	{
							$vendor_id = $this->model_vendor_shipping->getvendorbyname($vendor);
						}

						$country 	= $sheetData['B'];
						$country_id = 0;
						if(!empty($country)) {
							$country_id=$this->model_vendor_shipping->getCountrybyname($country);
						}
						$zipfrom  	= $sheetData['C'];
						$weightfrom	= $sheetData['D'];
						$weightto	= $sheetData['E'];
						$price		= $sheetData['F'];

						$data='';

						$data=array(
							'vendor_id'=>$this->request->post['vendor_id'],
							'country_id'=>$country_id,
							'zip_from'=>$zipfrom,
							
							'weight_from'=>$weightfrom,
							'weight_to'=>$weightto,
							'price'=>$price,
						);

						$this->model_vendor_shipping->addImport($this->request->post['vendor_id'],$data);
						
						$totalupdateaffiliate++;
					}
					
					$i++;
				}

				$this->session->data['success'] = $totalupdateaffiliate .' :: Total Affiliate update ' .$totalnewaffiliate. ':: Total New Affiliate added';

		////////////////////////// Started Import work  //////////////
				$this->response->redirect($this->url->link('vendor/shipping', $tokenchage , 'SSL'));
				} else {
					$this->error['warning'] = $this->language->get('error_warning');
				}
			

			
		}
	}
}