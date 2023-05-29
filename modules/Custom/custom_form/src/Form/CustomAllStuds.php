<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Code\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomAllStuds extends FormBase{
    public function getFormId() {
        return "custom_stud_all_details_form";
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['#attached']['library'][] = "custom_form/customjsform";
        
        // submit button to save the students
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'Save',
        ];
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

}