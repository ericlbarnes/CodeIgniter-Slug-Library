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
		'table' => 'mytable',
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

	$id = 1;
	$data = array(
		'title' => 'My Test',
	);
	$data['uri'] = $this->slug->create_uri($data, $id);
	$this->db->where('id', $id);
	$this->db->update('mytable', $data);

## Methods

### __construct($config = array())

Setup the library with your config options.

```php
$config = array(
	'table' => 'mytable,
	'id' => 'id',
	'field' => 'uri',
	'title' => 'title',
	'replacement' => 'dash' // Either dash or underscore
);
$this->load->library('slug', $config);
```

### set_config($config = array())

Pass an array of config vars that will override setup

**Paramaters**

* $config - (required) - Array of config options

```php
$config = array(
	'table' => 'mytable,
	'id' => 'id',
	'field' => 'uri',
	'title' => 'title',
	'replacement' => 'dash' // Either dash or underscore
);
$this->slug->set_config($config);
```
### create_uri($data = '', $id = '')

Creates the actual uri string and in the background validates against the table to ensure it is unique.

**Paramaters**

* $data - (requied) Array of data
* $id - (optional) Id of current record

```php
$data = array(
	'title' => 'My Test',
);
$this->slug->create_uri($data)
```

```php
$data = array(
	'title' => 'My Test',
);
$this->slug->create_uri($data, 1)
```

This returns a string of the new uri.
