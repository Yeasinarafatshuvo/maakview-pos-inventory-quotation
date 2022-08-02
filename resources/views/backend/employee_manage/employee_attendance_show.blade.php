@extends('backend.layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
<style>
    
table,thead, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
.absence:hover{
   color:red !important;
}
.absence_approve:hover{
   color:red !important;
}
.in_time_late_approve:hover{
   color:red !important;
}
.in_time_late_not_approve:hover{
   color:green !important;
}


</style>
<div id="print_area_of_div">
    <table style="width:100%">
        <tr>
        <tr class="text-center">
            <th colspan="7" style="border: 1px solid black !important">For The Month Of {{date("M-Y",strtotime($employee_attendance_data[1]->date))}} (Individual Statement)</th>
        </tr>
        <tr class="text-center">
            <th colspan="7" style="border: 1px solid black !important">Name:{{$employee_attendance_data[1]->name}}</th>
        </tr>
        <tr class="text-center">
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">Date</th>
          <th style="border: 1px solid black !important" colspan="2" class="text-center">Every Day</th>
          <th style="border: 1px solid black !important" colspan="2" class="text-center">Dellay</th>
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">Cre. Leave (Date)</th>
          <th style="border: 1px solid black !important" rowspan="2" class="text-center">App Leave (Date)</th>
        </tr>
        <tr>
          <td style="border: 1px solid black !important" class="text-center">Attended time</td>
          <td style="border: 1px solid black !important" class="text-center">Out Time</td>
          <td style="border: 1px solid black !important" colspan="1" class="text-center">App.Dellay</td>
          <td style="border: 1px solid black !important" colspan="1" class="text-center">Un App.Dellay</td>
        </tr>
        @foreach ($employee_attendance_data as $key => $employee_attendance_item)
        <?php
            $timestamp = strtotime($employee_attendance_item->date);
            $day = date('D', $timestamp);
        ?>
        @if ($key != 0)
            @if ($day !== 'Fri')
                <tr>
                    <td style="border: 1px solid black !important" class="p-2">{{$employee_attendance_item->date}}</td>
                    
                        <?php
                            $atttendance_time = date('h:i', strtotime($employee_attendance_item->check_in));
                            $office_time =  date('h:i', strtotime('09:35'));
                        
                        ?>
                    <td class="text-center" style="border: 1px solid black !important;background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}}">{{$employee_attendance_item->check_in}}</td>
                    <td style="border: 1px solid black !important">{{$employee_attendance_item->check_out == ""?'': date('h:i', strtotime($employee_attendance_item->check_out))}}</td>
                    {{-- late approve start --}}
                    @if (strtotime($atttendance_time) > strtotime($office_time))
                        <input type="hidden" class="in_time_late_approve_class" id="in_time_late_approve_class{{$key}}">
                        <td colspan="1" class="text-center" id="in_time_late_approve{{$key}}" style="background: {{strtotime($atttendance_time) > strtotime($office_time)? 'green':'white'}}"> {{$atttendance_time }} <i class="fa fa-times pl-5 in_time_late_approve" onclick="in_time_late_approve(<?php echo $key?>)" style="color:green; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <td  style="border: 1px solid black !important" colspan="1" class="text-center"></td>
                    @endif
                    {{-- late approve end --}}
                    
                    {{-- late not approve start --}}
                    @if (strtotime($atttendance_time) > strtotime($office_time))
                        <input type="hidden" class="in_time_late_not_approve_class" id="in_time_late_apprv{{$key}}">
                        <td class="text-center" id="in_time_late_not_approve{{$key}}" style="border: 1px solid black !important;background: {{strtotime($atttendance_time) > strtotime($office_time)? 'red':'white'}}">{{$atttendance_time }} <i class="fa fa-times pl-5 in_time_late_not_approve" onclick="in_time_late_not_approve(<?php echo $key?>)" style="color:red; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <td style="border: 1px solid black !important" class="text-center"></td>
                    @endif
                    {{-- late not approve approve end --}}

                    {{-- condition for absence leave --}}
                    @if ($employee_attendance_item->check_in == "")
                        <input type="hidden" class="absence_leave_id" id="absence_leave_id{{$key}}">
                        <td style="border: 1px solid black !important" class="text-center absence_leave"  id="absence_leave{{$key}}">{{$employee_attendance_item->date}}<i class="fa fa-times pl-5 absence" onclick="absence_leave(<?php echo $key?>)" style="color:white; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <td style="border: 1px solid black !important"></td>
                    @endif
                    {{-- condition for approve leave --}}
                    @if ($employee_attendance_item->check_in == "")
                        <input type="hidden" class="not_approve_absence_leave_id" id="not_approve_absence_leave_id{{$key}}">
                        <td style="border: 1px solid black !important" class="text-center approve_leave" id="approve_leave{{$key}}">{{$employee_attendance_item->date}}<i class="fa fa-times pl-5 absence_approve" onclick="approve_leave(<?php echo $key?>)" style="color:white; cursor:pointer" aria-hidden="true"></i></td>
                    @else
                        <td style="border: 1px solid black !important"></td>
                    @endif
                </tr>
            @endif
        @endif
        @endforeach
        <tr class="text-center">
            <td  style="border: 1px solid black !important">Total</td>
            <td  style="border: 1px solid black !important"></td>
            <td  style="border: 1px solid black !important"></td>
            <td  style="border: 1px solid black !important" id="approve_late"></td>
            <td  style="border: 1px solid black !important" id="un_approve_late"></td>
            <td  style="border: 1px solid black !important" id="un_approve_leave"></td>
            <td  style="border: 1px solid black !important" id="approve_leave"></td>
        </tr>
        <tr class="text-center">
            <td colspan="7" style="border: 1px solid black !important">Description</td>
        </tr>
        <tr class="text-center">
            <td colspan="3" style="border: 1px solid black !important">Name</td>
            <td colspan="1" style="border: 1px solid black !important">Salary</td>
            <td colspan="1" style="border: 1px solid black !important">Deduction Amount</td>
            <td colspan="3" style="border: 1px solid black !important">After Ded.Pay Amount</td>
        </tr>
        <tr class="text-center">
            <td colspan="3" style="border: 1px solid black !important">{{$employee_attendance_data[1]->name}}</td>
            <td id="print_salary_value" colspan="1" style="border: 1px solid black !important">
                <span style="visibility: hidden;position: absolute;left:380" id="after_print_show"></span>
                <input type="number" onkeyup="get_employee_salary()" id="employee_salary" value="" style="padding:5px;padding-left:50px;padding-right:50px;border:none">
            </td>
            <td colspan="1" style="border: 1px solid black !important" id="deducted_salary">0</td>
            <td colspan="3" style="border: 1px solid black !important" id="final_salary">0</td>
        </tr>
        <tr class="text-center">
            <td colspan="7" style="border: 1px solid black !important" id="final_salary">Note: Un App.Dellay <span id="un_approve_late_desc"></span> Day, Absence <span id="total_absence_unapr"></span> Day, After  Deduction Salary: <span id="salary_id"></span>/30*<span id="total_presence"></span>=<span id="ending_salary"></span></td>
        </tr>
       
      </table>
      
      <div class=" mt-5" >
        <span style="margin-right: 180px;font-weight:bold;font-size:16px"> Prepared By</span><span style="margin-right: 180px;font-weight:bold;font-size:15px;display:inline-block;width:110px"> Accounts officer</span><span style="margin-right: 180px;font-weight:bold;font-size:15px;display:inline-block;width:110px">Checked by  C.O.O</span><span style="font-weight:bold;font-size:15px;width:110px;display:inline-block">Approved by  C.E.O</span>
      </div>
     
</div>
<div class="text-center" style="margin-top: 5px">
    <button class="btn btn-primary btn-sm" onclick="printpage('print_area_of_div')">print</button>
</div>


<script type="text/javascript">
function printpage(print_area_of_div){
    $('#after_print_show').css({'visibility':'visible', 'font-size':'20px'});
    var css = '@page { size: portrait; }',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet){
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }
    head.appendChild(style);
    var printContents = document.getElementById(print_area_of_div).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    
    window.print();

    document.body.innerHTML = originalContents;

}
function absence_leave(key)
{
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#absence_leave'+key).html('');
        $('#absence_leave_id'+key).remove();
    }
   
    var total_day_for_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_absence;
    if(total_day_for_late_in_time >= 3)
    {
         total_absence = Math.floor(total_day_for_late_in_time / 3);
    }else{
        total_absence = 0;
    }
    var absence = $('.absence_leave_id').length;
    var all_absence_sum = total_absence + absence;

    var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));
    var deducted_amount = employee_salary - employee_final_salary;
    $('#total_presence').html(30 -all_absence_sum);
    $('#final_salary').html(Math.round(employee_final_salary));
    $('#deducted_salary').html(Math.round(deducted_amount));
    $('#ending_salary').html(Math.round(employee_final_salary));

    //total cost
   $('#un_approve_leave').html(absence);
   //note description
   $('#total_absence_unapr').html(all_absence_sum);


}
function approve_leave(key)
{
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#approve_leave'+key).html('');
        $('#not_approve_absence_leave_id'+key).remove();
        //total cost
        var total_approve_absence = $('.not_approve_absence_leave_id').length;
        $('#approve_leave').html(total_approve_absence);
    }
   

}

