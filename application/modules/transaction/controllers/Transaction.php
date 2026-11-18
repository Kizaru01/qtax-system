<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_transaction');
    }

    public function index()
    {
      if ($this->session->userdata('a_type') == 'NUMBER') {
        redirect('number');
      } else {
        redirect('transaction/serve');
      }
    }

    public function serve()
    {
        if ($this->session->userdata('accountId')) {
            $data['title'] = 'Transaction';
            $data['content'] = 'transaction/v_transaction';
            $this->load->view('layouts/v_master', $data);
            file_put_contents(basename('/_serve.txt'),file_get_contents(base_url().'transaction/ajax_get_serve'));
           
        }  else {
          redirect('account');
        }
    }

    // public function verification()
    // {
    //     if ($this->session->userdata('accountId')) {
    //         $data['title'] = 'Transaction';
    //         $data['content'] = 'transaction/v_verification';
    //         $this->load->view('layouts/v_master', $data);
    //         file_put_contents(basename('/_serve_verification.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_verification'));
    //     }  else {
    //       redirect('account');
    //     }
    // }

    // public function payment()
    // {
    //     if ($this->session->userdata('accountId')) {
    //         $data['title'] = 'Transaction';
    //         $data['content'] = 'transaction/v_transactionpayment-taxbill5';
    //         $this->load->view('layouts/v_master', $data);
    //         file_put_contents(basename('/_servepayment.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_payment'));
    //         file_put_contents(basename('/_servepayment_verify.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_payment_verify'));
    //         // file_put_contents(basename('/_servepayment_verify.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_payment_verify'));
    //     }  else {
    //       redirect('account');
    //     }
    // }

    // public function releasing()
    // {
    //     if ($this->session->userdata('accountId')) {
    //         $data['title'] = 'Transaction';
    //         $data['content'] = 'transaction/v_releasing';
    //         $this->load->view('layouts/v_master', $data);
    //         file_put_contents(basename('/_servereleasing.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_releasing'));
    //     }  else {
    //       redirect('account');
    //     }
    // }

    public function ajax_get_mic() {
      if ($this->input->get('id')) {
          // Re-announce the caller's ticket (bell + speech) when it is SERVING at their window.
          $announced = FALSE;
          $ticket_id = $this->input->get('ticket');
          $window = $this->session->userdata('window');
          if ($ticket_id && $this->session->userdata('accountId') && $window !== NULL && $window !== '') {
            $ticket = $this->mdl_transaction->m_get_serving_announcement($ticket_id);
            if ($ticket && (int) $ticket->window === (int) $window) {
              $this->_push_announcement($ticket->number, $ticket->window);
              $announced = TRUE;
            }
          }

          // Otherwise keep the previous behaviour: bell only.
          // (The announcement plays the bell itself, so skip it to avoid a double bell.)
          if (!$announced) {
            file_put_contents(basename('/_mic.txt'),time());
          }
          $result = array('status' => true,'content'=> '');
          echo json_encode($result);
      } else {
        $result = array('status' => false,'content'=> '');
        echo json_encode($result);
      }
    }

    public function ajax_get_serve() {
      $regular = $this->mdl_transaction->m_ajax_get_serve('REGULAR');
      $special = $this->mdl_transaction->m_ajax_get_serve('SPECIAL');
      $regular1 = $this->mdl_transaction->m_ajax_get_serve('REGULAR5-10');
      $special1 = $this->mdl_transaction->m_ajax_get_serve('SPECIAL5-10');

      // $regular_tax = $this->mdl_transaction->m_ajax_get_serve('REGULAR-TAX');
      // $special_tax = $this->mdl_transaction->m_ajax_get_serve('SPECIAL-TAX');

      $result = array(
        'status' => 'yes',
        'regular'=> $regular,
        'special'=> $special,
        'special1'=> $special1,
        'regular1'=> $regular1,
        // 'special_tax'=> $special_tax,
        // 'regular_tax'=> $regular_tax,
      );
      echo json_encode($result);
    }

    public function ajax_get_serve_verification() {
      $regular = $this->mdl_transaction->m_ajax_get_serve_verification('REGULAR');
      $special = $this->mdl_transaction->m_ajax_get_serve_verification('SPECIAL');
      $regular1 = $this->mdl_transaction->m_ajax_get_serve_verification('REGULAR5-10');
      $special1 = $this->mdl_transaction->m_ajax_get_serve_verification('SPECIAL5-10');

      // $regular_tax = $this->mdl_transaction->m_ajax_get_serve('REGULAR-TAX');
      // $special_tax = $this->mdl_transaction->m_ajax_get_serve('SPECIAL-TAX');

      $result = array(
        'status' => 'yes',
        'regular'=> $regular,
        'special'=> $special,
        'special1'=> $special1,
        'regular1'=> $regular1,
        // 'special_tax'=> $special_tax,
        // 'regular_tax'=> $regular_tax,
      );
      echo json_encode($result);
    }

    public function ajax_get_serve_payment() {
      $regular = $this->mdl_transaction->m_ajax_get_serve5to10('REGULAR');
      // $special = $this->mdl_transaction->m_ajax_get_serve5to10('SPECIAL');
      $regular1 = $this->mdl_transaction->m_ajax_get_serve5to10('REGULAR5-10'); // PAYMENT REGULAR 5 - 10
      // $special1 = $this->mdl_transaction->m_ajax_get_serve5to10('SPECIAL5-10');

      $regular_tax = $this->mdl_transaction->m_ajax_get_serve5to10('REGULAR-TAX'); // PAYMENT REGULAR
      $special_tax = $this->mdl_transaction->m_ajax_get_serve5to10('SPECIAL-TAX'); // PAYMENT SPECIAL

      $result = array(
        'status' => 'yes',
        'regular'=> $regular,
        // 'special'=> $special,
        'regular1'=> $regular1,
        // 'special1'=> $special1,
        'special_tax'=> $special_tax,
        'regular_tax'=> $regular_tax
      );
      echo json_encode($result);
    }

    public function ajax_get_serve_payment_verify() {
      $regular = $this->mdl_transaction->m_ajax_get_serve5to10_verify('REGULAR');
      // $special = $this->mdl_transaction->m_ajax_get_serve5to10('SPECIAL');
      // $regular1 = $this->mdl_transaction->m_ajax_get_serve5to10_verify('REGULAR5-10'); // PAYMENT REGULAR 5 - 10
      // $special1 = $this->mdl_transaction->m_ajax_get_serve5to10('SPECIAL5-10');

      // $regular_tax = $this->mdl_transaction->m_ajax_get_serve5to10_verify('REGULAR-TAX'); // PAYMENT REGULAR
      // $special_tax = $this->mdl_transaction->m_ajax_get_serve5to10_verify('SPECIAL-TAX'); // PAYMENT SPECIAL

      $result = array(
        'status' => 'yes',
        'regular'=> $regular,
        // 'special'=> $special,
        // 'regular1'=> $regular1,
        // 'special1'=> $special1,
        // 'special_tax'=> $special_tax,
        // 'regular_tax'=> $regular_tax
      );
      echo json_encode($result);
    }

    public function ajax_get_serve_releasing() {
      $regular = $this->mdl_transaction->m_ajax_get_serve_releasing('RELEASING-REGULAR');
      $special = $this->mdl_transaction->m_ajax_get_serve_releasing('RELEASING-SPECIAL');
    
      $result = array(
        'status' => 'yes',
        'regular'=> $regular,
        'special'=> $special,
      );
      echo json_encode($result);
    }

    public function save_trasaction() {
      $data = array(
        'id' =>  $this->input->post('id'),
        'status' => $this->input->post('status'),
        'window' => $this->input->post('window'),
      );

      if ($data['status'] == "VERIFY") {
        $data['doneby'] = $this->session->userdata('accountId');
        $data['dtdone'] = date("Y-m-d H:i:s");
        $data['window'] = NULL;
        $data['type'] = $this->input->post('type');
        $data['status'] = 'NA';
        $data['dtorder'] = date("Y-m-d H:i:s");
      }

      if ($data['status'] == "PENDING") {
        $data['pendingby'] = $this->session->userdata('accountId');
        $data['dtpending'] = date("Y-m-d H:i:s");
      }

      if ($data['status'] == "SERVING") {
        $data['serveby'] = $this->session->userdata('accountId');
        $data['dtserving'] = date("Y-m-d H:i:s");
      }

      if ($data['status'] == "FINISHED") {
        $data['doneby'] = $this->session->userdata('accountId');
        $data['dtdone'] = date("Y-m-d H:i:s");
        $data['dtupdated'] = date("Y-m-d H:i:s");
      }

      //===
      if ($data['status'] == "PAYMENT-PENDING") {
        $data['p_pendingby'] = $this->session->userdata('accountId');
        $data['p_dtpending'] = date("Y-m-d H:i:s");
      }

      if ($data['status'] == "PAYMENT-SERVING") {
        $data['p_serveby'] = $this->session->userdata('accountId');
        $data['p_dtserving'] = date("Y-m-d H:i:s");
         $data['dtdone'] = NULL;
      }

      if ($data['status'] == "PAYMENT-FINISHED") {
        $data['p_doneby'] = $this->session->userdata('accountId');
        $data['p_dtdone'] = date("Y-m-d H:i:s");
      }

      //releasing
      if ($data['status'] == "RELEASING-SERVING") {
        $data['r_serveby'] = $this->session->userdata('accountId');
        $data['r_dtserving'] = date("Y-m-d H:i:s");
      }
      if ($data['status'] == "RELEASING-PENDING") {
        $data['r_pendingby'] = $this->session->userdata('accountId');
        $data['r_dtpending'] = date("Y-m-d H:i:s");
      }
      if ($data['status'] == "RELEASING-DONE") {
        $data['r_doneby'] = $this->session->userdata('accountId');
        $data['r_dtdone'] = date("Y-m-d H:i:s");
      }

      if ($result = $this->mdl_transaction->m_save_trasaction($data)) {
        // Announce on the viewing monitor only when a row actually changed to SERVING.
        if ($data['status'] == "SERVING" && $this->db->affected_rows() > 0) {
          $ticket = $this->mdl_transaction->m_get_serving_announcement($data['id']);
          if ($ticket) {
            $this->_push_announcement($ticket->number, $ticket->window);
          }
        }

        file_put_contents(basename('/_getviewing.txt'),file_get_contents(base_url().'viewing/ajax_get_viewing'));


   
        // if ($data['status'] == "PENDING" || $data['status'] == "SERVING") {
        //  if ($data['status'] == "DONE") {
            // file_put_contents(basename('/_serve_verification.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_verification'));
        // }
         
          file_put_contents(basename('/_serve.txt'),file_get_contents(base_url().'transaction/ajax_get_serve'));
        // } else {
          // file_put_contents(basename('/_servepayment.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_payment'));
          // file_put_contents(basename('/_servepayment_verify.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_payment_verify'));
        // }
       
        // if ($data['status'] == "RELEASING-SERVING" || $data['status'] == "RELEASING-PENDING" || $data['status'] == "RELEASING-DONE" ||  $data['status'] == "PAYMENT-FINISHED") {
        //    file_put_contents(basename('/_servereleasing.txt'),file_get_contents(base_url().'transaction/ajax_get_serve_releasing'));
        // }

        $result = array('status' => 'yes');
      } else {
        $result = array('status' => 'no');
      }

      echo json_encode($result);
    }

    // Appends an announcement to _announce.txt, which _announce_check.php
    // long-polls for the viewing monitor. Each entry gets an increasing
    // sequence number so the monitor can keep order and skip duplicates.
    private function _push_announcement($number, $window)
    {
      $fp = @fopen(FCPATH.'_announce.txt', 'c+');
      if ($fp === FALSE) {
        log_message('error', 'Announcement: unable to open _announce.txt');
        return;
      }

      if (flock($fp, LOCK_EX)) {
        $log = json_decode(stream_get_contents($fp), TRUE);
        if (!is_array($log) || !isset($log['seq'], $log['items']) || !is_array($log['items'])) {
          $log = array('seq' => 0, 'items' => array());
        }

        $log['seq'] = (int) $log['seq'] + 1;
        $log['items'][] = array(
          'seq'    => $log['seq'],
          'number' => (string) $number,
          'window' => (string) $window,
        );
        // Keep a short history so a briefly disconnected monitor can catch up.
        $log['items'] = array_slice($log['items'], -20);

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($log));
        fflush($fp);
        flock($fp, LOCK_UN);
      } else {
        log_message('error', 'Announcement: unable to lock _announce.txt');
      }

      fclose($fp);
    }

}
