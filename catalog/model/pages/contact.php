<?php
class ModelPagesContact extends Model {
    public function getContent($content_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "page_contactus WHERE id = '".$content_id."' ");
        return $query->row;
    }
    
    public function saveCareers() {
        
        $sur_name = $_POST['sur_name'];
        $capacity = $_POST['capacity'];
        $phone = $_POST['phone'];
        $usa = $_POST['usa'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $query = $this->db->query("
        INSERT INTO " . DB_PREFIX . "page_contactus_form 
        SET 
            sur_name = '$sur_name', 
            capacity = '$capacity', 
            phone = '$phone', 
            usa = '$usa', 
            email = '$email', 
            message = '$message'
        ");


        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail_content = '<table border="1" style="width: 100%; max-width: 600px; margin: auto; border-collapse: collapse; border: 1px solid #000; font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <tr>
        <td colspan="2" style="background-color: #000; color:#FFF; padding: 20px; text-align: center; font-size: 18px; font-weight: bold;">
            Emile Rassam - Contact Request Submitted
        </td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold; width: 30%;">Name:</td>
        <td style="padding: 10px;">'.$sur_name.'</td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold;">Capacity:</td>
        <td style="padding: 10px;">'.$capacity.'</td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold;">Phone:</td>
        <td style="padding: 10px;">'.$phone.'</td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold;">Country:</td>
        <td style="padding: 10px;">'.$usa.'</td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold;">Email:</td>
        <td style="padding: 10px;">'.$email.'</td>
        </tr>
        <tr>
        <td style="padding: 10px; font-weight: bold;">Message:</td>
        <td style="padding: 10px;">'.$message.'</td>
        </tr>
        </table>';

    
        $mail->setTo($email);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode("Emile Rassam - Contact Request", ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode("Ye Aik message hay", ENT_QUOTES, 'UTF-8'));
        $mail->send();

    }
}
