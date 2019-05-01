<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Remove <br /> html tags from string to show in textarea with new linke
 * @param  string $text
 * @return string formatted text
 */
function clear_textarea_breaks($text)
{
    $_text  = '';
    $_text  = $text;
    $breaks = array(
        "<br />",
        "<br>",
        "<br/>"
    );
    $_text  = str_ireplace($breaks, "", $_text);
    $_text  = trim($_text);
    return $_text;
}
/**
 * Function that renders input for admin area based on passed arguments
 * @param  string $name             input name
 * @param  string $label            label name
 * @param  string $value            default value
 * @param  string $type             input type eq text,number
 * @param  array  $input_attrs      attributes on <input
 * @param  array  $form_group_attr  <div class="form-group"> html attributes
 * @param  string $form_group_class additional form group class
 * @param  string $input_class      additional class on input
 * @return string
 */
 
function render_input($name, $label = '', $value = '', $type = 'text', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '', $minlength='',$placeholder='',$icon = '',$col_sm = '', $required = false,$Input_id='',$validation_pattern='',$validation_message='',$data_attr= array(),$input_div_class='', $disabled = false)
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    $_form_data_attr = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '"';
    }
    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '"';
    }
    foreach ($data_attr as $key => $val) {
         
        $_form_data_attr .= $key . '=' . '"' . $val . '"';
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
/*    <div class="form-group">
                        <div class="input-icon icon-left icon-lg icon-color-primary">
                            <input type="text" class="form-control" placeholder="Email">
                            <span class="icon-addon">
                                <span class="la la-at"></span>
                            </span>
                        </div>
                    </div>
  */
    $input .= '<div class="form-group' . $input_class . '" id="'.$Input_id.'">';
    if(!empty($input_div_class)){
        $input.='<div class="'.$input_div_class.'">';
    }
	 
    if ($label != '') {
        $input .= '<label for="' . $name . '">' . _l($label,'',false); 
        if($required)
            {
                $input .= '<span class="required"> * </span>';  
            }
            $input .= '</label>';
    }
    $iconspan = '';
    if(!empty($icon)){
        $iconspan .= '<span class="icon-addon">
                    <span class="'.$icon.'"></span>
                </span>';
    }

    $disableAttr = '';
    if($disabled){
        $disableAttr = "readonly = readonly";
    }

    if(!empty($validation_pattern)){
        $input .= '<input type="' . $type . '" pattern="'.$validation_pattern.'" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'"'.' '.$_form_data_attr.' '.$disableAttr.'>'.$iconspan;
    } else if(!empty($validation_message) ){ 
        $input .= '<input type="' . $type . '" title="'._l($validation_message).'" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'" '.' '.$_form_data_attr.' '.$disableAttr.'>'.$iconspan;
    } else { 
        $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'" '.' '.$_form_data_attr.' '.$disableAttr.'>'.$iconspan;
    }
    if(!empty($input_div_class)){
        $input .= '</div>';
    }
    $input .= '</div>';
	
	return $input;
}


 
/**
 * Render textarea for admin area
 * @param  [type] $name             textarea name
 * @param  string $label            textarea label
 * @param  string $value            default value
 * @param  array  $textarea_attrs      textarea attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $textarea_class      <textarea> additional class
 * @return string
 */
function render_textarea($name, $label = '', $value = '', $textarea_attrs = array(), $form_group_attr = array(), $form_group_class = '', $textarea_class = '', $minlength='',$placeholder='',$col_sm = '',$required=false)

{

    $textarea         = '';
    $_form_group_attr = '';
    $_textarea_attrs  = '';
    if (!isset($textarea_attrs['rows'])) {
        $textarea_attrs['rows'] = 10;
    }

    foreach ($textarea_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_textarea_attrs .= $key . '=' . '"' . $val . '"';
    }
    foreach ($form_group_attr as $key => $val) {
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '"';
    }
    if (!empty($textarea_class)) {
        $textarea_class = ' ' . $textarea_class;
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    
	$textarea .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $textarea .= '<label >' . _l($label,'',false);
         if($required)

            {

                $textarea .= '<span class="required"> * </span>';  

            }
         $textarea .= '</label>';
    }
 
    $v = clear_textarea_breaks($value);
    if (strpos($textarea_class, 'tinymce') !== false) {
        $v = $value;
    }
    $textarea .= '<textarea rows="5" id="' . $name . '" name="' . $name . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'" class="form-control' . $textarea_class . '" ' . $_textarea_attrs . '>' . set_value($name, $v) . '</textarea>';


    $textarea .= '</div> ';

    return $textarea;
}

