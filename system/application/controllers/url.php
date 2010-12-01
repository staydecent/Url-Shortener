<?php

class Url extends Controller 
{
	function Url()
	{
		parent::Controller();
		
		$this->load->helper(array('form', 'url', 'date', 'urlshortener', 'simple_html_dom'));
		$this->load->database();
		$this->load->model('url_model');
		
		$this->data['base'] = base_url();
		$this->data['latest_urls'] = $this->url_model->get_latest_urls();
		$this->data['popular_urls'] = $this->url_model->get_popular_urls();
	}
	
	function index()
	{
		/**
		 * Check GET var 'short'.
		 * If set, then someone is using a short url.
		 * We need to load the long url, and show comments.
		 *
		 * @todo Log stats
		 */
		if(isset($_GET['short']) && !empty($_GET['short']))
		{
			$short = strip_tags($this->input->get('short', TRUE));
			$search = "SELECT * FROM urls WHERE short = ? LIMIT 1";

			if($result = $this->db->query($search, array($short)))
			{
				$row = $result->result_array();
			
				// url found, redirect, or error
			
				if(!empty($row))
				{
					/**
					 * Check if user is posting a comment.
					 * if so add comment, and refresh the same page.
					 */
					if(isset($_POST['email']) && !empty($_POST['email']))
					{
						$name = $this->input->post('username', TRUE);
						$email = $this->input->post('email', TRUE);
						$body = $this->input->post('body', TRUE);
						$body = strip_tags($body, '<a>');
						$user = $this->url_model->get_user($email, $name); // creates or retrieves user, returns array
						
						$add_comment = "INSERT INTO comments (url_id, user_id, date_created, body) VALUES(?, ?, now(), ?)";

						$this->db->query($add_comment, array($row[0]['id'], $user[0]['id'], $body));
						$id = mysql_insert_id();
					}
					
					$this->data['title'] = html_entity_decode($row[0]['title']);
					$this->data['url'] = htmlspecialchars($row[0]['url']);
					$this->data['short'] = htmlspecialchars(base_url() . $row[0]['short']);
					$this->data['date_created'] = strtotime($row[0]['date_created']);
					$this->data['comments'] = $this->url_model->get_comments($row[0]['id']);
					$this->data['keywords'] = 'blah';

					$this->load->view('template/frame', $this->data);
				}
				else
				{
					show_error('The short URL could not be found in our database, please make sure it is correct: ' . $short);
				}
			}
		}
		
		/**
		 * Check GET var 'url'.
		 * If set, create a short url, otherwise show the form.
		 */
		if(isset($_GET['url']) && !empty($_GET['url']))
		{
			$url = clean_url($this->input->get('url', TRUE));
			$short = generate_string(); // Random string, default length = 5
			$title = get_external_title($url);
			$tags = get_external_tags($url);
			
			// Search for a row with this url
			
			$search = "SELECT * FROM urls WHERE url = ? LIMIT 1";
			
			if($result = $this->db->query($search, array($url)))
			{
				$row = $result->result_array();
				// exists
				if(!empty($row))
				{
					$this->data['title'] = html_entity_decode($row[0]['title']);
					$this->data['url'] = htmlspecialchars($row[0]['url']);
					$this->data['short'] = htmlspecialchars(base_url() . $row[0]['short']);

					$this->load->view('content/url_exists', $this->data);
				}
				// create
				else
				{
					$url_id = $this->url_model->insert_url($url, $short, $title);
					$this->url_model->insert_tags($tags);
					$this->url_model->insert_url_tag($tags, $url_id);

					$this->data['title'] = html_entity_decode($title);
					$this->data['url'] = htmlspecialchars($url);
					$this->data['short'] = htmlspecialchars(base_url() . $short);
					$this->data['comments'] = $this->url_model->get_comments($url_id);
					$this->data['keywords'] = 'blah';

					redirect(base_url() . $short, 'refresh');
					exit;
					#$this->load->view('template/frame', $this->data);
				}
			}
		}
		/**
		 * No input, show home page.
		 */
		elseif(!isset($_GET['short']))
		{
			$this->load->view('content/url_add', $this->data);
		}
	}
	
	function edit()
	{
		/**
		 * Long url found.
		 */
		if(isset($_GET['url']) && !empty($_GET['url']))
		{
			$url = clean_url($this->input->get('url', TRUE));

			$this->data['url'] = htmlspecialchars($url);
			$this->load->view('content/url_edit', $this->data);
		}
		/**
		 * Custom short found.
		 */
		elseif(isset($_POST['short']) && !empty($_POST['short']) && isset($_POST['url']))
		{
			$short = strip_tags($this->input->post('short', TRUE));
			$short = str_replace(' ', '', $short); // remove spaces
			$url = clean_url($this->input->post('url', TRUE));
			$title = get_external_title($url);
			
			// Check for symbols
			
			if(preg_match("/[^A-Za-z0-9]/", $short))
			{
				show_error('Your short url cannot contain symbols: ' . $short);
			}
			
			// Make sure short(input) is not too small or too big.
			
			if(strlen($short) < 3 || strlen($short) > 10)
			{
				show_error('That short URL is either two small or to big. Please choose one that is atleast 3 characters and no more than 10 characters in length.');
			}
			
			// Search for a row with this short url
			
			$search = "SELECT * FROM urls WHERE short = ? LIMIT 1";

			if($result = $this->db->query($search, array($short)))
			{
				$row = $result->result_array();
				// exists
				if(!empty($row))
				{
					show_error('That short URL already exists.');
				}
				// create
				else
				{
					$this->url_model->insert_url($url, $short, $title);
				}
			}
			
			$this->data['url'] = htmlspecialchars($url);
			$this->data['title'] = html_entity_decode($title);
			$this->data['short'] = htmlspecialchars(base_url() . $short);
			$this->load->view('content/url_done', $this->data);
		}
		else
		{
			$this->load->view('content/url_add', $this->data);
		}

	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */