<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_viewing extends CI_Model
{
  public function m_ajax_get_queue_snapshot() {
    $this->load->helper('waiting_queue');
    $waiting_types = qtax_waiting_types();
    $queue_order = qtax_waiting_order();
    // Read both lists together so a call cannot put the same row in both lists.
    $sql = "
        SELECT id, number, `window`, ifpayment, type, status, dtadded
        FROM tbl_transaction
        WHERE DATE(dtadded) = CURDATE()
          AND isactive = 'yes'
          AND (
            status IN ('SERVING', 'VERIFY', 'RELEASING-SERVING')
            OR (
              status = 'NA'
              AND type IN ($waiting_types)
            )
          )
        ORDER BY $queue_order
    ";

    $snapshot = array('status' => 'yes', 'serving' => array(), 'waiting' => array());
    foreach ($this->db->query($sql)->result_array() as $ticket) {
      if ($ticket['status'] === 'NA') {
        if (count($snapshot['waiting']) < 5) {
          $snapshot['waiting'][] = $ticket;
        }
      } else {
        $snapshot['serving'][] = $ticket;
      }
    }
    return $snapshot;
  }

  public function m_ajax_get_serving_payment() {
		$sql = 'SELECT
            	`number`,
            	`window`,
              `ifpayment`,
              `type`,
              `status`
            FROM
            	`tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
            	AND (`status` = "PAYMENT-SERVING")
              AND isactive = "yes"';

		$query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
	}

  public function m_ajax_get_waiting1to6() {
		$this->load->helper('waiting_queue');
		$sql = 'SELECT
            	`number`,
            	`window`
            FROM
            	`tbl_transaction`
            WHERE
            	DATE(`dtadded`) = CURDATE()
            	AND `status` = "NA"
            	AND isactive = "yes"
            ORDER BY '.qtax_waiting_order().'
            LIMIT 6';

		$query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
	}

  // public function m_ajax_get_waiting4to6() {
	// 	$sql = 'SELECT
  //           	`number`,
  //           	`window`
  //           FROM
  //           	`tbl_transaction`
  //           WHERE
  //           	DATE(`dtadded`) = CURDATE()
  //           	AND `status` = "NA"
  //           	AND isactive = "yes"
  //           ORDER BY
  //           	`dtadded` ASC
  //           LIMIT 3 OFFSET 3';
  //
	// 	$query = $this->db->query($sql);
  //   if ($query->num_rows() > 0) {
  //     return $query->result();
  //   } else {
  //     return false;
  //   }
	// }

  public function m_ajax_get_pending1to6() {
		$sql = 'SELECT
            	`number`,
            	`window`
            FROM
            	`tbl_transaction`
            WHERE
            	DATE(`dtadded`) = CURDATE()
            	AND `status` = "PENDING"
            	AND isactive = "yes"
            ORDER BY
            	`dtadded` ASC
            LIMIT 6';

		$query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
	}

  // public function m_ajax_get_pending4to6() {
	// 	$sql = 'SELECT
  //           	`number`,
  //           	`window`
  //           FROM
  //           	`tbl_transaction`
  //           WHERE
  //           	DATE(`dtadded`) = CURDATE()
  //           	AND `status` = "PENDING"
  //           	AND isactive = "yes"
  //           ORDER BY
  //           	`dtadded` ASC
  //           LIMIT 3 OFFSET 3';
  //
	// 	$query = $this->db->query($sql);
  //   if ($query->num_rows() > 0) {
  //     return $query->result();
  //   } else {
  //     return false;
  //   }
	// }

  public function m_ajax_get_pending() {
		$sql = 'SELECT
            	`number`,
            	`window`
            FROM
            	`tbl_transaction`
            WHERE
              DATE(`dtadded`) = CURDATE()
            	AND `status` = "PENDING"
              AND isactive = "yes"';

		$query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
	}

}