function in_time_late_approve(key)
{
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }
    else
    {
        $('#in_time_late_approve'+key).css({'backgroundColor':'white'});
        $('#in_time_late_approve'+key).html('');

        //total
        $('#in_time_late_approve_class'+key).remove();
        var un_approve_in_late = $('.in_time_late_approve_class').length;
        $('#approve_late').html(un_approve_in_late);
    }
    
}
function in_time_late_not_approve(key)
{
    var employee_salary = $('#employee_salary').val();
    if(employee_salary == ""){
        Swal.fire('please, enter employee salary')
    }else{
        $('#in_time_late_not_approve'+key).css({'backgroundColor':'white'});
        $('#in_time_late_not_approve'+key).html('');
        $('#in_time_late_apprv'+key).remove();
        
        //total
        var approve_late_in_office = $('.in_time_late_not_approve_class').length;
        $('#un_approve_late').html(approve_late_in_office);
       
        //description part
        $('#un_approve_late_desc').html(approve_late_in_office);
        var total_absence;
        if(approve_late_in_office >= 3)
        {
            total_absence = Math.floor(approve_late_in_office / 3);
        }else{
            total_absence = 0;
        }

        var absence = $('.absence_leave_id').length;
        var all_absence_sum = total_absence + absence;
       
        $('#total_absence_unapr').html(all_absence_sum);
        $('#total_presence').html(30 - all_absence_sum);

        var employee_final_salary = (employee_salary / 30*(30-all_absence_sum));

        var deducted_amount = employee_salary - employee_final_salary;
        $('#final_salary').html(Math.round(employee_final_salary));
        $('#deducted_salary').html(Math.round(deducted_amount));
        $('#ending_salary').html(Math.round(employee_final_salary));
    }
}

