<?php include_once "header.html"; ?>

<main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full animate-fadeIn">
    
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-16 h-16 bg-brand-blue rounded-2xl flex items-center justify-center shadow-2xl border-b-4 border-yellow-400 rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-chart-pie text-2xl text-yellow-400"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
            </div>
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter text-brand-blue leading-none">Intelligence Hub</h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] mt-2 font-bold flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                    Relatórios Vulcanização B2
                </p>
            </div>
        </div>
        
    </header>

    <section class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-gray-100 p-8 mb-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-5">
            <i class="fas fa-filter text-8xl"></i>
        </div>
        
        <div class="flex items-center gap-3 mb-8">
            <div class="h-6 w-1 bg-brand-blue rounded-full"></div>
            <h2 class="text-xs font-black text-brand-blue uppercase tracking-widest">Painel de Filtragem Avançada</h2>
        </div>
        
        <form id="formFiltros" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 relative z-10">
            <div class="lg:col-span-2 group">
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 group-focus-within:text-brand-blue transition-colors">Relatório</label>
                <div class="relative">
                    <select id="tipo_relatorio" name="tipo_relatorio" required 
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-brand-blue focus:border-brand-blue focus:bg-white outline-none transition-all cursor-pointer appearance-none">
                        <option value="" disabled selected>O que deseja analisar?</option>
                        <option value="lubrificacao">🛢️ Lubrificação</option>
                        <option value="tpt">🌡️ Tempo, Temperatura e Pressão</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:col-span-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Início</label>
                    <input type="date" id="data_inicio" name="data_inicio" required
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-3 py-3 text-sm font-bold text-gray-700 outline-none focus:border-brand-blue focus:bg-white transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Fim</label>
                    <input type="date" id="data_fim" name="data_fim" required
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-3 py-3 text-sm font-bold text-gray-700 outline-none focus:border-brand-blue focus:bg-white transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Linha</label>
                <select id="linha_filtro" name="linha_filtro"
                        class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 outline-none focus:border-brand-blue focus:bg-white transition-all appearance-none">
                    <option value="todos">Todas</option>
                    <?php foreach(range('N', 'T') as $letra): ?>
                        <option value="<?= $letra ?>">Linha <?= $letra ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" id="btnBuscar"
                        class="w-full bg-brand-blue hover:bg-black text-white font-black uppercase italic tracking-widest text-xs py-4 rounded-2xl shadow-xl shadow-blue-900/20 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                    <i class="fas fa-search group-hover:rotate-12 transition-transform"></i>
                    Gerar Análise
                </button>
            </div>
        </form>
    </section>

    <div id="areaResultados" class="min-h-[400px]">
        
        <div id="mensagemInicial" class="flex flex-col items-center justify-center py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 text-gray-400">
            <div class="relative mb-6">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-layer-group text-4xl text-gray-200"></i>
                </div>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-brand-blue rounded-full flex items-center justify-center animate-bounce">
                    <i class="fas fa-arrow-up text-white text-[10px]"></i>
                </div>
            </div>
            <h3 class="font-black uppercase tracking-widest text-gray-500">Pronto para processar</h3>
            <p class="text-xs mt-2 text-gray-400 max-w-xs text-center leading-relaxed">Selecione o tipo de relatório e o período para visualizar os dados de produção.</p>
        </div>

        <div id="relatorioLubrificacao" class="hidden animate-fadeInUp">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 px-4 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-400/20">
                        <i class="fas fa-oil-can text-brand-blue"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-brand-blue italic uppercase tracking-tighter">Log de Lubrificação</h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Histórico de aplicações</p>
                    </div>
                </div>
                <button class="flex items-center gap-2 text-[10px] font-black text-brand-blue bg-white border-2 border-gray-100 px-5 py-2.5 rounded-xl hover:border-brand-blue transition-all uppercase tracking-widest shadow-sm">
                    <i class="fas fa-file-export"></i> Exportar Dados
                </button>
            </div>

            <div class="bg-white shadow-2xl rounded-[2rem] overflow-hidden border border-gray-50">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Data / Hora</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Operador</th>
                            <th class="px-8 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Posição</th>
                            <th class="px-8 py-5 text-center text-[10px] font-black text-brand-blue uppercase tracking-widest">Código de Barras</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaLubrificacaoCorpo" class="divide-y divide-gray-50 text-sm italic">
                        </tbody>
                </table>
            </div>
        </div>

        <div id="relatorioTPT" class="hidden animate-fadeInUp">
            <div class="flex items-center gap-3 mb-8 px-4">
                <div class="w-10 h-10 bg-brand-blue rounded-xl flex items-center justify-center shadow-lg shadow-blue-400/20">
                    <i class="fas fa-thermometer-half text-yellow-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-brand-blue italic uppercase tracking-tighter">Monitoramento de Processo</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tempo, Temperatura e Pressão</p>
                </div>
            </div>
            <div id="listaTPT" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
                </div>
        </div>
    </div>

    <div class="mt-16 flex justify-center">
        <a href="home" class="px-8 py-4 bg-gray-50 rounded-2xl flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-brand-blue hover:bg-white border-2 border-transparent hover:border-gray-100 transition-all group shadow-sm">
            <i class="fas fa-grid-2 text-xs group-hover:-translate-x-1 transition-transform"></i>
            Menu Principal
        </a>
    </div>

