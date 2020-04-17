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
/*Route::middleware('auth')->group(function () {

});*/

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@index')->name('home');

/*users routes*/
Route::get('/users', 'UserController@index')->name('users');
Route::get('/user-edit/{id}', 'UserController@edit')->name('user-edit');
Route::get('/user-delete/{id}', 'UserController@delete')->name('user-delete');
Route::get('/user-add', 'UserController@add')->name('user-add');
Route::post('/user-post', 'UserController@store')->name('user-post');

/*Errors routes*/
Route::get('404', ['as' => '404', 'uses' => 'ErrorController@notfound']);
Route::get('500', ['as' => '500', 'uses' => 'ErrorController@InternalError']);

/*Gráficos*/
Route::get('/matriculas-mes', 'HomeController@GetMatriculasMes')->name('matriculas-mes');
Route::get('/matriculas-dia', 'HomeController@GetMatriculasDia')->name('matriculas-dia');
Route::post('/receitas-despesas', 'HomeController@GetReceitasDespesas')->name('receitas-despesas');
Route::get('/frm-alunos-ativos-inativos', 'GraficosController@FrmAlunosAtivosInativos')->name('frm-alunos-ativos-inativos');
Route::post('/alunos-ativos-inativos', 'GraficosController@GetAlunosAtivosInativos')->name('alunos-ativos-inativos');

Route::get('/frm-faturamento-receita-despesa', 'GraficosController@FrmReceitasDespesas')->name('frm-faturamento-receita-despesa');
Route::post('/faturamento-receita-despesa', 'GraficosController@GetReceitasDespesas')->name('faturamento-receita-despesa');

Route::get('/situacao-lead', 'LeadController@situacaoLead')->name('situacao-lead');

/*reports*/
Route::get('/report-alunos', 'AlunoController@index')->name('report-alunos');
Route::get('/report-vendas', 'AlunoController@vendasOnline')->name('report-vendas');
Route::get('/report-leads', 'AlunoController@leads')->name('report-leads');
Route::get('/report-boletos', 'AlunoController@boletos')->name('report-boletos');
Route::post('/report-alunos', 'AlunoController@index')->name('report-alunos');
Route::post('/report-vendas', 'AlunoController@vendasOnline')->name('report-vendas');
Route::get('/report-faturamentos', 'FaturamentoController@index')->name('report-faturamentos');
Route::post('/report-faturamentos', 'FaturamentoController@index')->name('report-faturamentos');
Route::get('/alunos_leads_externos', 'LeadController@listaLeads')->name('alunos_leads_externos');
Route::get('/alunos_leads', 'LeadController@listaAlunosLeads')->name('alunos_leads');
Route::get('/report-turmas', 'TurmaController@index')->name('report-turmas');
Route::post('/report-turmas', 'TurmaController@index')->name('report-turmas');
Route::get('/report-funil-vendas', 'AlunoController@funil')->name('report-funil-vendas');
Route::post('/report-funil-vendas', 'AlunoController@funil')->name('report-funil-vendas');
Route::get('/report-leads-externos', 'LeadController@index')->name('report-leads-externos');
Route::get('/report-fatanalitico', 'FaturamentoAnaliticoController@index')->name('report-fatanalitico');
Route::post('/report-fatanalitico', 'FaturamentoAnaliticoController@index')->name('report-fatanalitico');
Route::get('/GetExcel', 'FaturamentoAnaliticoController@GetExcel')->name('GetExcel');
Route::post('/insert_lead', 'LeadController@store')->name('insert_lead');
Route::get('editar_lead/{id}/edit', 'LeadController@edit_lead')->name('editar_lead');
Route::get('editar_lead_aluno/{id}/edit', 'LeadController@edit_lead_aluno')->name('editar_lead_aluno');
// Remover Leads
Route::post('/remover', 'AlunoController@remover')->name('remover');
Route::post('leads_externos/update', 'LeadController@update')->name('leads_externos.update');
Route::post('status_aluno/update', 'LeadController@status')->name('status_aluno.update');
Route::post('leads_alunos_externos/update', 'LeadController@update_aluno')->name('leads_externos.update');
Route::get('/matriculas', 'LeadController@matriculados')->name('matriculas');

Route::resource('daterange', 'DateRangeController');

Route::get('leads-externos', 'LeadController@index');
