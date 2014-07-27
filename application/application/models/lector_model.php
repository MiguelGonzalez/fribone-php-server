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
        if($this->exist_user_lector($titulo, 'titulo')) {
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

    public function check_lector_public_key($id_user, $public_key) {
        $this->db->select('id');
        $this->db->select('id_user');
        $this->db->from('user_lector');
        $this->db->where('public_key', $public_key);
        $this->db->where('state', 'A');
        $this->db->where('id_user', $id_user);

        $query = $this->db->get();

        if($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

    public function activar_lector($id_user, $id_lector, $public_key) {
        $data = array(
            'state' => 'A',
            'public_key' => $public_key
        );

        $this->db->where('id', $id_lector);
        $this->db->where('id_user', $id_user);
        $this->db->where('state', 'I');

        $this->db->update('user_lector', $data);

        if ($this->db->affected_rows() === 1) {
            return array('lector_id' => $id_lector);
        }

        return NULL;
    }

    public function desactivar_lector($id_user, $id_lector) {
        $data = array(
            'state' => 'I'
        );

        $this->db->where('id', $id_lector);
        $this->db->where('id_user', $id_user);
        $this->db->where('state', 'A');

        $this->db->update('user_lector', $data);

        if ($this->db->affected_rows() === 1) {
            return array('lector_id' => $id_lector);
        }

        return NULL;
    }

    public function anadir_lector_token($id_user, $id_lector, $access_key_1, $access_key_2, $fecha_alta) {
        if($this->is_lector_user($id_user, $id_lector)) {
            $this->dar_baja_token_lector($id_lector);

            $data = array(
                'access_key_1' => $access_key_1,
                'access_key_2' => $access_key_2,
                'fecha_alta' => $fecha_alta,
                'id_lector' => $id_lector,
                'state' => 'A'
            );

            $this->db->insert('user_lector_token', $data);

            if ($this->db->affected_rows() === 1) {
                return $data;
            }
        }

        return NULL;
    }

    public function check_lector_token($access_key_1, $access_key_2) {
        $this->db->select('user_lector_token.access_key_1');
        $this->db->select('user_lector_token.fecha_alta');
        $this->db->select('user_lector_token.id_lector');
        $this->db->select('user_lector.id_user');
        $this->db->from('user_lector_token');
        $this->db->join('user_lector', 'user_lector.id = user_lector_token.id_lector');
        $this->db->where('user_lector_token.access_key_1', $access_key_1);
        $this->db->where('user_lector_token.access_key_2', $access_key_2);
        $this->db->where('user_lector_token.state', 'A');

        $query = $this->db->get();

        $tokens = $query->result();

        foreach($tokens as $token) {
            if(strtotime($token->fecha_alta) === (intval($token->access_key_1) - intval($token->id_user))) {
                $this->dar_baja_token_lector($token->id_lector);

                $system_time = strtotime(date('Y-m-d H:i:s'));

                // 60 minutos duración del token
                if($system_time <= strtotime($token->fecha_alta) + 60 * 60) {
                    return array(
                        'id_user' => $token->id_user,
                        'id_lector' => $token->id_lector
                    );
                }
            }
        }
        return NULL;
    }

    private function exist_user_lector($search, $by) {
        $this->db->select('1', FALSE);
        $this->db->from('user_lector');
        $this->db->where('(state = \'A\' or state = \'I\')');
        if($by === 'id') {
            $this->db->where('id', $search);
        } else if ($by === 'titulo') {
            $this->db->where('titulo', $search);
        }

        $query = $this->db->get();
        return $query->num_rows() === 1;
    }

    private function is_lector_user($id_user, $id_lector) {
        $this->db->select('1', FALSE);
        $this->db->from('user_lector');
        $this->db->where('user_lector.id', $id_lector);
        $this->db->where('user_lector.id_user', $id_user);

        $query = $this->db->get();
        return $query->num_rows() === 1;
    }

    private function dar_baja_token_lector($id_lector) {
        $data = array(
            'state' => 'B'
        );

        $this->db->where('id_lector', $id_lector);
        $this->db->where('state', 'A');

        $this->db->update('user_lector_token', $data);
    }
}