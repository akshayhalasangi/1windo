<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Services extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('1windo_view_hierarchy');
        // if (!has_permission('Services', '', 'HasPermission')) {
        //   ajax_access_denied();
        // }
    }

    public function index()
    {
        $id=$this->Products_model->getVendorCategoryID(get_staff_user_id());
        if(isset($id)){
            $data['servicesList'] = $this->Services_model->getService(null, array("S_CategoryID" => $id));
            $data['serviceID'] = $id;

            $this->load->model('Categories_model');
            $categoryName = $this->Categories_model->getCategory($id);
            $categoryName = $categoryName->C_Name;

            $data['title'] = $categoryName._l('txt_services');
	    $data['listAssets'] = 'true';
            $data['categoryName'] =  $categoryName;
            $this->load->view(SERVICE_URL . 'manage', $data);
        }else{
            redirect('Admin/Services');
        }
    }
    public function Features($serviceid)
    {
        $data['title'] = _l('title_services_features');
        $data['listAssets'] = 'true';
        $data['featuresList'] = $this->Services_model->getFeatures(null, array('F_ServiceID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-features', $data);
    }
    public function Steps($serviceid)
    {
        $data['title'] = _l('title_services_steps');
        $data['listAssets'] = 'true';
        $data['stepsList'] = $this->Services_model->getSteps(null, array('ST_ServiceID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-steps', $data);
    }
    public function Works($serviceid)
    {
        $data['title'] = _l('title_services_works');
        $data['listAssets'] = 'true';
        $data['worksList'] = $this->Services_model->getWorks(null, array('W_ServiceID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-works', $data);
    }

    public function Reviews($serviceid)
    {
        $data['title'] = _l('title_services_reviews');
        $data['listAssets'] = 'true';
        $data['reviewsList'] = $this->Services_model->getReviews(null, array('R_Type' => '1', 'R_RelationID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-reviews', $data);
    }
    public function Packages($serviceid)
    {
        $data['title'] = _l('title_services_packages');
        $data['listAssets'] = 'true';
        $data['packagesList'] = $this->Services_model->getPackages(null, array('P_ServiceID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-packages', $data);
    }
    public function Options($serviceid, $packageid)
    {
        $data['title'] = _l('title_services_options');
        $data['listAssets'] = 'true';
        $data['optionsList'] = $this->Services_model->getOptions(null, array('O_ServiceID' => $serviceid, 'O_PackageID' => $packageid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['PackageID'] = $packageid;
        $this->load->view(SERVICE_URL . 'manage-options', $data);
    }
    public function Timeslabs($serviceid)
    {
        $data['title'] = _l('title_services_timeslabs');
        $data['listAssets'] = 'true';
        $data['timeslabsList'] = $this->Services_model->getTimeslabs(null, array('T_ServiceID' => $serviceid));
        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $this->load->view(SERVICE_URL . 'manage-timeslabs', $data);
    }
    public function Service($id = '', $categoryId = '')
    {
        $data = $this->input->post();
        if (!empty($this->input->post())) {

            if (!empty($id) && $id != 0) {
                $success = $this->Services_model->update($data, $id);
                handle_service_display_image($id);

                if ($success == true) {
                    setFlashData(_l('service_update_success'), 'success', _l('success'));
                    redirect('Admin/Services/serviceList/'.$data['Val_Category']);
                } else {
                    setFlashData(_l('service_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $service = $this->Services_model->add($data);
                handle_service_display_image($service);

                if ($service != false) {
                    setFlashData(_l('service_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/serviceList/'.$data['Val_Category']);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }

        $data['çategoryId'] = $categoryId;

        if ($id == '' || $id == 0) {
            $data['title'] = _l('txt_new_service');
            $data['service'] = '';
        } else {
            $service = $this->Services_model->getService($id);
            $data['service'] = $service;
            $data['title'] = _l('txt_update_service');
        }

        $data['categories'] = $this->Categories_model->getCategory(null, array('C_Type' => '1', 'C_Level' => '1'));
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'service', $data);
    }
    public function Feature($serviceid, $id = '')
    {

        $data = $this->input->post();
        if (!empty($this->input->post())) {

            if (!empty($id)) {
                $success = $this->Services_model->updateFeatures($data, $id);

                if ($success == true) {
                    setFlashData(_l('service_feature_update_success'), 'success', _l('success'));
                } else {
                    setFlashData(_l('service_feature_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $feature = $this->Services_model->addFeatures($data);

                if ($feature != false) {
                    setFlashData(_l('service_feature_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Features/' . $serviceid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('add_new', _l('add_service_feature'));
            $data['service'] = '';
        } else {
            $feature = $this->Services_model->getFeatures($id);
            $data['feature'] = $feature;
            $data['title'] = _l('txt_update_service_feature');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['Services'] = $this->Services_model->getService(null);
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'feature', $data);
    }
    public function Step($serviceid, $id = '')
    {
        $data = $this->input->post();
        if (!empty($this->input->post())) {

            if (!empty($id)) {
                $success = $this->Services_model->updateSteps($data, $id);

                if ($success == true) {
                    setFlashData(_l('service_step_update_success'), 'success', _l('success'));
                    redirect('Admin/Services/Steps/' . $serviceid);
                } else {
                    setFlashData(_l('service_step_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $step = $this->Services_model->addSteps($data);

                if ($step != false) {
                    setFlashData(_l('service_step_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Steps/' . $serviceid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('add_new', _l('add_service_step'));
            $data['service'] = '';
        } else {
            $step = $this->Services_model->getSteps($id);
            $data['step'] = $step;
            $data['title'] = _l('txt_update_service_step');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['Services'] = $this->Services_model->getService(null);
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'step', $data);
    }
    public function Work($serviceid, $id = '')
    {
        $data = $this->input->post();
        if (!empty($this->input->post())) {

            if (!empty($id)) {
                $success = $this->Services_model->updateWorks($data, $id);
                handle_service_works_display_image($id);

                if ($success == true) {
                    setFlashData(_l('service_work_update_success'), 'success', _l('success'));
                    redirect('Admin/Services/Works/' . $serviceid);
                } else {
                    setFlashData(_l('service_work_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $work = $this->Services_model->addWorks($data);
                handle_service_works_display_image($work);

                if ($work != false) {
                    setFlashData(_l('service_work_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Works/' . $serviceid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('add_new', _l('add_service_work'));
            $data['service'] = '';
        } else {
            $work = $this->Services_model->getWorks($id);
            $data['work'] = $work;
            $data['title'] = _l('txt_update_service_work');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['Services'] = $this->Services_model->getService(null);
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'work', $data);
    }
    public function Package($serviceid, $id = '')
    {
        $data = $this->input->post();

        if (!empty($this->input->post())) {

            if (!empty($id)) {
                $success = $this->Services_model->updatePackages($data, $id);

                if ($success == true) {
                    setFlashData(_l('service_package_update_success'), 'success', _l('success'));
                    redirect('Admin/Services/Packages/' . $serviceid);
                } else {
                    setFlashData(_l('service_package_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $step = $this->Services_model->addPackages($data);

                if ($step != false) {
                    setFlashData(_l('service_package_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Packages/' . $serviceid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('txt_new_service_package');
            $data['package'] = '';
        } else {
            $package = $this->Services_model->getPackages($id);
            $data['package'] = $package;
            $data['title'] = _l('txt_update_service_package');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['Services'] = $this->Services_model->getService(null);
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'package', $data);
    }
    public function Option($serviceid, $packageid, $id = '')
    {
        $data = $this->input->post();

        if (!empty($this->input->post())) {
            $data['Val_Oprice'] = number_format($data['Val_Oprice'], 2, '.', '');
            if (!empty($id)) {

                $success = $this->Services_model->updateOptions($data, $id);

                if ($success == true) {
                    setFlashData(_l('service_option_update_success'), 'success', _l('success'));
                } else {
                    setFlashData(_l('service_option_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $step = $this->Services_model->addOptions($data);

                if ($step != false) {
                    setFlashData(_l('service_option_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Options/' . $serviceid . '/' . $packageid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('add_new', _l('add_service_option'));
            $data['option'] = '';
        } else {
            $option = $this->Services_model->getOptions($id);
            $data['option'] = $option;
            $data['title'] = _l('txt_update_service_option');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['PackageID'] = $packageid;
        $data['Packages'] = $this->Services_model->getPackages(null, array('P_ServiceID' => $serviceid));
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'option', $data);
    }
    public function Timeslab($serviceid, $id = '')
    {
        $data = $this->input->post();
        if (!empty($this->input->post())) {

            if (!empty($id)) {
                $success = $this->Services_model->updateTimeslabs($data, $id);

                if ($success == true) {
                    setFlashData(_l('service_timeslab_update_success'), 'success', _l('success'));
                    redirect('Admin/Services/Timeslabs/' . $serviceid);
                } else {
                    setFlashData(_l('service_timeslab_update_fail'), 'danger', _l('fail'));
                }

            } else {

                $feature = $this->Services_model->addTimeslabs($data);

                if ($feature != false) {
                    setFlashData(_l('service_timeslab_register_succes'), 'success', _l('success'));
                    redirect('Admin/Services/Timeslabs/' . $serviceid);
                } else {
                    setFlashData(_l('please_fill_all_fields'), 'danger', _l('fail'));
                }
            }
        }
        if ($id == '') {
            $data['title'] = _l('txt_new_service_timeslab');
            $data['timeslab'] = '';
        } else {
            $timeslab = $this->Services_model->getTimeslabs($id);
            $data['timeslab'] = $timeslab;
            $data['title'] = _l('txt_update_service_timeslab');
        }

        $data['serviceName'] = ($this->Services_model->getService($serviceid)->S_Name);
        $data['ServiceID'] = $serviceid;
        $data['Services'] = $this->Services_model->getService(null);
        $data['addAssets'] = true;
        $this->load->view(SERVICE_URL . 'timeslab', $data);
    }
    /* Delete Get Services Ajax */
    public function getServices()
    {
        $Type = $this->input->post('Val_Type');
        $Level = $this->input->post('Val_Level');
        $Services = $this->Services_model->getService(null, array('C_Type' => $Type, 'C_Level' => $Level), false);

        if ($Services) {
            setAjaxResponse(_l('services_fetched_success'), 'success', _l('success'), $Services);
        } else {
            setAjaxResponse(_l('services_fetched_fail'), 'warning', _l('fail'));
        }
    }
    /* Delete Staffs */
    public function DeleteService()
    {

        $ServiceId = $this->input->post('id');
        $Success = $this->Services_model->deleteService($ServiceId);

        if ($Success) {
            setAjaxResponse(_l('service_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    /* Delete Staffs */
    public function DeleteFeature()
    {

        $FeatureId = $this->input->post('id');
        $Success = $this->Services_model->deleteFeature($FeatureId);

        if ($Success) {
            setAjaxResponse(_l('service_feature_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_feature_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }

    /* Delete Staffs */
    public function DeleteStep()
    {

        $StepId = $this->input->post('id');
        $Success = $this->Services_model->deleteStep($StepId);

        if ($Success) {
            setAjaxResponse(_l('service_step_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_step_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Delete Staffs */
    public function DeleteWork()
    {

        $WorkId = $this->input->post('id');
        $Success = $this->Services_model->deleteWork($WorkId);

        if ($Success) {
            setAjaxResponse(_l('service_work_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_work_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Delete Staffs */
    public function DeletePackage()
    {

        $PackageId = $this->input->post('id');
        $Success = $this->Services_model->deletePackage($PackageId);

        if ($Success) {
            setAjaxResponse(_l('service_package_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_package_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Delete Staffs */
    public function DeleteOption()
    {

        $OptionId = $this->input->post('id');
        $Success = $this->Services_model->deleteOption($OptionId);

        if ($Success) {
            setAjaxResponse(_l('service_option_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_option_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Delete Staffs */
    public function DeleteTimeslab()
    {

        $TimeslabId = $this->input->post('id');
        $Success = $this->Services_model->deleteTimeslab($TimeslabId);

        if ($Success) {
            setAjaxResponse(_l('service_timeslab_deleted_success'), 'success', _l('success'));
            //setFlashData(_l('service_deleted_success'),'success',_l('success'));
        } else {
            setAjaxResponse(_l('service_timeslab_deleted_fail'), 'warning', _l('fail'));
            //setFlashData(_l('staff_deleted_fail'),'warning',_l('fail'));
        }
    }
    /* Update staff stauts */
    public function UpdateServiceStatus()
    {

        $data = $this->input->post();
        $ServiceID = $data['id'];
        $data['Val_Sstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->update($data, $ServiceID);

            if ($Success) {
                setAjaxResponse(_l('service_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }
    /* Update staff stauts */
    public function UpdateFeatureStatus()
    {

        $data = $this->input->post();
        $FeatureID = $data['id'];
        $data['Val_Fstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateFeatures($data, $FeatureID);

            if ($Success) {
                setAjaxResponse(_l('service_feature_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_feature_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }
    /* Update staff stauts */
    public function UpdateStepStatus()
    {

        $data = $this->input->post();
        $StepID = $data['id'];
        $data['Val_STstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateSteps($data, $StepID);

            if ($Success) {
                setAjaxResponse(_l('service_step_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_step_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }
    /* Update staff stauts */
    public function UpdateWorkStatus()
    {

        $data = $this->input->post();
        $WorkID = $data['id'];
        $data['Val_Wstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateWorks($data, $WorkID);

            if ($Success) {
                setAjaxResponse(_l('service_work_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_work_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }
    /* Update staff stauts */
    public function UpdateReviewStatus()
    {

        $data = $this->input->post();
        $ReviewID = $data['id'];
        $data['Val_Rstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateReviews($data, $ReviewID);

            if ($Success) {
                setAjaxResponse(_l('service_review_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_review_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }

    /* Update staff stauts */
    public function UpdatePackageStatus()
    {

        $data = $this->input->post();
        $PackageID = $data['id'];
        $data['Val_Pstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updatePackages($data, $PackageID);

            if ($Success) {
                setAjaxResponse(_l('service_package_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_package_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }

    /* Update staff stauts */
    public function UpdateOptionStatus()
    {

        $data = $this->input->post();
        $OptionID = $data['id'];
        $data['Val_Ostatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateOptions($data, $OptionID);

            if ($Success) {
                setAjaxResponse(_l('service_option_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_option_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }
    /* Update staff stauts */
    public function UpdateTimeslabStatus()
    {

        $data = $this->input->post();
        $TimeslabID = $data['id'];
        $data['Val_Tstatus'] = $data['status'];

        if (!empty($data)) {
            $Success = $this->Services_model->updateTimeslabs($data, $TimeslabID);

            if ($Success) {
                setAjaxResponse(_l('service_timeslab_status_update_success'), 'success', _l('success'));
            } else {
                setAjaxResponse(_l('service_timeslab_status_update_fail'), 'warning', _l('fail'));
            }
        }
    }

}
