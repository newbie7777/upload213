<?php
class ModelVendorDownload extends Model {
	public function addDownload($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "',date_added = NOW()");

		$download_id = $this->db->getLastId();

		foreach ($data['download_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_to_download WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_to_download SET download_id = '" . (int)$download_id . "', vendor_id = '" . (int)$this->vendor->getId() . "'");

		return $download_id;
	}

	public function editDownload($download_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "download SET filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "' WHERE download_id = '" . (int)$download_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

		foreach ($data['download_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_to_download WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_to_download SET download_id = '" . (int)$download_id . "', vendor_id = '" . (int)$this->vendor->getId() . "'");
	}

	public function deleteDownload($download_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "download WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_to_download WHERE download_id = '" . (int)$download_id . "'");
	}

	public function getDownload($download_id) {
		/* 17 11 2020 add new code */
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id)  LEFT JOIN ". DB_PREFIX ."vendor_to_download v2d on (d.download_id = v2d.download_id) WHERE d.download_id = '" . (int)$download_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "' and v2d.vendor_id = '" . (int)$this->vendor->getId() . "'");
		/* 17 11 2020 add new code */
		return $query->row;
	}

	public function getDownloads($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) LEFT JOIN " . DB_PREFIX . "vendor_to_download v2d ON (d.download_id = v2d.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' and v2d.vendor_id<>0";
		
		if(isset($data['vendor_id'])){
			$sql .= " and v2d.vendor_id='".(int)$data['vendor_id']."'";
		}
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND dd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		
		$sort_data = array(
			'dd.name',
			'd.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY dd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getDownloadDescriptions($download_id) {
		$download_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE download_id = '" . (int)$download_id . "'");

		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $download_description_data;
	}

	public function getTotalDownloads($data) {
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download d LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) LEFT JOIN " . DB_PREFIX . "vendor_to_download v2d ON (d.download_id = v2d.download_id) WHERE dd.language_id = '" . (int)$this->config->get('config_language_id') . "' and v2d.vendor_id<>0";
			
		if(isset($data['vendor_id'])){
			$sql .= " and v2d.vendor_id='".(int)$data['vendor_id']."'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}