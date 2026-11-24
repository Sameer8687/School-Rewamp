<?php
class Access_model extends CI_Model
{
    function fetchForms($id)
    {
        $forms = [];
        if (!isset($id)) return $forms;
        $modules = $this->db->query("SELECT id,icon, module_name FROM modules WHERE id IN(SELECT DISTINCT module_id FROM user_access WHERE user_id = $id) ORDER BY module_name")->result_array();
        foreach ($modules as $module) {
            $form['module_id'] = $module['id'];
            $form['module_name'] = $module['module_name'];
            $form['icon'] = $module['icon'];
            $form['forms'] = $this->db->query("SELECT id, form_name, form_link FROM form WHERE id IN (SELECT DISTINCT form_id FROM user_access WHERE user_id = $id AND module_id = $module[id])")->result_array();
            array_push($forms, $form);
        }
        return $forms;
    }
}