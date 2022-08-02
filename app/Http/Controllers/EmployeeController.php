<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\EmployeeImport;
use Excel;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function attendence_report_generate_view()
    {
        return view('backend.employee_manage.generate_attendance_view');
    }

    public function attendence_report_generate_excell_store(Request $request)
    {
        $employee_attendance_data = Employee::all();
        $delete_previous_employee_attendance =  DB::table('employees')->delete();
        if($delete_previous_employee_attendance ||  count($employee_attendance_data) == 0)
        {
            try{
                Excel::import(new EmployeeImport, $request->file);
            } catch(\Exception $exception){
                return view('backend.employee_manage.exception_employee');
            }
           

        }
        return redirect('/admin/employee_manage/attendance_generate_report');
    }
   

    public function attendence_report_generate()
    {
       
        $employee_attendance_data = Employee::all();
      
        return view('backend.employee_manage.employee_attendance_show', compact('employee_attendance_data'));
    }
}
