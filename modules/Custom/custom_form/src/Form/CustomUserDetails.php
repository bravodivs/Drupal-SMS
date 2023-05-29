<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class CustomUserDetails extends FormBase {

    private $student_name = '';


    public function getFormId() {
        return "custom_user_details_form";
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#attached']['library'][] = "custom_form/customjsform";
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => 'student Name',
        ];
        $form['attendance'] = [
            '#type' => 'select',
            '#title' => 'Attendance',
            '#options' => [
                'present' => 'Present',
                'absent' => 'Absent'
            ],
        ];
        $form['show_button'] = [
            '#type' => 'submit',
            '#value' => 'Show Details',
            '#submit' => ['::show_dets'],

        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Add',
        ];


        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::messenger()->addMessage("User Details Submitted Successfully");
        // array_push($this->student_names, $form_state->getValue('name'));
        
        // \Drupal::messenger()->addMessage($this->student_name);

        // TODO: save to db

        $name = $this->student_name;
        $attend = $form_state->getValue('attendance')=='present'?1:0;

        $query = \Drupal::database();
        $query->insert('info')->fields(array('name'=> $name, 'attendance'=>$attend))->execute();

        \Drupal::messenger()->addMessage('data is added');

    }

    public function validateForm(array &$form, FormStateInterface $form_state ){
        $this->student_name= $form_state->getValue('name'); 
        if(strlen($this->student_name)<=0)
            \Drupal::messenger()->addError("Please enter a valid name!!");
    }

    // redirecting to a new page
    public function show_dets(array &$form, FormStateInterface $form_state){

        $res =  new RedirectResponse('/sms/student_list');
        $res->send();

    }

}