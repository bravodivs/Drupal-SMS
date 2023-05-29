<?php

namespace Drupal\student_mgmt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Form for adding students.
 */
class AddStudentsForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'student_management_add_students_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student Name'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    $form['actions']['display'] = [
      '#type' => 'submit',
      '#value' => $this->t('Show list'),
      '#submit' => ['::show_dets'],
      // '#attributes' => array('onclick' => 'return (false);'),
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $name = $form_state->getValue('name');

    // Save the student name to the database.

    $query = \Drupal::database();
    $query->insert('student_management_students')->fields(array('name' => $name, 'date' => date("Y-m-d")))->execute();

    \Drupal::messenger()->addMessage('data is added');
  }

  // redirect to the list page
  public function show_dets(array &$form, FormStateInterface $form_state)
  {
    $res = new RedirectResponse('/sms/student-management/attendance');
    $res->send();
  }
}
