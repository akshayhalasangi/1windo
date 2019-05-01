<?php
defined('BASEPATH') or exit('No direct script access allowed');
define('EMAIL_TEMPLATE_SEND', true);
class Emails_model extends CI_Model
{
    private $attachment = array();
    private $client_email_templates;
    private $staff_email_templates;
    private $rel_id;
    private $rel_type; 

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
//        $this->client_email_templates = get_client_email_templates_slugs();
  //      $this->staff_email_templates  = get_staff_email_templates_slugs();
    }

    /**
     * @param  string
     * @return array
     * Get email template by type
     */
    public function get($where = array())
    {
        $this->db->where($where);

        return $this->db->get('tblemailtemplates')->result_array();
    }

    /**
     * @param  integer
     * @return object
     * Get email template by id
     */
    public function get_email_template_by_id($id)
    {
        $this->db->where('emailtemplateid', $id);

        return $this->db->get('tblemailtemplates')->row();
    }

    /**
     * Create new email template
     * @param mixed $data
     */
    public function add_template($data)
    {
        $this->db->insert('tblemailtemplates', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }

        return false;
    }

    /**
     * @param  array $_POST data
     * @param  integer ID
     * @return boolean
     * Update email template
     */
    public function update($data)
    {
        if (isset($data['plaintext'])) {
            $data['plaintext'] = 1;
        } else {
            $data['plaintext'] = 0;
        }

        if (isset($data['disabled'])) {
            $data['active'] = 0;
            unset($data['disabled']);
        } else {
            $data['active'] = 1;
        }
        $main_id      = false;
        $affectedRows = 0;
        $i            = 0;
        foreach ($data['subject'] as $id => $val) {
            if ($i == 0) {
                $main_id = $id;
            }

            $_data              = array();
            $_data['subject']   = $val;
            $_data['fromname']  = $data['fromname'];
            $_data['fromemail'] = $data['fromemail'];
            $_data['message']   = $data['message'][$id];
            $_data['plaintext'] = $data['plaintext'];
            $_data['active']    = $data['active'];

            $this->db->where('emailtemplateid', $id);
            $this->db->update('tblemailtemplates', $_data);
            if ($this->db->affected_rows() > 0) {
                $affectedRows++;
            }

            $i++;
        }
        $main_template = $this->get_email_template_by_id($main_id);

        if ($affectedRows > 0 && $main_template) {
            logActivity('Email Template Updated [' . $main_template->name . ']');

            return true;
        }

        return false;
    }

    /**
     * Send email - No templates used only simple string
     * @since Version 1.0.2
     * @param  string $email   email
     * @param  string $message message
     * @param  string $subject email subject
     * @return boolean
     */
    public function send_simple_email($email, $subject, $message)
    {
        $cnf = array(
            'from_email' => get_option('smtp_email'),
            'from_name' => get_option('companyname'),
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        );
         
        $cnf['message'] = check_for_links($cnf['message']);
        
        $cnf = do_action('before_send_simple_email', $cnf);

        $this->email->initialize($cnf);
        $this->email->set_newline("\r\n");
        $this->email->clear(true);
        $this->email->from($cnf['from_email'], $cnf['from_name']);
        $this->email->to($cnf['email']);

        // Possible action hooks data
        if (isset($cnf['bcc'])) {
            $this->email->bcc($cnf['bcc']);
        }

        if (isset($cnf['cc'])) {
            $this->email->cc($cnf['cc']);
        }

        if (isset($cnf['reply_to'])) {
            $this->email->reply_to($cnf['reply_to']);
        }

        $this->email->subject($cnf['subject']);
        $this->email->message($cnf['message']);



        $this->email->set_alt_message(strip_tags($cnf['message']));
        
        /*if (count($this->attachment) > 0) {
            foreach ($this->attachment as $attach) {
                if (!isset($attach['read'])) {
                    $this->email->attach($attach['attachment'], 'attachment', $attach['filename'], $attach['type']);
                } else {
                    $this->email->attach($attach['attachment'], '', $attach['filename']);
                }
            }
        }
     
        $this->clear_attachments();*/
     
        if ($this->email->send()) {
            logActivity('Email sent to: ' . $cnf['email'] . ' Subject: ' . $cnf['subject']);

            return true;
        }

        return false;
        
   }
   
   public function send_email($email, $subject, $message,$action){   
  
    $this->email->initialize(array(
      'protocol' => 'mail',
      'smtp_host' => 'sub5.mail.dreamhost.com',
      'smtp_user' => 'no-reply@intorque.net',
      'smtp_pass' => 'noreply@987',
      'smtp_port' => 465,
      'crlf' => "\r\n",
      'newline' => "\r\n"
    ));
    $body = '';
    $this->email->from('no-reply@intorque.net', 'In MY Call');
    $this->email->to($email);   
    $this->email->subject($subject);
    $this->email->message($body);
        
    if($this->email->send()) {      
        return true;
    }
    
    return false;

   }

    /**
     * Send email template
     * @param  string $template_slug email template slug
     * @param  string $email         email to send
     * @param  array $merge_fields  merge field
     * @param  string $ticketid      used only when sending email templates linked to ticket / used for piping
     * @param  mixed $cc
     * @return boolean
     */
    public function send_email_template($template_slug, $email, $merge_fields, $ticketid = '', $cc = '')
    {   
        $template                     = get_email_template_for_sending($template_slug, $email);
        $staff_email_templates_slugs  = get_staff_email_templates_slugs();
        $client_email_templates_slugs = get_client_email_templates_slugs();

        $inactive_user_table_check = '';
        // Dont send email templates for non active contacts/staff/ Do checking here
        if (in_array($template_slug, $staff_email_templates_slugs)) {
            $inactive_user_table_check = 'masters';
        } elseif (in_array($template_slug, $client_email_templates_slugs)) {
            $inactive_user_table_check = 'tblcontacts';
        }

        if ($inactive_user_table_check != '') {
            $this->db->select('active')->where('email', $email);
            $user = $this->db->get($inactive_user_table_check)->row();
            if ($user) {
                if ($user->active == 0) {
                    return false;
                }
            }
        }

        if (!$template) {
            logActivity('Email Template Not Found');

            return false;
        }

        if ($template->active == 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->clear_attachments();

            return false;
        }

        $template = parse_email_template($template, $merge_fields);

        // email config
        if ($template->plaintext == 1) {
            $this->config->set_item('mailtype', 'text');
            $template->message = strip_tags($template->message);
        }
        $fromemail = $template->fromemail;
        $fromname  = $template->fromname;
        if ($fromemail == '') {
            $fromemail = get_option('smtp_email');
        }
        if ($fromname == '') {
            $fromname = get_option('companyname');
        }

        $reply_to = false;
        if (is_numeric($ticketid) && $template->type == 'ticket') {
            $this->load->model('tickets_model');
            $ticket           = $this->tickets_model->get_ticket_by_id($ticketid);
            $department_email = get_department_email($ticket->department);
            if (!empty($department_email) && filter_var($department_email, FILTER_VALIDATE_EMAIL)) {
                $reply_to = $department_email;
            }
            // IMPORTANT
            // Dont change/remove this line, this is used for email piping so the software can recognize the ticket id.
            if (substr($template->subject, 0, 10) != "[Ticket ID") {
                $template->subject = '[Ticket ID: ' . $ticketid . '] ' . $template->subject;
            }
        }

        $hook_data['template'] = $template;
        $hook_data['email']    = $email;

        $hook_data['template']->message = check_for_links($hook_data['template']->message);

        $hook_data = do_action('before_email_template_send', $hook_data);

        $template = $hook_data['template'];
        $email    = $hook_data['email'];

        if (isset($template->prevent_sending)) {
            return false;
        }

        $this->email->initialize();
        $this->email->set_newline("\r\n");
        $this->email->clear(true);
        $this->email->from($fromemail, $fromname);
        $this->email->subject($template->subject);

        $this->email->message($template->message);
        if (is_array($cc) || !empty($cc)) {
            $this->email->cc($cc);
        }

        // Used for action hooks
        if (isset($template->bcc)) {
            $this->email->bcc($template->bcc);
        }

        if ($reply_to != false) {
            $this->email->reply_to($reply_to);
        } elseif (isset($template->reply_to)) {
            $this->email->reply_to($template->reply_to);
        }

        if ($template->plaintext == 0) {
            $this->email->set_alt_message(strip_tags($template->message));
        }

        $this->email->to($email);
        if (count($this->attachment) > 0) {
            foreach ($this->attachment as $attach) {
                if (!isset($attach['read'])) {
                    $this->email->attach($attach['attachment'], 'attachment', $attach['filename'], $attach['type']);
                } else {
                    $this->email->attach($attach['attachment'], '', $attach['filename']);
                }
            }
        }
        $this->clear_attachments();
        if ($this->email->send()) {
            logActivity('Email Send To [Email: ' . $email . ', Template: ' . $template->name . ']');

            return true;
        }

        return false;
    }

    /**
     * @param resource
     * @param string
     * @param string (mime type)
     * @return none
     * Add attachment to property to check before an email is send
     */
    public function add_attachment($attachment)
    {
        $this->attachment[] = $attachment;
    }

    /**
     * @return none
     * Clear all attachment properties
     */
    private function clear_attachments()
    {
        $this->attachment = array();
    }

    public function set_rel_id($rel_id)
    {
        $this->rel_id = $rel_id;
    }

    public function set_rel_type($rel_type)
    {
        $this->rel_type = $rel_type;
    }

    public function get_rel_id()
    {
        return $this->rel_id;
    }

    public function get_rel_type()
    {
        return $this->rel_type;
    }

    public function send_enquiry_email($email, $subject, $action,$name = ''){   

    $this->email->initialize(array(
      'protocol' => 'mail',
      'smtp_host' => 'sub5.mail.dreamhost.com',
      'smtp_user' => 'no-reply@projects.digital24x7.com',
      'smtp_pass' => 'NmrEJKe8!',
      'smtp_port' => 465,
      'crlf' => "\r\n",
      'newline' => "\r\n"
    ));
    /*$body = '';
    $body = $message;*/
    $body = '';
    $body .= '
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <title></title>  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
  #outlook a { padding: 0; }
  .ReadMsgBody { width: 100%; }
  .ExternalClass { width: 100%; }
  .ExternalClass * { line-height:100%; }
  body { margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
  p { display: block; margin: 13px 0; }
</style>

<style type="text/css">
  @media only screen and (max-width:480px) {
    @-ms-viewport { width:320px; }
    @viewport { width:320px; }
  }
</style>
    <link href="https://fonts.googleapis.com/css?family=Open Sans" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
    <style type="text/css">

        @import url(https://fonts.googleapis.com/css?family=Open Sans);
  @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);

    </style>
  <!--<![endif]--><style type="text/css">
            .ks-logo {
                font-size: 18px;
                text-decoration: none;
                color: #3a529b;
                font-weight: bold;
            }

            .ks-link {
                color: #22a7f0;
                text-decoration: none;
            }
        </style><style type="text/css">
  @media only screen and (min-width:480px) {
    .mj-column-per-100 { width:100%!important; }
  }
</style>
</head>
<body style="background: #f5f6fa;">
  
  <div class="mj-container" style="background-color:#f5f6fa;">
  <div style="margin:0px auto;max-width:600px;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;" align="center" border="0">
        <tbody>
            <tr>
                <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;padding-bottom:20px;padding-top:30px;">
                    <div style="cursor:auto;color:#000000;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;text-align:center;">
                        <a href="'.base_url().'" class="ks-logo">'._l('txt_1windo').'</a>
                    </div>
                    <div style="margin:0px auto;max-width:600px;background:#fff;"><table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0px;width:100%;background:#fff;" align="center" border="0"><tbody><tr><td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                    <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;"><table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0"><tbody><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-bottom:20px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;" align="center" border="0"><tbody> </tbody></table></td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-bottom:20px;" align="center">
                    <div style="cursor:auto;color:#333;font-family:Open Sans;font-size:30px;font-weight:bold;line-height:22px;text-align:center;">
                        <a href="'.base_url().'Authentication/ResetPassword?email='.$email.'&user=admin" target="_blank"> Forgot Your Password?</a>';   
                    $body .= '</div>
                </td>
            </tr>
            <tr>
                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-bottom:20px;" align="center">
                    <div style="cursor:auto;color:#333;font-family:Open Sans;font-size:18px;line-height:1.44;text-align:center;">It happens. Click the link bellow to reset your password.</div>
                </td>
            </tr>
            <tr>
                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-bottom:30px;" align="center"><table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:separate;" align="center" border="0">
                <tbody>
                    <tr>
                        <td style="border:none;border-radius:2px;color:#fff;cursor:auto;padding:12px 30px;" align="center" valign="middle" bgcolor="#3a529b">
                            <a href="#" style="text-decoration:none;background:#3a529b;color:#fff;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:14px;font-weight:500;line-height:120%;text-transform:none;margin:0px;" target="_blank">Reset Password</a>
                        </td>
                    </tr>
                </tbody>
    </table>
    </td>
    </tr>
    <tr>
        <td style="word-wrap:break-word;font-size:0px;padding:10px 25px;padding-bottom:10px;"><p style="font-size:1px;margin:0px auto;border-top:1px solid #e6e6e6;width:100%;"></p>
        
        <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="font-size:1px;margin:0px auto;border-top:1px solid #e6e6e6;width:100%;" width="600"><tr><td style="height:0;line-height:0;"> </td>
        </tr>
        </table>
        
        </td></tr><tr><td style="word-wrap:break-word;font-size:0px;padding:10px 25px;" align="left"></td></tr></tbody></table></div>
  </td></tr></tbody></table></div></div>
</body>
</html>
</html>';
    $this->email->from('no-reply@intorque.net', 'Intorque');
    $this->email->to($email);   
    $this->email->subject($subject);
    $this->email->message($body);
        
    if($this->email->send()) {      
        return true;
    }   
    return false;
   }
}
