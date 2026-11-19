<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_transaction extends CI_Model
{

  // public function m_save_trasaction($data) {
	// 	if($this->db->insert('tbl_transaction',$data)) {
  //   	return TRUE;
	// 	} else {
	// 		return FALSE;
	// 	}
	// }

  public function m_save_trasaction($data) {
		if ($data['status'] === 'SERVING') {
			return $this->m_call_ticket($data);
		}

		$id = $data['id'];
		unset($data['id']);
		$this->db->where('id', $id);
		if($this->db->update('tbl_transaction',$data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

  private function m_call_ticket($data) {
    $this->load->helper('waiting_queue');
    $waiting_types = qtax_waiting_types();
    $lane_heads = $this->m_waiting_lane_heads();

    // Check the head of the ticket's own lane and claim it in the same statement.
    $sql = "
        UPDATE tbl_transaction
        SET status = 'SERVING', `window` = ?, serveby = ?, dtserving = ?
        WHERE id = ?
          AND (
            type IS NULL OR type NOT IN ($waiting_types)
            OR status = 'PENDING'
            OR (status = 'NA' AND (id, deletedby) IN ($lane_heads))
          )
    ";

    return $this->db->query($sql, array($data['window'], $data['serveby'], $data['dtserving'], $data['id']))
      && $this->db->affected_rows() > 0;
  }

  private function m_waiting_lane_heads() {
    $queue_order = qtax_queue_arrival_order();
    $first_waiting = "
        SELECT id AS ticket_id, deletedby AS ticket_deletedby
        FROM tbl_transaction
        WHERE DATE(dtadded) = CURDATE()
          AND isactive = 'yes'
          AND status = 'NA'
          AND type IN (%s)
        ORDER BY $queue_order
        LIMIT 1
    ";
    $regular_head = sprintf($first_waiting, qtax_regular_waiting_types());
    $special_head = sprintf($first_waiting, qtax_special_waiting_types());

    return "SELECT * FROM ($regular_head) AS regular_head
            UNION ALL
            SELECT * FROM ($special_head) AS special_head";
  }

  // Ticket number and window for the viewing monitor announcement.
  // Returns FALSE unless the ticket is currently SERVING.
  public function m_get_serving_announcement($id) {
    $this->db->select('number, window');
    $this->db->where('id', $id);
    $this->db->where('status', 'SERVING');
    $query = $this->db->get('tbl_transaction');
    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }
  }

  public function m_ajax_get_serve($str) {
    $this->load->helper('waiting_queue');
    $lane_heads = $this->m_waiting_lane_heads();
    $waiting_types = qtax_waiting_types();
    $queue_order = qtax_queue_arrival_order();
    $sql = "
        SELECT ticket.id, number, IFNULL(`window`, '') AS `window`,
          CASE
            WHEN status = 'SERVING' THEN 'serving'
            WHEN status = 'PENDING' THEN 'pending'
            WHEN lane_head.ticket_id IS NOT NULL OR type NOT IN ($waiting_types) THEN 'serve'
            ELSE 'waiting'
          END AS remarks
        FROM tbl_transaction AS ticket
        LEFT JOIN ($lane_heads) AS lane_head
          ON lane_head.ticket_id = ticket.id AND lane_head.ticket_deletedby = ticket.deletedby
        WHERE DATE(dtadded) = CURDATE()
          AND status IN ('NA', 'SERVING', 'PENDING')
          AND type = ?
          AND isactive = 'yes'
        ORDER BY $queue_order
    ";

    $query = $this->db->query($sql, array($str));
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function m_ajax_get_serve_verification($str) {
    $sql = 'SELECT
              `id`,
              `number`,
              IFNULL(`window`,"") AS `window`,
              (
            	CASE
            		WHEN `status` = "VERIFY" THEN "serve"
            	END
            	) AS `remarks`
            FROM
              `tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
              AND `status` = "VERIFY"
              AND `type` = "'.$str.'"
              AND isactive = "yes"';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function m_ajax_get_serve5to10($str) {
    $sql = 'SELECT
              `id`,
              `number`,
              `ifpayment`,
              IFNULL(`window`,"") AS `window`,
              (
            	CASE
            		WHEN `status` = "FINISHED" THEN "serve"
            		WHEN `status` = "PAYMENT-SERVING" THEN "serving"
            		WHEN `status` = "PAYMENT-PENDING" THEN "pending"
            	END
            	) AS `remarks`
            FROM
              `tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
              AND (`status` = "FINISHED" OR `status` = "PAYMENT-SERVING" OR `status` = "PAYMENT-PENDING")
              AND `ifpayment` = "YES" ';

    if ($str == 'SPECIAL-TAX') {
      $sql .= ' AND (`type` = "'.$str.'" OR `type` = "SPECIAL5-10" OR `type` = "SPECIAL")';
    } else {
      $sql .= ' AND (`type` = "'.$str.'" OR `type` = "P'.$str.'")';
    }
             
    $sql .= ' AND isactive = "yes"
              ORDER BY `dtupdated` ASC';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function m_ajax_get_serve5to10_verify($str) {
    $sql = 'SELECT
              `id`,
              `number`,
              `ifpayment`,
              IFNULL(`window`,"") AS `window`,
              (
            	CASE
            		WHEN `status` = "FINISHED" THEN "serve"
            		WHEN `status` = "PAYMENT-SERVING" THEN "serving"
            		WHEN `status` = "PAYMENT-PENDING" THEN "pending"
            	END
            	) AS `remarks`
            FROM
              `tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
              AND (`status` = "FINISHED" OR `status` = "PAYMENT-SERVING" OR `status` = "PAYMENT-PENDING")
              AND `ifpayment` = "NO" ';

    // if ($str == 'SPECIAL-TAX') {
      $sql .= ' AND (`type` = "'.$str.'" OR `type` = "SPECIAL5-10" OR `type` = "REGULAR5-10" OR `type` = "SPECIAL")';
    // } else {
    //   $sql .= ' AND (`type` = "'.$str.'" OR `type` = "P'.$str.'")';
    // }
             
    $sql .= ' AND isactive = "yes"
              ORDER BY `dtupdated` ASC';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function m_ajax_get_serve_releasing($str) {
    $sql = 'SELECT
              `id`,
              `number`,
              `ifpayment`,
              IFNULL(`window`,"") AS `window`,
              (
            	CASE
            		WHEN `status` = "RELEASING" THEN "serve"
            		WHEN `status` = "PAYMENT-FINISHED" THEN "serve"
            		WHEN `status` = "RELEASING-SERVING" THEN "serving"
            		WHEN `status` = "RELEASING-PENDING" THEN "pending"
            	END
            	) AS `remarks`
            FROM
              `tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
              AND (`status` = "RELEASING" OR `status` = "PAYMENT-FINISHED" OR `status` = "RELEASING-SERVING" OR `status` = "RELEASING-PENDING")';

      // $sql .= ' AND `type` = "'.$str.'" ';

    if ($str == 'RELEASING-REGULAR') {
      $sql .= ' AND (`type` = "'.$str.'" OR `type` = "REGULAR" OR `type` = "PREGULAR" OR `type` = "PREGULAR5-10" OR `type`="REGULAR-TAX" OR `type`= "REGULAR5-10")';
    } else {
       $sql .= ' AND (`type` = "'.$str.'" OR `type` = "SPECIAL"  OR `type` = "SPECIAL-TAX")';
    }
             
    $sql .= ' AND isactive = "yes"
              ORDER BY `dtupdated` ASC';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

  public function m_ajax_get_servev2($type) {
    $type = ($type == '') ? 'REGULAR' : $type;

    $sql = 'SELECT
              `id`,
              `number`,
              IFNULL(`window`,"") AS `window`,
              (
            	CASE
            		WHEN `status` = "NA" THEN "serve"
            		WHEN `status` = "SERVING" THEN "serving"
            		WHEN `status` = "PENDING" THEN "pending"
            	END
            	) AS `remarks`
            FROM
              `tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
              AND (`status` = "NA" OR `status` = "SERVING" OR `status` = "PENDING")
              AND isactive = "yes"
              ORDER BY
              	`type` = "'.$type.'" AND
              	`number`
              ';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
  }

}
