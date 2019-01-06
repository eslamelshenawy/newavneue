<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
 

Route::get('/', 'HomeController@home');
Route::get('privacypolicy','HomeController@home');
Route::get('dd_leads',function(){
    foreach(App\Lead::get() as $lead){
        if(strtotime($lead->created_at) < 1525478399){
            $lead->delete();
        }
    }
});
include 'website.php';

Route::post(adminPath() . '/login_post', 'HomeController@login_post');
Route::get(adminPath() . '/login', 'HomeController@login');



Route::get('socket',function (){
    return view('socket');
});

Route::group(['prefix' => adminPath(), 'middleware' => ['lang', 'admin']], function () {
    Route::post('image_post','ProjectController@image_post');
    Route::get('subscribe',function()
    {
         return view('subscripe');
    });
    Route::post('facebook_post','SettingController@facebook');
    Route::get('deletepush/{id}','ReceivedprojectController@deletepush');

    Route::get('sort_projects','ProjectController@sort_project');
    Route::post('save_sorted','ProjectController@save_sorted');
    Route::post('save_sorted_mob','ProjectController@save_sorted_mob');
    Route::get('get_password',function(){
        return bcrypt('agent0123');
    });
    
    //if (checkRole('export_excel') or @auth()->user()->type == 'admin') {
        Route::view('xlsrequest', 'admin.requests.xlsrequest');
    //} else {
    //    Route::get('xlsrequest', function () {
    //        session()->flash('error', __('admin.you_dont_have_permission'));
    //        return back();
    //    });
    //}
    
    Route::view('notifications','admin.notifications');
    Route::post('guide','HomeController@guide');
    Route::post('xls','HomeController@xls');
    Route::post('xls1','HomeController@xls1');
    Route::get('/', 'HomeController@dashboard');
    Route::resource('lead_sources', 'LeadSourceController');
    Route::resource('leads', 'LeadController');
    Route::post('upload_excel', 'LeadController@upload_excel');
    Route::get('project_featured/{id}', 'ProjectController@project_featured');
    Route::get('delete-lead/{id}', 'LeadController@destroy');

    Route::get('project_un_featured/{id}', 'ProjectController@project_un_featured');
    Route::resource('agent_types', 'AgentTypeController');
    //Route::post('login_post','HomeController@login_post');
    //Route::get('login', 'HomeController@login');
    Route::resource('agent', 'AgentController');
    Route::resource('rental_units', 'RentalUnitController');
    Route::resource('calls', 'CallController');
    Route::resource('meetings', 'MeetingController');
    Route::get('logout', 'HomeController@logout');
//    Route::get('settings', 'SettingController@settings');
    Route::post('settings', 'SettingController@update_settings');
    Route::resource('unit_types', 'UnitTypeController');
    Route::resource('requests', 'RequestController');

    Route::resource('groups', 'GroupController');
    Route::post('get_group', 'GroupController@get_group');
    Route::get('leads/uploads', 'LeadController@upload_file');
    Route::post('leads/upload/excel', 'LeadController@upload_excel');
    Route::resource('tasks', 'TaskController');
    Route::get('confirm_task/{id}', 'TaskController@confirm_task');

    Route::resource('todos', 'ToDoController');
    Route::get('confirm_to_do/{id}', 'ToDoController@confirm_to_do');

    Route::get('leads/uploads/excel', 'LeadController@upload_file');
    Route::post('leads/upload/excel', 'LeadController@upload_excel');

    Route::resource('targets', 'TargetController');
    Route::resource('industries', 'IndustryController');
    Route::resource('location', 'LocationController');
    Route::resource('schools', 'SchoolController');
    Route::resource('companies', 'CompanyController');
    Route::resource('professions', 'ProfessionController');
    Route::resource('titles', 'TitleController');

    // Ajax

    Route::post('get_cities', 'AjaxController@get_cities');
    Route::post('get_contacts', 'AjaxController@get_contacts');
    Route::post('get_calls', 'AjaxController@get_calls');
    Route::post('get_calls_contacts', 'AjaxController@get_calls_contacts');
    Route::post('get_meetings', 'AjaxController@get_meetings');
    Route::post('get_phones', 'AjaxController@get_phones');
    Route::post('get_requests', 'AjaxController@get_requests');
    Route::post('get_unit_types', 'AjaxController@get_unit_types');
    Route::post('get_property', 'AjaxController@get_property');
    Route::post('save_main_slider', 'AjaxController@save_main_slider');
    Route::get('delete_currency/{id}', 'CurrencyController@delete_currency');
    Route::put('edit_currency/{id}', 'CurrencyController@edit_currency');
    Route::post('get_proposal', 'AjaxController@get_proposal');
    Route::post('get_proposal_html', 'AjaxController@get_proposal_html');
    Route::post('get_units', 'AjaxController@get_units');

    // End Ajax

    Route::get('language/{lang}', 'HomeController@lang');
    Route::resource('developers', 'DeveloperController');
    Route::resource('properties', 'PropertyController');
    Route::resource('facilities', 'FacilityController');
    Route::resource('projects', 'ProjectController');
    Route::resource('contacts', 'ContactController');
    Route::get('add/phase/{id}', 'PhaseController@create');
    Route::get('phases/show/{id}', 'PhaseController@show');
    Route::get('phases/edit/{id}', 'PhaseController@edit');
    Route::post('phases/store', 'PhaseController@store');
    Route::post('phases/destroy', 'PhaseController@destroy');
    Route::put('phases/{id}', 'PhaseController@update');
    Route::post('phases/property', 'PhaseController@store_property');
    Route::resource('resale_units', 'ResaleUnitController');
    Route::get('finances', 'HomeController@all_finances');
    Route::post('location/destroy1', 'LocationController@destroy1');
    Route::get('inventory', 'HomeController@inventory');
    Route::get('calendar', 'Controller@calender');
    Route::resource('bank', 'BankController');
    Route::post('add_currency', 'CurrencyController@create');
    Route::get('delete_bank/{id}', 'BankController@destroy');
    Route::post('add_bank', 'BankController@store');
    Route::post('add_income', 'IncomeController@create');
    Route::post('add_outcome', 'OutcomeController@create');
    Route::get('collect_income/{id}', 'IncomeController@collect');
    Route::post('edit_bank/{id}', 'BankController@edit');
    Route::post('add_safe', 'SafeController@create');
    Route::put('edit_safe/{id}', 'SafeController@edit');
    Route::get('delete_safe/{id}', 'SafeController@destroy');
    Route::get('confirm_proposal/{id}', 'ProposalController@confirm_proposal');
    Route::resource('proposals', 'ProposalController');
    Route::resource('deals', 'ClosedDealController');
    Route::resource('tags', 'TagController');
    Route::resource('icons', 'IconController');
    Route::get('get_project', 'ReceivedprojectController@get_notification');
    Route::post('send_cil', 'LeadController@send_cil');
    Route::post('switch_leads', 'LeadController@switch_leads');
    Route::resource('campaign_types', 'CampaignTypeController');
    Route::resource('campaigns', 'CampaignController');
    Route::get('settings', 'SettingController@index');
    Route::resource('competitors', 'CompetitorController');
    Route::get('developers_facebook', 'DeveloperController@developers_facebook');
    Route::get('competitors_facebook', 'DeveloperController@competitors_facebook');
    Route::get('projects_facebook', 'DeveloperController@projects_facebook');

    Route::resource('developers', 'DeveloperController');
    Route::resource('properties', 'PropertyController');
    Route::resource('facilities', 'FacilityController');
    Route::resource('projects', 'ProjectController');
    Route::resource('contacts', 'ContactController');
    Route::get('add/phase/{id}', 'PhaseController@create');
    Route::get('phases/show/{id}', 'PhaseController@show');
    Route::get('phases/edit/{id}', 'PhaseController@edit');
    Route::post('phases/store', 'PhaseController@store');
    Route::post('phases/destroy', 'PhaseController@destroy');
    Route::put('phases/{id}', 'PhaseController@update');
    Route::post('phases/property', 'PhaseController@store_property');
    Route::resource('resale_units', 'ResaleUnitController');
    Route::get('finances', 'HomeController@all_finances');
    Route::post('location/destroy1', 'LocationController@destroy1');
    Route::get('inventory', 'HomeController@inventory');
    Route::get('calendar', 'Controller@calender');
    Route::resource('bank', 'BankController');
    Route::post('add_currency', 'CurrencyController@create');
    Route::get('delete_bank/{id}', 'BankController@destroy');
    Route::post('add_bank', 'BankController@store');
    Route::post('add_income', 'IncomeController@create');
    Route::post('add_outcome', 'OutcomeController@create');
    Route::get('collect_income/{id}', 'IncomeController@collect');
    Route::post('edit_bank/{id}', 'BankController@edit');
    Route::post('add_safe', 'SafeController@create');
    Route::put('edit_safe/{id}', 'SafeController@edit');
    Route::get('delete_safe/{id}', 'SafeController@destroy');
    Route::get('confirm_proposal/{id}', 'ProposalController@confirm_proposal');
    Route::resource('proposals', 'ProposalController');
    Route::resource('deals', 'ClosedDealController');
    Route::resource('tags', 'TagController');
    Route::resource('icons', 'IconController');
    Route::post('send_cil', 'LeadController@send_cil');
    Route::get('main_slider', 'WebsiteController@arrange_main_slider');
    Route::post('switch_leads', 'LeadController@switch_leads');
    Route::resource('campaign_types', 'CampaignTypeController');
    Route::resource('campaigns', 'CampaignController');
    Route::post('add_to_main_slider', 'AjaxController@save_main_slider');
    Route::get('reorder_units', 'ResaleUnitController@reorder');
    Route::post('reorder_units', 'ResaleUnitController@reorder_post');
    Route::post('reorder_projects', 'ResaleUnitController@reorder_projects');
    Route::get('settings_menu', 'HomeController@settings_menu');
    Route::get('sub_payed/{id}', 'ClosedDealController@sub_payed');
    Route::get('main_payed/{id}', 'ClosedDealController@main_payed');
    Route::get('accept/{id}', 'ReceivedprojectController@accept');
    Route::resource('events', 'EventController');
    Route::post('delete_event_image', 'EventController@delete_event_image');

    Route::resource('lands', 'LandController');
    Route::post('delete_land_image', 'LandController@delete_land_image');

    Route::get('sitemap', 'HomeController@sitemap');
    Route::post('fav_lead', 'AjaxController@fav_lead');
    Route::post('hot_lead', 'AjaxController@hot_lead');
    Route::post('add_note', 'LeadNoteController@store');
    Route::post('get_lands', 'AjaxController@get_lands');
    Route::post('lead_notifications', 'LeadNotificationController@store');
    Route::post('export_xls','CampaignController@export_xls');
    Route::post('delete_resale_image', 'ResaleUnitController@delete_resale_image');
    Route::post('delete_rental_image', 'RentalUnitController@delete_rental_image');
    Route::resource('roles', 'RoleController');
    Route::resource('logs', 'LogController');
    Route::post('get_suggestions', 'AjaxController@get_suggestions');
    Route::post('add_doc', 'LeadDocumentController@store');
    Route::post('get_projects', 'AjaxController@get_projects');
    Route::post('update_lead', 'LeadController@update_lead');
    Route::post('seo', 'HomeController@seo');
    Route::get('send_mail','HomeController@send_mail');
    Route::post('mail_post','HomeController@mail_post');
    Route::get('inbox','HomeController@inbox');
    Route::post('get_mail/{id}','HomeController@get_mail');
    Route::post('send_unit','HomeController@send_unit');

    Route::get('chat', function() {
        return view('admin.chat');
    });
    // require public_path('../vendor/autoload.php');

    Route::get('get_msg', function() {
        $options = array(
            'cluster' => 'eu',
            'encrypted' => true
        );
        $pusher = new Pusher\Pusher(
            '77ab43aa6bf2cb6aab94',
            '8f91214bcb3d73ec23ea',
            '445473',
            $options
        );

        $data['message'] = 'hello world';
        $pusher->trigger('chat', 'ChatEvent', $data);
    });

    Route::post('get_developer_projects','ProjectController@get_developer_projects');

    Route::get('reports',function (){
        return view('admin.reports',['title' => __('admin.reports')]);
    });
    Route::post('get_report_form', 'AjaxController@get_report_form');
    Route::post('get_lead_report', 'AjaxController@get_lead_report');
    Route::post('get_leads_data', 'AjaxController@get_leads_data');
    Route::post('get_target', 'AjaxController@get_target');
    Route::post('get_developer_report', 'AjaxController@get_developer_report');
    Route::post('get_project_deals', 'AjaxController@get_project_deals');
    Route::post('get_phases', 'AjaxController@get_phases');
    Route::post('get_phase_units', 'AjaxController@get_phase_units');
    Route::post('get_sales_forecast_report', 'AjaxController@get_sales_forecast_report');
    Route::post('cil_change_status/{id}', 'CilController@cil_change_status');
    Route::get('leads_ajax', 'LeadController@leads_ajax');
    Route::get('leads_ind_ajax', 'LeadController@leads_ind_ajax');
    Route::get('leads_fav_ajax', 'LeadController@leads_fav_ajax');
    Route::get('leads_hot_ajax', 'LeadController@leads_hot_ajax');
    Route::get('team_leads_ajax', 'LeadController@team_leads_ajax');

    Route::post('get_countries_cities', 'AjaxController@get_countries_cities');
    Route::post('get_cities_districts', 'AjaxController@get_cities_districts');

    Route::resource('forms', 'FormController');
    Route::post('get_form_projects', 'AjaxController@get_form_projects');
    Route::post('get_form_phases', 'AjaxController@get_form_phases');
    Route::resource('contracts', 'ContractController');
    
    Route::post('filter_team_leads', 'LeadController@filter_team_leads');
    Route::post('notification-status', 'HomeController@notificationStatus');
    Route::post('rate_employee', 'AjaxController@rate_employee');
    Route::post('unread', 'HomeController@unread');
    
    Route::resource('call_statuses', 'CallStatusController');
    Route::resource('meeting_statuses', 'MeetingStatusController');
    
    Route::resource('out_cats', 'OutCatController');
    Route::resource('out_sub_cats', 'OutSubCatController');
    Route::post('get_sub_cats', 'HomeController@get_sub_cats');
    Route::post('filter_leads', 'LeadController@filter_leads');
    
    Route::get('interested-request/{unit}/{req}', 'RequestController@interestedRequest');
    
    Route::get('delete-contracts/{id}', 'ContractController@delete');
    Route::post('get_req_projects', 'RequestController@getProjects');
    
    Route::post('search-team', 'LeadController@searchTeam');
    // Route::get('update-projects', function() {
    //     $projects = \App\Project::get();
    //     foreach($projects as $pro) {
    //         $pro->developer_pdf = json_encode([]);
    //         $pro->broker_pdf = json_encode([]);
    //         $pro->save();
    //     }
    //     return 'true';
    // });
    
    Route::get('test-lead', function() {
        $lead = \App\Lead::where('phone', '01001590310')->get();
        return $lead;
    });
    
    Route::post('get_group_agents', 'LeadController@getGroupAgents');
     /////////////////////// HR Module //////////////////////
    Route::post('notification-status', 'HomeController@notificationStatus');
    Route::post('unread', 'HomeController@unread');
    Route::resources([
        'job_categories'=>'JobCategoryController',
        'job_titles'=>'JobTitleController',
        'vacancies'=>'VacancyController',
        'applications'=>'ApplicationController',
        'employees'=>'EmployeeController',
        'vacations'=>'VacationController',
        'custodies'=>'CustodiesController',
        'kpi'=>'KpiController',
        'rates'=>'RatesController',
        'salaries'=>'SalariesController',
        'salaries-details'=>'SalariesDetailsController',
        'hr-settings'=>'HrSettingsController',
        'attendance'=>'AttendanceController',
        'payroll'=>'PayrollController',
    ]);
    Route::post('/salaries/slips','SalariesController@salary_slip');
    Route::get('applications/vacancy/{id}','VacancyController@get_vacancy_applications');
    Route::get('employees/create/{id?}','EmployeeController@create');
    Route::get('applications/create/{id?}','ApplicationController@create');
    Route::post('get_titles','JobTitleController@get_titles');
    Route::post('get_applications','ApplicationController@get_applications');
    Route::post('applications/proposed','JobProposalController@store');
    Route::put('applications/proposed/{id}','JobProposalController@update');
    Route::get('change-category', 'ApplicationController@changeSelector');
    Route::get('change-title', 'ApplicationController@changeTitle');
    Route::post('update_employee', 'EmployeeController@updateEmployee');
    Route::post('image_collector', 'EmployeeController@imageCollector');
    Route::post('update-salary-notes', 'EmployeeController@salaryNotes');
    Route::post('add-er-contact', 'EmployeeController@addErContact');
    Route::post('add-custodies', 'EmployeeController@addCustody');
    Route::post('update-custody', 'CustodiesController@deliverCustody');
    Route::post('request-vacation', 'VacationController@requestVacation');
    Route::post('approve_vacation', 'VacationController@approveVacation');
    Route::post('disapprove_vacation', 'VacationController@disApproveVacation');
    Route::post('update-settings', 'HrSettingsController@updateSetting');
    Route::get('cal-salary', 'HrSettingsController@calculateSalary');
    Route::view('rules-procedure', 'admin.employee.rules_of_procedure');
    Route::post('insert-attendance', 'HrSettingsController@insertAttendance');
    Route::view('xattendance', 'admin.employee.xlsrequest');
    Route::post('time-interval-attendance', 'AjaxController@dateInterval');
//    Route::post('attendance', 'SalariesController@salaryCalculations');



    Route::get('emp-dashboard','EmployerDashboard@statics');
    Route::get('hr-settings', function () {

        return view('admin.employee.setting');
    });
    Route::get('emp-settings', function () {
        return view('admin.employee.setting');
    });

    Route::post('allow-rate', 'EmployeeController@allowRate');
    Route::post('update-rate','EmployeeController@updateRate');

});

