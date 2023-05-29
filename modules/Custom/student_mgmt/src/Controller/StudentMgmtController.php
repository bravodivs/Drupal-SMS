<?php

namespace Drupal\student_mgmt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for student management system.
 */
class StudentMgmtController extends ControllerBase {

  /**
   * Displays the form to add students.
   *
   * @return array
   *   The form render array.
   */
  public function addStudents() {
    $form = \Drupal::formBuilder()->getForm('Drupal\student_mgmt\Form\AddStudentsForm');
    return $form;
  }

  /**
   * Displays the attendance form.
   *
   * @return array
   *   The form render array.
   */
  public function attendanceForm() {
    $form = \Drupal::formBuilder()->getForm('Drupal\student_mgmt\Form\AttendanceForm');
    return $form;
  }

  /**
   * Displays the attendance reports.
   *
   * @return array
   *   The response object.
   */
  public function attendanceReports() {
    $output = '<h2>Attendance Reports</h2>';
    // Add your custom logic here to generate attendance reports.
    // You can query the database and format the report as needed.
    $form = \Drupal::formBuilder()->getForm('Drupal\student_mgmt\Form\AttendanceReportForm');
    return $form;
    // return new Response($output);
  }

}
