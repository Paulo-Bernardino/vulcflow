<?php
require_once __DIR__ . '/../../Config/init.php'; 
include_once "layouts/header.php"; 
?>

<div id="modalSucesso" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl text-center transform scale-90 transition-transform duration-300">
        <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fas fa-check"></i>
        </div>
        <h3 class="text-2xl font-black text-brand-blue uppercase italic mb-2">Sucesso!</h3>
        <p class="text-gray-500 font-bold text-sm mb-8 uppercase tracking-widest">Lubrificação registrada!</p>
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
                            <input type="date" id="data_validade" name="data_validade" required class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Linha de Produção</label>
                            <select id="linha" name="linha" required class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none cursor-pointer appearance-none">
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
                        <input type="text" id="cavidade" name="cavidade" maxlength="4" required placeholder="EX: PN01" autocomplete="off" class="block w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl text-sm font-black text-brand-blue outline-none transition-all uppercase">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest mb-2 ml-1">Leitura de Barcode</label>
                        <input type="text" id="barcode" name="barcode" maxlength="8" required placeholder="Aguardando leitura..." autocomplete="off" class="block w-full px-5 py-4 bg-brand-blue/5 border-2 border-brand-blue/20 rounded-2xl text-sm font-black text-brand-blue outline-none transition-all">
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="w-full flex items-center justify-center gap-3 py-5 bg-brand-blue hover:bg-black text-white rounded-2xl shadow-xl transition-all active:scale-[0.98]">
                    <span class="font-black italic uppercase tracking-[0.2em] text-xs">Confirmar Lubrificação</span>
                </button>
            </form>
        </div>
    </div>
    <div class="mt-8 flex justify-center">
        <a href="index.php" class="inline-flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-blue transition-all">
            <i class="fas fa-chevron-left text-[8px]"></i> Menu Principal
        </a>
    </div>
</main>

<script>
function fecharModal() {
    document.getElementById('modalSucesso').classList.add('hidden');
    setTimeout(() => { document.getElementById('cavidade').focus(); }, 100);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLubrificacao');
    const btnSubmit = document.getElementById('btnSubmit');
    const inputData = document.getElementById('data_validade');
    const selectLinha = document.getElementById('linha');
    const inputCavidade = document.getElementById('cavidade');
    const inputBarcode = document.getElementById('barcode');

    // 1. Persistência Local
    inputData.value = localStorage.getItem('lub_data') || new Date().toISOString().split('T')[0];
    selectLinha.value = localStorage.getItem('lub_linha') || "";

    // 2. Navegação Enter
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            if (e.target === inputCavidade) {
                e.preventDefault();
                inputBarcode.focus();
            } else if (e.target === inputBarcode) {
                // Deixa o submit natural do form acontecer ou força aqui
            }
        }
    });

    // 3. Envio AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> GRAVANDO...';

        // Salva estados
        localStorage.setItem('lub_data', inputData.value);
        localStorage.setItem('lub_linha', selectLinha.value);

        const formData = new URLSearchParams();
        formData.append('action', 'save_lubrificacao');
        formData.append('data_validade', inputData.value);
        formData.append('linha', selectLinha.value);
        formData.append('cavidade', inputCavidade.value);
        formData.append('barcode', inputBarcode.value);

        fetch('../../Config/backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (err) {
                console.error("Resposta não é JSON:", text);
                throw new Error("Erro interno do servidor (PHP explodiu).");
            }
        })
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
            console.error(err);
            alert(err.message || "Erro de conexão.");
        })
        .finally(() => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<span class="font-black italic uppercase tracking-[0.2em] text-xs">Confirmar Lubrificação</span>';
        });
    });

    inputCavidade.focus();
});
</script>

<?php include_once "layouts/footer.php"; ?>