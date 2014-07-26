<?php

class Lector_model extends CI_Model {

    public function get_lectores_user($id_user) {
        $this->db->select('id');
        $this->db->select('titulo');
        $this->db->select('public_key');
        $this->db->select('fecha_alta');
        $this->db->select('fecha_modificacion');
        $this->db->select('state');
        $this->db->from('user_lector');
        $this->db->where('user_lector.id_user', $id_user);

        $query = $this->db->get();

        return $query->result();
    }

    public function add_lector_user($id_user, $titulo) {
        if($this->exist_user_lector($id_user, $titulo, 'titulo')) {
            return NULL;
        }
        $data = array(
            'id_user' => $id_user,
            'titulo' => trim($titulo),
            'state' => 'I'
        );

        $this->db->insert('user_lector', $data);

        if ($this->db->affected_rows() === 1) {
            $frigorifico_id = $this->db->insert_id();

            return array('lector_id' => $frigorifico_id);
        }

        return NULL;
    }

    public function get_lector_user($id_user, $id_lector) {
        $this->db->select('id');
        $this->db->select('titulo');
        $this->db->select('public_key');
        $this->db->select('fecha_alta');
        $this->db->select('fecha_modificacion');
        $this->db->select('state');
        $this->db->from('user_lector');
        $this->db->where('user_lector.id_user', $id_user);
        $this->db->where('user_lector.id', $id_lector);

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

    private function exist_user_lector($search, $by) {
        $this->db->select('1', FALSE);
        $this->db->from('user_lector');
        $this->db->where('state','A');
        $this->db->or_where('state','I');
        if($by === 'id') {
            $this->db->where('id', $search);
        } else if ($by === 'titulo') {
            $this->db->where('titulo', $search);
        }

        $query = $this->db->get();
        return $query->num_rows() === 1;
    }
}