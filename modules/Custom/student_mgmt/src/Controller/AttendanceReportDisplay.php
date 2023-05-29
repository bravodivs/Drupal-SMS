<?php

namespace Drupal\student_mgmt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for displaying the attendance report.
 */
class AttendanceReportDisplay extends ControllerBase
{

  /**
   * Display the attendance report.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   * @param string $date
   *   The date for which the report is generated.
   *
   * @return array
   *   The render array for the attendance report page.
   */
  public function displayAttendanceReport(Request $request, $date)
  {
    // Get the number of absentees and their names for the selected date.
    $absentees = $this->getAbsenteesByDate($date);

    // Building the attendance report table.
    $header = [
      $this->t('Date'),
      $this->t('Number of Absentees'),
      $this->t('Absentees'),
    ];

    $rows = [];
    foreach ($absentees as $absentee) {
      $names = $this->buildAbsenteesList($absentee->absentees);
      $rows[] = [
        'data' => [
          $absentee->date,
          $absentee->absentees_count,
          implode(', ', $names),
        ],
      ];
    }

    $table = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    $form['attendance_report'] = $table;

    return $form;
  }

  /**
   * Get the number of absentees and their names for the selected date.
   *
   * @param string $date
   *   The selected date.
   *
   * @return array
   *   An array containing the date, number of absentees, and their names.
   */
  protected function getAbsenteesByDate($date)
  {
    $query = \Drupal::database()->select('student_management_attendance', 'a');
    $query->addField('a', 'date');
    $query->addExpression('COUNT(*)', 'absentees_count');
    $query->addExpression('GROUP_CONCAT(s.name)', 'absentees');
    $query->join('student_management_students', 's', 's.id = a.student_id');
    $query->condition('a.status', 'absent');
    $query->condition('a.date', $date, '=');
    $query->groupBy('a.date');

    return $query->execute()->fetchAll();
  }

  /**
   * Build a list of absentees
   *
   * @param string $names
   *   The names of the absentees.
   *
   * @return \Drupal\Core\GeneratedLink[]
   *   An array of generated absentees.
   */
  protected function buildAbsenteesList($names)
  {
    $names = explode(',', $names);
    $names = array_map('trim', $names);
    return $names;
  }
}
