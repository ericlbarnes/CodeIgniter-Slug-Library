# CodeIgniter Slug Library

This library is designed to help you generate friendly uri strings for your content stored in the database.

For example if you have a blog post table then you would want uri strings such as: mysite.com/post/my-post-title

The problem with this is each post needs a unique uri string and this library is designed to handle that for you.

So if you add another with the same uri or title it would convert it to: mysite.com/post/my-post-title-2

# Requirements

* CodeIgniter
* Some form of database supported by active record

# Usage

## Here is an example setup:

Please note that these fields map to your database table fields.

	$config = array(
		'field' => 'uri',
		'title' => 'title',
		'table' => 'mytable,
		'id' => 'id',
	);
	$this->load->library('slug', $config);

## Adding and Editing Records:

When creating a uri for adding to the database you will use something like this:

	$data = array(
		'title' => 'My Test',
	);
	$data['uri'] = $this->slug->create_uri($data);
	$this->db->insert('mytable, $data);

Then for editing: (Notice the create_uri uses the second param to compare against other fields).

	$data = array(
		'title' => 'My Test',
	);
	$data['uri'] = $this->slug->create_uri($data, $id);
	$this->db->where('id', $id);
	$this->db->update('mytable', $data);