/**
 * Render textarea for admin area
 * @param  [type] $name             textarea name
 * @param  string $label            textarea label
 * @param  string $value            default value
 * @param  array  $textarea_attrs      textarea attributes
 * @param  array  $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string $form_group_class form group div wrapper additional class
 * @param  string $textarea_class      <textarea> additional class
 * @return string
 */
function render_editor($name ,$id, $label = '', $value = '', $textarea_attrs = array(), $form_group_attr = array(), $form_group_class = '', $textarea_class = '', $minlength='',$placeholder='',$col_sm = '',$required=false)
{

    $textarea         = '';
    $_form_group_attr = '';
    $_textarea_attrs  = '';
    if (!isset($textarea_attrs['rows'])) {
        $textarea_attrs['rows'] = 10;
    }

    foreach ($textarea_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_textarea_attrs .= $key . '=' . '"' . $val . '"';
    }
    foreach ($form_group_attr as $key => $val) {
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '"';
    }
    if (!empty($textarea_class)) {
        $textarea_class = ' ' . $textarea_class;
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
   
     $textarea .= '<div class="col-sm-'.$col_sm.'">';
    $textarea .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $textarea .= '<label for="' . $name . '" class="control-label">' . _l($label,'',false);
         if($required)

            {

                $textarea .= '<span class="required"> * </span>';  

            }
         $textarea .= '</label>';
    }

    $v = clear_textarea_breaks($value);
    if (strpos($textarea_class, 'tinymce') !== false) {
        $v = $value;
    }
    $textarea .= '<div id="' . $id . '" name="' . $name . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'" class="summernote form-control bg-white' . $textarea_class . '" ' . $_textarea_attrs . '>' . set_value($name, $v) . '</div>'; 


    $textarea .= '</div></div>';

    return $textarea;
}


/**
 * Render <select> field optimized for admin area and bootstrap-select plugin
 * @param  string  $name             select name
 * @param  array  $options          option to include
 * @param  array   $option_attrs     additional options attributes to include, attributes accepted based on the bootstrap-selectp lugin
 * @param  string  $label            select label
 * @param  string  $selected         default selected value
 * @param  array   $select_attrs     <select> additional attributes
 * @param  array   $form_group_attr  <div class="form-group"> div wrapper html attributes
 * @param  string  $form_group_class <div class="form-group"> additional class
 * @param  string  $select_class     additional <select> class
 * @param  boolean $include_blank    do you want to include the first <option> to be empty
 * @return string
 */