</main>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #002c5f; border-radius: 10px; }
</style>

<script>
    // Função para renderizar Lubrificação com visual de "Zebra" Moderno
    function renderLubrificacao(data) {
        let html = '';
        data.forEach((item, index) => {
            html += `
                <tr class="hover:bg-blue-50/40 transition-all group border-l-4 border-transparent hover:border-brand-blue">
                    <td class="px-8 py-5 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-brand-blue font-black">${item.datahora.split(' ')[0]}</span>
                            <span class="text-[10px] text-gray-400 font-bold">${item.datahora.split(' ')[1]}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap uppercase text-[11px] font-black text-gray-500">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gray-100 rounded-full flex items-center justify-center text-[9px] text-brand-blue">
                                ${item.usuario.substring(0,2)}
                            </div>
                            ${item.usuario}
                        </div>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-center">
                        <span class="bg-brand-blue text-white text-[10px] px-3 py-1.5 rounded-lg font-black tracking-tighter">
                            LINHA ${item.linha} <i class="fas fa-arrow-right mx-1 opacity-50 text-[8px]"></i> CAV ${item.cavidade}
                        </span>
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-center font-mono font-bold text-gray-400 group-hover:text-brand-light transition-colors">
                        ${item.barcode}
                    </td>
                    <td class="px-8 py-5 whitespace-nowrap text-right">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            OK
                        </span>
                    </td>
                </tr>`;
        });
        document.getElementById('tabelaLubrificacaoCorpo').innerHTML = html;
        showSection('relatorioLubrificacao');
    }

    // Função para renderizar TPT como "Factory Widgets"
    function renderTPT(data) {
        let html = '';
        data.forEach(group => {
            const hasProblem = group.checks.some((check, index) => {
                // ... sua lógica de status existente ...
                return false; // exemplo
            });

            html += `
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 p-6 border-2 ${hasProblem ? 'border-red-100' : 'border-gray-50'} hover:border-brand-blue transition-all cursor-pointer group relative overflow-hidden" onclick="showTPTDetails('${group.id}')">
                    ${hasProblem ? '<div class="absolute top-0 right-0 w-20 h-20 bg-red-500 rotate-45 translate-x-10 -translate-y-10 flex items-end justify-center pb-2"><i class="fas fa-exclamation text-white text-xs -rotate-45"></i></div>' : ''}
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="px-4 py-1.5 bg-gray-50 rounded-full text-[9px] font-black text-gray-400 uppercase tracking-widest group-hover:bg-brand-blue group-hover:text-white transition-colors">${group.data}</div>
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center ${hasProblem ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500'}">
                            <i class="fas ${hasProblem ? 'fa-triangle-exclamation' : 'fa-check-circle'} text-lg"></i>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em]">Célula de Trabalho</p>
                        <h4 class="text-3xl font-black italic text-brand-blue uppercase flex items-baseline gap-2">
                            ${group.linha} <span class="text-sm text-gray-300">LINHA</span>
                            <span class="text-yellow-400">/</span>
                            ${group.cavidade} <span class="text-sm text-gray-300">CAV</span>
                        </h4>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between">
                         <div class="flex flex-col">
                            <span class="text-[8px] font-black uppercase text-gray-300">Turno</span>
                            <span class="text-xs font-black text-brand-blue uppercase tracking-widest">${group.turno}º Turno</span>
                         </div>
                         <div class="h-10 w-10 rounded-full border-2 border-gray-100 flex items-center justify-center group-hover:border-brand-blue group-hover:bg-brand-blue group-hover:text-white transition-all">
                            <i class="fas fa-arrow-right text-xs"></i>
                         </div>
                    </div>
                </div>`;
        });
        document.getElementById('listaTPT').innerHTML = html;
        showSection('relatorioTPT');
    }

    function showSection(id) {
        document.getElementById('mensagemInicial').classList.add('hidden');
        document.getElementById('relatorioLubrificacao').classList.add('hidden');
        document.getElementById('relatorioTPT').classList.add('hidden');
        document.getElementById(id).classList.remove('hidden');
    }
</script>

<?php include_once "footer.html"; ?>