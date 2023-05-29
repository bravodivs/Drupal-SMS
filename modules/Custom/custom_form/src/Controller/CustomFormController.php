<?php
namespace Drupal\custom_form\Controller;
use Drupal\Core\Controller\ControllerBase;

class CustomFormController extends ControllerBase{
    public function show_details(){
        $query = \Drupal::database();
        $result = $query->select('info', 'i')
            ->fields('i', ['name', 'attendance'])
            ->execute()->fetchAll(\PDO::FETCH_OBJ);

        $data = [];
        foreach($result as $row){
            $data[] = [
                'name'=>$row->name,
                'attendance' => $row->attendance,
            ];
        }

        $total_studs = count($data);
        $total_absent = 0;
        $absentees = [];
        foreach($data as $a){
            if($a['attendance']==0){
                $total_absent+=1;
                array_push($absentees, $a['name']);
            }
        }
    
        $header = array('Name','Attendance');
        $build['table']= [
            '#type'=>'table',
            '#header'=>$header,
            '#rows' =>$data
        ];
        
        \Drupal::messenger()->addMessage("Total studs: ".$total_studs);
        \Drupal::messenger()->addMessage("Total absentees: ".$total_absent);
        \Drupal::messenger()->addMessage("absentees: ".$absentees);



        return [
            $build, 
            '#title' => 'Students list'
        ];
    }
    function show_all(){
        return [
            '#markup' => '<p>hello world</p>',
        ];
    }

}