function render_select($name, $options, $option_attrs = array(), $label = '', $selected = '', $select_attrs = array(), $form_group_attr = array(), $form_group_class = '', $select_class = '', $include_blank = true)
{

    $callback_translate = '';
    if (isset($options['callback_translate'])) {
        $callback_translate = $options['callback_translate'];
        unset($options['callback_translate']);
    }
    $select           = '';
    $_form_group_attr = '';
    $_select_attrs    = '';
    if (!isset($select_attrs['data-width'])) {
        $select_attrs['data-width'] = '100%';
    }
    if (!isset($select_attrs['data-none-selected-text'])) {
        $select_attrs['data-none-selected-text'] = _l('dropdown_non_selected_tex');
    }
    foreach ($select_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_select_attrs .= $key . '=' . '"' . $val . '"';
    }
    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '"';
    }
    if (!empty($select_class)) {
        $select_class = ' ' . $select_class;
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    $select .= '<div class="form-group' . $form_group_class . '" ' . $_form_group_attr . '>';
    if ($label != '') {
        $select .= '<label for="' . $name . '" class="control-label">' . _l($label,'',false) . '</label>';
    }
    $select .= '<select id="' . $name . '" name="' . $name . '" class="selectpicker' . $select_class . '" ' . $_select_attrs . ' data-live-search="true">';
    if ($include_blank == true) {
        $select .= '<option value=""></option>';
    }
    foreach ($options as $option) {
        $val       = '';
        $_selected = '';
        $key       = '';
        if (isset($option[$option_attrs[0]]) && !empty($option[$option_attrs[0]])) {
            $key = $option[$option_attrs[0]];
        }
        if (!is_array($option_attrs[1])) {
            $val = $option[$option_attrs[1]];
        } else {
            foreach ($option_attrs[1] as $_val) {
                $val .= $option[$_val] . ' ';
            }
        }
        $val = trim($val);

        if ($callback_translate != '') {
            if (function_exists($callback_translate) && is_callable($callback_translate)) {
                $val = call_user_func($callback_translate, $key);
            }
        }
        $data_sub_text = '';
        if (!is_array($selected)) {
            if ($selected != '') {
                if ($selected == $key) {
                    $_selected = ' selected';
                }
            }
        } else {
            foreach ($selected as $id) {
                if ($key == $id) {
                    $_selected = ' selected';
                }
            }
        }
        if (isset($option_attrs[2])) {

            if (strpos($option_attrs[2], ',') !== false) {
                $sub_text = '';
                $_temp    = explode(',', $option_attrs[2]);
                foreach ($_temp as $t) {
                    if (isset($option[$t])) {
                        $sub_text .= $option[$t] . ' ';
                    }
                }
            } else {
                if (isset($option[$option_attrs[2]])) {
                    $sub_text = $option[$option_attrs[2]];
                } else {
                    $sub_text = $option_attrs[2];
                }
            }
            $data_sub_text = ' data-subtext=' . '"' . $sub_text . '"';
        }
        $data_content = '';
        if (isset($option['option_attributes'])) {
            foreach ($option['option_attributes'] as $_opt_attr_key => $_opt_attr_val) {
                $data_content .= $_opt_attr_key . '=' . '"' . $_opt_attr_val . '"';
            }
        }
        $select .= '<option value="' . $key . '"' . $_selected . $data_content . '' . $data_sub_text . '>' . $val . '</option>';
    }
    $select .= '</select>';
    $select .= '</div>';
    return $select;
}
 

/**
 * Init admin head
 * @param  boolean $aside should include aside
 */
function init_head($auth=false,$aside = true)
{
    $CI =& get_instance();
    if($auth == true){
        $CI->load->view('authentication/includes/auth_header');  
    } else {
        if ($aside == true) {
            $CI->load->view('admin/includes/header');
            $CI->load->view('admin/includes/topbar');
            $CI->load->view('admin/includes/sidebar');
            //$CI->load->view('admin/includes/sidebar');
        }
    }
   
}

/**
 * Init admin tail
 * @param  boolean $aside should include aside
 */
function init_tail($auth=false,$aside = true)
{
    $CI =& get_instance();
    if($auth == true){
        $CI->load->view('authentication/includes/auth_footer');  
    } else {
        if ($aside == true) {
            $CI->load->view('admin/includes/scripts');
            $CI->load->view('admin/includes/footer');
            $CI->load->view('admin/includes/models');
        }
    }
   
}

/**
 * Init front enquiry head
 * @param  boolean $aside should include aside
 */
function init_enquiry_head()
{
    $CI =& get_instance();
    $CI->load->view('front/includes/enquiry_header');
     
}

/**
 * Init front enquiry tail
 * @param  boolean $aside should include aside
 */
function init_enquiry_tail()
{
    $CI =& get_instance();
    $CI->load->view('front/includes/enquiry_footer');
     
}


/**
 * Init admin head
 * @param  boolean $aside should include aside
 */
function init_front_head($menu = true)
{
    $CI =& get_instance();
    
    if ($menu == true) {
        $CI->load->view('front/includes/header');
 
    }    
}

/**
 * Init admin tail
 * @param  boolean $aside should include aside
 */
function init_front_tail()
{
    $CI =& get_instance();
     
    $CI->load->view('front/includes/footer');
   
}


/**
 * Init admin tail
 * @param  boolean $aside should include aside
 */
function init_front_newsletter()
{
    $CI =& get_instance();
     
    $CI->load->view('front/includes/newsletter');
   
}