function get_employee_salary()
{
    var employee_salary = $('#employee_salary').val();
    var total_day_for_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_absence;
    if(total_day_for_late_in_time >= 3)
    {
         total_absence = Math.floor(total_day_for_late_in_time / 3);
    }else{
        total_absence = 0;
    }
    var absence = $('.absence_leave_id').length;
    var all_absence_sum = total_absence + absence;
    var employee_final_salary = (employee_salary / 30*(30-all_absence_sum))
    var deducted_amount = employee_salary - employee_final_salary;
    $('#final_salary').html(Math.round(employee_final_salary));
    $('#ending_salary').html(Math.round(employee_final_salary));
    $('#deducted_salary').html(Math.round(deducted_amount));
    $('#total_presence').html(30 -all_absence_sum);
    $('#total_absence_unapr').html(all_absence_sum);
    $('#salary_id').html(employee_salary);
    $('#after_print_show').html(employee_salary);
}

$(document).ready(function () {
    var total_approve_late_in_time = $('.in_time_late_approve_class').length;
    var total_not_approve_late_in_time = $('.in_time_late_not_approve_class').length;
    var total_not_approve_absence = $('.absence_leave_id  ').length;
    var total_approve_absence = $('.not_approve_absence_leave_id').length;
    $('#approve_late').html(total_approve_late_in_time);
    $('#un_approve_late').html(total_not_approve_late_in_time);
    $('#un_approve_leave').html(total_not_approve_absence);
    $('#approve_leave').html(total_approve_absence);

    //Note description part
    $('#un_approve_late_desc').html(total_not_approve_late_in_time);

    var total_absence;
    if(total_not_approve_late_in_time >= 3)
    {
         total_absence = Math.floor(total_not_approve_late_in_time / 3);
    }else{
        total_absence = 0;
    }
 
    var grand_total_absence_late_not_approve_absence = 30 - (total_absence+total_not_approve_absence);
    $('#total_absence_unapr').html((total_absence+total_not_approve_absence));
    $('#total_presence').html(grand_total_absence_late_not_approve_absence);


});

</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
