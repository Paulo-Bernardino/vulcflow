

<div id="modalSucesso" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl text-center transform scale-90 transition-transform duration-300">
        <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fas fa-check"></i>
        </div>
        <h3 class="text-2xl font-black text-brand-blue uppercase italic mb-2">Sucesso!</h3>
        <p class="text-gray-500 font-bold text-sm mb-8 uppercase tracking-widest">Lubrificação registrada com sucesso.</p>
        <button onclick="fecharModal()" class="w-full bg-brand-blue text-white py-4 rounded-2xl font-black uppercase italic tracking-widest shadow-lg shadow-blue-200">OK</button>
    </div>
</div>

<main class="flex-1 p-4 md:p-8 max-w-lg mx-auto w-full animate-fadeIn">
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-brand-blue p-8 text-white relative">
            <div class="absolute top-0 right-0 p-6 opacity-10"><i class="fas fa-oil-can text-6xl"></i></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-yellow-400 mb-1">Check-List </p>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter flex items-center gap-3">
                    <i class="fas fa-droplet"></i> Lubrificação
                </h2>
            </div>
        </div>

        <div class="p-8">
            <form id="formLubrificacao" class="space-y-8">
                <div class="bg-gray-50 p-6 rounded-[2rem] border-2 border-dashed border-gray-200 relative group focus-within:border-brand-blue transition-all">
                    <span class="absolute -top-3 left-6 bg-white px-3 py-1 text-[9px] font-black text-brand-blue uppercase tracking-widest border-2 border-gray-100 rounded-full">
                        <i class="fas fa-lock mr-1"></i> Dados Fixos
                    </span>
                    <div class="grid grid-cols-1 gap-6 mt-2">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Data de Validade</label>
                            <input type="date" id="data_validade" name="data_validade" required class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Linha de Produção</label>
                            <select id="linha" name="linha" required class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none appearance-none cursor-pointer">
                                <option value="" disabled selected>Selecione...</option>
                                <?php foreach(range('N', 'T') as $letra): ?>
                                    <option value="<?= $letra ?>">Linha <?= $letra ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-2">
                    <div>
                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest mb-2 ml-1">Identificação da Cavidade</label>
                        <input type="text" id="cavidade" name="cavidade" required placeholder="EX: C01" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest mb-2 ml-1">Leitura de Barcode</label>
                        <input type="text" id="barcode" name="barcode" required placeholder="Aguardando leitura..." class="block w-full px-5 py-4 bg-brand-blue/5 border-2 border-brand-blue/20 rounded-2xl text-sm font-black text-brand-blue focus:border-brand-blue outline-none transition-all">
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="w-full flex items-center justify-center gap-3 py-5 bg-brand-blue text-white rounded-2xl shadow-xl active:scale-[0.98]">
                    <span class="font-black italic uppercase tracking-[0.2em] text-xs">Confirmar Lubrificação</span>
                </button>
            </form>
        </div>
    </div>
    <div class="mt-8 flex justify-center">
        <a href="?page=home" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-all">
            <i class="fas fa-chevron-left text-[8px]"></i> Menu Principal
        </a>
    </div>
</main>

<script>
function fecharModal() {
    document.getElementById('modalSucesso').classList.add('hidden');
    setTimeout(() => { document.getElementById('cavidade').focus(); }, 150);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLubrificacao');
    const btnSubmit = document.getElementById('btnSubmit');
    const inputData = document.getElementById('data_validade');
    const selectLinha = document.getElementById('linha');
    const inputCavidade = document.getElementById('cavidade');
    const inputBarcode = document.getElementById('barcode');

    // Recupera memória
    inputData.value = localStorage.getItem('lub_data') || new Date().toISOString().split('T')[0];
    selectLinha.value = localStorage.getItem('lub_linha') || "";

    // Navegação Inteligente (ENTER)
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            if (e.target === inputCavidade) inputBarcode.focus();
            else if (e.target === inputBarcode && inputBarcode.value.trim() !== "") form.requestSubmit();
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'GRAVANDO...';

        localStorage.setItem('lub_data', inputData.value);
        localStorage.setItem('lub_linha', selectLinha.value);

        const formData = new URLSearchParams(new FormData(form));
        formData.append('action', 'save_lubrificacao');

        // O CAMINHO É O SEGREDO: Verifique se Config está 2 pastas acima da View
        fetch('Config/backend.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalSucesso').classList.remove('hidden');
                inputCavidade.value = '';
                inputBarcode.value = '';
                inputCavidade.focus();
            } else {
                alert("Erro: " + data.message);
            }
        })
        .catch(err => {
            console.error("Erro no Fetch:", err);
            alert("Erro de conexão com o servidor. Verifique o console (F12).");
        })
        .finally(() => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = 'Confirmar Lubrificação';
        });
    });

    inputCavidade.focus();
});
</script>