/**
 * Init admin head
 * @param  boolean $aside should include aside
 */
function init_front_question_tail()
{
    $CI =& get_instance();
    $CI->load->view('front/includes/questions');  
}


 
/**
 * Get company logo from company uploads folder
 * @param  string $url     href url of image
 * @param  string $href_class Additional classes on href
 */
function get_company_logo($uri = '', $href_class = '')
{
    $company_logo = get_option('company_logo');
    $company_name = get_option('companyname');

    if ($uri == '') {
        $logo_href = site_url();
    } else {
        $logo_href = site_url($uri);
    }

    if ($company_logo != '') {
        echo '<a href="' . $logo_href . '" class="' . $href_class . ' logo img-responsive"><img src="' . base_url('uploads/company/' . $company_logo) . '" class="img-responsive" alt="' . $company_name . '"></a>';
    } else if ($company_name != '') {
        echo '<a href="' . $logo_href . '" class="' . $href_class . ' logo">' . $company_name . '</a>';
    } else {
        echo '';
    }
}

/**
 * Function that renders input for admin area based on passed arguments
 * @param  string $name             input name
 * @param  string $label            label name
 * @param  string $value            default value
 * @param  string $type             input type eq text,number
 * @param  array  $input_attrs      attributes on <input
 * @param  array  $form_group_attr  <div class="form-group"> html attributes
 * @param  string $form_group_class additional form group class
 * @param  string $input_class      additional class on input
 * @return string
 */
 
function render_input_front($name, $label = '', $value = '', $type = 'text', $input_attrs = array(), $form_group_attr = array(), $form_group_class = '', $input_class = '', $minlength='',$placeholder='',$icon = '',$col_sm = '', $required = false)
{
    $input            = '';
    $_form_group_attr = '';
    $_input_attrs     = '';
    foreach ($input_attrs as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_input_attrs .= $key . '=' . '"' . $val . '"';
    }
    foreach ($form_group_attr as $key => $val) {
        // tooltips
        if ($key == 'title') {
            $val = _l($val);
        }
        $_form_group_attr .= $key . '=' . '"' . $val . '"';
    }
    if (!empty($form_group_class)) {
        $form_group_class = ' ' . $form_group_class;
    }
    if (!empty($input_class)) {
        $input_class = ' ' . $input_class;
    }
  
    $input .= '<div class="form-group' . $input_class . '">';
     
    if ($label != '') {
        $input .= '<label for="' . $name . '">' . _l($label,'',false); 
        if($required)
            {
                $input .= '<span class="required"> * </span>';  
            }
            $input .= '</label>';
    }
    $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . set_value($name, $value) . '" minlength="'.$minlength.'" placeholder="'._l($placeholder,'',false).'">';
     
    $input .= '</div>';
    
    return $input;
}
 
 
/**
 * @param $n
 * @return string
 * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
 */
function number_format_short( $n ) {
    if ($n > 0 && $n < 1000) {
        // 1 - 999
        $n_format = floor($n);
        $suffix = '';
    } else if ($n >= 1000 && $n < 1000000) {
        // 1k-999k
        $n_format = floor($n / 1000);
        $suffix = 'K';
    } else if ($n >= 1000000 && $n < 1000000000) {
        // 1m-999m
        $n_format = floor($n / 1000000);
        $suffix = 'M';
    } else if ($n >= 1000000000 && $n < 1000000000000) {
        // 1b-999b
        $n_format = floor($n / 1000000000);
        $suffix = 'B';
    } else if ($n >= 1000000000000) {
        // 1t+
        $n_format = floor($n / 1000000000000);
        $suffix = 'T';
    }

    return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
}


/*function random_string($length) {
    
        $random = '';

        $alphabets = range('A','Z');

        $numbers = range('0','9');

        $additional_characters = array('_','@','$','%');

        $final_array = array_merge($alphabets,$numbers,$additional_characters);

       while($length--) {



              $key = array_rand($final_array);

              $random .= $final_array[$key];



            }

      if (preg_match('/[A-Za-z0-9]/', $random))

        {

         return $random;

        }

        else{

        return  random_string($length);

        }

    

     }*/