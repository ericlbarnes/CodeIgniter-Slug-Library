# CodeIgniter Slug Library

This library is designed to help you generate friendly uri strings for your content stored in the database.

# Usage

Here is an example setup:

	$this->load->library('slug');
	$config = array(
		'field_uri' => 'uri',
		'field_title' => 'title',
		'field_table' => 'mytable,
		'field_id' => 'id'
	);
	$this->slug->set_config($config);

Adding a Record:

	$data = array(
		'title' = 'My Test',
	);
	$data['uri'] = $this->slug->create_uri($data);
	$this->db->insert('mytable, $data);

Editing a record:

	$data = array(
		'title' = 'My Test',
	);
	$data['uri'] = $this->slug->create_uri($data, $id);
	$this->db->where('id', $id);
	$this->db->update('mytable', $data);
