<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Educations extends CI_Controller {

    private $limit = 10;

    public function __construct() {
        parent::__construct();
        $this->load->model('meducation', 'education_model');
    }

    public function index($offset = 0) {
        $data['title'] = "Education";
        $data['btn_add'] = anchor('educations/add', 'Add new Education');
        $data['btn_home'] = anchor(base_url(), 'Home');
        // offset
        $uri_segment = 3;
        $offset = $this->uri->segment($uri_segment);

        // load data
        $data['educations'] = $this->education_model->get_page_list($this->limit, $offset)->result();

        // generate paginate
        $this->load->library('pagination');
        $config['base_url'] = site_url('educations/index/');
        $config['total_rows'] = $this->education_model->count_all();
        $config['per_page'] = $this->limit;
        $config['uri_segment'] = $uri_segment;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('staff_education/index', $data);
    }

    function add() {
        $data['title'] = 'Add new Education';
        $data['form_action'] = site_url('educations/save');
        $data['link_back'] = anchor('educations/', 'Back', array('class' => 'back'));

        $data['id'] = '';
        $data['edu_year'] = array('name' => 'edu_year');
        $data['edu_gelar'] = array('name' => 'edu_gelar');
        $data['edu_name'] = array('name' => 'edu_name');
        $data['btn_save'] = array('name' => 'btn_save', 'value' => 'Save Education');

        $this->load->view('staff_education/frm_education', $data);
    }

    function edit($id) {
        $edu = $this->education_model->find($id)->row();
        $data['id'] = $edu->edu_id;
        $data['edu_year'] = array('name' => 'edu_year', 'value' => $edu->edu_year);
        $data['edu_gelar'] = array('name' => 'edu_gelar', 'value' => $edu->edu_gelar);
        $data['edu_name'] = array('name' => 'edu_name', 'value' => $edu->edu_name);

        $data['btn_save'] = array('name' => 'btn_save', 'value' => 'Update Education');

        $data['title'] = 'Update Education';
        $data['message'] = '';
        $data['form_action'] = site_url('educations/update');
        $data['link_back'] = anchor('educations/', 'Back');

        $this->load->view('staff_education/frm_education', $data);
    }

    function save() {
        $this->form_validation->set_rules('edu_year', 'edu_year', 'required');
        $this->form_validation->set_rules('edu_gelar', 'edu_gelar', 'required');
        $this->form_validation->set_rules('edu_name', 'edu_name', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['message'] = '';
        } else {
            $edu = array(
                'edu_year' => $this->input->post('edu_year'),
                'edu_gelar' => $this->input->post('edu_gelar'),
                'edu_name' => $this->input->post('edu_name')
            );
            $this->education_model->save($edu);

            // set user message
            $data['message'] = '<div class="success">add new Education success</div>';
            redirect('educations/', 'refresh');
        }
    }

    function update() {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('id', 'ID Record', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="error">' . validation_errors() . '</div>');
            redirect('educations/');
        } else {
            $edu = array(
                'edu_year' => $this->input->post('edu_year'),
                'edu_gelar' => $this->input->post('edu_gelar'),
                'edu_name' => $this->input->post('edu_name')
            );
            $this->education_model->update($id, $edu);
            redirect('educations/');
        }
    }

    function delete($id) {
        $this->education_model->delete($id);
        redirect('educations/', 'refresh');
    }

}