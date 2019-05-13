<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Location extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Location_Model');

    }

    public function index(){

        $dataa['title'] = _l('Countries');
        $dataa['listAssets'] = 'true';
        $dataa['country']= $this->Location_Model->getLocations();
        $this->load->view( LOCATION_URL.'manage', $dataa);
    }
    public function States(){

        $dataa['title'] = _l('States');
        $dataa['listAssets'] = 'true';
        $dataa['country']= $this->Location_Model->getStates();
        $this->load->view( LOCATION_URL.'states', $dataa);
    }
    public function Cities(){

        $dataa['title'] = _l('Cities');
        $dataa['listAssets'] = 'true';
        $dataa['country']= $this->Location_Model->getCities();
        $this->load->view( LOCATION_URL.'cities', $dataa);
    }

    public function Area(){

        $dataa['title'] = _l('Area');
        $dataa['listAssets'] = 'true';
        $dataa['country']= $this->Location_Model->getArea();
        $this->load->view( LOCATION_URL.'area', $dataa);
    }

    public function Location($id = '')
    {

        $data = $this->input->post();
        if(!empty($this->input->post())){

            if(!empty($id)){


                    $success = $this->Location_Model->update($data,$id);

                    if ($success == true) {
                        setFlashData(_l('Country update successfully'),'success',_l('success'));
                        redirect('Admin/Location/Location/'.$id);
                    } else {
                        setFlashData(_l('Country update failed'),'danger',_l('fail'));
                    }


            } else {

                $success = $this->Location_Model->add($data);

                    if($success != false){
                        setFlashData(_l('Country added'),'success',_l('success'));
                        redirect('Admin/Location/Location/'.$id);
                    } else {
                        setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));
                    }

            }
        }


        $data['listAssets'] = 'true';
        if($id == '' || $id == 0){
            $data['title'] = _l('add_new',_l('Update Country'));
            $data['country'] = '';
        } else {
            $data['country'] = $this->Location_Model->getCoutryDetail($id);
            $data['addAssets'] = true;
            $data['title'] = _l('Update Country');
        }



        $this->load->view(LOCATION_URL . 'location', $data);
    }

    public function city($id = '')
    {

        $data = $this->input->post();
        if(!empty($this->input->post())){

            if(!empty($id)){


                $success = $this->Location_Model->updatecity($data,$id);

                if ($success == true) {
                    setFlashData(_l('city update successfully'),'success',_l('success'));
                    redirect('Admin/Location/city/'.$id);
                } else {
                    setFlashData(_l('city update failed'),'danger',_l('fail'));
                }


            } else {

                $success = $this->Location_Model->addcity($data);

                if($success != false){
                    setFlashData(_l('city added'),'success',_l('success'));
                    redirect('Admin/Location/city/'.$id);
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));
                }

            }
        }


        $data['listAssets'] = 'true';
        if($id == '' || $id == 0){
            $data['countryList'] = $this->Location_Model->getLocations();
            $data['stateList'] = $this->Location_Model->getStates();
            $data['title'] = _l('add_new',_l('Update city'));
            $data['country'] = '';
        } else {
            $data['country'] = $this->Location_Model->getCitydetail($id);
            $data['countryList'] = $this->Location_Model->getLocations();
            $data['stateList'] = $this->Location_Model->getStates();
            $data['addAssets'] = true;
            $data['title'] = _l('Update city');
        }



        $this->load->view(LOCATION_URL . 'cityedit', $data);
    }

    public function state($id = '',$country_id='')
    {

        $data = $this->input->post();
        if(!empty($this->input->post())){

            if(!empty($id)){


                $success = $this->Location_Model->updatesate($data,$id);

                if ($success == true) {
                    setFlashData(_l('state update successfully'),'success',_l('success'));
                    redirect('Admin/Location/state/'.$id);
                } else {
                    setFlashData(_l('state update failed'),'danger',_l('fail'));
                }


            } else {

                $success = $this->Location_Model->addState($data);

                if($success != false){
                    setFlashData(_l('state added'),'success',_l('success'));
                    redirect('Admin/Location/state/'.$id);
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));
                }

            }
        }


        $data['listAssets'] = 'true';
        if($id == '' || $id == 0){
            $data['countryList'] = $this->Location_Model->getLocations();
            $data['title'] = _l('add_new',_l('Update state'));
            $data['country'] = '';
        } else {
            $data['countryList'] = $this->Location_Model->getLocations();
            $data['CID']=$country_id;
            $data['country'] = $this->Location_Model->getStatedetail($id);
            $data['addAssets'] = true;
            $data['title'] = _l('Update state');
        }



        $this->load->view(LOCATION_URL . 'stateedit', $data);
    }

    public function areas($id = '',$country_id='')
    {

        $data = $this->input->post();
        if(!empty($this->input->post())){

            if(!empty($id)){


                $success = $this->Location_Model->updatearea($data,$id);

                if ($success == true) {
                    setFlashData(_l('Area update successfully'),'success',_l('success'));
                    redirect('Admin/Location/areas/'.$id);
                } else {
                    setFlashData(_l('state update failed'),'danger',_l('fail'));
                }


            } else {

                $success = $this->Location_Model->addarea($data);

                if($success != false){
                    setFlashData(_l('Area added'),'success',_l('success'));
                    redirect('Admin/Location/areas/'.$id);
                } else {
                    setFlashData(_l('please_fill_all_fields'),'danger',_l('fail'));
                }

            }
        }


        $data['listAssets'] = 'true';
        if($id == '' || $id == 0){
            $data['countryList'] = $this->Location_Model->getCities();
            $data['title'] = _l('add_new',_l('Update state'));
            $data['country'] = '';
        } else {
            $data['countryList'] = $this->Location_Model->getCities();

            $data['country'] = $this->Location_Model->getAreadetail($id);
            $data['addAssets'] = true;
            $data['title'] = _l('Update area');
        }



        $this->load->view(LOCATION_URL . 'areaedit', $data);
    }
    /* Delete Staffs */
    public function DeleteCountry(){


        $Id = $this->input->post('id');
        $Success = $this->Customers_model->DeleteCountry($Id);


        if($Success){
            setAjaxResponse( _l('customer_deleted_success'),'success',_l('success'));
            //setFlashData(_l('customer_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('customer_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    public function DeleteState(){


        $Id = $this->input->post('id');
        $Success = $this->Customers_model->DeleteState($Id);


        if($Success){
            setAjaxResponse( _l('customer_deleted_success'),'success',_l('success'));
            //setFlashData(_l('customer_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('customer_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    public function DeleteCity(){


        $Id = $this->input->post('id');
        $Success = $this->Customers_model->DeleteCity($Id);


        if($Success){
            setAjaxResponse( _l('customer_deleted_success'),'success',_l('success'));
            //setFlashData(_l('customer_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('customer_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    public function DeleteArea(){


        $Id = $this->input->post('id');
        $Success = $this->Customers_model->DeleteArea($Id);


        if($Success){
            setAjaxResponse( _l('customer_deleted_success'),'success',_l('success'));
            //setFlashData(_l('customer_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse( _l('customer_deleted_fail'),'warning',_l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
}
