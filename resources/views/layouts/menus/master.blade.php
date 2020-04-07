<li>
                                        <a><i class="fa fa-database"></i> Cadastros <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{ route('users') }}">Usuários</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-list"></i> Relatórios <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{ route('report-alunos') }}">Alunos</a></li>
                                            <li><a href="{{ route('report-turmas') }}">Turmas</a></li>
                                            <li><a href="{{ route('report-faturamentos') }}">Faturamento</a></li>
                                            <li><a href="{{ route('report-fatanalitico') }}">Faturamento Analítico</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-bar-chart-o"></i> Gráficos <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{ route('frm-alunos-ativos-inativos') }}">Alunos Ativos x Inativos</a></li>
                                            <li><a href="{{ route('frm-faturamento-receita-despesa') }}">Receitas x Despesas</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-laptop"></i> Cursos Online <span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <li><a href="{{ route('report-vendas') }}"> Matriculas </a> </li>
                                            <li><a href="{{ route('report-leads') }}"> Leads </a> </li>
                                            <li><a href="{{ route('report-boletos') }}"> Pagamentos Pendentes </a> </li>
                                            <li><a href="{{ route('report-leads-externos') }}"> Leads Externos </a> </li>
                                            <li><a href="{{ route('report-funil-vendas') }}"> Funil de Vendas </a> </li>
                                        </ul>
                                    </li>