<?php

namespace Drupal\student_mgmt\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for generating the attendance report.
 */
class AttendanceReportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attendance_report_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Select Date'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Report'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Process the form submission and generate the attendance report.
    $date = $form_state->getValue('date');

    $form_state->setRedirect('student_mgmt.attendance_report_display', ['date' => $date]);
  }

}