Route::post('fblead1',function (){
    return ['status'=>'ok'];
});

Route::group(['prefix' => 'ajax'], function () {
    Route::get('property', function () {
        return view('admin.projects.content.create.property');
    });
});

Route::get('fb_api', function () {
    $fb = \App\Developer::select('facebook')->get();
    return $fb;
});

Route::get('fb_api2', function () {
    $fb = \App\Developer::pluck('facebook');
    return $fb;
});

Route::get('image_test',function (){
    return view('admin.image_test');
});
Route::get('img_post','HomeController@img');

Route::get('privacy-policy', function(){
    return view('privacy-policy');
});

Route::get('form/{slug}', 'HomeController@form');
Route::post('form-lead', 'LeadController@formLead');

Route::get('contracts/{url}', 'HomeController@contract');
Route::post('confirm-contract', 'HomeController@confirmContract');
Route::post('contract-form', 'HomeController@contractForm');

Route::get('socket', function(){
    exec('node socket/index', $output);
    return $output;
});

Route::get('change_marker','ProjectController@change_markers');

// Route::get('sheno','HomeController@test5');


//
// use Facebook\Facebook;
// use Facebook\Exceptions\FacebookResponseException;
//use FacebookAds\Api;
//use FacebookAds\Object\AdSet;
//use FacebookAds\Object\Fields\AdSetFields;
//use FacebookAds\Object\AdAccount;
//
//Route::get('fb', function () {
//    Api::init(
//        '371011733023863',
//        'b5bf2312f741998d6b4c8a612285c9dc',
//        '371011733023863|1lgHZaIL9a_K3u6zNtvVzaaZX9U'
//    );
//    $account_id = 'act_123123';
//    $campaign_id = '123456';
//
//    $account = new AdAccount($account_id);
//    $account->read();
//});

// Route::get('testfb',function(){
//     return view('test1');
// });

// Route::post('webhook.php',function(){
//     $challenge = $_REQUEST['hub_challenge'];
//     $verify_token = $_REQUEST['hub_verify_token'];

//     if ($verify_token === 'abc123') {
//       echo $challenge;
//     }
// });
