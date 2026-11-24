<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data extends CI_Controller
{
    /*
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller i
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
    */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->model('Aadhar_detail_model');
        date_default_timezone_set("Asia/Kolkata");
    }

    public function Student_Adhar()
    {
        if ($this->input->method() == 'post') {
            $file_name = $this->uploadFile();
            $status = $this->Aadhar_detail_model->Aadhar_save($file_name);
        }

        $__id = $this->input->get('student-id');
        // $token = $this->input->get('token');

        $data['profile'] = $this->Student_model->getstudentDetails($__id);
        // $data['profile'] = $this->Student_model->getstudentDetails($token);
        $data['submit_enabled'] = true;
        $data['record'] = $this->Aadhar_detail_model->data($__id);
        $this->load->view('Adhar_detail', $data);
    }
    public function uploadFile()
    {
        // Check if a file was uploaded
        if (!empty($_FILES['aadhar_front_img']['name'])) {
            // Define the maximum file size you want (1 MB in bytes)
            $maxFileSize = 1024 * 1024; // 1 MB

            // Get the uploaded file's size and type
            $fileSize = $_FILES['aadhar_front_img']['size'];
            $fileType = $_FILES['aadhar_front_img']['type'];

            // Check if compression is needed based on file size and type
            if ($fileSize > $maxFileSize && ($fileType === 'application/pdf' || strpos($fileType, 'image/') === 0)) {
                // Compress the file based on its type
                $uploadedFile = $_FILES['aadhar_front_img']['tmp_name'];

                // Determine the destination file path
                $destination = 'upload/' . $_FILES['aadhar_front_img']['name'];

                if ($fileType === 'application/pdf') {
                    // Implement your PDF compression logic here (e.g., using PDFtk or other PDF libraries)
                    // Save the compressed PDF to the destination
                } elseif (strpos($fileType, 'image/') === 0) {
                    // Compress the image using GD library
                    list($width, $height) = getimagesize($uploadedFile);
                    $newWidth = 800; // Adjust this to your desired width
                    $newHeight = ($height / $width) * $newWidth;

                    $imageResized = imagecreatetruecolor($newWidth, $newHeight);
                    $image = imagecreatefromstring(file_get_contents($uploadedFile));

                    imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    // Save the compressed image to the destination
                    imagejpeg($imageResized, $destination, 90); // 90 is the JPEG quality, adjust as needed

                    imagedestroy($imageResized);
                    imagedestroy($image);

                    return $_FILES['aadhar_front_img']['name'];
                }
            } else {
                // No need to compress, save the file as is
                $destination = 'upload/' . $_FILES['aadhar_front_img']['name'];
                move_uploaded_file($_FILES['aadhar_front_img']['tmp_name'], $destination);
                return $_FILES['aadhar_front_img']['name'];
            }
            // You can then proceed to save the $destination path in your database or use it as needed.
        }
        // Handle other parts of your code (e.g., database insertion or response to the user)
    }
}
