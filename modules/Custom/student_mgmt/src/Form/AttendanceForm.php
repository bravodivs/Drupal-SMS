<?php

namespace Drupal\student_mgmt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Implements the AttendanceForm form.
 */
class AttendanceForm extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'attendance_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL)
  {
    // Get the list of students from the database or any other source.
    $students = $this->getStudents();

    // Build the attendance form for each student.
    foreach ($students as $student) {
      $student_id = $student->id;
      $status = $form_state->get(['students', $student_id, 'status']);
      if ($status === NULL) {
        $status = 'present';
      }

      $form['students'][$student_id] = [
        '#type' => 'container',
      ];
      $form['students'][$student_id]['name'] = [
        '#type' => 'item',
        '#markup' => $student->name,
      ];
      $form['students'][$student_id]['status'] = [
        '#type' => 'button',
        '#value' => $this->t($status),
        '#name' => 'status-' . $student_id,
        '#ajax' => [
          'callback' => '::toggleStatusAjaxCallback',
          'event' => 'click',
          'wrapper' => 'attendance-form',
        ],
        '#attributes' => [
          'class' => ['attendance-status-button'],
          'data-student-id' => $student_id,
          'data-status' => $status,
        ],
        '#default_value' => $status,
      ];

      $form['students'][$student_id]['#status_value'] = $status;

      $form['students'][$student_id]['status_select'][$student_id] = [
        '#type' => 'select',
        '#options' => [
          'present' => $this->t('Present'),
          'absent' => $this->t('Absent'),
        ],
        '#default_value' => $status,
      ];
    }

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#submit' => ['::submitForm'],
    ];

    $form['actions']['report'] = [
      '#type' => 'submit',
      '#value' => $this->t('Goto attendance report'),
      '#submit' => ['::show_report'],
      '#attributes' => array('onclick' => 'return show_report();'),
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $students = $this->getStudents();
    $table = 'student_management_attendance';

    foreach ($students as $student) {
      $student_id = $student->id;
      $date = date("Y-m-d");
      $status = $form_state->getValue($student_id);


      // if student there in db at a date and need to update its attendance.
      $query = \Drupal::database();
      $result = $query->select($table, 's')
        ->fields('s', ['student_id'])
        ->condition('s.student_id', $student_id)
        ->condition('s.date', $date)->execute()->fetchField();
      // if found the update
      if ($result) {
        $r = $query->update($table)
          ->fields(['status' => $status])
          ->condition('student_id', $student_id)
          ->condition('date', $date)
          ->execute();
        \Drupal::messenger()->addMessage("value updated for " . $student_id);
      }
      // if not found then add
      else {
        $r = $query->insert($table)
          ->fields(['student_id' => $student_id, 'status' => $status, 'date' => $date])
          ->execute();
        \Drupal::messenger()->addMessage("value added for " . $student_id);
      }
    }

    \Drupal::messenger()->addStatus("Saved to db successfully");
  }

  /**
   * Ajax callback to toggle the attendance status button.
   */
  public function toggleStatusAjaxCallback(array &$form, FormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('.attendance-form-wrapper', $form['students']));

    return $response;
  }


  private function getStudents()
  {
    $query = \Drupal::database();
    $result = $query->select('student_management_students', 's')
      ->fields('s', ['id', 'name'])
      ->condition('date', date("Y-m-d"))
      ->execute()->fetchAll(\PDO::FETCH_OBJ);
    return $result;
  }
  public function show_report()
  {
    $res  = new RedirectResponse('/sms/student-management/reports');
    $res->send();
  }
}
