<?php
function my_courses_status($userid = null)
{
    global $USER;

    if(is_null($userid)) {
        $userid = $USER->id;
    }
    
    $courses = enrol_get_my_courses();    
    if(count($courses) == 0)
        return null;
    
    $status = array('complete' => array(), 'inprogress' => array(), 'notstarted' => array());
    // find status each courses
    foreach($courses as $course) {
        $completioninfo = new completion_info($course);
        // if(!$completioninfo->is_enabled())
        //     continue;

        $completions = $completioninfo->get_completions($userid);
        
        $activities = array();
        $activities_complete = 0;

        foreach ($completions as $completion) {
            $criteria = $completion->get_criteria();
            $complete = $completion->is_complete();
            
            // Activities are a special case, so cache them and leave them till last.
            if ($criteria->criteriatype == COMPLETION_CRITERIA_TYPE_ACTIVITY) {
                $activities[$criteria->moduleinstance] = $complete;
                if ($complete) {
                    $activities_complete++;
                }
            }
        }
        // Load course completion.
        $params = array(
            'userid' => $userid,
            'course' => $course->id
        );
        $ccompletion = new completion_completion($params);
        $coursecomplete = $completioninfo->is_course_complete($userid);
        $criteriacomplete = $completioninfo->count_course_user_data($userid);

        if($coursecomplete) {
            $status['complete'][] = array(
                'course' => $course->id,
                'totalactivity' => count($activities),
                'activitycompleted' => $activities_complete
               );
         } else if (!$criteriacomplete && !$ccompletion->timestarted) {
            $status['notstarted'][] = array(
                'course' => $course->id,
                'totalactivity' => count($activities),
                'activitycompleted' => $activities_complete
               );
         } else {
            $status['inprogress'][] = array(
                'course' => $course->id,
                'totalactivity' => count($activities),
                'activitycompleted' => $activities_complete
               );
         }
    }

    return $status;
}