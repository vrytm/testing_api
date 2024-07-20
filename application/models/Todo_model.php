<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_model extends CI_Model
{
		public function getData($id)
		{
				return $this->db->get_where('todo', ["id" => $id])->row();
		
		}

		public function insert($data)
		{
				$q = $this->db->insert('todo', $data);

				return $this->db->insert_id();
		}

    public function update($data)
    {
			$q = $this->db->update('todo', $data, array('id' => $data['id']));
			return $q;
    }

		public function delete($id)
    {
			$q = $this->db->delete('todo', array('id' => $id));
			return $q;
    }
}
