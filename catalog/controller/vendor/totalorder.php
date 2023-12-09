<?php
class ControllerVendorTotalOrder extends Controller {
	public function index() {
		$this->load->language('vendor/totalorder');
			/* update 02 11 2020 */
		$this->load->model('vendor/order_report');
			/* update 02 11 2020 */
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');
		$filter_data=array(
			'vendor_id' 	=> $this->vendor->getId()		
		);
			/* update 02 11 2020 */
		$data['totalorder'] = $this->model_vendor_order_report->getTotalReport($filter_data);
		/* update 02 11 2020 */
		$data['orderhref'] = $this->url->link('vendor/order_report');
		
		return $this->load->view('vendor/totalorder', $data);
	}
}
