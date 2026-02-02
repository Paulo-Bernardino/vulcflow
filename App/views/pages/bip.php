<div id="statusModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0" id="modalContent">
        <div id="modalHeader" class="p-8 text-center transition-colors duration-500">
            <div id="modalIcon" class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 bg-white/20 text-white text-3xl">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <h3 id="modalTitle" class="text-white font-black italic uppercase tracking-tighter text-2xl">Enviando...</h3>
        </div>
        <div class="p-6 text-center">
            <p id="modalMessage" class="text-gray-600 font-bold text-sm mb-6 uppercase tracking-wider">Aguarde a confirmação do sistema.</p>
            <button onclick="closeStatusModal()" id="btnModalClose" class="hidden w-full py-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-2xl font-black italic uppercase text-xs transition-all">
                Fechar
            </button>
        </div>
    </div>
</div>

<main class="flex-1 p-4 md:p-8 max-w-lg mx-auto w-full animate-fadeIn">
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400 mb-1">Notificação em Tempo Real</p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter">B I P</h2>
            </div>
        </div>

        <div class="p-8">
            <form id="formBip" class="space-y-6">
                <input type="hidden" id="inputBipar" name="bipar" value="">

                <div>
                    <label class="block text-[11px] font-black text-brand-blue uppercase tracking-widest mb-3 ml-1">Acionar Responsável:</label>
                    <select id="selectBip" class="block w-full px-6 py-4 bg-gray-50 border-2 border-gray-100 text-brand-blue text-sm rounded-2xl focus:border-brand-blue outline-none font-black italic uppercase cursor-pointer">
                        <option value="" disabled selected>Escolher setor...</option>
                        <option value="313">Mecânica / Elétrica</option>
                        <option value="238">Líder de Produção</option>
                        <option value="826">Monitor de Qualidade</option>
                    </select>
                    <div id="tagsContainer" class="flex flex-wrap gap-2 px-1 min-h-[30px] mt-4"></div>
                </div>
                
                <div>
                    <label for="mensagem" class="block text-[11px] font-black text-brand-blue uppercase tracking-widest mb-3 ml-1">Ocorrência / Detalhes:</label>
                    <textarea id="mensagem" name="mensagem" rows="4" required maxlength="250" placeholder="Descreva o problema..." class="block w-full p-5 bg-gray-50 border-2 border-gray-100 rounded-[2rem] text-sm focus:border-brand-blue outline-none transition-all resize-none"></textarea>
                </div>
                
                <button type="submit" id="btnSubmit" class="w-full flex items-center justify-center gap-3 py-5 bg-brand-blue hover:bg-black text-white rounded-2xl shadow-xl transition-all font-black italic uppercase tracking-[0.2em] text-xs">
                    Disparar Alerta BIP <i class="fas fa-paper-plane text-yellow-400 ml-2"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <a href="home" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-all">
            <i class="fas fa-chevron-left text-[8px]"></i> Menu Principal
        </a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const components = {
        select: document.getElementById('selectBip'),
        container: document.getElementById('tagsContainer'),
        hiddenInput: document.getElementById('inputBipar'),
        form: document.getElementById('formBip'),
        textarea: document.getElementById('mensagem'),
        btn: document.getElementById('btnSubmit'),
        modal: document.getElementById('statusModal'),
        modalContent: document.getElementById('modalContent'),
        modalHeader: document.getElementById('modalHeader'),
        modalIcon: document.getElementById('modalIcon'),
        modalTitle: document.getElementById('modalTitle'),
        modalMsg: document.getElementById('modalMessage'),
        modalClose: document.getElementById('btnModalClose')
    };

    let selectedSectors = [];

    // FUNÇÕES DO MODAL
    const showModal = (type) => {
        components.modal.classList.remove('hidden');
        setTimeout(() => {
            components.modalContent.classList.remove('scale-95', 'opacity-0');
            components.modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        if (type === 'loading') {
            components.modalHeader.className = 'p-8 text-center bg-brand-blue transition-colors duration-500';
            components.modalIcon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            components.modalTitle.innerText = 'ENVIANDO...';
            components.modalMsg.innerText = 'Comunicando com o servidor de BIP.';
            components.modalClose.classList.add('hidden');
        }
    };

    const updateModal = (success, msg) => {
        if (success) {
            components.modalHeader.classList.replace('bg-brand-blue', 'bg-green-500');
            components.modalIcon.innerHTML = '<i class="fas fa-check scale-125"></i>';
            components.modalTitle.innerText = 'BIP ENVIADO!';
        } else {
            components.modalHeader.classList.replace('bg-brand-blue', 'bg-red-500');
            components.modalIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            components.modalTitle.innerText = 'ERRO NO ENVIO';
        }
        components.modalMsg.innerText = msg;
        components.modalClose.classList.remove('hidden');
    };

    window.closeStatusModal = () => {
        components.modalContent.classList.replace('scale-100', 'scale-95');
        components.modalContent.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => components.modal.classList.add('hidden'), 200);
    };

    // LÓGICA DAS TAGS E DO CAMPO HIDDEN
    const syncHiddenInput = () => {
        // Pega todos os IDs e junta por vírgula: "238,826"
        components.hiddenInput.value = selectedSectors.map(s => s.id).join(',');
    };

    const renderTags = () => {
        components.container.innerHTML = '';
        selectedSectors.forEach(item => {
            const tag = document.createElement('div');
            tag.className = "flex items-center gap-2 bg-brand-blue text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase italic border-b-2 border-yellow-400 animate-fadeIn";
            tag.innerHTML = `<span>${item.name}</span><button type="button" onclick="removeSector('${item.id}')"><i class="fas fa-times-circle"></i></button>`;
            components.container.appendChild(tag);
        });
        syncHiddenInput(); // Atualiza o hidden toda vez que renderiza
    };

    window.removeSector = (id) => {
        selectedSectors = selectedSectors.filter(s => s.id !== id);
        renderTags();
    };

    components.select.addEventListener('change', (e) => {
        const id = e.target.value;
        const name = e.target.options[e.target.selectedIndex].text;
        if (id && !selectedSectors.some(s => s.id === id)) {
            selectedSectors.push({ id, name });
            renderTags();
        }
        e.target.selectedIndex = 0;
    });

    // ENVIO DO FORMULÁRIO
    components.form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const biparValue = components.hiddenInput.value; // Pega o "238,826"
        
        if (!biparValue) {
            alert("Selecione pelo menos um responsável.");
            return;
        }

        showModal('loading');

        const payload = {
            action: 'send_new_order_alert',
            bipar: biparValue,
            mensagem: components.textarea.value
        };

        fetch('../../Config/backend.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                updateModal(true, "O alerta foi disparado com sucesso.");
                selectedSectors = [];
                renderTags();
                this.reset();
            } else {
                updateModal(false, data.message);
            }
        })
        .catch(() => updateModal(false, "Falha de conexão com o servidor."))
        .finally(() => {
            components.btn.disabled = false;
        });
    });
});
</script>