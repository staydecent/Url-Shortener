<?php

class Url_model extends Model 
{
	function Url_model()
	{
		parent::Model();
	}

	/**
	 * Inserts url.
	 * @return Int Id of new row
	 */
	function insert_url($url, $short, $title)
	{
		$insert = "INSERT INTO urls (url, short, date_created, title) VALUES(?, ?, now(), ?)";

		$this->db->query($insert, array($url, $short, $title));
		$id = mysql_insert_id();

		return $id;
	}

	/**
	 * Inserts tag names.
	 * @return Array id's of inserted tags
	 */
	function insert_tags($tags)
	{
		$ids = array();
		$insert = "INSERT INTO tags (name) VALUES(?)";

		foreach($tags as $t)
		{
			$this->db->query($insert, array($t));
			$ids[] = mysql_insert_id();
		}

		return $ids;
	}

	/**
	 * Inserts tag associations.
	 */
	function insert_url_tag($tags, $url_id)
	{
		$search = "SELECT * FROM tags WHERE name = ?";
		$insert = "INSERT INTO url_tag (url_id, tag_id) VALUES(?, ?)";

		foreach($tags as $t)
		{
			if($result = $this->db->query($search, array($t)))
			{
				$row = $result->result_array();

				// if tag name exists
				if(!empty($row))
				{
					$tag_id = $row[0]['id'];
				}
				else
				{
					$tag_id = $this->insert_tags($t);
					$tag_id = $rid[0];
				}

				$this->db->query($insert, array($url_id, $tag_id));
			}
		}
	}

	/**
	 * create or retrieve user in array.
	 */
	function get_user($email, $name)
	{
		// Search if user exits

		$search = "SELECT * FROM users WHERE email = ? LIMIT 1";

		if($result = $this->db->query($search, array($email)))
		{
			$row = $result->result_array();
			// exists 
			if(count($row) > 0)
			{
				return $row;
			}
			// create
			else
			{
				$insert = "INSERT INTO users (date_created, email, name) VALUES(now(), ?, ?)";
				$this->db->query($insert, array($email, $name));
				$id = mysql_insert_id();

				$search = "SELECT * FROM users WHERE id = ? LIMIT 1";
				if($result = $this->db->query($search, array($id)))
				{
					if($row = $result->result_array())
					{
						return $row;
					}
					else
					{
						return false;
					}
				}
			}
		}
	}
	
	/**
	 * Get comments to show on url page.
	 * Queries oldest to newest, to give the first comment an id of 1.
	 */
	function get_comments($url_id, $c = 0, $array = array())
	{
		$search = "SELECT * FROM comments WHERE url_id = ? ORDER BY date_created ASC";

		if($result = $this->db->query($search, array($url_id)))
		{
			if($rows = $result->result_array())
			{
				$search_user = "SELECT name FROM users WHERE id = ? LIMIT 1";

				foreach($rows as $row)
				{
					$array[$c]['id'] = $c; // Not a reference to the db, used for unique ID's in markup
					$array[$c]['body'] = nl2br($row['body']);
					$array[$c]['date'] = $row['date_created'];

					if($result_user = $this->db->query($search_user, array($rows[$c]['user_id'])))
					{
						if($users = $result_user->result_array())
						{
							$array[$c]['username'] = $users[0]['name'];
						}
						else
						{
							$array[$c]['username'] = 'null';
						}
					}
					++$c;
				}
				return $array;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Widget to show latest urls.
	 */
	function get_latest_urls($limit = 5)
	{
		$search = "SELECT * FROM urls ORDER BY date_created DESC LIMIT ?";

		if($result = $this->db->query($search, array((int) $limit)))
		{
			if($rows = $result->result_array())
			{
				return $rows;
			}
			else
			{
				return false;
			}
		}
	}
	
	/**
	 * Widget to show popular urls(# of comments).
	 */
	function get_popular_urls($limit = 5, $a = array(), $c = 0)
	{
		$search_urls = "SELECT * FROM urls";
		$search_comments = "SELECT * FROM comments WHERE url_id = ?";

		if($result_urls = $this->db->query($search_urls, array((int) $limit)))
		{
			if($urls = $result_urls->result_array())
			{
				foreach($urls as $u)
				{				
					if($result_comments = $this->db->query($search_comments, array($u['id'])))
					{
						if($comments = $result_comments->result_array())
						{
							$a[$c]['title'] = $u['title'];
							$a[$c]['url'] = $u['url'];
							$a[$c]['short'] = $u['short'];
							$a[$c]['num_comments'] = count($comments);
							
							++$c;
						}
					}
				}
				uasort($a, 'cmp');
				return $a;
			}
			else
			{
				return false;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */